<?php
require_once "./connect.php";
require_once "./utilities.php";

if (!isset($_POST["member-levels"])) {
  alertAndBack("請勾選會員限制");
  exit;
}
if (!isset($_POST["category"])) {
  alertAndBack("請勾選商品分類");
  exit;
}


//表單驗證
// $required = ["name", "code", "discount", "member-levels","categories"];
// $wordings = ["請填寫優惠券名稱", "請填寫優惠碼", "請填寫折扣欄位", "請勾選會員限制", "請勾選適用商品分類"];
// $errors = [];

// foreach($required as $index => $value){
//   if($_POST[$value] == ""){
//     $errors[$value] = $wordings[$index];
//   }
// }

// if($_POST["expire-type"] === "fixed"){
//   if( ($_POST["start-at"] == "0000-00-00" || $_POST["start-at"] == "") || ($_POST["end-at"] == "0000-00-00" || $_POST["end-at"] == "") ){
//   $errors["start-at"] = "請填寫有效日期區間";
//   }
// }

// if($_POST["expire-type"] === "relative"){
//   if($_POST["valid-days"] == ""){
//   $errors["valid-days"] = "請填寫有效天數";
//   }
// }

// // if (count($errors) > 0) {
// //     require "update.php";
// // }
// session_start();

// if (count($errors) > 0) {
//     $_SESSION["errors"] = $errors;
//     $_SESSION["old"] = $_POST; // 把剛剛填的表單內容也帶回去，讓畫面可以回填

//     header("Location: update.php?id=$id"); // redirect 回 update 頁面
//     exit;
// }




$id = $_POST["id"];
$name = htmlspecialchars($_POST["name"]);
$code = htmlspecialchars($_POST["code"]);
$discountType = intval($_POST["discount-type"]);
$discount = floatval($_POST["discount"]);
$minDiscount = intval($_POST["min-discount"]);
$maxAmount = $_POST["max-amount"] === "" ? null : intval($_POST["max-amount"]);
$memberLevels = $_POST["member-levels"];
$categories = $_POST["category"] ?? [];
$expireType = $_POST["expire-type"];
$startAt = $_POST["start-at"];
$endAt = $_POST["end-at"];
$validDays = intval($_POST["valid-days"]);

if ($expireType == "fixed") {
  $validDays = null;
} else {
  $startAt = null;
  $endAt = null;
}




$sql = "UPDATE `coupons` SET `name` = :name, `code` = :code, `discount_type` = :discount_type, `discount` = :discount, `min_discount` = :min_discount,`max_amount` = :max_amount,`start_at` = :start_at,`end_at` = :end_at,`valid_days` = :valid_days WHERE `id` = :id";
$values = [
  ':name' => $name,
  ':code' => $code,
  ':discount_type' => $discountType,
  ':discount' => $discount,
  ':min_discount' => $minDiscount,
  ':max_amount' => $maxAmount,
  ':start_at' => $startAt,
  ':end_at' => $endAt,
  ':valid_days' => $validDays,
  ':id' => $id
];




$sqlAllCate = "SELECT `category_id` FROM `products_category`";
$sqlDelCate = "DELETE FROM `coupon_categories` WHERE `coupon_id` = ?";
$sqlCate = "INSERT INTO `coupon_categories` (`coupon_id`, `category_id`) VALUES (?, ?)";

$sqlAllLv = "SELECT `id` FROM `member_levels`";
$sqlDelLv = "DELETE FROM `coupon_levels` WHERE `coupon_id` = ?";
$sqlLv = "INSERT INTO `coupon_levels` (`coupon_id`, `level_id`) VALUES (?, ?)";



try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute($values);
  
  // 商品類別勾選刪除與新增
  $stmtDelCate = $pdo->prepare($sqlDelCate);
  $stmtDelCate->execute([$id]);

  if (in_array("all", $categories)) {
    $stmtAllCate = $pdo->prepare($sqlAllCate);
    $stmtAllCate->execute();
    $categories = $stmtAllCate->fetchAll(PDO::FETCH_COLUMN);
  }

  $stmtCate = $pdo->prepare($sqlCate);
  foreach ($categories as $categoryId) {
    $stmtCate->execute([$id, $categoryId]);
  }


  // 會員類別勾選刪除與新增
  $stmtDelLv = $pdo->prepare($sqlDelLv);
  $stmtDelLv->execute([$id]);

  if (in_array("all", $memberLevels)) {
    $stmtAllLv = $pdo->prepare($sqlAllLv);
    $stmtAllLv->execute();
    $memberLevels = $stmtAllLv->fetchAll(PDO::FETCH_COLUMN);
  }

  $stmtLv = $pdo->prepare($sqlLv);
  foreach ($memberLevels as $memberLevelId) {
    $stmtLv->execute([$id, $memberLevelId]);
  }


} catch (PDOException $e) {
  echo "錯誤: {{$e->getMessage()}}";
  exit;
}

alertGoBack("更新優惠券成功");

