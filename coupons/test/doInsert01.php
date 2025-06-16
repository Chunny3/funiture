<?php
require_once "./connect.php";


$name = $_POST["name"];;
$content =$_POST["content"];

$sql="INSERT INTO `msgs` (`name`, `content`) VALUES ('$name', '$content');";

try{
    $pdo->exec($sql);

}catch(PDOException $e){
    echo "錯誤: {{$e->getMessage()}}";
    exit;
}

echo "新增資料成功";