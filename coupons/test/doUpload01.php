<?php
require_once "Utilities.php";

if(!isset($_POST["name"])){
    alertGoBack("請從正常管道進入");
}

echo $_POST["name"],"<br>";
// 搬移檔案 單一檔案
if($_FILES["myFile"]["error"] == 0){
    // $timestamp = time();
    // $uuid = uniqid();
    $uuidv4 = generateUuidV4();
    $ext = pathinfo($_FILES["myFile"]["name"], PATHINFO_EXTENSION);
    $newFile = "{$uuidv4}.{$ext}";
    $file = "./uploads/$newFile";
    if(move_uploaded_file($_FILES["myFile"]["tmp_name"], $file)){
        echo "<img src='$file'>";
    }else{
        echo "上傳檔案失敗";
    }
}

function generateUuidV4() {
  $data = random_bytes(16);
  $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
  $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
  return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}