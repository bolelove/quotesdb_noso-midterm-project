<?php   
    // Ensure no output is made before setting headers
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // Include files
    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate quote object
    $quote = new Quote($db);

    // Check for missing parameters in the input
    $inputdata = json_decode(file_get_contents("php://input"), true);

    // If the 'quote' parameter is missing
    if (!isset($inputdata['quote'])) {
        echo json_encode(['message' => 'Missing Required Parameters']);
        exit;
    }

    // Get raw data
    $data = json_decode(file_get_contents("php://input"));

    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    // Attempt to create the quote
    if($quote->create()) {
        echo json_encode(['message' => 'Quote Created']);
    } else {
        echo json_encode(['message' => 'Quote Not Created']);
    }
?>
