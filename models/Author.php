<?php
    class Author{
        //DB Stuff
        private $conn;
        private $table = 'authors';

        // Properties

        public $id;
        public $author; 

        // Constructor with DB 
        public function __construct($db){
            $this->conn =$db;
        }

        // Get categories
        public function read(){
          
            $query = 'SELECT
                id,
                author
            FROM
                '  . $this->table  . ' 
            //ORDER BY
                //id DESC';
            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }
        public function read_single(){
            // Check if given author exists
            $query = "SELECT * FROM authors WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
            $author = $stmt->fetch(PDO::FETCH_ASSOC);
          
            //Given author id does not exist, error out
            if (!$author) {
              echo json_encode(['message' => 'author_id Not Found']);
              exit();
            }
      
            // Create query
            $query = 'SELECT
                  id,
                  author
                FROM
                  ' . $this->table . '
                WHERE id = ?';
      
            //Prepare statement
            $stmt = $this->conn->prepare($query);
      
            // Bind ID
            $stmt->bindParam(1, $this->id);
      
            // Execute query
            $stmt->execute();
      
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
            // set properties
            $this->id = $row['id'];
            $this->author = $row['author'];
          }