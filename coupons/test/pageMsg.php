<?php
require_once "./connect.php";
require_once "./Utilities.php";

if (!isset($_GET["id"])) {
  echo "請從正常管道進入";
  exit;
}

$id = $_GET["id"];
$sql = "SELECT 
            `msgs`.*,
            GROUP_CONCAT(imgs.file SEPARATOR ',') AS `imgs`
        FROM `msgs`
        LEFT JOIN `imgs`
        ON `msgs`.`id` = `imgs`.`msg_id`
        WHERE `msgs`.`id` = ?
        GROUP BY `msgs`.`id`;";

$sqlCate = "SELECT * FROM `category`";
$sqlReply = "SELECT * FROM `replies` WHERE `msg_id` = ?";

$sqlActive = "SELECT * FROM `activies` WHERE `msg_id` = ?";

try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$id]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row["imgs"]) {
    $row["imgs"] = explode(",", $row["imgs"]);
  } else {
    $row["imgs"] = [];
  }

  $stmtActive = $pdo->prepare($sqlActive);
  $stmtActive->execute([$id]);
  $rowsActive = $stmtActive->fetchAll(PDO::FETCH_ASSOC);
  
  $stmtReply = $pdo->prepare($sqlReply);
  $stmtReply->execute([$id]);
  $rowsReply = $stmtReply->fetchAll(PDO::FETCH_ASSOC);

  $stmtCate = $pdo->prepare($sqlCate);
  $stmtCate->execute();
  $rowsCate = $stmtCate->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "錯誤: {{$e->getMessage()}}";
  exit;
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>留言版</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

  <style>
    .wh200px {
      width: 200px;
      height: 200px;
      object-fit: cover;
    }

    .reply{
      margin-bottom: 2px;
      &::before{
        content: ">>";
        background-color: #a82b0c;
        color: #fff;
      }
    }
  </style>
</head>

<body>
  <div class="container mt-3">
    <?php if (!$row): ?>
      資料不存在
      <a href="./pageMsgsList.php?" class="btn btn-primary">返回</a>
    <?php else: ?>
      <form action="./doUpdate01.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $row["id"] ?>">
        <div class="input-group">
          <span class="input-group-text">名稱</span>
          <input name="name" type="text" class="form-control" placeholder="發文者名稱" value="<?= $row["name"] ?>">
        </div>
        <div class="input-group mt-1">
          <span class="input-group-text">內容</span>
          <textarea name="content" class="form-control"><?= $row["content"] ?></textarea>
        </div>
        <div class="input-group mt-1">
          <input class="form-control" type="file" name="myFile" accept=".png,.jpg,.jpeg">
        </div>
        <div class="input-group mt-1 mb-2">
          <span class="input-group-text">分類</span>
          <select name="category" class="form-select">
            <option value selected disabled>請選擇</option>
            <?php foreach ($rowsCate as $rowCate): ?>
              <option value="<?= $rowCate["id"] ?>" <?= ($rowCate["id"] == $row["category_id"]) ? "selected" : "" ?>>
                <?= $rowCate["name"] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mt-1 text-end">
          <button type="submit" class="btn btn-info">送出</button>
          <a href="./pageMsgsList.php" class="btn btn-warning">取消修改</a>
        </div>
      </form>
      <form action="./doReply.php" method="post">
        <div class="input-group mt-3">
          <span class="input-group-text">留言</span>
          <input type="hidden" name="msgID" value="<?= $id ?>">
          <input type="text" name="reply" class="form-control">
          <button class="btn btn-primary" type="submit">送出</button>
        </div>
      </form>
      <div class="replies">
        <?php foreach($rowsReply as $rowReply): ?>
        <div class="reply"><?=$rowReply["text"]?></div>
        <?php endforeach; ?>
      </div>


      <form action="./doActive.php" method="post">
        <div class="input-group mt-3">
          <span class="input-group-text">活動名稱</span>
          <input type="hidden" name="msgID" value="<?= $id ?>">
          <input type="text" name="activeName" class="form-control">
          <input type="date" name="start" class="form-control">
          <input type="date" name="end" class="form-control">
          <button class="btn btn-primary" type="submit">送出</button>
        </div>
      </form>
      <div class="replies">
        <?php foreach($rowsActive as $rowActive): ?>
        <div class="reply"><?=$rowActive["name"].$rowActive["start_at"].$rowActive["end_at"]?></div>
        <?php endforeach; ?>
      </div>


      <form class="mt-5 p-2 bg-primary-subtle" method="post" action="./doUpload03.php" enctype="multipart/form-data">
        <div class="contentArea"></div>
        <input type="hidden" name="msgID" value="<?= $id ?>">
        <div class="mt-1 text-end">
          <button type="submit" class="btn btn-info btn-save">寫入 img 資料庫</button>
          <button type="button" class="btn btn-primary btn-add">增加一組</button>
        </div>
      </form>
      <?php if ($row["img"]): ?>
        <img class="img-fluid my-1" src="./uploads/<?= $row["img"] ?>" alt="">
      <?php endif; ?>
      <?php foreach ($row["imgs"] as $img): ?>
        <img class="wh200px" src="./uploads/<?= $img ?>" alt="">
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <template id="inputs">
    <div class="input-group mt-1">
      <input class="form-control" type="file" name="imgFile[]" accept=".png,.jpg,.jpeg">
    </div>
  </template>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
    crossorigin="anonymous"></script>
  <script>
    const btnAdd = document.querySelector(".btn-add");
    const contentArea = document.querySelector(".contentArea");
    const template = document.querySelector("#inputs");

    btnAdd.addEventListener("click", function (e) {
      // e.preventDefault();
      const node = template.content.cloneNode(true);
      contentArea.append(node);
    });
  </script>
</body>

</html>