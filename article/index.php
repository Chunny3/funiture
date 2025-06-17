<?php
require_once "../connect.php";
require_once "./utilitiesArticle.php";
$current_page = basename($_SERVER['PHP_SELF']);

$cid = intval($_GET["cid"] ?? 0);
$values = [];
$where = "WHERE article.is_valid = 1";

if ($cid > 0) {
    $where .= " AND article.category_id = :cid";
    $values["cid"] = $cid;
}

$map = [
    "createTime" => "created_date",
    "title" => "title"
];

$qTypeKey = $_GET["searchType"] ?? "createTime";
$qType = $map[$qTypeKey] ?? "created_date";

$start_date = trim($_GET['start_date'] ?? '');
$end_date = trim($_GET['end_date'] ?? '');
if ($start_date !== '') {
    $where .= " AND article.created_date >= :start_date";
    $values["start_date"] = "$start_date 00:00:00";
}
if ($end_date !== '') {
    $where .= " AND article.created_date <= :end_date";
    $values["end_date"] = "$end_date 23:59:59";
}

// 關鍵字搜尋（只在選擇標題時生效）
$search = trim($_GET["search"] ?? "");
$searchType = $_GET["searchType"] ?? "title";
if ($search !== "" && $searchType === "title") {
    $where .= " AND article.title LIKE :search ";
    $values["search"] = "%$search%";
}

$perPage = 10;
$page = intval($_GET["page"] ?? 1);
$pageStart = ($page - 1) * $perPage;

$order = $_GET["order"] ?? "desc";
$sortBy = $_GET["sortBy"] ?? "";
$order = strtolower($order) === "asc" ? "ASC" : "DESC";
if ($sortBy === "") {
    $sortSQL = "ORDER BY article.id ASC";
} else if ($sortBy === "categoryName") {
    $sortSQL = "ORDER BY categoryName $order";
} else {
    $sortSQL = "ORDER BY article.id ASC";
}


$sqlCate = "SELECT * FROM `article_category`";
// $sqlAll = "SELECT * FROM `article` WHERE  `is_valid` = 1";
$sqlAll = "
SELECT COUNT(DISTINCT article.id)
FROM article
LEFT JOIN article_tag ON article.id = article_tag.article_id
LEFT JOIN tag ON tag.id = article_tag.tag_id
LEFT JOIN article_category ON article.article_category_id = article_category.id
$where
";
$sql = "SELECT 
article.*,
MAX(article.created_date) AS created_date,
GROUP_CONCAT(DISTINCT article_category.name) AS categoryName,
GROUP_CONCAT(tag.name) AS tagName
FROM `article`
LEFT JOIN `article_tag`
ON `article`.`id` = `article_tag`.`article_id`
LEFT JOIN `tag`
ON `tag`.`id` = `article_tag`.`tag_id`
LEFT JOIN `article_category`
ON `article`.`article_category_id` = `article_category`.`id`
-- 改這下面
$where
GROUP BY `article`.`id`
$sortSQL
LIMIT $perPage OFFSET $pageStart";



try {

    $stmt = $pdo->prepare($sql);
    foreach ($values as $key => $val) {
        $stmt->bindValue(":" . $key, $val);
    }
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmtAll = $pdo->prepare($sqlAll);
    foreach ($values as $key => $val) {
        $stmtAll->bindValue(":" . $key, $val);
    }
    $stmtAll->execute();
    $length = $stmtAll->fetchColumn();

} catch (PDOException $e) {
    echo "系統錯誤，請恰管理人員<br>";
    echo "Error: {$e->getMessage()}<br>";
    exit;
}

