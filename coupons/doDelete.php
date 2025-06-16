<?php
require_once "./connect.php";
require_once "./utilities.php";


$id = $_GET["id"];


$sql = "UPDATE `coupons` SET `is_valid` = 0 WHERE `id` = ?";


try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$id]);
  confirmGoBack();
} catch (PDOException $e) {
  echo "錯誤: {$e->getMessage()}";
  exit;
}

