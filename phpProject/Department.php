<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
include 'db.php';
class Category {
    
    public $queryData;
    public $displayName;
    public $imageURL;
    
}



$cat=new Category();
//$resData = new RespObj();

$result = $conn->query("SELECT D_DepartmentName AS queryData,D_DepartmentName AS displayName,D_DepartmentImagePath AS imageURL FROM DepartmentInfo");

$Deparray = array();
$product_item = array();
while($row =mysqli_fetch_assoc($result))
{
    //$Deparray[] = $row;
    extract($row);
    
    $_POST['queryData'] = $row["queryData"];
    $_POST['displayName'] = $row["displayName"];
    $_POST['imageURL'] = $row["imageURL"];
    
    array_push($product_item,$_POST);
    //echo sizeof($product_item);
}



$Deparray["catergoryList"] = $product_item;

echo json_encode($Deparray);


$conn->close()

?>