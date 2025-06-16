<?php
require_once "./connect.php";
require_once "./utilities.php";


if (!isset($_POST["category"])) {
  alertAndBack("請選擇適用商品類別");
  exit;
}

if (!isset($_POST["member-levels"])) {
  alertAndBack("請選擇會員限制");
  exit;
}

$name = htmlspecialchars($_POST["name"]);
$code = htmlspecialchars($_POST["code"]);
$discountType = intval($_POST["discount-type"]);
$discount = floatval($_POST["discount"]);
$minDiscount = intval($_POST["min-discount"]);
$maxAmount = $_POST["max-amount"] === "" ? null : intval($_POST["max-amount"]);
$memberLevels = $_POST["member-levels"];
$categories = $_POST["category"];
$expireType = $_POST["expire-type"];
$startAt = $_POST["start-at"];
$endAt = $_POST["end-at"];
$validDays = intval($_POST["valid-days"]);

// 有效日期
if ($expireType == "fixed") {
  $validDays = null;
} else {
  $startAt = null;
  $endAt = null;
}


$sql = "INSERT INTO `coupons` (`name`, `code`, `discount_type`, `discount`, `min_discount`, `max_amount`, `start_at`, `end_at`, `valid_days`) VALUES (:name, :code, :discount_type, :discount, :min_discount, :max_amount, :start_at, :end_at, :valid_days);";

$sqlAllCate = "SELECT `category_id` FROM `products_category`";
$sqlCate = "INSERT INTO `coupon_categories` (`coupon_id`, `category_id`) VALUES (?, ?)";

$sqlAllLv = "SELECT `id` FROM `member_levels`";
$sqlLv = "INSERT INTO `coupon_levels` (`coupon_id`, `level_id`) VALUES (?, ?)";

try {
  // 優惠券主表
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":name" => $name,
    ":code" => $code,
    ":discount_type" => $discountType,
    ":discount" => $discount,
    ":min_discount" => $minDiscount,
    ":max_amount" => $maxAmount,
    ":start_at" => $startAt,
    ":end_at" => $endAt,
    ":valid_days" => $validDays
  ]);

  $couponId = $pdo->lastInsertId();

  // 商品分類
  if (in_array("all", $categories)) {
    $stmtAllCate = $pdo->prepare($sqlAllCate);
    $stmtAllCate->execute();
    $categories = $stmtAllCate->fetchAll(PDO::FETCH_COLUMN);
  }

  $stmtCate = $pdo->prepare($sqlCate);
  foreach ($categories as $categoryId) {
    $stmtCate->execute([$couponId, $categoryId]);
  }

  // 會員限制
  if (in_array("all", $memberLevels)) {
    $stmtAllLv = $pdo->prepare($sqlAllLv);
    $stmtAllLv->execute();
    $memberLevels = $stmtAllLv->fetchAll(PDO::FETCH_COLUMN);
  }

  $stmtLv = $pdo->prepare($sqlLv);
  foreach ($memberLevels as $memberLevelId) {
    $stmtLv->execute([$couponId, $memberLevelId]);
  }


} catch (PDOException $e) {
  echo "錯誤: {{$e->getMessage()}}";
  exit;
}

alertGoBack("新增優惠券成功");