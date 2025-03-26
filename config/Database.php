<?php
class Database {
    private $conn;
    private $host;
    private $dbname;
    private $username;
    private $password;
    

    public function __construct() {
        $this->username = getenv('USERNAME');
        $this->password = getenv('PASSWORD');
        $this->dbname = getenv('DBNAME');
        $this->host = getenv('HOST');
        
    }
  
      public function connect() {
        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
        if (is_null($this->conn)) {
          try { 
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
          }
        } 
        return $this->conn;    
      }
    }
    
}

// $this->port = getenv('PORT');
//  private $port;
?>
