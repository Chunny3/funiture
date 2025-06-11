<?php
require_once "../connect.php";
require_once "../utilities.php";

$sql = "SELECT * FROM `article` WHERE `is_valid` = 1 ORDER BY `id` DESC";
try{
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){
  echo "系統錯誤，請恰管理人員<br>";
  echo "Error: {$e->getMessage()}<br>";
  exit;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <style>
      .w30px{
        width: 30px;
      }
      .w110px{
        width: 110px;
      }
    </style>
  </head>
  <body>
    <div class="container my-3">
      
      <div class="ctrl d-flex mb-1 align-items-center">
        <h1>文章管理系統</h1>
        <div class="ms-auto me-1">
          <!-- <div class="input-group input-group-sm">
            <input type="text" class="form-control" placeholder="請輸入標籤進行搜尋">
            <button class="btn btn-primary btn-search">送出</button>
          </div> -->
        </div>

        <div>
          <a class="btn btn-primary btn-sm" href="add.php">新增</a>
        </div>
        
      </div>
      <div class="uint d-flex text-bg-dark p-1 mb-1">
        <div class="sn w30px text-center">#</div>
        <div class="title flex-fill">標題</div>
        <div class="ctrl w110px text-end">管理</div>
      </div>

      <?php foreach($rows as $index => $row): ?>
        <div class="uint d-flex mb-1">
          <div class="sn w30px text-center"><?=$index+1?></div>
          <div class="title flex-fill"><?=$row["title"]?></div>
          <div class="ctrl w110px text-end">
             <a href="./doDelete.php?id=<?= $row["id"] ?>" class="btn btn-danger btn-sm">刪除</a>
            <a class="btn btn-primary btn-sm" href="update.php?id=<?=$row["id"]?>">修改</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
  </body>
</html>