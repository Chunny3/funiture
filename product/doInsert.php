<?php
require_once "./connect.php";
require_once "./Utilities.php";

// if(!isset($_POST["name"])){
//   echo "請從正常管道進入";
//   exit;
// }

if(!isset($_POST["category"])){
  alertAndBack("請填寫分類");
  exit;
}
if(!isset($_FILES["myFile"])){
  alertAndBack("沒有選擇圖片");
  exit;
}

$names = $_POST["name"];
$contents = $_POST["content"];
$categories = $_POST["category"];
$styles = $_POST["style"];
$colors = $_POST["color"];
$count = count($names);
$countCategory = count($categories);

if($count != $countCategory){
  alertAndBack("請填寫分類");
  exit;
}

$emptyCheck = false;
for($i = 0;$i < $count;$i++){
  $name = $names[$i];
  $content = $contents[$i];
  if($name == "" || $content == ""){
    $emptyCheck = true;
  }
}

if($emptyCheck == true){
  echo "請填寫全部欄位";
  goBack();
  exit;
}

$imgs = [];
$countFile = count($_FILES["myFile"]["name"]);
$timestamp = time();
for($i = 0; $i < $countFile ;$i++){

  if($_FILES["myFile"]["error"][$i] == 0){
    $ext = pathinfo($_FILES["myFile"]["name"][$i], PATHINFO_EXTENSION);
    $newFile = ($timestamp + $i) .".{$ext}";
    $file = "./uploads/{$newFile}";
    if(move_uploaded_file($_FILES["myFile"]["tmp_name"][$i], $file)){
      array_push($imgs, $newFile);
    }else{
      array_push($imgs, null);
    }
  }else{
    array_push($imgs, null);
  }

}


$user_id = 1; 
$sql = "INSERT INTO `products` ( `user_id`,`name`,`category_id`,`description` ,`price`,`quantity`,`style`,`color`) VALUES (?, ?, ?, ?,  ?,?,?,?)";

try {
  $stmt = $pdo->prepare($sql);

  for($i = 0; $i < $count; $i++){
    $name = htmlspecialchars($names[$i]);
    $category_id = intval($_POST["category"][$i] ?? 0);
    $description = htmlspecialchars($contents[$i]);
    $style = htmlspecialchars($styles[$i]);
    $price = floatval($_POST["price"][$i] ?? 0);
    $quantity = intval($_POST["quantity"][$i] ?? 0);
    $color = htmlspecialchars($_POST["color"][$i] ?? 0);


    // 插入商品主表
    $stmt->execute([$user_id, $name, $category_id, $description,$price,$quantity,$style,$color]);

    // 取得剛剛插入的商品 ID
    $product_id = $pdo->lastInsertId();

    // 如果圖片對應正確且有圖片
    if(isset($imgs) && $imgs[$i] !== null){
      $imgFileName = $imgs[$i];
      
      $sql_img = "INSERT INTO `product_img` (`product_id`, `img`) VALUES (?, ?)";
      $stmt_img = $pdo->prepare($sql_img);
      $stmt_img->execute([$product_id, $imgFileName]);
    }
  }
} catch (PDOException $e) {
  echo "錯誤: {$e->getMessage()}";
  exit;
}


alertGoBack("新增成功");