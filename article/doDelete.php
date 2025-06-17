<?php
session_start();
require_once "../connect.php";
require_once "./utilitiesArticle.php";

$id = $_GET["id"];

$sql = "UPDATE `article` SET `is_valid` = 0 WHERE `id` = ?";

try{
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}catch(PDOException $e){
    echo "錯誤: {{$e->getMessage()}}";  
    exit;
}
alertGoBack("刪除資料成功");