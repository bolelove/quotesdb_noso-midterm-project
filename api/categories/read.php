<?php 

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate category  pbject
  $category  = new Category($db);

  // Category read query
  $result = $category->read();  
  // Get row count
  $num = $result->rowCount();

  // check category  if....
  if($num > 0) {
        // category  array
          $category_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);

          $category_item = array(
            'id' => $id,
            'category ' => $category 
          );

          // Push to "data"
          array_push($category_arr['data'], $category_item);
        }

        // Turn to JSON & output
        echo json_encode($category_arr);
        //else...
  } else {
        // No categories
        echo json_encode(
          array('message' => 'No categories Found')
        );
  }