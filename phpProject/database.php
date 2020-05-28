<?php
class Database{
    
    // specify your own database credentials
    private $host = "148.72.232.176:3306";
    private $db_name = "id13357026_saravanasupermarket";
    private $username = "NSS";
    private $password = "NSS@123";
    public $conn;
    
    // get the database connection
    public function getConnection(){
        
        $this->conn = null;
        
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}
?>