<?php
class Product{
    
    // database connection and table name
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
        $query = "SELECT Product_Department AS queryData,Product_Name AS displayName,Product_PhotoPath AS imageURL,Product_MRP AS mrp,Product_SRate AS price,Product_DiscountRate AS save,Product_Code AS prodCode,Product_Name AS prodName,Product_Brand AS Brand FROM ProductInfo WHERE  Product_Department= '?' ";
        
        //$result=$this->conn->query($sql);
        $stmt=$this->conn->prepare( $query );
        
        echo $this->conn->error;
        
        // bind id of product to be updated
        $stmt->bind_param("s",$this->Product_Department);
        
        // execute query
        $stmt->execute();
        
        $stmt->bind_result(
            $this->queryData,
            $this->displayName,
            $this->imageURL,
            $this->mrp,
            $this->price,
            $this->save,
            $this->prodCode,
            $this->prodName,
            $this->Brand
            );
        $stmt->store_result();
        $result=$stmt -> get_result();
        // get retrieved row
        //echo $result;
        $row = mysqli_fetch_assoc($result);
        
        //echo $row;
        // set values to object properties
        $this->displayName = $row['displayName'];
        $this->imageURL = $row['imageURL'];
        $this->mrp = $row['mrp'];
        $this->price = $row['price'];
        $this->save = $row['save'];
        $this->prodCode = $row['prodCode'];
        $this->prodName = $row['prodName'];
        $this->Brand = $row['Brand'];
        $this->Product_Department = $row['Product_Department'];
        
        
    }
}
?>