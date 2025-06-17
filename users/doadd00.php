<?php
require_once "../connect.php";
require_once "./utilitiesUser.php";

if(!isset($_POST["email"])){
  alertGoTo("請從正常管道進入", "./index.php");
  exit;
}

$email = $_POST["email"];
$password1 = $_POST["password1"];
$password2 = $_POST["password2"];

if($email == ""){
  alertAndBack("請輸入信箱");
  exit;
};

if($password1 == ""){
  alertAndBack("請輸入密碼");
  exit;
};

if($password2 == ""){
  alertAndBack("請再次輸入密碼");
  exit;
};

if($password1 !== $password2){
  alertAndBack("兩次密碼不同");
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
  if(password_verify($password1, $row["password"])){
    $_SESSION["user"] = [
      "id" => $row["id"],
      "name" => $row["name"],
      "email"=> $row["email"],
      "img" => $row["img"]
    ];
    alertGoTo("登入成功", "../pageMsgsList.php");
  }else{
    alertAndBack("登入失敗");
  }
}

