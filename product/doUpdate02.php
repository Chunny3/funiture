<?php

require_once "./connect.php";
require_once "./Utilities.php";

if(!isset($_POST["msgID"]) ){
  echo "不要再從網址使用 do 系列的檔案了, 罷脫";
  exit;
}

if(!isset($_FILES["imgFile"])){
  alertAndBack("沒有選擇圖片");
  exit;
}

$msgID = $_POST["msgID"]; 
$fileCount = count($_FILES["imgFile"]["name"]);


$sql = "INSERT INTO `product_img` (`product_id`, `img`) VALUES (?, ?)";

$susCount = 0;
$timestamp = time();

try {
  for($i = 0; $i < $fileCount; $i++) {
    if($_FILES["imgFile"]["error"][$i] == 0) {
      $ext = pathinfo($_FILES["imgFile"]["name"][$i], PATHINFO_EXTENSION);
      $newFile = ($timestamp + $i) . ".{$ext}";
      $file = "./uploads/{$newFile}";
      
      if(move_uploaded_file($_FILES["imgFile"]["tmp_name"][$i], $file)) {
        $stmt = $pdo->prepare($sql);
        if($stmt->execute([$msgID, $newFile])) {
          $susCount++;
        }
      }
    }
  }
  
  if($susCount > 0) {
    alertGoTo("上傳圖片成功", "./Update.php?id=$msgID");
  } else {
    alertGoTo("沒有上傳圖片", "./Update.php?id=$msgID");
  }
} catch(PDOException $e) {
  echo "錯誤: {$e->getMessage()}";
  exit;
}
