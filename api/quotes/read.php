<?php 
  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate quote object
  $quote  = new Quote($db);

  // Quote read query
  $result = $quote->read();  

  // Get row count
  $num = $result->rowCount();

  // Check if there are any quotes
  if($num > 0) {
        // Initialize quote array and the 'data' key as an array
        $quotes_arr = array();
        $quotes_arr['data'] = array();  // Initialize 'data' as an empty array

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);

          $quote_item = array(
            'id' => $id,
            'quote' => $quote, 
            'author' => $author,
            'category' => $category
          );

          // Push to "data" key of $quotes_arr
          array_push($quotes_arr['data'], $quote_item);
        }

        // Turn to JSON & output
        echo json_encode($quotes_arr);
  } else {
        // No quotes found
        echo json_encode(
          array('message' => 'No quotes Found')
        );
  }
?>
