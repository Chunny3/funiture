<?php
require_once "./connect.php";
$sql = "SELECT * FROM `category`";
$errorMsg = "";
try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  // echo "錯誤: {{$e->getMessage()}}";
  // exit;
  $errorMsg = $e->getMessage();
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>新增用的表單</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>

<body>
  <div class="container mt-3">
    <?php if ($errorMsg != ""): ?>
      <div>
        分類讀取錯誤: <?= $errorMsg ?>
      </div>
      <div class="text-end">
        <a href="./pageMsgsList.php" class="btn btn-primary">回主頁</a>
      </div>
    <?php else: ?>
      <form action="./doInsert05.php" method="post" enctype="multipart/form-data">
        <div class="content-area">
          <div class="input-group">
            <span class="input-group-text">名稱</span>
            <input name="name[]" type="text" class="form-control" placeholder="發文者名稱">
          </div>
          <div class="input-group mt-1">
            <span class="input-group-text">內容</span>
            <textarea name="content[]" class="form-control"></textarea>
          </div>
          <div class="input-group mt-1">
            <input class="form-control" type="file" name="myFile[]" accept=".png,.jpg,.jpeg" multiple>
          </div>
          <div class="input-group mt-1 mb-2">
            <span class="input-group-text">分類</span>
            <select name="category[]" class="form-select">
              <option value selected disabled>請選擇</option>
              <?php foreach ($rows as $row): ?>
                <option value="<?= $row["id"] ?>"><?= $row["name"] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="mt-1 text-end">
          <button type="submit" class="btn btn-info">送出</button>
          <button type="button" class="btn btn-primary btn-add">增加一組</button>
          <a href="./pageMsgsList.php" class="btn btn-warning">取消</a>
        </div>
      </form>
    <?php endif; ?>
  </div>
  
  <template id="inputs">
    <div class="input-group">
      <span class="input-group-text">名稱</span>
      <input name="name[]" type="text" class="form-control" placeholder="發文者名稱">
    </div>
    <div class="input-group mt-1">
      <span class="input-group-text">內容</span>
      <textarea name="content[]" class="form-control"></textarea>
    </div>
    <div class="input-group mt-1">
      <input class="form-control" type="file" name="myFile[]" accept=".png,.jpg,.jpeg" multiple>
    </div>
    <div class="input-group mt-1 mb-2">
      <span class="input-group-text">分類</span>
      <select name="category[]" class="form-select">
        <option value selected disabled>請選擇</option>
        <?php foreach ($rows as $row): ?>
          <option value="<?= $row["id"] ?>"><?= $row["name"] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </template>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
    crossorigin="anonymous"></script>
  <script>
    const contentArea = document.querySelector(".content-area");
    const btnAdd = document.querySelector(".btn-add");
    const template = document.querySelector("#inputs");
    btnAdd.addEventListener("click", (e) => {
      // e.preventDefault();
      const node = template.content.cloneNode(true);
      contentArea.append(node);
    });
  </script>
</body>

</html>