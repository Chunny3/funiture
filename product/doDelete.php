<?php
// 刪除會員主要程式
require_once "./connect.php";
require_once "./Utilities.php";

if(!isset($_GET["id"])){
  alertGoTo("請從正常管道進入", "./productlist.php");
  exit;
}

$id = $_GET["id"];
$sql = "UPDATE `products` SET `is_valid` = 0 WHERE `id` = ?";
$values = [$id];

try {
  $stmt = $pdo->prepare($sql);
  // $stmt->execute($values);
  $stmt->execute([$id]);

if ($stmt->rowCount() > 0) {
  alertGoTo("刪除成功", "./productlist.php");
} else {
  alertGoTo("已刪除", "./productlist.php");
}
} catch (PDOException $e) {
  echo "錯誤: {{$e->getMessage()}}";
  exit;
}


