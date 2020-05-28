<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once 'database.php';

class OrderList{
    
    private $conn;
    private $table_name = "id13357026_saravanasupermarket.Order";
    
    // object properties
    public $name;
    public $mobileNo;
    public $orderID;
    public $mrp;
    public $price;
    public $status;
    public $Save;
    public $Date;
    public $Time;
    public $doorNo;
    public $street;
    public $landMark;
    public $location;
    public $city;
    public $pincode;
    public $state;
    public $Prodcode;
    public $ProdName;
    public $Brand;
    public $category;
    public $cartProdCount;
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // create product
    public function getOrderList(){
        
        
        try {
            
           $query=" SELECT Order_MobileNo AS mobileNo, Order_CustomerName AS name,Order_OrderNo AS orderID, 
                  Order_MRP AS mrp,Order_DiscountRate AS price,Order_MRP-Order_DiscountRate AS Save,Order_Status AS status,
                   Order_ShippingAddress1 AS doorNo,Order_ShippingAddress2 AS street,Order_ShippingAddress3 AS landMark,Order_ShippingAddress4 AS location,
                   Order_ShippingAddress5 AS city,Order_ShippingAddress6 AS pincode,Order_ShippingAddress7 AS state,Order_ProductCode AS Prodcode,
                   Order_ProductName AS ProdName,Order_Department AS category,Order_ProductBrand AS Brand,
                  Order_ProductQuantity AS cartProdCount FROM ".$this->table_name." WHERE Order_OrderNo='$this->orderID' GROUP BY Order_ProductName";
            $stmt = $this->conn->prepare($query);
            
            // execute query
            if($stmt->execute())
            {
                return $stmt;
            }
            else{
                $array = $stmt->errorInfo();
                print_r($array);
            }
            return false;
        }
        catch (PDOException $e)
        {
            die("ERROR: Could not able to execute $query. "
                .$e->getMessage());
        }
        
        
        
    }
}

$database = new Database();
$db = $database->getConnection();

$orderlist = new OrderList($db);

$data=json_decode(file_get_contents("php://input"));
$orderlist->orderID = $data->orderID;


// create the product
$stmt=$orderlist->getOrderList();
$num = $stmt->rowCount();

$orderlist_detail=array();
$orderlist=array();

$orderlist["productList"]=array();
$orderlist_arr=array();
$orderlist_arr["productList"]=array();
if($num>0){
  
    
    //$orderlist_detail["productList"]=array();
 
    $deliveryAddress=array();
    
    $productList = array();
       // create array
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        extract($row);
  
        $deliveryAddress=array(
            "doorNo" =>  $doorNo,
            "street" => $street,
            "landMark" => $landMark,
            "location" => $location,
            "city" => $city,
            "pincode" => $pincode,
            "state" => $state
            
        );
       
        $orderlist=array(
            
            
            "Prodcode"=> $Prodcode,
            "ProdName"=> $ProdName,
            "category"=> $category,
            "Brand"=> $Brand,
            "cartProdCount"=> $cartProdCount,
            "mrp"=>$mrp,
            "price" =>  $price,
            "Save" => $Save,
            "status" => $status
            
            
        );
        
        array_push($productList,$orderlist);
     
    }
    $orderlist_detail = array(
        "mobileNo"=>$mobileNo,
        "name"=>$name,
        "orderID"=>$orderID,
        "deliveryAddress" => $deliveryAddress,
        "productList" => $productList,
    );
    
    // set response code - 201 created
    http_response_code(201);
    
    // tell the user
    echo json_encode($orderlist_detail);
}

// if unable to create the product, tell the user
else{
    
    // set response code - 503 service unavailable
    http_response_code(503);
    
    // tell the user
    echo json_encode(array("status" => "Failed to List Order"));
}


?>