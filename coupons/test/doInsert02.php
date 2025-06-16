<?php
require_once "./connect.php";


$name = $_POST["name"];;
$content =$_POST["content"];

$sql="INSERT INTO `msgs` (`name`, `content`) VALUES ('$name', '$content');";

// 預處理器的寫法 1
// 這支檔案只有使用到方法的一半
// 變數仍然在 SQL 當中自行組合
try{
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}catch(PDOException $e){
    echo "錯誤: {{$e->getMessage()}}";
    exit;
}

echo "新增資料成功";