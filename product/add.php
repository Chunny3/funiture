<?php
require_once "./connect.php";

$sql = "SELECT * FROM `products`";
$sqlCate = "SELECT * FROM `products_category`";
$sqlSty = "SELECT * FROM `style`";
$errorMsg = "";
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmtCate = $pdo->prepare($sqlCate);
    $stmtCate->execute();
    $rowsCate = $stmtCate->fetchAll();

    $stmtSty = $pdo->prepare($sqlSty);
    $stmtSty->execute();
    $rowsSty = $stmtSty->fetchAll();

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

    <title>新增商品 - 商品管理系統</title>

    <!-- Custom fonts for this template -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"
        type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .sidebar {
            background-color: rgb(113, 154, 139) !important;
        }

        .sidebar .nav-item .nav-link {
            color: #fff !important;
        }

        .sidebar .sidebar-brand {
            color: #fff !important;
        }

        .sidebar .sidebar-heading {
            color: #fff !important;
        }

        .sidebar .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15) !important;
        }

        .sidebar-brand-text {
            color: rgb(113, 154, 139) !important;
        }

        .btn-dark {
            background-color: #a0a599 !important;
            border-color: #a0a599 !important;
        }

        .btn-dark:hover {
            background-color: rgb(113, 154, 139) !important;
            border-color: rgb(113, 154, 139) !important;
        }

        .card-header {
            background-color: rgb(113, 154, 139) !important;
            color: #fff !important;
        }

        .card-header h6 {
            color: #fff !important;
        }

        .input-group {
            margin-bottom: 1rem !important;
        }

        .input-group-text {
            min-width: 100px;
            justify-content: center;
        }

        .form-control,
        .form-select {
            height: 38px;
            width: 100%;
        }

        .form-select {
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            background-color: #fff;
        }

        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 1rem;
        }

        .preview-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-group {
            gap: 10px;
        }

        .form-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 2rem;
        }

        .btn-success {
            background-color: rgb(113, 154, 139);
            border-color: rgb(113, 154, 139);
        }

        .btn-success:hover {
            background-color: rgb(113, 154, 139);
            border-color: rgb(113, 154, 139);
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav  sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">商品管理系統</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="index.html">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>儀表板</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                商品管理
            </div>

            <!-- Nav Item - Tables -->
            <li class="nav-item active">
                <a class="nav-link" href="productlist.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>商品列表</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">管理員</span>
                                <img class="img-profile rounded-circle"
                                    src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    個人資料
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    設定
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    登出
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">新增商品</h1>

                    <?php if ($errorMsg != ""): ?>
                        <div class="alert alert-danger">
                            分類讀取錯誤: <?= $errorMsg ?>
                        </div>
                        <div class="text-end">
                            <a href="./productlist.php" class="btn btn-primary">回主頁</a>
                        </div>
                    <?php else: ?>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">商品資訊</h6>
                            </div>
                            <div class="card-body">
                                <form action="./doInsert.php" method="post" enctype="multipart/form-data">
                                    <div class="content-area">
                                        <div class="inputs border rounded p-3 mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text">商品圖片</span>
                                                <input class="form-control" type="file" name="myFile[]"
                                                    accept=".png,.jpg,.jpeg" onchange="previewImages(this)">
                                            </div>
                                            <div class="preview-container" id="imagePreview"></div>
                                            <div class="input-group">
                                                <span class="input-group-text">名稱</span>
                                                <input name="name[]" type="text" class="form-control" placeholder="請輸入商品名稱">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-text">產品描述</span>
                                                <textarea name="content[]" class="form-control" rows="3"
                                                    placeholder="請輸入商品描述"></textarea>
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-text">價錢</span>
                                                <input name="price[]" type="text" class="form-control"
                                                    placeholder="請輸入商品價格">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-text">數量</span>
                                                <input name="quantity[]" type="text" class="form-control"
                                                    placeholder="請輸入商品數量">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-text">顏色</span>
                                                <input name="color[]" type="text" class="form-control"
                                                    placeholder="請輸入商品顏色">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-text">分類</span>
                                                <select name="category[]" class="form-select">
                                                    <option value selected disabled>請選擇分類</option>
                                                    <?php foreach ($rowsCate as $row): ?>
                                                        <option value="<?= $row["category_id"] ?>"><?= $row["category_name"] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-text">風格</span>
                                                <select name="style[]" class="form-select">
                                                    <option value selected disabled>請選擇風格</option>
                                                    <?php foreach ($rowsSty as $row): ?>
                                                        <option value="<?= $row["des"] ?>"><?= $row["des"] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-buttons">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save"></i> 完成
                                        </button>
                                        <a href="./productlist.php" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> 返回
                                        </a>
                                        <input type="hidden" name="msgID" value="<?= $id ?>">
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; 商品管理系統 2024</span>
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
                    <h5 class="modal-title" id="exampleModalLabel">確定要登出嗎？</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">如果您確定要登出，請點擊下方的「登出」按鈕。</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">取消</button>
                    <a class="btn btn-primary" href="login.html">登出</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/js/sb-admin-2.min.js"></script>

    <script>
        function previewImages(input) {
            const previewContainer = document.getElementById('imagePreview');
            previewContainer.innerHTML = '';

            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'preview-image';
                        previewContainer.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }
    </script>

</body>

</html>