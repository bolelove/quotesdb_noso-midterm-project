<?php
  // Headers
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');
  
  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate quote object 
  $quote = new Quote($db);

  // Get raw data
  $data = json_decode(file_get_contents("php://input"));

  // If paramters are missing in the body...
  if (!isset($data->id) || (!isset($data->quote))) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit;
  }

  // Set ID to UPDATE
  $quote->id = $data->id;
  $quote->quote = $data->quote;
  $quote->author_id = $data->author_id;
  $quote->category_id = $data->category_id;

  $data = json_decode(file_get_contents("php://input"), true);

  // Update post if...
  if($quote->update()) {
    echo json_encode(
      array('id' => $data['id'],
      'quote' => $data['quote'],
      'author_id' => $data['author_id'],
      'category_id' => $data['category_id'])
    );
//else...
  } else {
    echo json_encode(
      array('message' => 'Quote not updated')
    );
  }
?>