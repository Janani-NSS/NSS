<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
date_default_timezone_set("Asia/Kolkata");
// get database connection
include_once 'database.php';

class Order{
    
    public $conn;
    private $table_name = "id13357026_saravanasupermarket.Order";
    
    
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
    public $doorNo;
    public $street;
    public $landMark;
    public $location;
    public $city;
    public $pincode;
    public $state;
    
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    public function placeOrder()
    {
        try {
            $query="";
            $data= file_get_contents("php://input");
            $array=json_decode($data,true);
            
            
            
            $today = date("Ymd");
            $rand = strtoupper(substr(uniqid(sha1(time())),0,4));
            $orderID="ID" . $today . $rand;
            $date=date("Y-m-d");
            $time=date("h:i a");
            $name=$array["name"];
            $mobileNo=$array["mobileNo"];
            
            
            
            $doorNo=$array["deliveryAddress"]["doorNo"];
            $street=$array["deliveryAddress"]["street"];
            $landMark=$array["deliveryAddress"]["landMark"];
            $location=$array["deliveryAddress"]["location"];
            $city=$array["deliveryAddress"]["city"];
            $pincode=$array["deliveryAddress"]["pincode"];
            $state=$array["deliveryAddress"]["state"];
            
            foreach($array["OrderItems"] AS $row2)
            {
                $prodCode=$row2["prodCode"];
                $prodName=$row2["prodName"];
                $queryData=$row2["queryData"];
                $Brand=$row2["Brand"];
                $selectedCount=$row2["selectedCount"];
                if($row2["selectedCount"]>1)
                {
                    $mrp=$row2["mrp"]*$row2["selectedCount"];
                    $price=$row2["price"]*$row2["selectedCount"];
                }else{
                    
                    $mrp=$row2["mrp"];
                    $price=$row2["price"];
                }
                
                $query .= "INSERT INTO ".$this->table_name." (Order_OrderNo, Order_Date,Order_Time,Order_CustomerName,
                  Order_MobileNo,Order_ShippingAddress1,Order_ShippingAddress2,Order_ShippingAddress3,Order_ShippingAddress4,
                   Order_ShippingAddress5,Order_ShippingAddress6,Order_ShippingAddress7,Order_ProductCode,
                   Order_ProductName,Order_Department,Order_ProductBrand,
                  Order_ProductQuantity,Order_MRP,Order_DiscountRate,Order_Status) VALUES(
                  '".$orderID."','".$date."','".$time."','".$name."','".$mobileNo."','".$doorNo."','".$street."','".$landMark."','".$location."','".$city."','".$pincode."','".$state."','".$prodCode."',
                  '".$prodName."','".$queryData."','".$Brand."','".$selectedCount."','".$mrp."','".$price."','Pending');";
                
                // prepare query
                $stmt = $this->conn->prepare($query);
                //print($query);
            }
            
            
            // execute query
            if($stmt->execute($row))
            {
                // set response code - 201 created
                http_response_code(201);
                //print($query);
                // tell the user
                echo json_encode(array("OrderStates" => "You Successfully Place The Order",
                    "orderID" =>"$orderID"
                ));
            }
            else{
                $array = $stmt->errorInfo();
                
                
                // set response code - 503 service unavailable
                http_response_code(503);
                
                // tell the user
                echo json_encode(array("OrderStates" => "Order Failure"));
            }
        }
        catch (PDOException $e)
        {
            die("ERROR: Could not able to execute $query. "
                .$e->getMessage());
        }
        
    }
}

// create product


$database = new Database();
$db = $database->getConnection();

$order = new Order($db);
$order->placeorder();



?>