<?php
require_once "./connect.php";


$name = $_POST["name"];;
$content =$_POST["content"];

$sql="INSERT INTO `msgs` (`name`, `content`) VALUES (:name, :content);"; //佔位符（placeholder），代表之後要填進去的值

// 預處理器的寫法 2

try{
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":name" => $name,
        ":content" => $content
    ]);
}catch(PDOException $e){
    echo "錯誤: {{$e->getMessage()}}";
    exit;
}

echo "新增資料成功";