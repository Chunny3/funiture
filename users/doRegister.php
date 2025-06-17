<?php
// 新增會員主要程式
require_once "../connect.php";
require_once "./utilitiesUser.php";

if (!isset($_POST["email"])) {
    alertGoTo("請從正常管道進入", "./index.php");
    exit;
}
// 取得資料表
$email = $_POST["email"];
$password1 = $_POST["password1"];
$password2 = $_POST["password2"];

// $password = $_POST["password"];
// $name = $_POST["name"];
// $birthday = $_POST["birthday"];
// $phone = $_POST["phone"];
// $city = $_POST["city"];
// $area = $_POST["area"];
// $address = $_POST["address"];
// $level_id = 1; // 預設「木芽會員」的 id 是 1

// if($name == ""){
//   alertAndBack("請輸入姓名");
//   exit;
// };

if ($email == "") {
    alertAndBack("請輸入信箱");
    exit;
}
;

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    alertAndBack("請輸入信箱");
    exit;
}

if ($password1 == "") {
    alertAndBack("請輸入密碼");
    exit;
}
;
$passwordLength = strlen($password1);
if ($passwordLength < 5 || $passwordLength > 20) {
    alertAndBack("密碼必須在 5 到 20 個字元之間");
    exit;
}
if ($password2 == "") {
    alertAndBack("請再次輸入密碼");
    exit;
}
;

if ($password1 !== $password2) {
    alertAndBack("兩次密碼不同");
    exit;
}
;
// 密碼加密
$password = password_hash($password, PASSWORD_BCRYPT);


// 檢查信箱是否已存在
$sql = "INSERT INTO users (email, password)
  VALUES (?, ?)";
$values = [$email, $password];



$sqlEmail = "SELECT COUNT(*) as count FROM `users` WHERE `email` = ?;";

try {
    $stmtEmail = $pdo->prepare($sqlEmail);
    $stmtEmail->execute([$email]);
    // $row = $stmtEmail->fetch(PDO::FETCH_ASSOC);
    // $count = $row["count"];
    $count = $stmtEmail->fetchColumn();
    if ($count > 0) {
        alertAndBack("此帳號已經使用過");
        exit;
    }
    // 新增會員
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
    // 取得新會員 id
    $newId = $pdo->lastInsertId();
} catch (PDOException $e) {
    echo "錯誤: {{$e->getMessage()}}";
    exit;
}

alertGoNext("./update.php?id=$newId"); 