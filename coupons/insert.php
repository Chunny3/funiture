<?php
require_once "../connect.php";

if(!isset($_SESSION["user"])){
  header("location: /users/login.php");
  exit;
}

$sqlCate = "SELECT * FROM `products_category`";
$sqlLv = "SELECT * FROM `member_levels`";
$errorMsg = "";
try {
    $stmtCate = $pdo->prepare($sqlCate);
    $stmtCate->execute();
    $rowsCate = $stmtCate->fetchAll(PDO::FETCH_ASSOC);

    $stmtLv = $pdo->prepare($sqlLv);
    $stmtLv->execute();
    $rowsLv = $stmtLv->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // echo "錯誤: {{$e->getMessage()}}";
    // exit;
    $errorMsg = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>新增優惠券</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    <!-- Custom fonts for this template -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <link rel="stylesheet" href="../css/mycss.css">


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper" class="d-flex min-vh-100">

        <!-- Sidebar -->
        <div id="sidebar" class="bg-light" style="min-width:220px; min-height:100vh; height:100%;">
            <?php include "../index/sideBar.php"; ?>
        </div>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include "../index/topBar.php"; ?>

                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">


                    <!-- 優惠券列表 -->
                    <div class="card shadow max-width1200 mx-auto">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-white">新增優惠券</h6>
                        </div>
                        <div class="container mt-5 max-width800">
                            <form action="./doInsert.php" method="post">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">名稱</span>
                                    <input required name="name" type="text" class="form-control" placeholder="請輸入優惠券名稱"
                                        minlength="3" maxlength="30" title="請輸入 3~30 字的優惠券名稱">
                                </div>

                                <!-- 折扣碼與折扣金額 -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text">折扣碼</span>
                                            <input required name="code" type="text" class="form-control" minlength="5"
                                                maxlength="12" title="請輸入 4~12 字母或數字" id="coupon-code">
                                            <button type="button" class="input-group-btn" title="自動產生折扣碼"><i
                                                    class="fa-solid fa-wand-magic-sparkles"></i></button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <select id="discount_type" name="discount-type" class="form-select"
                                                style="max-width: 140px;">
                                                <option value="1">現金折扣</option>
                                                <option value="2">百分比折扣</option>
                                            </select>
                                            <input required type="number" id="discount_value" name="discount"
                                                class="form-control" step="0.01">
                                            <span class="input-group-text dollor">元</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- 折扣碼與折扣金額 -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text">最低消費金額</span>
                                            <input required name="min-discount" type="number" class="form-control"
                                                min="0" value="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text">發行數量</span>
                                            <input name="max-amount" type="number" class="form-control"
                                                placeholder="未填寫則不限數量">
                                        </div>
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <label class="form-label">會員限制</label><br>
                                    <div class="d-flex flex-wrap gap-2 justify-content-start" role="group">
                                        <input type="checkbox" class="btn-check btn-all-lv" id="member-btncheck"
                                            name="member-levels[]" value="all" checked>
                                        <label class="btn btn-outline-secondary rounded-pill"
                                            for="member-btncheck">全部會員</label>
                                        <?php foreach ($rowsLv as $rowLv): ?>
                                            <input type="checkbox" class="btn-check btn-other-lv"
                                                id="member-btncheck<?= $rowLv["id"] ?>" name="member-levels[]"
                                                value="<?= $rowLv["id"] ?>">
                                            <label class="btn btn-outline-secondary rounded-pill"
                                                for="member-btncheck<?= $rowLv["id"] ?>"><?= $rowLv["name"] ?></label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <label class="form-label">適用商品類別</label><br>
                                    <div class="d-flex flex-wrap gap-2 justify-content-start" role="group">
                                        <input type="checkbox" class="btn-check btn-all-cate" id="category-btncheck"
                                            name="category[]" value="all" checked>
                                        <label class="btn btn-outline-primary rounded-pill"
                                            for="category-btncheck">全部類別</label>
                                        <?php foreach ($rowsCate as $rowCate): ?>
                                            <input type="checkbox" class="btn-check btn-other-cate"
                                                id="category-btncheck<?= $rowCate["category_id"] ?>" name="category[]"
                                                value="<?= $rowCate["category_id"] ?>">
                                            <label class="btn btn-outline-primary rounded-pill"
                                                for="category-btncheck<?= $rowCate["category_id"] ?>"><?= $rowCate["category_name"] ?></label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <label class="form-label">有效期限設定</label>

                                    <!-- 類型選擇：固定區間 / 領取後幾天 -->
                                    <div class="d-flex align-items-center mb-2 gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="expire-type"
                                                id="expire_fixed" value="fixed" checked>
                                            <label class="form-check-label" for="expire_fixed">固定日期區間</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="expire-type"
                                                id="expire_relative" value="relative">
                                            <label class="form-check-label" for="expire_relative">領取後有效天數</label>
                                        </div>
                                    </div>

                                    <!-- 固定日期區塊 -->
                                    <div id="fixed_section" class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text">優惠開始日期</span>
                                                <input type="date" class="form-control input-date start-at"
                                                    name="start-at">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text">優惠結束日期</span>
                                                <input type="date" class="form-control input-date end-at" name="end-at">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 領取後幾天區塊 -->
                                    <div id="relative_section" class="d-none mb-4">
                                        <div class="input-group">
                                            <span class="input-group-text">領取後</span>
                                            <input type="number" class="form-control" name="valid-days" min="1"
                                                placeholder="請輸入有效天數">
                                            <span class="input-group-text">天內有效</span>
                                        </div>
                                    </div>

                                    <div class="mt-1 text-end mb-5">
                                        <button type="submit" class="btn btn-info">確認</button>
                                        <a href="./couponsList.php" class="btn btn-info">取消</a>
                                    </div>
                            </form>
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
                        <span>Copyright &copy; Oak!y 2025</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>


    <!-- bootstrap  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/datatables-demo.js"></script>

    <!-- 我的JS -->
    <script src="./js/couponForm.js"></script>


</body>

</html>