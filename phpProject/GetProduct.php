<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once 'database.php';
include_once 'Product.php';

// get database connection
$database = new Database();
$db = $database->getConnection();


// prepare product object
$product=new Product($db);

// set ID property of record to read
//$product->Product_Department = isset($_GET['Product_Department']) ? $_GET['Product_Department'] : die();

// read the details of product to be edited
$stmt=$product->readOne();
$num = $stmt->rowCount();
if($num>0){
    // products array
    $products_arr=array();<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');
    
    // include database and object files
    include_once 'database.php';
    include_once 'Product.php';
    
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
        
        // tell the user no products found
        echo json_encode(
            array("message" => "No products found.")
            );
    }
    
    ?>
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
    
    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
        );
}

?>