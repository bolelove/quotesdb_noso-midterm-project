<?php
// Headers
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow- Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Author.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate author object
  $author = new Author($db);

  // Check for missing param
  $inputdata = json_decode(file_get_contents("php://input"), true);

  // Create Author, if...
  if (!isset($inputdata['author'])) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit;
  }

  // Get raw data
  $data = json_decode(file_get_contents("php://input"));

  $author->author = $data->author;

  // Create Author, if...
  if($author->create()) {
    // else...
  } else {
    echo json_encode(
      array('message' => 'Author Not Created')
    );
  }
?>