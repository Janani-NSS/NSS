<?php
class Caregory {
  // Properties
  public $name;
  public $color;

  // Methods
  function set_name($name) {
    $this->name = $name;
  }
  function get_name() {
    return $this->name;
  }
  function set_color($color) {
    $this->color = $color;
  }
  function get_color() {
    return $this->color;
  }
}

class RespObj{
	public $categoryList = array();
}

$apple = new Caregory();
$apple->set_name('Apple');
$apple->set_color('Red');
echo "Name: " . $apple->get_name();
echo "<br>";
echo "Color: " .  $apple->get_color();


$fruitArr = array();
array_push($fruitArr,$apple);
array_push($fruitArr,$apple);
array_push($fruitArr,$apple);
array_push($fruitArr,$apple);

echo sizeof($fruitArr);

$resData = new RespObj();
$resData->categoryList = $fruitArr;

echo json_encode($resData);
//echo "\n\n" + json_encode($resData);
?>
 
</body>
</html>