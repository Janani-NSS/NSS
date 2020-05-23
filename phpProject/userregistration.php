<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once 'database.php';

class Register{
    
    private $conn;
      
    
    // object properties
    public $name;
    public $emailId;
    public $mobileNumber; 
    public $otp;
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // create product
    public function placeorder(){
        
        
        try {
            $query = "SELECT CUSTOMER_NAME,MOBILE_NUMBER,EmailId FROM CustomerProfileInfo WHERE MOBILE_NUMBER='$this->mobileNumber'";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            $num = $stmt->rowCount();
            
            if($num>0){
                $sql = "UPDATE CustomerProfileInfo SET CUSTOMER_NAME='$this->name',MOBILE_NUMBER='$this->mobileNumber',EmailId='$this->emailId' WHERE
            MOBILE_NUMBER='$this->mobileNumber'";
                
                $stmt = $this->conn->prepare($sql);
              
                if($stmt->execute())
                {
                    
                    return true;
                }
                else{
                    $array = $stmt->errorInfo();
                    print_r($array);
                }
                //echo json_encode(array("status" => "User Already Exist"));
                
            }else{
                $query = "INSERT INTO CustomerProfileInfo (CUSTOMER_NAME, MOBILE_NUMBER,Status,EmailId,OTP) VALUES(
                 '$this->name','$this->mobileNumber','Registered','$this->emailId','$this->otp');";
                
                // prepare query
                $stmt = $this->conn->prepare($query);
                
                // execute query
                if($stmt->execute())
                {
                    
                    return true;
                }
                else{
                    $array = $stmt->errorInfo();
                    print_r($array);
                }
                
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

$register = new Register($db);

$data=json_decode(file_get_contents("php://input"));


if(
    !empty($data->name) &&
    !empty($data->mobileNumber) &&
    !empty($data->emailId) && 
    !empty($data->otp) 
    ){
$register->name = $data->name;
$register->mobileNumber = $data->mobileNumber;
$register->emailId = $data->emailId;
$register->otp = $data->otp;
// create the product
if($register->placeorder()){
    
    // set response code - 201 created
    http_response_code(201);
    
    // tell the user
    echo json_encode(array("status" => "Registraion Successfully Completed"));
}

// if unable to create the product, tell the user
else{
    
    // set response code - 503 service unavailable
    http_response_code(503);
    
    // tell the user
    echo json_encode(array("status" => "Registraion Failure"));
}
}
// tell the user data is incomplete
else{
    
    // set response code - 400 bad request
    http_response_code(400);
    
    // tell the user
    echo json_encode(array("status" => "Please Enter All details"));
}
?>