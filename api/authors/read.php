<?php 

  include_once '../../config/Database.php';
  include_once '../../models/Author.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate author pbject
  $author = new Author($db);

  // Author read query
  $result = $author->read(); 
  
  // Get row count
  $num = $result->rowCount();

  // check authors if....
  if($num > 0) {
        // author array
        $author_arr = array();
        $author_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);

          $author_item = array(
            'id' => $id,
            'author' => $author
          );

          // Push to "data"
          array_push($author_arr['data'], $author_item);
        }

        // Turn to JSON & output
        echo json_encode($author_arr);
        //else...
  } else {
        // No authors
        echo json_encode(
          array('message' => 'No authors Found')
        );
  }