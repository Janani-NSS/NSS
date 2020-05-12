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
        
        // get retrieved row
        /* while($row = $stmt->fetch(PDO::FETCH_ASSOC))
         {
         //exract($row);
         $this->queryData = $row['queryData'];
         $this->displayName = $row['displayName'];
         $this->imageURL = $row['imageURL'];
         $this->mrp = $row['mrp'];
         $this->price = $row['price'];
         $this->save = $row['save'];
         $this->prodCode = $row['prodCode'];
         $this->prodName = $row['prodName'];
         $this->Brand = $row['Brand'];
         //$this->Product_Department = $row['Product_Department'];
         }*/
        //echo $row;
    }
}
?>