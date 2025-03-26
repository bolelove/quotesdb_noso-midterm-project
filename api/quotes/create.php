
<?php   
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate quote  object
  $quote = new Quote($db);

  // Check for missing param
  $inputdata = json_decode(file_get_contents("php://input"), true);

  // Create Quote, if...
  if (!isset($inputdata['quote'])) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit;
  }

  // Get raw data
  $data = json_decode(file_get_contents("php://input"));

  $quote->quote = $data->quote;
  $quote->author_id = $data->author_id;
  $quote->category_id = $data->category_id;


  // Create Quote, if...
  if($quote->create()) {
    // else...
  } else {
    echo json_encode(
      array('message' => 'Quote Not Created')
    );
  }
?>