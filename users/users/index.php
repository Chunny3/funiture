<?php
require_once "../connect.php";
require_once "../Utilities.php";

$perPage = 10;
$page = intval($_GET["page"] ?? 1);
$pageStart = ($page - 1) * $perPage;

$search = $_GET["search"] ?? "";
$dateType = $_GET["dateType"] ?? "created_at"; // 預設註冊日期
$date1 = $_GET["date1"] ?? "";
$date2 = $_GET["date2"] ?? "";
$values = [];
$where = "WHERE `is_valid` = 1";

if ($search != "") {
  $where .= " AND (users.name LIKE :search OR users.email LIKE :search OR users.birthday LIKE :search OR users.phone LIKE :search OR users.city LIKE :search OR users.area LIKE :search OR users.address LIKE :search)";
  $values[":search"] = "%$search%";
}
if ($date1 && $date2) {
  $where .= " AND {$dateType} BETWEEN :date1 AND :date2";
  $values[":date1"] = $date1;
  $values[":date2"] = $date2;
} elseif ($date1) {
  $where .= " AND {$dateType} >= :date1";
  $values[":date1"] = $date1;
} elseif ($date2) {
  $where .= " AND {$dateType} <= :date2";
  $values[":date2"] = $date2;
}

$sql = "SELECT users.*, levels.name AS level_name FROM users 
        LEFT JOIN levels ON users.level_id = levels.id 
        $where LIMIT $perPage OFFSET $pageStart";
$sqlAll = "SELECT users.*, levels.name AS level_name FROM users 
        LEFT JOIN levels ON users.level_id = levels.id 
        $where";

try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute($values);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $stmtAll = $pdo->prepare($sqlAll);
  $stmtAll->execute($values);
  $totalCount = $stmtAll->rowCount();
} catch (PDOException $e) {
  echo "錯誤: {{$e->getMessage()}}";
  exit;
}
$totalPage = ceil($totalCount / $perPage);
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>會員管理系統</title>
  <!-- SB Admin 2 字型與主題 -->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <!-- 你的自訂CSS（如有） -->
  <link rel="stylesheet" href="../css/mycss.css">
</head>

<style>
  .rounded-circle {
    border-radius: 50% !important;
    object-fit: cover;
  }

  .table td,
  .table th {
    vertical-align: middle;
  }
</style>
</head>

