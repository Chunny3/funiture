<?php
require_once "../connect.php";
$sql = "SELECT * FROM `article_category`";
$errorMsg = "";
try {
    $stmt = $pdo->
        prepare($sql);
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
    <title>新增文章表單</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/article.css">
    <link rel="stylesheet" href="../css/mycss.css">


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper" class="d-flex min-vh-100">
        <div id="sidebar" class="bg-light" style="min-width:220px; min-height:100vh; height:100%;">
            <?php include "../index/sideBar.html"; ?>
        </div>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include "../index/topBar.html"; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">


                    <!-- Basic Card Example -->
                    <div class="card shadow max-width1200 mx-auto">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-white">新增文章表單</h6>
                            
                        </div>
                        <form action="./doAdd.php" method="post" enctype="multipart/form-data">
                            <div class="container mt-3 max-width800">
                                <div class="input-group mb-2">
                                    <div class="input-group-text">標題</div>
                                    <input required type="text" name="title" class="form-control">
                                </div>

                                <!-- ckeditor -->
                                <div id="editor">
                                    <p>請輸入內容</p>
                                </div>

                                <div class="input-group mb-2 mt-2">
                                    <span class="input-group-text">標籤</span>
                                    <div
                                        class="form-control bg-white input-group-text tag-list text-start text-wrap flex-wrap">
                                    </div>

                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">標籤輸入</span>
                                    <input type="text" name="tag" list="tag-options" class="form-control"
                                        placeholder="不需要#，輸入後按 ctrl + Enter 結束一組的輸入">
                                    <datalist id="tag-options"></datalist>
                                    <input type="hidden" name="tags">
                                </div>
                                <div class="input-group mt-1 mb-2">
                                    <span class="input-group-text">分類</span>
                                    <select name="category" class="form-select">
                                        <option value selected disabled>請選擇分類</option>
                                        <?php foreach ($rows as $row): ?>
                                            <option value="<?= $row["id"] ?>"><?= $row["name"] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex mt-1 mb-3">
                                <div class="btn btnAdd ms-auto btn-send ">送出</div>
                                <a href="./index.php" class="btn btnAdd ms-2 me-5">取消</a>
                            </div>
                        </form>
                    </div>

                </div>


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

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="../js/articleAdd.js"></script>

</body>

</html>