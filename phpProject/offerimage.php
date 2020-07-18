<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
ini_set("allow_url_fopen", 1);
// include database and object files

include_once 'database.php';

class Image{
    
    private $conn;
    private $table_name = "ImageInfo";
    
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
    public $ImageNameTerm;
    public $skipUpTo;
    public $limit;
    
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    public function ImageList(){
        $query="SELECT DISTINCT Product_PhotoPath AS imageURL FROM ProductInfo";
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        return $stmt;
    }
}

// instantiate database and Image object
$database = new Database();
$db = $database->getConnection();

// initialize object
$Image = new Image($db);

// get keywords
//$keywords=isset($_GET["ImageNameTerm"]) ? $_GET["ImageNameTerm"] : die();

// query Images
$stmt = $Image->ImageList();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){
    
    // Images array
    $Images_arr=array();
    $Images_arr["ListOfImageURL"]=array();
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        
        $Image_item=array(
            
            "imageURL" => $imageURL
        );
       
        
        array_push($Images_arr["ListOfImageURL"], $Image_item);
    }
    
    // set response code - 200 OK
    http_response_code(200);
    
    // show Images data
    
    echo json_encode($Images_arr);
 
}

else{
    // set response code - 404 Not found
    http_response_code(404);
    
    // tell the user no Images found
    echo json_encode(
        array("message" => "No Images found.")
        );
}

?>