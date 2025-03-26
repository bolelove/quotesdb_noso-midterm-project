<?php
  // Headers
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');
  
  include_once '../../config/Database.php';
  include_once '../../models/Category.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate category  object
  $category = new Category($db);

  // Get raw data
  $data = json_decode(file_get_contents("php://input"));

  // If paramters are missing in the body...
  if (!isset($data->id) || (!isset($data->category))) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit;
  }

  // Set ID to UPDATE
  $category->id = $data->id;  

  $category->category = $data->category;

  $data = json_decode(file_get_contents("php://input"), true);

  // Update post if...
  if($category->update()) {
    echo json_encode(
      array('id' => $data['id'],
            'category' => $data['category'])
    );
//else...
  } else {
    echo json_encode(
      array('message' => 'Category not updated')
    );
  }
?>