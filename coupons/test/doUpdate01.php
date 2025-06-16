<?php
require_once "./connect.php";
require_once "./Utilities.php";

if(!isset($_POST["id"])){
  echo "不要再從網址使用 do 系列的檔案了, 罷脫";
  exit;
}

if(!isset($_POST["category"])){
  alertAndBack("請填寫分類");
  exit;
}

$required = ["name", "content"];
$wordings = ["請填寫姓名", "請填寫留言"];

foreach($required as $index => $value){
  if($_POST[$value] == ""){
    echo $wordings[$index];
    goBack();
    exit;
  }
}

$img = "";
if($_FILES["myFile"]["error"] == 0){
  $timestamp = time();
  $ext = pathinfo($_FILES["myFile"]["name"], PATHINFO_EXTENSION);
  $newFile = "{$timestamp}.{$ext}";
  $file = "./uploads/{$newFile}";
  if(move_uploaded_file($_FILES["myFile"]["tmp_name"], $file)){
    $img = $newFile;
  }
}

$id = $_POST["id"];
$name = htmlspecialchars($_POST["name"]);
$content = htmlspecialchars($_POST["content"]);
$category = intval($_POST["category"]);

if($img == ""){
  $sql = "UPDATE `msgs` SET `name` = ?, `content` = ?, `category_id` = ?, `update_at` = CURRENT_TIMESTAMP WHERE `id` = ?";
  $values = [$name, $content, $category, $id];
}else{
  $sql = "UPDATE `msgs` SET `name` = ?, `content` = ?, `img` = ?, `category_id` = ?, `update_at` = CURRENT_TIMESTAMP WHERE `id` = ?";
  $values = [$name, $content, $img, $category, $id];
}

$sqlImg = "SELECT `img` FROM `msgs` WHERE `id` = ?;";

try {
  $stmtImg = $pdo->prepare($sqlImg);
  $stmtImg->execute([$id]);
  $rowOldImg = $stmtImg->fetch(PDO::FETCH_ASSOC);
  if($rowOldImg){
    $path = "./uploads/{$rowOldImg["img"]}";
    if(file_exists($path)){
      unlink($path);
    }
  }

  $stmt = $pdo->prepare($sql);
  $stmt->execute($values);
} catch (PDOException $e) {
  echo "錯誤: {{$e->getMessage()}}";
  exit;
}

echo "更新資料成功";
timeoutGoBack(500);


