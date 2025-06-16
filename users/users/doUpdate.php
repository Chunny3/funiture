<?php
// 修改會員主要程式
require_once "../connect.php";
require_once "../Utilities.php";

if(!isset($_POST["id"])){
  alertGoTo("請從正常管道進入", "./");
  exit;
}

$id = $_POST["id"];
$name = $_POST["name"];
$password = $_POST["password"];
$email = $_POST["email"];
$birthday = $_POST["birthday"];
$phone = $_POST["phone"];
$city = $_POST["city"];
$area = $_POST["area"];
$address = $_POST["address"];
$set = [];
$values = [":id"=>$id];
$level_id = $_POST["level_id"] ?? null;

if($name !== "") {
  $set[] = "`name` = :name";
  $values[":name"] = $name;
}
if($password !== "") {
  $set[] = "`password` = :password";
  $values[":password"] = password_hash($password, PASSWORD_DEFAULT);
}
if($email !== "") {
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    alertAndBack("請輸入正確的信箱");
  }
  $set[] = "`email` = :email";
  $values[":email"] = $email;
}
if($birthday !== "") {
  $set[] = "`birthday` = :birthday";
  $values[":birthday"] = $birthday;
}
if($phone !== "") {
  $set[] = "`phone` = :phone";
  $values[":phone"] = $phone;
}
if($city !== "") {
  $set[] = "`city` = :city";
  $values[":city"] = $city;
}
if($area !== "") {
  $set[] = "`area` = :area";
  $values[":area"] = $area;
}
if($address !== "") {
  $set[] = "`address` = :address";
  $values[":address"] = $address;
}
if($level_id !== null) {
  $set[] = "`level_id` = :level_id";
  $values[":level_id"] = $level_id;
}
if(isset($_FILES["myFile"]) && $_FILES["myFile"]["error"] == 0){
  $img = null;
  $timestamp = time();
  $ext = pathinfo($_FILES["myFile"]["name"], PATHINFO_EXTENSION);
  $newFileName = "{$timestamp}.{$ext}";
  $file = "../uploads/{$newFileName}";
  if(move_uploaded_file($_FILES["myFile"]["tmp_name"], $file)){
    $img = $newFileName;
  }
  $set[] = "`img` = :img";
  $values[":img"] = $img;
}


if(count($set) == 0){
  alertAndBack("沒有修改任何欄位");
}

$sql = "UPDATE users SET " . implode(", ", $set) . " WHERE id = :id";

try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute($values);
} catch (PDOException $e) {
  echo "錯誤: {{$e->getMessage()}}";
  exit;
}

alertGoTo("修改資料成功", "./update.php?id={$id}");