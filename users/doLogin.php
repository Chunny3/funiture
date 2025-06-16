<?php
require_once "../connect.php";
require_once "./utilitiesUser.php";

if(!isset($_POST["email"])){
  alertGoTo("請從正常管道進入", "./index.php");
  exit;
}

$email = $_POST["email"];
$password = $_POST["password"];

if($email == ""){
  alertAndBack("請輸入信箱");
  exit;
};

if($password == ""){
  alertAndBack("請輸入密碼");
  exit;
};

$sql = "SELECT * FROM `users` WHERE `email` = ?";

try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$email]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "錯誤: {{$e->getMessage()}}";
  exit;
}

if(!$row){
  alertAndBack("登入失敗");
}else{
  if(password_verify($password, $row["password"])){
    $_SESSION["user"] = [
      "id" => $row["id"],
      "name" => $row["name"],
      "email"=> $row["email"],
      "img" => $row["img"]
    ];
    alertGoTo("登入成功", "./index.php");
  }else{
    alertAndBack("登入失敗");
  }
}

