<?php
require_once "./connect.php";


$name = $_POST["name"];;
$content =$_POST["content"];

$sql="INSERT INTO `msgs` (`name`, `content`) VALUES (?, ?);";

// 預處理器的寫法 3

try{
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $content]);
}catch(PDOException $e){
    echo "錯誤: {{$e->getMessage()}}";
    exit;
}

echo "新增資料成功";