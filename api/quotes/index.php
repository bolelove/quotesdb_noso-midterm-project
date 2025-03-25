<?php

    require_once '../../config/Database.php';
    require_once '../../models/Quote.php';
    include_once '../../config/Database.php'; 
    include_once '../../models/Quote.php';   

    $method = $_SERVER['REQUEST_METHOD'];
    $endpoint = $_SERVER['REQUEST_URI'];

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    

    if ($method === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        exit();
    }
    
    switch($method){
        case 'GET':
            require '../../API/quotes/read.php';
            break;
        case 'POST':
            require '../../API/quotes/create.php';
            break; 
        case 'PUT':
            require '../../API/quotes/update.php';
            break; 
        case 'DELETE':
            require '../../API/quotes/delete.php';
            break;  
    }