$totalPage = ceil($length / $perPage);
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>文章管理</title>


    <!-- Custom fonts for this template -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <link href="./css/article.css" rel="stylesheet">
    <link href="../css/mycss.css" rel="stylesheet">


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper" class="d-flex min-vh-100">
        <div id="sidebar" class="bg-light" style="min-width:220px; min-height:100vh; height:100%;">
            <?php include "../index/sideBar.php"; ?>
        </div>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column flex-fill">

            <!-- Main Content -->
            <div id="content">

                <?php include "../index/topBar.html"; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->

                    <div class="d-flex justify-content-between mb-1">
                        <h1 class="h3 text-gray-800 ml-3">文章管理系統</h1>
                        <div class="mr-3">
                            <a href="add.php" class="btn btn-info btn-sm">新增文章&nbsp;&nbsp;<i
                                    class="fa-solid fa-plus"></i></a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">

                        <a href="./index.php" class="btn btn-secondary btn-sm">清除篩選&nbsp;&nbsp;<i
                                class="fa-solid fa-rotate"></i></a>

                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <!-- 時間篩選區塊 -->
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="fw-bold">時間：</span>
                                <input type="date" name="start_date" class="form-control form-control-sm w-auto"
                                    style="min-width: 140px;" value="<?= htmlspecialchars($start_date) ?>">
                                <span class="text-muted mr-2 ml-2">至</span>
                                <input type="date" name="end_date" class="form-control form-control-sm w-auto"
                                    style="min-width: 140px;" value="<?= htmlspecialchars($end_date) ?>">
                            </div>

                            <!-- 搜尋欄位 -->
                            <form class="input-group w-auto ml-3">
                                <input type="hidden" id="searchType" name="searchType" value="title">
                                <input name="search" type="text" class="form-control form-control-sm"
                                    placeholder="搜尋文章標題">
                                <button id="search-btn" type="button" class="btn btn-sm btn-search">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </form>
                        </div>


                    </div>
                    <div class="alert my-alert-bg" role="alert">
                        文章管理系統，請注意文章的分類與標籤。
                    </div>
                    <div>
                    </div>
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-white">文章列表</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center"  width="100%"
                                    cellspacing="0">
                                    <colgroup>
                                        <col class="col-1">
                                        <col class="col-2">
                                        <col class="col-3">
                                        <col class="col-4">
                                        <col class="col-5">
                                        <col class="col-6">
                                        <col class="col-7">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>文章標題</th>
                                            <th>作者</th>
                                            <th>分類
                                                <button class="sort-btn" data-sort="categoryName"
                                                    style="background:none;border:none;">
                                                    <i class="fa-solid fa-sort sort-icon"></i>
                                                </button>
                                            </th>
                                            <th>標籤</th>
                                            <th>日期</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($rows as $index => $row): ?>
                                            <tr>
                                                <td><?= $pageStart + $index + 1 ?></td>
                                                <td><?= $row["title"] ?></td>
                                                <td><?= $row["user_id"] ?></td>
                                                <td><?= $row["categoryName"] ?></td>
                                                <td><?= $row["tagName"] ?></td>
                                                <td>
                                                    <!-- <span class='date-status date-pending'>未發佈</span><br> -->
                                                    <?= $row["created_date"] ?>
                                                </td>
                                                <td>
                                                    <!-- <button type="button" class="btn-view" data-toggle="modal"
                                                        data-target="#viewModal123">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button> -->
                                                    <a class="btn btn-sm" href="update.php?id=<?= $row["id"] ?>"><i
                                                            class="fa-solid fa-pencil"></i></a>
                                                    <a href="./doDelete.php?id=<?= $row["id"] ?>" class="btn btn-sm"><i
                                                            class="fa-solid fa-trash"></i></a>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex mt-3 mb-2 justify-content-between align-items-center">
                                    <div>顯示第 <?= $pageStart + 1 ?> 到第 <?= $pageStart + $perPage ?> 筆，共 <?= $length ?>
                                        筆資料</div>
                                    <ul class="pagination pagination-sm justify-content-center mb-1">
                                        <li class="page-item active"></li>
                                    </ul>
                                </div>
                                <ul class="pagination justify-content-center mb-1">
                                    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                                        <li class="page-item <?= $page == $i ? "active" : "" ?>">
                                            <?php
                                            $link = "./index.php?page={$i}";
                                            if ($cid > 0)
                                                $link .= "&cid={$cid}";
                                            // if ($searchType != "")
                                            //     $link .= "&search={$search}&qType={$searchType}";
                                            if ($start_date != "")
                                                $link .= "&date1={$start_date}";
                                            if ($end_date != "")
                                                $link .= "&date2={$end_date}";
                                            ?>
                                            <a class="page-link" href="<?= $link ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>

                            </div>
                        </div>

                    </div>

                </div>
                <!-- /.container-fluid -->


            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../js/demo/datatables-demo.js"></script>
    <script src="./js/index.js"></script>


</body>

</html>