<?php
class Product{
    
    private $conn;
    //private $table_name = "ProductInfo";
    
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
    public $category;
    public $productNameTerm;
    public $skipUpTo;
    public $limit;
    
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    public function readOne(){ 
        //$query = "SELECT Product_Department AS queryData,Product_Name AS displayName,Product_PhotoPath AS imageURL,Product_MRP AS mrp,Product_SRate AS price,Product_DiscountRate AS save,Product_Code AS prodCode,Product_Name AS prodName,Product_Brand AS Brand FROM productinfo ";
      
        if(isset($_POST['productNameTerm'])|| !($_POST['category']))
        {          
            
            $productNameTerm=$_POST['productNameTerm'];
            $query = "SELECT Product_Department AS queryData,Product_Name AS displayName,Product_PhotoPath AS imageURL,Product_MRP AS mrp,Product_SRate AS price,Product_DiscountRate AS save,Product_Code AS prodCode,Product_Name AS prodName,Product_Brand AS Brand FROM productinfo WHERE Product_Name LIKE '%" .$productNameTerm. "%'  ORDER BY Product_Name ASC";
            
        }
        elseif(isset($_POST['category'])||!($_POST['category']) && ($_POST['productNameTerm']))
        {
            
            $category=$_POST['category'];
            $query = "SELECT Product_Department AS queryData,Product_Name AS displayName,Product_PhotoPath AS imageURL,Product_MRP AS mrp,Product_SRate AS price,Product_DiscountRate AS save,Product_Code AS prodCode,Product_Name AS prodName,Product_Brand AS Brand FROM productinfo WHERE Product_Department ='$category' ORDER BY Product_Name ASC";
            
        } 
        elseif(isset($_POST['category']) && ($_POST['productNameTerm']) || !($_POST['category']) )
        {
            
            $category=$_POST['category'];
            $productNameTerm=$_POST['productNameTerm'];
            $query = "SELECT Product_Department AS queryData,Product_Name AS displayName,Product_PhotoPath AS imageURL,Product_MRP AS mrp,Product_SRate AS price,Product_DiscountRate AS save,Product_Code AS prodCode,Product_Name AS prodName,Product_Brand AS Brand FROM productinfo WHERE Product_Department ='$category' AND Product_Name LIKE '%" .$productNameTerm. "%'  ORDER BY Product_Name ASC";
            
        }
        elseif(isset($_POST['skipUpTo']) && ($_POST['limit'])){
            
            $skipUpTo=$_POST['skipUpTo'];
            $limit=$_POST['limit'];
            $query = "SELECT Product_Department AS queryData,Product_Name AS displayName,Product_PhotoPath AS imageURL,Product_MRP AS mrp,Product_SRate AS price,Product_DiscountRate AS save,Product_Code AS prodCode,Product_Name AS prodName,Product_Brand AS Brand FROM productinfo ORDER BY Product_Name ASC LIMIT ".$skipUpTo.", ".$limit." ";
        } 
        elseif(isset($_POST['category']) && ($_POST['skipUpTo']) &&($_POST['limit']) || !($_POST['category'])){
            $category=$_POST['category'];
            $skipUpTo=$_POST['skipUpTo'];
            $limit=$_POST['limit'];
            $query = "SELECT Product_Department AS queryData,Product_Name AS displayName,Product_PhotoPath AS imageURL,Product_MRP AS mrp,Product_SRate AS price,Product_DiscountRate AS save,Product_Code AS prodCode,Product_Name AS prodName,Product_Brand AS Brand FROM productinfo WHERE Product_Department ='$category' ORDER BY Product_Name ASC LIMIT ".$skipUpTo.", ".$limit." ";
        }
       
        elseif(isset($_POST['productNameTerm']) && ($_POST['skipUpTo']) &&($_POST['limit'])){
            $productNameTerm=$_POST['productNameTerm'];
            $skipUpTo=$_POST['skipUpTo'];
            $limit=$_POST['limit'];
            $query = "SELECT Product_Department AS queryData,Product_Name AS displayName,Product_PhotoPath AS imageURL,Product_MRP AS mrp,Product_SRate AS price,Product_DiscountRate AS save,Product_Code AS prodCode,Product_Name AS prodName,Product_Brand AS Brand FROM productinfo WHERE Product_Name LIKE '%" .$productNameTerm. "%'  ORDER BY Product_Name ASC LIMIT ".$skipUpTo.", ".$limit."";
        }
       
        elseif(isset($_POST['category']) && ($_POST['productNameTerm']) && ($_POST['skipUpTo']) &&($_POST['limit']))
        {
            $category=$_POST['category'];
            $productNameTerm=$_POST['productNameTerm'];
            $skipUpTo=$_POST['skipUpTo'];
            $limit=$_POST['limit'];
            $query = "SELECT Product_Department AS queryData,Product_Name AS displayName,Product_PhotoPath AS imageURL,Product_MRP AS mrp,Product_SRate AS price,Product_DiscountRate AS save,Product_Code AS prodCode,Product_Name AS prodName,Product_Brand AS Brand FROM productinfo WHERE Product_Department ='$category' AND Product_Name LIKE '%" .$productNameTerm. "%'  ORDER BY Product_Name ASC LIMIT ".$skipUpTo.", ".$limit."";
            
        }
        else
        {
            $query = "SELECT Product_Department AS queryData,Product_Name AS displayName,Product_PhotoPath AS imageURL,Product_MRP AS mrp,Product_SRate AS price,Product_DiscountRate AS save,Product_Code AS prodCode,Product_Name AS prodName,Product_Brand AS Brand FROM productinfo ORDER BY Product_Name ASC LIMIT 50";
        }
        
        
      /* if(isset($_POST['category']))
        {
            $category=$_POST['category'];
            if(strpos($query, "WHERE")== false)
            {
                $query1 = "WHERE Product_Department ='$category' ";
            }
            //$query .= " Product_Department ='$category' ";
        }
        
        if(isset($_POST['productNameTerm']))
        {
            $productNameTerm=$_POST['productNameTerm'];
            if(strpos($query, "WHERE")== false)
            {
                $query1 = "WHERE Product_Name LIKE '%" .$productNameTerm. "%' ";
            }
            else
            {
                $query2 ="AND Product_Name LIKE '%" .$productNameTerm. "%'";
            }
            
        }
        
        if(isset($_POST['category']) && ($_POST['productNameTerm']))
        {
            $category=$_POST['category'];
            $productNameTerm=$_POST['productNameTerm'];
            if(strpos($query, "WHERE")== false)
            {
                $query1 = "WHERE Product_Department='.$category.' AND Product_Name LIKE '%" .$productNameTerm. "%' ";
            }
            
            //$query .="Product_Department='.$category.' AND Product_Name LIKE '%" .$productNameTerm. "%' ";
        }
        if(isset($_POST['skipUpTo']) &&($_POST['limit'])){
            $skipUpTo=$_POST['skipUpTo'];
            $limit=$_POST['limit'];
            $query1 ="LIMIT ".$skipUpTo.", ".$limit."";
        }
        
        //if(strpos($query, "WHERE")== false && strpos($query, "LIMIT")== false){
            //$query2 = " LIMIT 50 ";
        //}
        $query0="";
        $query2 =" ORDER BY Product_Name ASC LIMIT 50 ";
        //$sql = $query . "ORDER BY Product_Name ASC";
        if(strpos($query0, "WHERE")==false){
            
            $sql =$query . $query0 . $query2 ;
        }
        else {
            $sql =$query . $query1 . $query2 ;
        }*/
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        return $stmt;
        
    }
    // search products
    function search($keywords){
        
        // select all query
        $query = "SELECT Product_Department AS queryData,Product_Name AS displayName,Product_PhotoPath AS imageURL,Product_MRP AS mrp,Product_SRate AS price,Product_DiscountRate AS save,Product_Code AS prodCode,Product_Name AS prodName,Product_Brand AS Brand FROM ProductInfo
                   WHERE Product_Name LIKE ? ORDER BY Product_Name ASC";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
        
        // bind
        $stmt->bindParam(1, $keywords);
        //$stmt->bindParam(2, $keywords);
        //$stmt->bindParam(3, $keywords);
        
        // execute query
        $stmt->execute();
        
        return $stmt;
    }
    // read products with pagination
    public function readPaging($skipUpTo, $limit){
        
        // select query
        $query = "SELECT Product_Department AS queryData,Product_Name AS displayName,Product_PhotoPath AS imageURL,Product_MRP AS mrp,Product_SRate AS price,Product_DiscountRate AS save,Product_Code AS prodCode,Product_Name AS prodName,Product_Brand AS Brand FROM ProductInfo ORDER BY Product_Name ASC
            LIMIT ?, ?";
        
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
       
        
        // bind variable values
        $stmt->bindParam(3, $skipUpTo, PDO::PARAM_INT);
        $stmt->bindParam(4, $limit, PDO::PARAM_INT);
        
        // execute query
        $stmt->execute();
        
        // return values from database
        return $stmt;
    }
    // used for paging products
    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM ProductInfo";
        
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total_rows'];
    }
}
?>