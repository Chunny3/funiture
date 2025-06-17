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
$name = $_POST["name"];
$birthday = $_POST["birthday"];
$phone = $_POST["phone"];
$city = $_POST["city"];
$area = $_POST["area"];
$address = $_POST["address"];
$level_id = 1; // 預設「木芽會員」的 id 是 1

if($name == ""){
  alertAndBack("請輸入姓名");
  exit;
};

if ($email == "") {
    alertAndBack("請輸入信箱");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    alertAndBack("請輸入正確的信箱格式");
    exit;
}

if ($password1 == "") {
    alertAndBack("請輸入密碼");
    exit;
}

$passwordLength = strlen($password1);
if ($passwordLength < 5 || $passwordLength > 20) {
    alertAndBack("密碼必須在 5 到 20 個字元之間");
    exit;
}

if ($password2 == "") {
    alertAndBack("請再次輸入密碼");
    exit;
}

if ($password1 !== $password2) {
    alertAndBack("兩次密碼不同");
    exit;
}

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

// 密碼加密
$password = password_hash($password1, PASSWORD_BCRYPT);

// 檢查信箱是否已存在
$sqlEmail = "SELECT COUNT(*) as count FROM `users` WHERE `email` = ?;";

try {
    $stmtEmail = $pdo->prepare($sqlEmail);
    $stmtEmail->execute([$email]);
    $count = $stmtEmail->fetchColumn();
    if ($count > 0) {
        alertAndBack("此帳號已經使用過");
        exit;
    }
    
    // 處理圖片上傳
    $img = null;
    if(isset($_FILES["myFile"]) && $_FILES["myFile"]["error"] == 0){
        $timestamp = time();
        $ext = pathinfo($_FILES["myFile"]["name"], PATHINFO_EXTENSION);
        $newFileName = "{$timestamp}.{$ext}";
        $file = "./uploads/{$newFileName}";
        if(move_uploaded_file($_FILES["myFile"]["tmp_name"], $file)){
            $img = $newFileName;
        }
    }
    
    // 新增會員
    $sql = "INSERT INTO users (email, password, name, birthday, phone, city, area, address, img, level_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $values = [$email, $password, $name, $birthday, $phone, $city, $area, $address, $img, $level_id];
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
    // 取得新會員 id
    $newId = $pdo->lastInsertId();
} catch (PDOException $e) {
    echo "錯誤: {{$e->getMessage()}}";
    exit;
}

alertGoTo("註冊成功", "./index.php");