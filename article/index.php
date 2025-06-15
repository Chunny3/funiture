<?php
require_once "../connect.php";
require_once "../utilities.php";

$cid = intval($_GET["cid"] ?? 0);

if ($cid == 0) {
    $cateSQL = "";
    $values = [];
} else {
    $cateSQL = "`category_id` = :cid AND";
    $values = ["cid" => $cid];
}

$search = $_GET["search"] ?? "";
$searchType = $_GET["qType"] ?? "";
if ($search == "") {
    $searchSQL = "";

} else {
    $searchSQL = "`$searchType` LIKE :search AND";
    $values["search"] = "%$search%";
}

$date1 = $_GET["date1"] ?? "";
$date2 = $_GET["date2"] ?? "";
$dateSQL = "";
if ($searchType == "createTime") {
    if ($date1 != "" && $date2 != "") {
        $startDateTime = "{$date1} 00:00:00";
        $endDateTime = "{$date2} 23:59:59";
    } else if ($date1 == "" && $date2 != "") {
        $startDateTime = "{$date2} 00:00:00";
        $endDateTime = "{$date2} 23:59:59";
    } else if ($date2 == "" && $date1 != "") {
        $startDateTime = "{$date1} 00:00:00";
        $endDateTime = "{$date1} 23:59:59";
    }
    $dateSQL = "(`createTime` BETWEEN :startDateTime AND :endDateTime) AND ";
    $values["startDateTime"] = $startDateTime;
    $values["endDateTime"] = $endDateTime;
}


$perPage = 10;
$page = intval($_GET["page"] ?? 1);
$pageStart = ($page - 1) * $perPage;

$order = $_GET["order"] ?? "desc";
$sortBy = $_GET["sortBy"] ?? "";
$sortSQL = "ORDER BY `categoryName` DESC";
if ($sortBy == "categoryName") {
    $order = strtolower($order) === "asc" ? "ASC" : "DESC";
    $sortSQL = "ORDER BY `categoryName` $order";

} else if ($sortBy == "") {
    $sortSQL = "ORDER BY `article`.`id` ASC";
}
;

// $sql = "SELECT * FROM `article` WHERE $cateSQL $searchSQL $dateSQL (`created_date` IS NULL OR `created_date` < NOW()) AND is_valid = 1 LIMIT $perPage OFFSET $pageStart";
$sqlAll = "SELECT * FROM `article` WHERE $cateSQL $searchSQL $dateSQL (`created_date` IS NULL OR `created_date` < NOW()) AND is_valid = 1";
$sqlCate = "SELECT * FROM `article_category`";


$sql = "SELECT 
article.*,
GROUP_CONCAT(DISTINCT article_category.name) AS categoryName,
GROUP_CONCAT(tag.name) AS tagName
FROM `article` 
LEFT JOIN `article_tag`
ON `article`.`id` = `article_tag`.`article_id`
LEFT JOIN `tag`
ON `tag`.`id` = `article_tag`.`tag_id`
LEFT JOIN `article_category`
ON `article`.`article_category_id` = `article_category`.`id`
WHERE is_valid = 1
GROUP BY `article`.`id`
$sortSQL
LIMIT $perPage OFFSET $pageStart";



try {

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmtAll = $pdo->prepare($sqlAll);
    $stmtAll->execute($values);
    $length = $stmtAll->rowCount();

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
    <title>Bootstrap demo</title>
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

    <link href="../css/mycss.css" rel="stylesheet">
    <link href="../css/article.css" rel="stylesheet">


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper" class="d-flex min-vh-100">
        <div id="sidebar" class="bg-light" style="min-width:220px; min-height:100vh; height:100%;">
            <?php include "../index/sideBar.html"; ?>
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
                    <div class="d-flex align-items-center mb-3 justify-content-between flex-wrap">
                        <div class="d-flex gap-12 ml-3">
                            <a href="./index.php" class="btn btn-secondary btn-sm">清除篩選&nbsp;&nbsp;<i
                                    class="fa-solid fa-rotate"></i></a>
                            <!-- <div class="btn-group">
                                <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    全部商品類別
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" type="button">全部類別</button>
                                </div>
                            </div> -->

                        </div>

                        <div class="d-flex gap-8 mr-3 align-items-center">
                            <!-- <div class="btn-group">
                                <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    日期狀態選擇
                                </button>
                                <ul class="dropdown-menu" id="category-dropdown">
                                    <li><button type="button" class="dropdown-item" data-value="">全部類別</button></li>
                                </ul>
                            </div> -->
                            <!-- 搜尋日期 -->
                            <span>有效日期範圍</span>
                            <input id="input-date1" name="date1" type="date"
                                class=" form-control input-date form-control-sm w-150">
                            <span> ~ </span>
                            <input id="input-date2" name="date2" type="date"
                                class="form-control input-date form-control-sm w-150">

                            <!-- 搜尋 -->
                            <form class="input-group w-250 ml-4 d-flex">
                                <input name="searchType" id="searchType3" type="radio" class="form-check-input"
                            value="createTime" placeholder="搜尋文章名稱">
                                <input id="search-input" name="search" type="text" class="form-control form-control-sm"
                                    placeholder="搜尋文章名稱">
                                <button id="search-btn" type="button" class="btn btn-sm btn-search"><i
                                        class="fa-solid fa-magnifying-glass"></i></button>
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
                                <table class="table table-bordered text-center" id="dataTable" width="100%"
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
                                                    <?= $row["created_date"] ?></td>
                                                <td>
                                                    <button type="button" class="btn-view" data-toggle="modal"
                                                        data-target="#viewModal123">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>
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
                                            if ($searchType != "")
                                                $link .= "&search={$search}&qType={$searchType}";
                                            if ($date1 != "")
                                                $link .= "&date1={$date1}";
                                            if ($date2 != "")
                                                $link .= "&date2={$date2}";
                                            ?>
                                            <a class="page-link" href="<?= $link ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                                <div>
                                    <button class="btn btn-info ml-auto">
                                        <i class="fa-solid fa-trash"></i>
                                        垃圾桶
                                    </button>
                                </div>
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
    <script src="js/demo/datatables-demo.js"></script>
    <script src="../js/index.js"></script>


</body>

</html>