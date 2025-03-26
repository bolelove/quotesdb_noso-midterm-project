<?php 
  class Database {
    private $conn;
    private $host;
    private $db_name;
    private $username;
    private $password;

    public function __construct() {
      $this->username = getenv('USERNAME');
      $this->password = getenv('PASSWORD');
      $this->db_name = getenv('DBNAME');
      $this->host = getenv('HOST');
      
    }
    
    // DB Connect
    public function connect() {
      if ($this->conn) {
        return $this->conn;
      } else {
        // $dsn = "pgsql:host={$this->host};port={$this->port};db_name={$this->db_name}";
        $dsn = "pgsql:host={$this->host};db_name={$this->db_name}";

        try {
          $this->conn = new PDO($dsn, $this->username, $this->password);
          $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
          echo 'Connection Error: ' . $e->getMessage();
        }
        return $this->conn;
      }
    }
  }

// $this->port = getenv('PORT');
//  private $port;
?>
