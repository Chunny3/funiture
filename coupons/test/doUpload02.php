<?php
require_once "Utilities.php";

if(!isset($_POST["name"])){
    alertGoBack("請從正常管道進入");
}

echo $_POST["name"],"<br>";

// 搬移檔案 單個檔案
$count = count($_FILES["myFile"]["name"]);
$timestamp = time();

for($i = 0 ; $i < $count ; $i++){

    if($_FILES["myFile"]["error"][$i] == 0){
        $ext = pathinfo($_FILES["myFile"]["name"][$i], PATHINFO_EXTENSION);
        $newFile = ($timestamp +  $i).".{$ext}";
        $file = "./uploads/$newFile";
        if(move_uploaded_file($_FILES["myFile"]["tmp_name"][$i], $file)){
            echo "<img src='$file'>";
        }else{
            echo "上傳檔案失敗";
        }
    }
}

