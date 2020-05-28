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
        
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // create product
    public function getOrderList(){
        
        
        try {
            
            $query="SELECT Order_OrderNo AS orderID,sum(Order_MRP) AS mrp,sum(Order_DiscountRate) AS price,sum(Order_MRP-Order_DiscountRate) AS Save,Order_Status AS status,Order_Date AS Date,Order_Time AS Time
                    FROM ".$this->table_name." WHERE Order_MobileNo='$this->mobileNo' AND Order_CustomerName='$this->name' AND Order_Status='Order Placed' Group BY Order_OrderNo";
            $stmt = $this->conn->prepare($query);
            //print($query);
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
$orderlist->name = $data->name;
$orderlist->mobileNo = $data->mobileNo;

// create the product
$stmt=$orderlist->getOrderList();
$num = $stmt->rowCount();
if($num>0){
    $orderlist_arr=array();
   // $orderlist_arr["orderList"]=array();
    
    // create array
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        extract($row);
        
        $orderlist_detail = array(
            "orderID"=>$orderID,
            "mrp"=>$mrp,
            "price" =>  $price,
            "Save" => $Save,
            "status" => $status,
            "Date" => $Date,
            "Time" => $Time
           
            
        );
        array_push($orderlist_arr, $orderlist_detail);
    }
    
    // set response code - 201 created
    http_response_code(201);
    
    // tell the user
    echo json_encode($orderlist_arr);
}

// if unable to create the product, tell the user
else{
    
    // set response code - 503 service unavailable
    http_response_code(503);
    
    // tell the user
    echo json_encode(array("status" => "Failed to List Order"));
}


?>