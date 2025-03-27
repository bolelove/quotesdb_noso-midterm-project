
<?php
  // Headers
  // header('Access-Control-Allow-Methods: POST');
  // header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

    // Instantiate cat obj
    $category = new Category($db);
      
   $inputdata = json_decode(file_get_contents("php://input"), true);

    // If parameters are missing...
    if (!isset($inputdata['category'])) {
      echo json_encode(['message' => 'Missing Required Parameters']);
      exit;
    }

  // Get raw data
  $data = json_decode(file_get_contents("php://input"));

  $category->category = $data->category;

    // Delete author if...
  if($category->create()) {
//else..
  } else {
    echo json_encode(
      array('message' => 'Category not deleted')
    );
  }
?>