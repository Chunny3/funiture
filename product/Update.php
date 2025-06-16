<?php
require_once "./connect.php";
require_once "./Utilities.php";

if (!isset($_GET["id"])) {
    echo "請從正常管道進入";
    exit;
}

$id = $_GET["id"];

$sqlSty = "SELECT * FROM `style`";
$sql = "SELECT
          `products`.*,
          GROUP_CONCAT(`product_img`.`img` SEPARATOR ',') AS `imgs`
        FROM `products`
        LEFT JOIN `product_img`
        ON  `products`.`id` = `product_img`.`product_id`
        WHERE `products`.`id` = ?
        GROUP BY `products`.`id`";
$sqlCate = "SELECT * FROM `products_category`";
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if
    ($row["imgs"]) {

        $row["imgs"] = explode(",", $row["imgs"]);
    } else {
        $row["imgs"] = [];
    }
    $stmtSty = $pdo->prepare($sqlSty);
    $stmtSty->execute();
    $rowsSty = $stmtSty->fetchAll(PDO::FETCH_ASSOC);

    $stmtCate = $pdo->prepare($sqlCate);
    $stmtCate->execute();
    $rowsCate = $stmtCate->fetchAll();
} catch (PDOException $e) {
    echo "錯誤: {{$e->getMessage()}}";
    exit;
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

    <title>產品修改頁 - 商品管理系統</title>

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
            flex-direction: row;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 1rem;
        }

        .btn-success {
            background-color: rgb(113, 154, 139);
            border-color: rgb(113, 154, 139);
        }

        .btn-success:hover {
            background-color: rgb(113, 154, 139);
            border-color: rgb(113, 154, 139);
        }

        .wh200px {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin: 5px;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

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
                    <h1 class="h3 mb-4 text-gray-800">修改商品</h1>

                    <?php if (!$row): ?>
                        <div class="alert alert-danger">
                            資料不存在
                            <a href="./productlist.php" class="btn btn-primary">返回</a>
                        </div>
                    <?php else: ?>
                        <!-- 現有圖片 -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold">現有圖片</h6>
                            </div>
                            <div class="card-body">
                                <?php foreach ($row["imgs"] as $img): ?>
                                    <?php if (strpos($img, 'http') === 0): ?>
                                        <img class="wh200px" src="<?= $img ?>" alt="">
                                    <?php else: ?>
                                        <img class="wh200px" src="./uploads/<?= $img ?>" alt="">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- 新增圖片 -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold">新增圖片</h6>
                            </div>
                            <div class="card-body">
                                <form method="post" action="./doUpdate02.php" enctype="multipart/form-data">
                                    <input type="hidden" name="msgID" value="<?= $id ?>">
                                    <input type="file" id="multiFileInput" name="imgFile[]" multiple
                                        accept=".png,.jpg,.jpeg" style="display: none;"
                                        onchange="previewMultipleImages(event)">
                                    <div id="previewContainer" class="preview-container">
                                        <!-- 預覽圖片將會顯示在這裡 -->
                                    </div>
                                    <div class="form-buttons">
                                        <label for="multiFileInput" class="btn btn-dark mb-0">
                                            <i class="fas fa-plus"></i> 新增圖片
                                        </label>
                                        <button type="submit" class="btn btn-success mb-0">
                                            <i class="fas fa-upload"></i> 上傳圖片
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- 編輯商品資訊 -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold">編輯商品資訊</h6>
                            </div>
                            <div class="card-body">
                                <form method="post" action="./doUpdate01.php" enctype="multipart/form-data">
                                    <input type="hidden" name="msgID" value="<?= $id ?>">
                                    <input type="file" id="singleFileInput" name="imgFile" accept=".png,.jpg,.jpeg"
                                        style="display: none;" onchange="previewImage(event)">
                                    <img id="preview" src="#" alt="預覽圖片"
                                        style="display:none; width: 300px; height: 300px; object-fit: cover; border:1px solid #ddd; border-radius: 4px;">
                                    <div class="mb-3">
                                        <label for="singleFileInput" class="btn btn-dark mt-3">
                                            <i class="fas fa-image"></i> 上傳首圖
                                        </label>
                                    </div>

                                    <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                    <div class="input-group">
                                        <span class="input-group-text">商品名稱</span>
                                        <input name="name" type="text" class="form-control" value="<?= $row["name"] ?>">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text">商品描述</span>
                                        <textarea name="description" class="form-control"
                                            rows="3"><?= $row["description"] ?></textarea>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text">商品價格</span>
                                        <input name="price" type="text" class="form-control" value="<?= $row["price"] ?>">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text">商品數量</span>
                                        <input name="quantity" type="text" class="form-control"
                                            value="<?= $row["quantity"] ?>">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text">顏色</span>
                                        <input name="color" type="text" class="form-control" value="<?= $row["color"] ?>">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text">分類</span>
                                        <select name="category" class="form-select">
                                            <option value selected disabled>請選擇分類</option>
                                            <?php foreach ($rowsCate as $rowCat): ?>
                                                <option value="<?= $rowCat["category_id"] ?>"
                                                    <?= ($row["category_id"] == $rowCat["category_id"]) ? "selected" : "" ?>>
                                                    <?= $rowCat["category_name"] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text">風格</span>
                                        <select name="style" class="form-select">
                                            <option value selected disabled>請選擇風格</option>
                                            <?php foreach ($rowsSty as $rowSty): ?>
                                                <option value="<?= $rowSty["des"] ?>" <?= ($row["style"] == $rowSty["des"]) ? "selected" : "" ?>><?= $rowSty["des"] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-buttons">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save"></i> 完成編輯
                                        </button>
                                        <a href="./productlist.php" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> 返回
                                        </a>
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
        // 單一圖片預覽
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        // 多圖片預覽
        function previewMultipleImages(event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('previewContainer');
            previewContainer.innerHTML = "";

            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'preview-image';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    </script>

</body>

</html>