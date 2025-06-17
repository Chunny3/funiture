<?php
session_start();
require_once "../connect.php";
require_once "utilitiesArticle.php";
header('Content-Type: application/json');

$user_id = $_SESSION["user"]["id"] ?? null;
if (!$user_id) {
    // 未登入，導向登入頁或給錯誤
    header("Location: login.php");
    exit;
}$title = $_POST['title'];
$content = $_POST['content'];

preg_match_all('/<img[^>]+src=["\']uploads\/([^"\']+)["\']/i', $content, $matches);
$imgFiles = $matches[1]; // 這是所有 uploads/ 後面的檔名陣列
$tags = array_filter(array_map('trim', explode(',', $_POST['tag']))); // 取得標籤並去除空白
$category = $_POST['category'];






$sql = "INSERT INTO `article` (`title`, `content`, `user_id`, `article_category_id`) VALUES (?, ?, ?, ?)";
$values = [$title, $content, $user_id, $category];

try {
  $pdo->beginTransaction();

  $stmt = $pdo->prepare($sql);
  $stmt->execute($values);
  $articleId = $pdo->lastInsertId(); // 取得剛插入的文章ID

$sqlImg = "INSERT INTO `article_img` (`article_id`, `img`) VALUES (?, ?)";

  foreach($imgFiles as $imgFile){
  $stmt = $pdo->prepare($sqlImg);
  $stmt->execute([$articleId, $imgFile]);
}

  foreach ($tags as $tag) {
    $tag = trim($tag);
    if (!$tag)
      continue;

    $stmt = $pdo->prepare("SELECT id FROM tag WHERE name = ?");
    $stmt->execute([$tag]);
    $tagId = $stmt->fetchColumn();

    if (!$tagId) {
      $stmt = $pdo->prepare("INSERT INTO tag (name) VALUES (?)");
      $stmt->execute([$tag]);
      $tagId = $pdo->lastInsertId();
    }

    // 建立關聯
    $stmt = $pdo->prepare("INSERT INTO article_tag (article_id, tag_id) VALUES (?, ?)");
    $stmt->execute([$articleId, $tagId]);
  }

  $pdo->commit();
    echo json_encode([
    "status" => "success",
    "message" => "新增文章成功"
  ]);
  exit;
} catch (PDOException $e) {
  $pdo->rollBack();
  http_response_code(500);
  echo json_encode([
    "status" => "error",
    "message" => "系統錯誤，請恰管理人員",
    "error" => $e->getMessage()
  ]);
  exit;
}


?>