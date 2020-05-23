<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once 'database.php';

class Order{
    
    private $conn;
    private $table_name = "Order";
    
    
    // object properties
    public $mobileNo;
    public $name;
    public $custLoc;
    public $deliveryAddress;
    public $cartProdCount;
    public $Key;
    public $ProdCode;
    public $Value;
    public $Count;
     
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // create product
    public function placeorder(){
        
        
   try {
       $query="";
            $data= file_get_contents("php://input");
            $array=json_decode($data,true);
            foreach($array as $row) 
            {
                      
                  $query .= "INSERT INTO id13357026_saravanasupermarket.Order (Order_OrderNo, Order_Date,Order_CustomerName,
                  Order_MobileNo,Order_ShippingAddress,Order_ProductCode,
                  Order_ProductName,Order_Department,Order_ProductBrand,
                  Order_ProductQuantity,Order_MRP,Order_Amount) VALUES(
                  '".$row["orderId"]."','".$row["date"]."','".$row["name"]."','".$row["mobileNo"]."','".$row["deliveryAddress"]."','".$row["ProdCode"]."',
                  '".$row["ProdName"]."','".$row["category"]."','".$row["Brand"]."','".$row["cartProdCount"]."','".$row["mrp"]."','".$row["amount"]."');";
            
                    // prepare query
                    $stmt = $this->conn->prepare($query);
                 
                    // execute query
                    if($stmt->execute($row))
                   {
                         //print($query);
                         return true;
                   }
                   else{
                         $array = $stmt->errorInfo();
                         print_r($array);
                 }
            
            }
          
   }
    catch (PDOException $e)
   {
        die("ERROR: Could not able to execute $query. "
            .$e->getMessage());
   } 
   return false;
  }
    
}

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);

// create the product
if($order->placeorder()){
    
    // set response code - 201 created
    http_response_code(201);
    
    // tell the user
    echo json_encode(array("OrderStates" => "You Successfully Place The Order",
        "Order_OrderNo" =>"$order->Order_OrderNo"
    ));
}

// if unable to create the product, tell the user
else{
    
    // set response code - 503 service unavailable
    http_response_code(503);
    
    // tell the user
    echo json_encode(array("OrderStates" => "Order Failure"));
}
?>