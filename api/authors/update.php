<?php
  // Headers
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');
  
  include_once '../../config/Database.php';
  include_once '../../models/Author.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate author object
  $author = new Author($db);

  // Get raw data
  $data = json_decode(file_get_contents("php://input"));

  // If paramters are missing in the body...
  if (!isset($data->id) || (!isset($data->author))) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit;
  }

  // Set ID to UPDATE
  $author->id = $data->id;

  $author->author = $data->author;

  $data = json_decode(file_get_contents("php://input"), true);

  // Update post if...
  if($author->update()) {
    echo json_encode(
      array('id' => $data['id'],
            'author' => $data['author'])
    );
//else...
  } else {
    echo json_encode(
      array('message' => 'Author not updated')
    );
  }
?>