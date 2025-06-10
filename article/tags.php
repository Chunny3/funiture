<?php
require_once "../connect.php";
header('Content-Type: application/json');

$keyword = trim($_GET['keyword'] ?? '');

if ($keyword === '') {
  echo json_encode([]);
  exit;
}

$sql = "SELECT `name` FROM `tag` WHERE `name` LIKE ? ORDER BY `name` LIMIT 10";
$stmt = $pdo->prepare($sql);
$stmt->execute(["{$keyword}%"]);
$tags = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($tags);