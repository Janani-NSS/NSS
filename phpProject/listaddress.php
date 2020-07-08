<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once 'database.php';

class Address{
    
    private $conn;
    
    
    // object properties
    public $name;
    public $mobileNo;
    public $doorNo;
    public $street;
    public $landMark;
    public $location;
    public $city;
    public $pincode;
    public $state;
    public $id;
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // create product
    public function deliveryaddress(){
        
        
        try {
                         
            $query="SELECT c.CUSTOMER_NAME AS name,s.MobileNo As mobileNo,s.ShippingAddress_Add1 AS doorNo, s.ShippingAddress_Add2 AS street,s.ShippingAddress_Add3 AS landMark,s.ShippingAddress_Add4 AS location,s.ShippingAddress_Add5 AS city,s.ShippingAddress_Add6 AS pincode,s.ShippingAddress_Address7 AS state,s.ShippingAddress_Id AS id 
                     FROM ShippingAddressInfo s INNER JOIN CustomerProfileInfo c ON s.MobileNo=c.MOBILE_NUMBER  
                          WHERE s.MobileNo='$this->mobileNo'";
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

$address = new Address($db);

$data=json_decode(file_get_contents("php://input"));
$address->name = $data->name;
$address->mobileNo = $data->mobileNo;
$address->doorNo = $data->doorNo;
$address->street = $data->street;
$address->landMark = $data->landMark;
$address->location = $data->location;
$address->city = $data->city;
$address->pincode = $data->pincode;
$address->state = $data->state;
$address->id = $data->id;
// create the product
$stmt=$address->deliveryaddress();
$num = $stmt->rowCount();
if($num>0){
    $address_arr=array();
    $address_arr["AddressList"]=array();
    
    // create array
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        extract($row);
        
        $address_detail = array(
            "name"=>$name,
            "mobileNo"=>$mobileNo,
            "doorNo" =>  $doorNo,
            "street" => $street,
            "landMark" => $landMark,
            "location" => $location,
            "city" => $city,
            "pincode" => $pincode,
            "state" => $state,
            "id" => $id
            
        );
        array_push($address_arr["AddressList"], $address_detail);
    }
    
    // set response code - 201 created
    http_response_code(201);
    
    // tell the user
    echo json_encode($address_arr);
}

// if unable to create the product, tell the user
else{
    
    // set response code - 503 service unavailable
    http_response_code(503);
    
    // tell the user
    echo json_encode(array("status" => "Failed to List Address"));
}


?>