<?php
require_once "../connect.php";
require_once "../utilities.php";

$perPage = 10;
$page = intval($_GET["page"] ?? 1);
$pageStart = ($page - 1) * $perPage;

$sql = "SELECT * FROM `article` WHERE `is_valid` = 1 ORDER BY `id` DESC";
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "系統錯誤，請恰管理人員<br>";
    echo "Error: {$e->getMessage()}<br>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <title>文章管理</title>


    <!-- Custom fonts for this template -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="../css/mycss.css" rel="stylesheet">
    <link href="../css/article.css" rel="stylesheet">



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
                     <div class="d-flex align-items-center mb-4">
                         <h1 class="h3 text-gray-800 mb-0">文章管理系統</h1>
                         <a class="btn btnAdd btn-sm ml-auto mr-5" href="add.php">新增文章
                            <i class="fas fa-plus"></i>
                         </a>
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
                                            <th>分類</th>
                                            <th>標籤</th>
                                            <th>日期</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($rows as $index => $row): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= $row["title"] ?></td>
                                                <td><?= $row["user_id"] ?></td>
                                                <td><?= $row["article_category_id"] ?></td>
                                                <td>111</td>
                                                <td><?= $row["created_date"] ?></td>
                                                <td>
                                                    <a href="./doDelete.php?id=<?= $row["id"] ?>"
                                                        class="btn btn-danger btn-sm">刪除</a>
                                                    <a class="btn btn-primary btn-sm"
                                                        href="update.php?id=<?= $row["id"] ?>">修改</a>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
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
    <script src="../js/article.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    </head>
</body>