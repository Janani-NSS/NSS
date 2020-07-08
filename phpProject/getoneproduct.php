<?php 

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once 'database.php';

class Product{
    
    private $conn;
    
    
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
        
        if(isset($_POST['prodCode']))
        {
        $prodCode=$_POST['prodCode'];
        $query = "SELECT Product_Department AS queryData,Product_Name AS displayName,Product_PhotoPath AS imageURL,Product_MRP AS mrp,Product_DiscountRate AS price,Product_MRP-Product_DiscountRate AS save,Product_Code AS prodCode,Product_Name AS prodName,Product_Brand AS Brand FROM ProductInfo WHERE Product_Code='$prodCode' ";
        }        
             
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        return $stmt;
        
    }
    
}

// get database connection
$database = new Database();
$db = $database->getConnection();


// prepare product object
$product=new Product($db);

// set ID property of record to read
//$product->category = isset($_GET['category']) ? $_GET['category'] : die();

// read the details of product to be edited
$stmt=$product->readOne();
$num = $stmt->rowCount();
if($num>0){
    // products array
    $products_arr=array();
    $products_arr["productList"]=array();
    
    // create array
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        extract($row);
        
        $product_item = array(
            
            "queryData" =>  $queryData,
            "displayName" => $displayName,
            "imageURL" => $imageURL,
            "mrp" => $mrp,
            "price" => $price,
            "save" => $save,
            "prodCode" => $prodCode,
            "prodName" => $prodName,
            "Brand" => $Brand
        );
        array_push($products_arr["productList"], $product_item);
    }
    // set response code - 200 OK
    http_response_code(200);
    
    // make it json format
    echo json_encode($products_arr);
}
else{
    // set response code - 404 Not found
    http_response_code(404);
    
    //tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
        );
}

?>