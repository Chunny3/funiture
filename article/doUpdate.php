<?php
session_start();
require_once "../connect.php";
require_once "./utilitiesArticle.php";
header('Content-Type: application/json');


$user_id = $_SESSION["user"]["id"] ?? null;
if (!$user_id) {
    // 未登入，導向登入頁或給錯誤
    header("Location: login.php");
    exit;
}
$id = $_POST["id"];
$title = $_POST["title"];
$content = $_POST["content"];
preg_match_all('/<img[^>]+src=["\']uploads\/([^"\']+)["\']/i', $content, $matches);
$imgFiles = $matches[1];
$tags = array_filter(array_map('trim', explode(',', $_POST['tag']))); // 取得標籤並去除空白
$category = $_POST['category'];



$sql = "UPDATE `article` SET `title` = ?, `content` = ?, `article_category_id` = ?, `upload_at` = NOW() WHERE id = ?";
$values = [$title, $content, $category, $id];

$sqlImg = "SELECT `img` FROM `article_img` WHERE `article_id` = ? ";

$sqlDelTag = "DELETE FROM `article_tag` WHERE `article_id` = ?";
$sqlSearchTag = "SELECT `id` FROM `tag` WHERE `name` = ?";
$sqlAddTag = "INSERT INTO `tag` (`name`) VALUES (?)";
$sqlArticleTag = "INSERT INTO `article_tag` (`article_id`, `tag_id`) VALUES (?, ?)";

$sqlDelImg = "DELETE FROM `article_img` WHERE `article_id` = ?";
$sqlSearchImg = "SELECT `id` FROM `article_img` WHERE `img` = ?";
$sqlAddImg = "INSERT INTO `article_img`(`article_id`, `img`) VALUES (?, ?)";




try {
    $stmtImg = $pdo->prepare($sqlImg);
    $stmtImg->execute([$id]);
    $rowOldImg = $stmtImg->fetch(PDO::FETCH_ASSOC);
    preg_match_all('/<img[^>]+src=["\']uploads\/([^"\']+)["\']/i', $content, $matches);
    $newImgs = $matches[1];

    $stmtDelTag = $pdo->prepare($sqlDelTag);
    $stmtDelTag->execute([$id]);
    foreach ($tags as $tag) {
        $stmtSearchTag = $pdo->prepare($sqlSearchTag);
        $stmtSearchTag->execute([$tag]);
        $tagId = $stmtSearchTag->fetchColumn();

        if (!$tagId) {
            $stmtSearchTag = $pdo->prepare($sqlAddTag);
            $stmtSearchTag->execute([$tag]);
            $tagId = $pdo->lastInsertId();
        }

        $stmtArticleTag = $pdo->prepare($sqlArticleTag);
        $stmtArticleTag->execute([$id, $tagId]);
    }

    // $stmtDelImg = $pdo->prepare($sqlDelImg);
    // $stmtDelImg->execute([$id]);
    // foreach ($imgFiles as $imgFile) {
    //     $stmtSearchImg = $pdo->prepare($sqlSearchImg);
    //     $stmtSearchImg->execute([$imgFile]);
    //     $imgId = $stmtSearchImg->fetchColumn();

    //     if (!$imgId) {
    //         $stmtAddImg = $pdo->prepare($sqlAddImg);
    //         $stmtAddImg->execute([$id, $imgFile]);
    //     }
    // }

    if (!is_array($rowOldImg))
        $rowOldImg = [];
    if (!is_array($newImgs))
        $newImgs = [];

    $imgsToDelete = array_diff($rowOldImg, $newImgs);
    foreach ($imgsToDelete as $img) {
        // 刪除資料庫紀錄
        $stmtDel = $pdo->prepare("DELETE FROM article_img WHERE article_id = ? AND img = ?");
        $stmtDel->execute([$id, $img]);
        // 刪除檔案
        $path = __DIR__ . "/uploads/$img";
        if (file_exists($path))
            unlink($path);
    }
    $imgsToAdd = array_diff($newImgs, $rowOldImg);
    foreach ($imgsToAdd as $img) {
        $stmtAdd = $pdo->prepare("INSERT INTO article_img (article_id, img) VALUES (?, ?)");
        $stmtAdd->execute([$id, $img]);
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);

    echo json_encode([
        "status" => "success",
        "message" => "更新成功"
    ]);
    exit;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
    exit;
}