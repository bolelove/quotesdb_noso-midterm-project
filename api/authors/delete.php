<?php
// Headers
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow- Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Author.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate author object
  $author = new Author($db);

  // Get raw data
  $data = json_decode(file_get_contents("php://input"));

  // Create Author, if...
  if (!isset($inputdata['author'])) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit;
  }

  // Get raw data
   $data = json_decode(file_get_contents("php://input"));

  // Set ID to UPDATE
  $author->id = $data->id;

  $data = json_decode(file_get_contents("php://input"), true);

  //  // if delete auth ...
  if($author->delete()) {
    echo json_encode(
      array('id' => $data['id'])
    );
	//else...
  } else {
    echo json_encode(
      array('message' => 'Author not deleted')
    );
  }
?>