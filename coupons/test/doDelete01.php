<?php
require_once "./connect.php";
require_once "./Utilities.php";

if(!isset($_GET["id"])){
  echo "不要再從網址使用 do 系列的檔案了, 罷脫";
  exit;
}



$id = $_GET["id"];

// $sql = "DELETE FROM `msgs` WHERE `id` = ?";
// $sql = "UPDATE `msgs` SET `is_valid` = 0 WHERE `id` = ?";
$sql = "UPDATE `msgs` SET `end_at` = CURRENT_TIMESTAMP WHERE `id` = ?";

try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$id]);
} catch (PDOException $e) {
  echo "錯誤: {{$e->getMessage()}}";
  exit;
}
alertGoBack("刪除資料成功");