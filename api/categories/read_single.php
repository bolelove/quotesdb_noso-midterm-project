<?php
  include_once '../../config/Database.php';
  include_once '../../models/Category.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();
  // Instantiate Category object
  $category = new Category($db); 

  // Get ID
  $category->id = isset($_GET['id']) ? $_GET['id'] : die();

  // Get category
  $category->read_single();

  // Create array
  $category = array(
    'id' => $category->id,
    'category' => $category->category
  );

  // Make JSON
  print_r(json_encode($category_arr));
  ?>