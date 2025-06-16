<?php
// 新增會員主要程式
require_once "../connect.php";
require_once "../Utilities.php";

if(!isset($_POST["email"])){
  alertGoTo("請從正常管道進入", "./index.php");
  exit;
}
// 取得資料表
$email = $_POST["email"];
$password1 = $_POST["password1"];
$password2 = $_POST["password2"];
$name = $_POST["name"];
$birthday = $_POST["birthday"];
$phone = $_POST["phone"];
$city = $_POST["city"];
$area = $_POST["area"];
$address = $_POST["address"];
$level_id = 1; // 預設「木芽會員」的 id 是 1



if($email == ""){
  alertAndBack("請輸入信箱");
  exit;
};

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
  alertAndBack("請輸入信箱");
  exit;
}

if($password1 == ""){
  alertAndBack("請輸入密碼");
  exit;
};

if($password2 == ""){
  alertAndBack("請再次輸入密碼");
  exit;
};

$passwordLength = strlen($password);
if($passwordLength < 5 || $passwordLength > 20){
  alertAndBack("請輸入密碼");
  exit;
}
if($password1 !== $password2){
  alertAndBack("兩次密碼輸入不一致");
  exit;
}
$password = password_hash($password1,PASSWORD_BCRYPT);

if($name == ""){
  alertAndBack("請輸入姓名");
  exit;
};
if($birthday == ""){
  alertAndBack("請輸入生日");
  exit;
};
if($phone == ""){
  alertAndBack("請輸入電話");
  exit;
};
if($city == ""){
  alertAndBack("請選擇縣市");
  exit;
};
if($area == ""){
  alertAndBack("請選擇區域");
  exit;
};
if($address == ""){
  alertAndBack("請輸入地址");
  exit;
};

$sql = "INSERT INTO users (email, password, name, birthday, phone, city, area, address, level_id)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$values = [$email, $password, $name, $birthday, $phone, $city, $area, $address, $level_id];

if(isset($_FILES["myFile"]) && $_FILES["myFile"]["error"] == 0){
  $img = null;
  $timestamp = time();
  $ext = pathinfo($_FILES["myFile"]["name"], PATHINFO_EXTENSION);
  $newFileName = "{$timestamp}.{$ext}";
  $file = "../uploads/{$newFileName}";
  if(move_uploaded_file($_FILES["myFile"]["tmp_name"], $file)){
    $img = $newFileName;
  }
  $sql = "INSERT INTO `users` (`email`, `password`, `name`, `birthday`, `phone`, `city`, `area`, `address`, `img`, `level_id`) 
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
  $values = [$email, $password, $name, $birthday, $phone, $city, $area, $address, $img, $level_id];
}


;
$sqlEmail = "SELECT COUNT(*) as count FROM `users` WHERE `email` = ?;";


try {
  $stmtEmail = $pdo->prepare($sqlEmail);
  $stmtEmail->execute([$email]);
  // $row = $stmtEmail->fetch(PDO::FETCH_ASSOC);
  // $count = $row["count"];
  $count = $stmtEmail->fetchColumn();
  if($count > 0){
    alertAndBack("此帳號已經使用過");
    exit;
  }

  $stmt = $pdo->prepare($sql);
  $stmt->execute($values);
} catch (PDOException $e) {
  echo "錯誤: {{$e->getMessage()}}";
  exit;
}

alertGoTo("新增資料成功", "./index.php");