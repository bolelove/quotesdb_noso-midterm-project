<?php
    class Quote{
        //DB Stuff
        private $conn;
        private $table = 'quotes';

        // Properties

        public $id;
        public $quote;
        public $author_id;
        public $category_id;
        public $author;
        public $category;

        // Constructor with DB 
        public function __construct($db){
            $this->conn =$db;
        }

        public function read() {
          // Retrieve category_id and author_id query string from URL, if present, then convert them to integers
          $category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;
          $author_id = isset($_GET['author_id']) ? intval($_GET['author_id']) : null;
    
          // Create default query
          $query = 'SELECT 
            q.id AS id, 
            q.quote AS quote, 
            c.category AS category,
            a.author AS author 
          FROM ' . $this->table . ' q 
            INNER JOIN categories c ON q.category_id = c.id 
            INNER JOIN authors a ON q.author_id = a.id 
            ORDER BY q.id';
    
          // If category id is provided in URL, return only quotes in that category
          if ($category_id) {
            $query = 'SELECT 
              q.id AS id, 
              q.quote AS quote, 
              c.category AS category,
              a.author AS author 
            FROM ' . $this->table . ' q 
              INNER JOIN categories c ON q.category_id = c.id 
              INNER JOIN authors a ON q.author_id = a.id 
              WHERE category_id = ' . $category_id . ' 
              ORDER BY q.id';
          }
    
          // If author id is provided in URL, return only quotes from that author
          if ($author_id) {
            $query = 'SELECT 
              q.id AS id, 
              q.quote AS quote, 
              c.category AS category,
              a.author AS author 
            FROM ' . $this->table . ' q 
              INNER JOIN categories c ON q.category_id = c.id 
              INNER JOIN authors a ON q.author_id = a.id 
              WHERE author_id = ' . $author_id . ' 
              ORDER BY q.id';
          }
    
          // If both author and category id provided, only return quotes from that author and category
          if ($category_id && $author_id) {
            $query = 'SELECT 
              q.id AS id, 
              q.quote AS quote, 
              c.category AS category,
              a.author AS author 
            FROM ' . $this->table . ' q 
              INNER JOIN categories c ON q.category_id = c.id 
              INNER JOIN authors a ON q.author_id = a.id 
              WHERE author_id = ' . $author_id . ' 
              AND category_id = ' . $category_id . ' 
              ORDER BY q.id';
          }
          
          // Prepare statement
          $stmt = $this->conn->prepare($query);
    
          // Execute query
          $stmt->execute();
    
          return $stmt;
        }   
    
        public function read_single() {
          // Check if given quote exists
          $query = "SELECT * FROM quotes WHERE id = :id";
          $stmt = $this->conn->prepare($query);
          $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
          $stmt->execute();
          $quote = $stmt->fetch(PDO::FETCH_ASSOC);
          
          //Given quote id does not exist, error out
          if (!$quote) {
            echo json_encode(['message' => 'No Quotes Found']);
            exit();
          }
          
          // Create query
          $query = 'SELECT 
            q.id AS id, 
            q.quote AS quote, 
            c.category AS category,
            a.author AS author 
          FROM ' . $this->table . ' q 
            INNER JOIN categories c ON q.category_id = c.id 
            INNER JOIN authors a ON q.author_id = a.id 
          WHERE 
            q.id = ?';
    
          // Prepare statement
          $stmt = $this->conn->prepare($query);
    
          // Bind ID
          $stmt->bindParam(1, $this->id);
    
          // Execute query
          $stmt->execute();
    
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
          // Set properties
          $this->id = $row['id'];
          $this->quote = $row['quote'];
          $this->author = $row['author'];
          $this->category = $row['category'];
        }
    
    
    
    
    
    
    
        public function create() {
    
          // Create query
          $query = 'INSERT INTO ' . 
            $this->table . 
            '(quote, author_id, category_id) 
            VALUES (:quote, :author_id, :category_id)';
    
          // Prepare statement
          $stmt = $this->conn->prepare($query);
    
          // Clean data
          $this->quote = htmlspecialchars(strip_tags($this->quote));
          $this->author_id = htmlspecialchars(strip_tags($this->author_id));
          $this->category_id = htmlspecialchars(strip_tags($this->category_id));
    
          // Bind data
          $stmt->bindParam(':quote', $this->quote);
          $stmt->bindParam(':author_id', $this->author_id);
          $stmt->bindParam(':category_id', $this->category_id);
    
          // Check if provided author exists
          $author_check = $this->conn->prepare('SELECT id FROM authors WHERE id = :author_id');
          $author_check->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
          $author_check->execute();
    
          // No such author, send error message
          if ($author_check->rowCount() === 0) {
            echo json_encode(['message' => 'author_id Not Found']);
            exit;
          }
    
          // Check if provided category exists
          $category_check = $this->conn->prepare('SELECT id FROM categories WHERE id = :category_id');
          $category_check->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
          $category_check->execute();
    
          // No such category, send error message
          if ($category_check->rowCount() === 0) {
            echo json_encode(['message' => 'category_id Not Found']);
            exit;
          }
    
          // Execute query
          if($stmt->execute()) {
            // If successful, print details
            echo json_encode(
              array('id' => $this->conn->lastInsertId(), 
                    'quote' => $this->quote,
                    'author_id' => $this->author_id,
                    'category_id' => $this->category_id)
            );
            return true;
          }
    
          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);
    
          return false;
        } 
    
        public function update() {
    
          // Get raw UPDATE request data
          $data = json_decode(file_get_contents("php://input"), true);
    
          // Check if id provided
          $quote_id = isset($data['id']) ? intval($data['id']) : 0;
    
          // No id provided, error out
          if ($quote_id === 0) {
            echo json_encode(['message' => 'Error: No id provided']);
            exit;
          }
    
          // Check if the given quote id exists
          $query = "SELECT * FROM quotes WHERE id = :id";
          $stmt = $this->conn->prepare($query);
          $stmt->bindParam(':id', $quote_id, PDO::PARAM_INT);
          $stmt->execute();
          $quote = $stmt->fetch(PDO::FETCH_ASSOC);
    
          // Given quote id does not exist, error out
          if (!$quote) {
            echo json_encode(['message' => 'No Quotes Found']);
            exit();
          }
    
          // Grab the given author id
          $author_id = isset($data['author_id']) ? intval($data['author_id']) : 0;
    
          // Check if the given author id exists
          $query = "SELECT * FROM authors WHERE id = :id";
          $stmt = $this->conn->prepare($query);
          $stmt->bindParam(':id', $author_id, PDO::PARAM_INT);
          $stmt->execute();
          $author = $stmt->fetch(PDO::FETCH_ASSOC);
    
          // Given author id does not exist, error out
          if (!$author) {
            echo json_encode(['message' => 'author_id Not Found']);
            exit();
          }
    
          // Grab the given category id
          $category_id = isset($data['category_id']) ? intval($data['category_id']) : 0;
    
          // Check if the given category id exists
          $query = "SELECT * FROM categories WHERE id = :id";
          $stmt = $this->conn->prepare($query);
          $stmt->bindParam(':id', $category_id, PDO::PARAM_INT);
          $stmt->execute();
          $category = $stmt->fetch(PDO::FETCH_ASSOC);
    
          // Given category id does not exist, error out
          if (!$category) {
            echo json_encode(['message' => 'category_id Not Found']);
            exit();
          }
    
          // Create query
          $query = 'UPDATE ' . $this->table . '
            SET quote = :quote, author_id = :author_id, category_id = :category_id
            WHERE id = :id';
    
          // Prepare statement
          $stmt = $this->conn->prepare($query);
    
          // Clean data
          $this->quote = htmlspecialchars(strip_tags($this->quote));
          $this->author_id = htmlspecialchars(strip_tags($this->author_id));
          $this->category_id = htmlspecialchars(strip_tags($this->category_id));
          $this->id = htmlspecialchars(strip_tags($this->id));
    
          // Bind data
          $stmt->bindParam(':quote', $this->quote);
          $stmt->bindParam(':author_id', $this->author_id);
          $stmt->bindParam(':category_id', $this->category_id);
          $stmt->bindParam(':id', $this->id);
    
          // Execute query
          if($stmt->execute()) {
            return true;
          }
    
          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);
    
          return false;
        }
        
            public function delete() {
    
          // Get raw DELETE request data
          $data = json_decode(file_get_contents("php://input"), true);
    
          // Check if id provided
          $quote_id = isset($data['id']) ? intval($data['id']) : 0;
    
          // No id provided, error out
          if ($quote_id === 0) {
            echo json_encode(['message' => 'Error: No id provided']);
            exit;
          }
    
          // Check if the given quote id exists
          $query = "SELECT * FROM quotes WHERE id = :id";
          $stmt = $this->conn->prepare($query);
          $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
          $stmt->execute();
          $quote = $stmt->fetch(PDO::FETCH_ASSOC);
    
          // Given quote id does not exist, error out
          if (!$quote) {
            echo json_encode(['message' => 'No Quotes Found']);
            exit();
          }
    
          // Create query
          $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
    
          // Prepare statement
          $stmt = $this->conn->prepare($query);
    
          // Clean data
          $this->id = htmlspecialchars(strip_tags($this->id));
    
          // Bind data
          $stmt->bindParam(':id', $this->id);
    
          // Execute query
          $stmt->execute();
    
          // Check that a deletion has actually taken place
          if ($stmt->rowCount() > 0) {
            echo json_encode(['id' => $this->id]);
            return true;
          } else if ($stmt->rowCount() === 0) {
            // Row count is 0 -> no deletion has taken place
            echo json_encode(['message' => 'No Quotes Found']);
          } else {
          // In case something goes wrong
          printf("Error: %s.\n", $stmt->error);
          return false;
          }
        } 
      }
    ?>