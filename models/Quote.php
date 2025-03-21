<?php 
  class Quote {
    // DB stuff
    private $conn;
    private $table = 'quotes';

    // quote Properties
    public $id;
    public $quote;
    public $author_id;
    public $category_id;
    
    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get quotes
    public function read() {
      // Create query
      $query = 'SELECT p.id, p.quote, p.author_id, p.category_id
                                FROM ' . $this->table . ' p
                                LEFT JOIN
                                  categories c ON p.category_id = c.id
                                LEFT JOIN
                                  authors a ON p.author_id = a.id
                                ORDER BY
                                  p.id DESC';
      
      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Post

    public function read_single(){
        // Create query
        $query = 'SELECT p.id, p.quote, p.author_id, p.category_id
        FROM ' . $this->table . ' p
        LEFT JOIN
            categories c ON p.category_id = c.id
        WHERE 
            p.id = ?
        LIMIT 0,1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->quote = $row['quote'];
        $this->author_id = $row['author_id'];
        $this->category_id = $row['category_id'];
        
    }

    // Create quote

    public function create(){
      // Create query
      $query = 'INSERT INTO ' . 
      $this->table . '
        SET
          quote = :quote,
          author_id = :author_id,
          category_id = :category_id';

      // Prepare statemnet
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->quote = htmlspecialchars(strip_tags($this->quote));
      $this->author_id = htmlspecialchars(strip_tags($this->author_id));
      $this->category_id = htmlspecialchars(strip_tags($this->category_id));
      
      //Bind data
      $stmt->bindParam(':quote', $this->quote);
      $stmt->bindParam(':author_id', $this->author_id);
      $stmt->bindParam(':category_id', $this->category_id);

      //Execute query
      if($stmt->execute()){
        return true;

      }

      // Print error
      printf("Error: %s.\n", $stmt->error);
      
      return false;
    }

    public function update() {
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
  
  
  // Delete Quote
  public function delete() {
    // Create query
    $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean data
    $this->id = htmlspecialchars(strip_tags($this->id));

    // Bind data
    $stmt->bindParam(':id', $this->id);

    // Execute query
    if($stmt->execute()) {
      return true;
    }

    // Print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);

    return false;
  }
}