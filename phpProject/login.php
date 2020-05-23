<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once 'database.php';

class Login{
    
    private $conn;
    
    
    // object properties

    public $mobileNumber;
       
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    public function readOne(){
        if(isset($_GET['mobileNumber']))
        {
           
            $mobileNumber=$_GET['mobileNumber'];
            
        $query = "SELECT CUSTOMER_NAME AS name,MOBILE_NUMBER AS mobileNumber,EmailId AS emailId FROM CustomerProfileInfo WHERE MOBILE_NUMBER='$mobileNumber'";
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
$login=new Login($db);

// set ID property of record to read
//$product->category = isset($_GET['category']) ? $_GET['category'] : die();

// read the details of product to be edited
$stmt=$login->readOne();
$num = $stmt->rowCount();
if($num>0){
    $products_arr=array();
    //$products_arr["userDetails"]=array();
    
    // create array
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        extract($row);
        
        $product_item = array(
            
            "name" =>  $name,          
            "emailId" => $emailId,
            "mobileNumber" => $mobileNumber,
        );
        array_push($products_arr, $product_item);
    }
    // set response code - 200 OK
    http_response_code(200);
    
    // make it json format
    //echo json_encode( array("status" => "Login Success"));
    echo json_encode($products_arr);
    
}
else{
    // set response code - 404 Not found
    http_response_code(404);
    
    //tell the user no products found
    echo json_encode(
        array("status" => "user Not Exist")
        );
}

?>