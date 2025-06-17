<?php
// 刪除會員主要程式
require_once "../connect.php";
require_once "./utilitiesProduct.php";

if(!isset($_GET["id"])){
  alertGoTo("請從正常管道進入", "./productlist.php");
  exit;
}

$id = $_GET["id"];
$sql = "UPDATE `products` SET `is_valid` = 0 WHERE `id` = ? AND `is_valid` = 1";
// $values = [$id];

try {
  $stmt = $pdo->prepare($sql);
  // $stmt->execute($values);
  $stmt->execute([$id]);

  // 檢查商品是否已經被刪除
  $checkSql = "SELECT is_valid FROM products WHERE id = ?";
  $checkStmt = $pdo->prepare($checkSql);
  $checkStmt->execute([$id]);
  $product = $checkStmt->fetch(PDO::FETCH_ASSOC);

  if ($product && $product['is_valid'] == 0) {
    alertGoTo("刪除成功", "./productlist.php");
  } else {
    alertGoTo("已刪除", "./productlist.php");
  }
} catch (PDOException $e) {
  echo "錯誤: {{$e->getMessage()}}";
  exit;
}


