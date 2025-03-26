<?php 

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate quote   pbject
  $quote  = new Quote($db);

  // Quote read query
  $result = $quote ->read();  

  // Get row count
  $num = $result->rowCount();

  // check quote if....
  if($num > 0) {
        // quote  array
        $quotes_arr = array();
        $quote_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);

          $quote_item = array(
            'id' => $id,
            'quote' => $quote, 
            'author' => $author,
            'category' => $category
          );

          // Push to "data"
          array_push($quote_arr['data'], $quote_item);
        }

        // Turn to JSON & output
        echo json_encode($quote_arr);
        //else...
  } else {
        // No categories
        echo json_encode(
          array('message' => 'No categories Found')
        );
    }
  ?>

