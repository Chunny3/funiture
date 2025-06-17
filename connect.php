<?php
// 啟動 session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "admin";
// $username = "root";
$password = "a12345";
// $password = "1234";
$dbname = "furniture";
$port = 3306;

try{
  $pdo = new PDO(
    "mysql:host={$servername};
     dbname={$dbname};
     port={$port};
     charset=utf8", 
    $username, 
    $password);
}catch(PDOException $e){
  echo "資料庫連線失敗<br>";
  echo "Error: " .$e->getMessage() ."<br>";
  exit;
}
?>