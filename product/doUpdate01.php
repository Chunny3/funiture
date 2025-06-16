<?php
require_once "./connect.php";
require_once "./Utilities.php";

if(!isset($_POST["id"])){
  echo "不要再從網址使用 do 系列的檔案了, 罷脫";
  exit;
}

if(!isset($_POST["category"])){
  alertAndBack("請選擇分類");
  exit;
}
if(!isset($_POST["description"])){
  alertAndBack("請填寫產品描述");
  exit;
}
if(!isset($_POST["style"])){
  alertAndBack("請選擇風格");
  exit;
}
if(!isset($_POST["quantity"])){
  alertAndBack("請選擇數量");
  exit;
}
if(!isset($_POST["price"])){
  alertAndBack("請選擇價錢");
  exit;
}
if(!isset($_POST["color"])){
  alertAndBack("請選擇顏色");
  exit;
}

if(!isset($_POST["msgID"]) ){
  echo "不要再從網址使用 do 系列的檔案了, 罷脫";
  exit;
}

$required = ["name", "description","category","quantity","style","color"];
$wordings = ["請填寫名稱", "請填寫產品描述","請選擇分類","請選擇數量","請選擇風格","請選擇顏色"];

foreach($required as $index => $value){
  if($_POST[$value] == ""){
    echo $wordings[$index];
    goBack();
    exit;
  }
}

// 獲取當前商品資料
$id = $_POST["id"];
$sql = "SELECT * FROM `products` WHERE `id` = ?";
try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$id]);
  $currentProduct = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "錯誤: {$e->getMessage()}";
  exit;
}

// 使用 POST 值或當前值
$name = htmlspecialchars($_POST["name"] ?? $currentProduct["name"]);
$category = $_POST["category"] ?? $currentProduct["category_id"];
$description = htmlspecialchars($_POST["description"] ?? $currentProduct["description"]);
$style = htmlspecialchars($_POST["style"] ?? $currentProduct["style"]);
$price = floatval($_POST["price"] ?? $currentProduct["price"]);
$quantity = intval($_POST["quantity"] ?? $currentProduct["quantity"]);
$color = htmlspecialchars($_POST["color"] ?? $currentProduct["color"]);
$product_id = $_POST["msgID"] ?? $id;

// 處理圖片上傳
$img = "";
if(isset($_FILES["imgFile"]) && $_FILES["imgFile"]["error"] == 0){
  $timestamp = time();
  $ext = pathinfo($_FILES["imgFile"]["name"], PATHINFO_EXTENSION);
  $newFile = "{$timestamp}.{$ext}";
  $file = "./uploads/{$newFile}";
  if(move_uploaded_file($_FILES["imgFile"]["tmp_name"], $file)){
    $img = $newFile;
  }
}

$sql = "UPDATE `products` SET `name` = ?, `category_id` = ?, `description` = ?, `style` = ?, `price` = ?, `quantity` = ?, `color` = ?, `update_at` = NOW() WHERE `id` = ?";
$values = [$name, $category, $description, $style, $price, $quantity, $color, $id];

try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute($values);
} catch (PDOException $e) {
  echo "商品更新錯誤: {$e->getMessage()}";
  exit;
}

if($img !== ""){
  $sqlImg = "SELECT `img` FROM `product_img` WHERE `product_id` = ? ";
  try{
    $stmtImg = $pdo->prepare($sqlImg);
    $stmtImg->execute([$product_id]);
    $rowOldImg = $stmtImg->fetch(PDO::FETCH_ASSOC);
    
    if($rowOldImg){
      // 刪除舊的圖片文件
      $path = "./uploads/{$rowOldImg["img"]}";
      if(file_exists($path)){
        unlink($path);
      }
      
      // 更新圖片表
      $sqlUpdateImg = "UPDATE `product_img` SET `img` = ? WHERE `product_id` = ? LIMIT 1";
      $stmtUpdateImg = $pdo->prepare($sqlUpdateImg);
      $stmtUpdateImg->execute([$img, $product_id]);
    } else {
      // 如果沒有舊圖片，則插入新圖片
      $sqlInsertImg = "INSERT INTO `product_img` (`product_id`, `img`) VALUES (?, ?)";
      $stmtInsertImg = $pdo->prepare($sqlInsertImg);
      $stmtInsertImg->execute([$product_id, $img]);
    }
  } catch (PDOException $e) {
    echo "圖片更新錯誤: {$e->getMessage()}";
    exit;
  }
}

// 處理新增圖片
if(isset($_FILES["additionalImages"]) && is_array($_FILES["additionalImages"]["name"])) {
  $fileCount = count($_FILES["additionalImages"]["name"]);
  $timestamp = time();
  
  for($i = 0; $i < $fileCount; $i++) {
    if($_FILES["additionalImages"]["error"][$i] == 0) {
      $ext = pathinfo($_FILES["additionalImages"]["name"][$i], PATHINFO_EXTENSION);
      $newFile = ($timestamp + $i) . ".{$ext}";
      $file = "./uploads/{$newFile}";
      
      if(move_uploaded_file($_FILES["additionalImages"]["tmp_name"][$i], $file)) {
        $sql = "INSERT INTO `product_img` (`product_id`, `img`) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$product_id, $newFile]);
      }
    }
  }
}

// 計算商品在列表中的位置
$sql = "SELECT COUNT(*) as position FROM products WHERE id <= ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$position = $result['position'];
$page = ceil($position / 10); // 假設每頁顯示10筆資料

alertGoTo("更新資料成功", "./productlist.php?page=" . $page);


