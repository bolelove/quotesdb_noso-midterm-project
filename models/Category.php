<?php
    class Category{
        //DB Stuff
        private $conn;
        private $table = 'categories';

        // Properties

        public $id;
        public $category;

        // Constructor with DB 
        public function __construct($db){
            $this->conn =$db;
        }

        // Get categories
        public function read(){
            // Create query
            $query = 'SELECT
                id,
                category
            FROM
                '  . $this->table;

            //$stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

           public function read_single(){
      // Check if the given category id exists
      $query = "SELECT * FROM categories WHERE id = :id";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
      $stmt->execute();
      $category = $stmt->fetch(PDO::FETCH_ASSOC);
      
      //Given category id does not exist, error out
      if (!$category) {
        echo json_encode(['message' => 'category_id Not Found']);
        exit();
      }
      
      // Create query
      $query = 'SELECT
            id,
            category
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
      $this->category = $row['category'];
    }
    public function create() {
      $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category)';

      // Prepare Statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->category = htmlspecialchars(strip_tags($this->category));

      // Bind data
      $stmt-> bindParam(':category', $this->category);

      // Execute query
      if($stmt->execute()) {
        echo json_encode(
          array('id' => $this->conn->lastInsertId(),
                'category' => $this->category)
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
      $category_id = isset($data['id']) ? intval($data['id']) : 0;

      // No id provided, error out
      if ($category_id === 0) {
        echo json_encode(['message' => 'Error: No id provided']);
        exit;
      }

      // Check if the given category id exists
      $query = "SELECT * FROM categories WHERE id = :id";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':id', $category_id, PDO::PARAM_INT);
      $stmt->execute();
      $category = $stmt->fetch(PDO::FETCH_ASSOC);

      // Given category id does not exist, error out
      if (!$category) {
        echo json_encode(['message' => 'No Category Found']);
        exit();
      }

      if (!isset($data['category'])) {
        echo json_encode(['message' => 'Error: No category name provided']);
        exit;
      }

      // Create Query
      $query = 'UPDATE ' .
        $this->table . '
      SET
        category = :category
      WHERE
        id = :id';

      // Prepare Statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->category = htmlspecialchars(strip_tags($this->category));
      $this->id = htmlspecialchars(strip_tags($this->id));

      // Bind data
      $stmt-> bindParam(':category', $this->category);
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
      $category_id = isset($data['id']) ? intval($data['id']) : 0;

      // No id provided, error out
      if ($category_id === 0) {
        echo json_encode(['message' => 'Error: No id provided']);
        exit;
      }

      // Check if the given category id exists
      $query = "SELECT * FROM categories WHERE id = :id";
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':id', $category_id, PDO::PARAM_INT);
      $stmt->execute();
      $category = $stmt->fetch(PDO::FETCH_ASSOC);

      // Given category id does not exist, error out
      if (!$category) {
        echo json_encode(['message' => 'No Category Found']);
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