<body>
  <div class="container mt-3">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between mb-1">
      <h1 class="h3 text-gray-800 ml-3">會員管理系統</h1>
    </div>
    <div class="d-flex mb-3 justify-content-end flex-wrap">
      <div class="d-flex gap-8 mr-1 align-items-center">
        <!-- 隱藏的 dateType select，供 JS 讀取與設定 -->
        <select id="dateType" name="dateType" style="display:none;">
          <option value="created_at" <?= $dateType == "created_at" ? "selected" : "" ?>>註冊日期</option>
          <option value="birthday" <?= $dateType == "birthday" ? "selected" : "" ?>>生日</option>
        </select>
        <!-- 註冊日期下拉式選單 -->
        <div class="btn-group">
          <button id="dateTypeBtn" class="btn btn-outline-success btn-sm dropdown-toggle" type="button"
            data-bs-toggle="dropdown" aria-expanded="false">
            <?= $dateType == "birthday" ? "生日" : "註冊日期" ?>
          </button>
          <ul class="dropdown-menu" id="date-dropdown">
            <li><button type="button" class="dropdown-item" data-value="created_at">註冊日期</button></li>
            <li><button type="button" class="dropdown-item" data-value="birthday">生日</button></li>
          </ul>
        </div>
        <!-- 註冊日期篩選 -->
        <input id="date1Input" name="date1" type="date" class="form-control input-date form-control-sm w-150"
          value="<?= $date1 ?>">
        <span> ~ </span>
        <input id="date2Input" name="date2" type="date" class="form-control input-date form-control-sm w-150"
          value="<?= $date2 ?>">

        <!-- 搜尋 -->
        <div class="input-group w-250 ml-1 d-flex">
          <input name="search" type="text" class="form-control form-control-sm" placeholder="搜尋 關鍵字">
          <button type="button" class="btn btn-sm btn-search">
            <i class="fa-solid fa-magnifying-glass"></i></button>
        </div>

        <!-- <div class="d-flex gap-12 ml-3">
        <a href="./index02.php" class="btn btn-secondary btn-sm">清除篩選&nbsp;&nbsp;<i class="fa-solid fa-rotate"></i></a>
      </div> -->
        <div class="d-flex gap-12 ml-1">
          <a href="./add.php" class="btn btn-secondary btn-sm"><i class="fa-solid fa-plus"></i></a>
        </div>
      </div>
    </div>

    <!-- 會員列表 -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-white">會員列表</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">

            <thead>
              <tr>
                <th>編號</th>
                <th>照片</th>
                <th>姓名</th>
                <th>Email</th>
                <th>生日</th>
                <th>手機</th>
                <th>地址</th>
                <th>會員等級</th>
                <th>註冊時間</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($rows as $index => $row): ?>
                <tr>
                  <td><?= $index + 1 + ($page - 1) * $perPage ?></td>
                  <!-- 如果沒有上傳照片就顯示預設圖片 -->
                  <td>
                    <img class="rounded-circle"
                      src="../uploads/<?= $row["img"] ? htmlspecialchars($row["img"]) : 'no-image.jpg' ?>" alt="照片"
                      style="width:40px; height:40px;">
                  </td>
                  <td><?= $row["name"] ?></td>
                  <td><?= $row["email"] ?></td>
                  <td><?= $row["birthday"] ?></td>
                  <td><?= $row["phone"] ?></td>
                  <td><?= $row["city"] . $row["area"] . $row["address"] ?></td>
                  <td><?= $row["level_name"] ?></td>
                  <td><?= $row["created_at"] ?></td>
                  <td>
                    <!-- <button type="button" class="btn-view" data-toggle="modal"
                                            data-target="#viewModal123" title="檢視">
                                            <i class="fa-solid fa-eye"></i>
                                        </button> -->
                    <a class="a-edit" href="./update.php?id=<?= $row["id"] ?>" title="編輯">
                      <i class="fa-solid fa-pencil"></i>
                    </a>
                    <button class="btn-del" data-id="<?= $row["id"] ?>" title="刪除">
                      <i class="fa-solid fa-trash"></i>
                    </button>

                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <!-- 資料筆數/分頁 -->
        <div class="d-flex mt-3 mb-2 justify-content-between align-items-center">
          <div>
            顯示第 <?= ($page - 1) * $perPage + 1 ?> 到 <?= min($page * $perPage, $totalCount) ?> 筆，共
            <?= $totalCount ?> 筆資料
          </div>
          <ul class="pagination pagination-sm justify-content-center mb-1">
            <?php for ($i = 1; $i <= $totalPage; $i++): ?>
              <li class="page-item <?= $page == $i ? "active" : "" ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../js/sb-admin-2.min.js"></script>
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>


  <script>
    // 刪除確認
    document.querySelectorAll(".btn-del").forEach((btn) => {
      btn.addEventListener("click", function () {
        if (confirm("確定要刪除嗎?")) {
          window.location.href = `./doDelete.php?id=${btn.dataset.id}`;
        }
      });
    });
    // 搜尋
    document.querySelector(".btn-search").addEventListener("click", function () {
      let query = document.querySelector("input[name=search]").value;
      let dateType = document.getElementById("dateType").value;
      let date1 = document.getElementById("date1Input").value;
      let date2 = document.getElementById("date2Input").value;
      let url = `./index.php?search=${encodeURIComponent(query)}&dateType=${encodeURIComponent(dateType)}`;
      if (date1) url += `&date1=${encodeURIComponent(date1)}`;
      if (date2) url += `&date2=${encodeURIComponent(date2)}`;
      window.location.href = url;
    });

    document.querySelectorAll('#category-dropdown .dropdown-item').forEach(function (item) {
      item.addEventListener('click', function () {
        const value = this.getAttribute('data-value');
        // 這裡可依需求帶入篩選條件
        // 例如 window.location.href = `?category=${value}`;
        alert('你選擇了商品類別: ' + value);
      });
    });

    // 註冊日期下拉式選單事件
    document.querySelectorAll('#date-dropdown .dropdown-item').forEach(function (item) {
      item.addEventListener('click', function () {
        const value = this.getAttribute('data-value');
        document.getElementById('dateType').value = value;
        document.getElementById('dateTypeBtn').textContent = this.textContent;
      });
    });
  </script>
</body>

</html>