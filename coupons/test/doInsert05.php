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

$names = $_POST["name"];
$contents = $_POST["content"];
$categorys = $_POST["category"];
$count = count($names);
$countCategory = count($categorys);

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

for($i = 0 ; $i < $countFile ; $i++){

    if($_FILES["myFile"]["error"][$i] == 0){
        $ext = pathinfo($_FILES["myFile"]["name"][$i], PATHINFO_EXTENSION);
        $newFile = ($timestamp +  $i).".{$ext}";
        $file = "./uploads/$newFile";
        if(move_uploaded_file($_FILES["myFile"]["tmp_name"][$i], $file)){
          array_push($imgs, $newFile);
        }else{
          array_push($imgs, null);
        }
    }else{
      array_push($imgs, null);
    }
}


$sql = "INSERT INTO `msgs` (`name`, `content`,`img` ,`category_id`) VALUES (?, ?, ?, ?);";

try {
  $stmt = $pdo->prepare($sql);
  for($i = 0;$i < $count;$i++){
    $name = htmlspecialchars($names[$i]);
    $content = htmlspecialchars($contents[$i]);
    $category = intval($categorys[$i]);
    $img = $imgs[$i];
    $stmt->execute([$name, $content, $img, $category]);
  }
} catch (PDOException $e) {
  echo "錯誤: {{$e->getMessage()}}";
  exit;
}

alertGoBack("新增資料成功");