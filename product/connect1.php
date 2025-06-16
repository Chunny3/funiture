<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "product";
$port = 3307;

try {
  $pdo = new PDO("mysql:host=$servername;dbname=$dbname;port=$port;charset=utf8", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  echo "✅ 資料庫連線成功！<br>";
} catch (PDOException $e) {
  echo "❌ 資料庫連線失敗：" . $e->getMessage();
  exit;
}
