<?php
class Product{
    
    private $conn;
    private $table_name = "ProductInfo";
    
    // object properties
    public $queryData;
    public $displayName;
    public $imageURL;
    public $mrp;
    public $price;
    public $Save;
    public $prodCode;
    public $prodName;
    public $Brand;
    public $Product_Department;
    public $Product_Name;
    
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    function readOne(){
        
        // query to read single record
        $query = "SELECT Product_Department AS queryData,Product_Name AS displayName,Product_PhotoPath AS imageURL,Product_MRP AS mrp,Product_SRate AS price,Product_DiscountRate AS save,Product_Code AS prodCode,Product_Name AS prodName,Product_Brand AS Brand FROM ProductInfo WHERE Product_Department ='$this->Product_Department'";
        
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        //echo $this->conn->error;
        
        
        // bind id of product to be updated
        $stmt->bindParam("s",$this->Product_Department);
        
        // execute query
        $stmt->execute();
        return $stmt;
        
        
        //$arr = $stmt->errorInfo();
        //print_r($arr);
        
       
        //echo $row;
    }
    // search products
    function search($keywords){
        
        // select all query
        $query = "SELECT Product_Department AS queryData,Product_Name AS displayName,Product_PhotoPath AS imageURL,Product_MRP AS mrp,Product_SRate AS price,Product_DiscountRate AS save,Product_Code AS prodCode,Product_Name AS prodName,Product_Brand AS Brand FROM ProductInfo 
                   WHERE Product_Department LIKE ? OR Product_Name LIKE ? ORDER BY Product_Name ASC";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
        
        // bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        //$stmt->bindParam(3, $keywords);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
}
?>