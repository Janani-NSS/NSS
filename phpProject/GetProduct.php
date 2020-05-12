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
$product->Product_Department = isset($_GET['Product_Department']) ? $_GET['Product_Department'] : die();

// read the details of product to be edited
$product->readOne();

if($product->displayName!=null){
    // create array
    $product_arr = array(
        "queryData" =>  $product->queryData,
        "displayName" => $product->displayName,
        "imageURL" => $product->imageURL,
        "mrp" => $product->mrp,
        "price" => $product->price,
        "save" => $product->save,
        "prodCode" => $product->prodCode,
        "prodName" => $product->prodName,
        "Brand" => $product->Brand
        
        
    );
    
    // set response code - 200 OK
    http_response_code(200);
    
    // make it json format
    echo json_encode($product_arr);
}

else{
    // set response code - 404 Not found
    http_response_code(404);
    
    // tell the user product does not exist
    echo json_encode(array("message" => "Product does not exist."));
}
?>