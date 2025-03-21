
<?php   
    //Headers

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    
    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';


    // Instantiate DB & connect

    $database = new Database();
    $db = $database->connect();

    
    // Instantiate quote object

    $quote = new Quote($db);

    // Blog quote query

    $result = $quote -> read();
    //Get row count
    $num = $result->rowCount();

    //Check if any quotes

    if($num>0){
        // quote Array
        $quote_arr = array();
        $quotes_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $quote_item = array(
                'id' => $id,
                'quote' => $quote,
                'author_id' => $author_id,
                'category_id' => $category_id,
                
            );

            // Push to "data

            array_push($quotes_arr['data'], $quote_item);
        }

        // Trun to JSON & output

        echo json_encode($quotes_arr);

    } else{

        // No quotes
        echo json_encode(
            array('message' => 'No quotes Found')
        );

    }