<?php
class Database{
    
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "id13357026_saravanasupermarket";
    private $username = "id13357026_nss";
    private $password = "NellaiSystems@123";
    public $conn;
    
    // get the database connection
    public function getConnection(){
        $conn=new mysqli( $this->host,$this->username,$this->password,$this->db_name);
        if (!$conn) {
            die('Could not connect to database!');
        } else {
            $this->conn = $conn;
            echo 'Connection established!';
            
        }
        return $this->conn;
        
        
    }
}
?>