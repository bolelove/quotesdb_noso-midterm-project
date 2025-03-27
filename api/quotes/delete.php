<?php 
  // Headers
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate quote object
  $quote  = new Quote($db);

  // Get raw data
  $data = json_decode(file_get_contents("php://input"));

  
  // Set ID to update
  //$quote->id = $data->id;

  $data = json_decode(file_get_contents("php://input"), true);

  // if delete quote ...
  if($quote->delete()) {
    // else...
  } else {
    echo json_encode(
      array('message' => 'Category not deleted')
    );
  }
?>