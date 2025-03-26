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
          

    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (author) VALUES (:author)';
  
        // Prepare Statement
        $stmt = $this->conn->prepare($query);
  
        // Clean data
        $this->author = htmlspecialchars(strip_tags($this->author));
  
        // Bind data
        $stmt-> bindParam(':author', $this->author);
  
        // Execute query
        if($stmt->execute()) {
          echo json_encode(
            array('id' => $this->conn->lastInsertId(),
                  'author' => $this->author)
          );
          return true;
        }
  
        // Print error if something goes wrong
        printf("Error: $s.\n", $stmt->error);
  
        return false;
      }
  
  
  
  
  
  
  
      public function update() {
  
        // Get raw UPDATE request data
        $data = json_decode(file_get_contents("php://input"), true);
  
        // Check if id provided
        $author_id = isset($data['id']) ? intval($data['id']) : 0;
  
        // No id provided, error out
        if ($author_id === 0) {
          echo json_encode(['message' => 'Error: No id provided']);
          exit;
        }
  
        // Check if the given category id exists
        $query = "SELECT * FROM authors WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $author_id, PDO::PARAM_INT);
        $stmt->execute();
        $author = $stmt->fetch(PDO::FETCH_ASSOC);
  
        // Given author id does not exist, error out
        if (!$author) {
          echo json_encode(['message' => 'No Author Found']);
          exit();
        }
  
        if (!isset($data['author'])) {
          echo json_encode(['message' => 'Error: No author name provided']);
          exit;
        }
  
        // Create Query
        $query = 'UPDATE ' .
          $this->table . '
        SET
          author = :author
          WHERE
          id = :id';
  
        // Prepare Statement
        $stmt = $this->conn->prepare($query);
  
        // Clean data
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->id = htmlspecialchars(strip_tags($this->id));
  
        // Bind data
        $stmt-> bindParam(':author', $this->author);
        $stmt-> bindParam(':id', $this->id);
  
        // Execute query
        if($stmt->execute()) {
          return true;
        }
  
        // Print error if something goes wrong
        printf("Error: $s.\n", $stmt->error);
  
        return false;
      }
  
  
  
  
  
  
  
      public function delete() {
  
        // Get raw DELETE request data
        $data = json_decode(file_get_contents("php://input"), true);
  
        // Check if id provided
        $author_id = isset($data['id']) ? intval($data['id']) : 0;
  
        // No id provided, error out
        if ($author_id === 0) {
          echo json_encode(['message' => 'Error: No id provided']);
          exit;
        }
  
        // Check if the given category id exists
        $query = "SELECT * FROM authors WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $author_id, PDO::PARAM_INT);
        $stmt->execute();
        $author = $stmt->fetch(PDO::FETCH_ASSOC);
  
        // Given author id does not exist, error out
        if (!$author) {
          echo json_encode(['message' => 'No Author Found']);
          exit();
        }
  
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
  
        // Prepare Statement
        $stmt = $this->conn->prepare($query);
  
        // clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
  
        // Bind Data
        $stmt-> bindParam(':id', $this->id);
  
        // Execute query
        if($stmt->execute()) {
          return true;
        }
  
        // Print error if something goes wrong
        printf("Error: $s.\n", $stmt->error);
  
        return false;
      }
    }
  ?>