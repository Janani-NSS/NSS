
<?php

//header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Methods: POST");
include 'db.php';
  
$result = $conn->query("SELECT D_DepartmentName AS queryData,D_DepartmentName AS displayName,D_DepartmentImagePath AS imageURL FROM DepartmentInfo");

//echo "Number of rows: $result->num_rows";
 $Deparray = array();
 $pic_array = array();
 
    while($row =mysqli_fetch_assoc($result))
    {
       //$Deparray[] = $row;
     $_POST['queryData'] = $row["queryData"];
     $_POST['displayName'] = $row["displayName"];
     $_POST['imageURL'] = $row["imageURL"];
     array_push($pic_array, $_POST);
      
 
     
    }

    $Deparray = array('catergoryList' => $pic_array);

    echo json_encode($Deparray);
    
    //}
//echo json_encode(array("categories" => $Deparray));


$conn->close();


?>
