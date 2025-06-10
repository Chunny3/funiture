<?php
require_once "../connect.php";
require_once "../Utilities.php";
header('Content-Type: application/json');

$result = [];
// 取得標題和內容
$title = $_POST['title'];
$content = $_POST['content'];
$tags = array_filter(array_map('trim', explode(',', $_POST['tag']))); // 取得標籤並去除空白
$user_id = 1;
$article_category_id = 1;



$sql = "INSERT INTO `article` (`title`, `content`, `user_id`, `article_category_id`) VALUES (?, ?, ?, ?)";
$values = [$title, $content, $user_id, $article_category_id];

try {
  $pdo->beginTransaction();

  $stmt = $pdo->prepare($sql);
  $stmt->execute($values);
  $articleId = $pdo->lastInsertId(); // 取得剛插入的文章ID

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

alertGoTo("新增文章成功");
?>