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
    
    public $conn;
    public $table_name = "id13357026_saravanasupermarket.Order";
    
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
    public $noofproducts;
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // create product
    public function getOrderList(){
        
        
        try {
            
            $query=" SELECT o.Order_MobileNo AS mobileNo, o.Order_CustomerName AS name,o.Order_OrderNo AS orderID,
                  o.Order_MRP AS mrp,o.Order_DiscountRate AS price,o.Order_MRP-o.Order_DiscountRate AS save,o.Order_Status AS status,
                   o.Order_ShippingAddress1 AS doorNo,o.Order_ShippingAddress2 AS street,o.Order_ShippingAddress3 AS landMark,o.Order_ShippingAddress4 AS location,
                   o.Order_ShippingAddress5 AS city,o.Order_ShippingAddress6 AS pincode,o.Order_ShippingAddress7 AS state,
                   (SELECT SUM(Order_MRP) FROM ".$this->table_name." WHERE Order_OrderNo='$this->orderID') AS totalmrp,(SELECT SUM(Order_DiscountRate) FROM ".$this->table_name." WHERE Order_OrderNo='$this->orderID') AS totalprice,
                   ((SELECT SUM(Order_MRP) FROM ".$this->table_name." WHERE Order_OrderNo='$this->orderID')-(SELECT SUM(Order_DiscountRate) FROM ".$this->table_name." WHERE Order_OrderNo='$this->orderID')) AS totalsavings,o.Order_Date AS Date,o.Order_Time AS Time,
                  o.Order_ProductCode AS prodcode,o.Order_ProductName AS prodName,o.Order_ProductName AS displayName,o.Order_Department AS queryData,o.Order_ProductBrand AS Brand,
                   (SELECT SUM(Order_ProductQuantity) FROM ".$this->table_name." WHERE Order_OrderNo='$this->orderID') AS NoOfProducts,
                  o.Order_ProductQuantity AS selectedCount,p.Product_PhotoPath AS imageURL FROM ".$this->table_name." o INNER JOIN ProductInfo p ON o.Order_ProductCode=p.Product_Code WHERE o.Order_OrderNo='$this->orderID' GROUP BY o.Order_ProductName";
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
    $abc=array();
    // create array
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        extract($row);
        $abc=array("NoOfProducts"=>$NoOfProducts);
        $deliveryAddress=array(
            "doorNo" =>  $doorNo,
            "street" => $street,
            "landMark" => $landMark,
            "location" => $location,
            "city" => $city,
            "pincode" => $pincode,
            "state" => $state
            
        );
        $billDetails=array(
            "totalmrp" =>$totalmrp,
            "totalprice"=>$totalprice,
            "totalsavings"=>$totalsavings,
            "Date"=>$Date,
            "Time"=>$Time
            
        );
        
        $orderlist=array(
            
            
            "prodcode"=> $prodcode,
            "prodName"=> $prodName,
            "Brand"=> $Brand,
            "displayName"=>$displayName,
            "queryData"=> $queryData,
            "imageURL"=>$imageURL,
            "mrp"=>$mrp,
            "price" =>  $price,
            "save" => $save,
            "status" => $status,
            "selectedCount"=> $selectedCount
            
            
        );
        
        array_push($productList,$orderlist);
        
    }
    $orderlist_detail = array(
        "mobileNo"=>$mobileNo,
        "name"=>$name,
        "orderID"=>$orderID,
        "deliveryAddress" => $deliveryAddress,
        "billDetails"=>$billDetails,
        "productList" => $productList,
    );
    
    // set response code - 201 created
    http_response_code(201);
    
    // tell the user
    echo json_encode($orderlist_detail);
    
    try {
        
        $database = new Database();
        $db = $database->getConnection();
        
        $orderlist = new OrderList($db);
        $sql="Select OT_OrderID From OrderTotal Where OT_OrderID='".$orderID."'";
        $stmt = $orderlist->conn->prepare($sql);
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0)
        {
            
        }else{
            $sql="insert into OrderTotal(OT_OrderID,OT_BillDate,OT_BillTime,OT_CustomerName,OT_MobileNo,
              OT_FinalAmount,OT_MRP,OT_Savings,OT_NoOfProducts,OT_Status)values('".$orderID."','".$Date."','".$Time."','".$name."','".$mobileNo."',
                '".$totalprice."','".$totalmrp."','".$totalsavings."','".$NoOfProducts."','Pending')";
            
            $stmt = $orderlist->conn->prepare($sql);
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
        
    }  catch (PDOException $e)
    {
        die("ERROR: Could not able to execute $sql. "
            .$e->getMessage());
    }
    
    
    
}

// if unable to create the product, tell the user
else{
    
    // set response code - 503 service unavailable
    http_response_code(503);
    
    // tell the user
    echo json_encode(array("status" => "Failed to List Order"));
}




?>