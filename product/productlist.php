<?php
// session_start();
// if(!isset($_SESSION["user"])){
//   header("location: /users/login.php");
//   exit;
// }

require_once "./connect.php";

$cid = intval($_GET["cid"] ?? 0);

if ($cid == 0) {
    $cateSQL = "";
    $values = [];
} else {
    $cateSQL = "p.`category_id` = :cid AND";
    $values = ["cid" => $cid];
}
;

$search = $_GET["search"] ?? "";
$searchType = $_GET["qType"] ?? "";
if ($search == "") {
    $searchSQL = "";
} else {
    $searchSQL = "p.`$searchType` LIKE :search AND";
    $values["search"] = "%$search%";
}

$date1 = $_GET["date1"] ?? "";
$date2 = $_GET["date2"] ?? "";
$dateSQL = "";
if ($searchType == "create_at") {
    if ($date1 != "" && $date2 != "") {
        $startDateTime = "{$date1} 00:00:00";
        $endDateTime = "{$date2} 23:59:59";
    } elseif ($date1 == "" && $date2 != "") {
        $startDateTime = "{$date2} 00:00:00";
        $endDateTime = "{$date2} 23:59:59";
    } elseif ($date2 == "" && $date1 != "") {
        $startDateTime = "{$date1} 00:00:00";
        $endDateTime = "{$date1} 23:59:59";
    }
    $dateSQL = "(p.`create_at` BETWEEN :startDateTime AND :endDateTime) AND";
    $values["startDateTime"] = $startDateTime;
    $values["endDateTime"] = $endDateTime;
}

$deleteSQL = "p.`is_valid` = 1";  // 只有還存在的商品



$perPage = 10;
$page = intval($_GET["page"] ?? 1);
$pageStart = ($page - 1) * $perPage;

$sql = "SELECT p.*, c.category_name 
        FROM `products` p
        LEFT JOIN `products_category` c ON p.category_id = c.category_id
        WHERE $cateSQL $searchSQL $dateSQL $deleteSQL LIMIT $perPage OFFSET $pageStart";

$sqlAll = "SELECT p.*, c.category_name 
           FROM `products` p
           LEFT JOIN `products_category` c ON p.category_id = c.category_id
           WHERE $cateSQL $searchSQL $dateSQL $deleteSQL";

$sqlCate = "SELECT * FROM `products_category`";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
    $rows = $stmt->fetchAll();

    $stmtCate = $pdo->prepare($sqlCate);
    $stmtCate->execute();
    $rowsCate = $stmtCate->fetchAll(PDO::FETCH_ASSOC);



    $stmtAll = $pdo->prepare($sqlAll);
    $stmtAll->execute($values);
    $msgLength = $stmtAll->rowCount();
} catch (PDOException $e) {
    echo "錯誤: {$e->getMessage()}";
    exit;
}

$totalPage = ceil($msgLength / $perPage);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>商品列表</title>

    <!-- Custom fonts for this template -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet"
        type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        .bg-gradient-primary {

            background-image: url("./bar-bg.jpg") !important;
            background-color: rgb(113, 154, 139) !important;
            background-position: calc(100%) calc(100% + 10px) !important;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            mix-blend-mode: multiply !important;
        }

        .logo {
            width: 63%;
        }

        .btn-primary {
            background-color: rgb(113, 154, 139) !important;
            border-color: rgb(113, 154, 139) !important;
        }

        .btn-primary:hover {
            background-color: #a0a599 !important;
            border-color: #a0a599 !important;
        }

        .nav-link {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 45px;
            margin-right: 10px;
            margin-bottom: 10px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .category-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .category-nav a {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        .category-nav a:hover {
            background-color: rgb(113, 154, 139);
            color: white;
            border-color: rgb(113, 154, 139);
        }

        .category-nav a.active {
            background-color: rgb(113, 154, 139);
            color: white;
            border-color: rgb(113, 154, 139);
        }

        .table td,
        .table th {
            vertical-align: middle;
            text-align: center;
        }

        .table td.description {
            max-width: 200px;
            text-align: left;
        }

        .table td.style,
        .table td.quantity {
            min-width: 120px;
        }

        .btn-group-horizontal {
            display: flex;
            gap: 10px;
            justify-content: center;
        }


        .table {
            table-layout: fixed;
            width: 100%;
        }

        .table td {
            white-space: normal;
            /* 允許文字換行 */
            overflow: visible;
            /* 讓內容完全顯示 */
            text-overflow: unset;
            /* 移除文字截斷效果 */
        }

        .table td.description {
            white-space: normal;
        }

        .card-body {
            overflow-x: hidden;
        }

        .pagination .page-link {
            color: rgb(113, 154, 139);
            /* 主題顏色 */
            border: 1px solid rgb(113, 154, 139);
            /* 主題邊框顏色 */
        }

        .pagination .page-link:hover {
            background-color: rgb(113, 154, 139);
            /* 主題背景顏色 */
            color: white;
            /* 滑鼠懸停時文字顏色 */
        }

        .pagination .page-item.active .page-link {
            background-color: rgb(113, 154, 139);
            /* 主題背景顏色 */
            border-color: rgb(113, 154, 139);
            /* 主題邊框顏色 */
            color: white;
            /* 文字顏色 */
        }


        .btn-group-horizontal {
            display: flex;
            gap: 5px;
            /* 調整按鈕之間的間距 */
            justify-content: center;
            margin: 0 10px;
            /* 調整按鈕與邊框的距離 */

        }

        .table-responsive {
            overflow-x: unset;
            width: 100%;
        }

        .table {
            table-layout: fixed;
            width: 100%;
        }

      
        /* 圖片 */
        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 16%;
        }

        /* 名稱 */
        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 21%;
        }

         /* 商品描述 */
        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 24%;
        }

       /* 風格 */
        .table th:nth-child(4),
        .table td:nth-child(4) {
            width: 11%;
        }

        /* 顏色 */
        .table th:nth-child(5),
        .table td:nth-child(5) {
            width: 8%;
        }

        /* 分類 */
        .table th:nth-child(6),
        .table td:nth-child(6) {
            width: 6%;
        }

        /* 數量 */
        .table th:nth-child(7),
        .table td:nth-child(7) {
            width: 6%;
        }

         /* 價格 */
        .table th:nth-child(8),
        .table td:nth-child(8) {
            width: 8%;
        }

       
        .table th:nth-child(9),
        .table td:nth-child(9) {
            width: 9%;
        }

     
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <img src="./Oakly-logo.png" alt="Oakly" class="logo">
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="index.html">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>儀表板</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Components</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Utilities</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="utilities-color.html">Colors</a>
                        <a class="collapse-item" href="utilities-border.html">Borders</a>
                        <a class="collapse-item" href="utilities-animation.html">Animations</a>
                        <a class="collapse-item" href="utilities-other.html">Other</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Addons
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Pages</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Login Screens:</h6>
                        <a class="collapse-item" href="login.html">Login</a>
                        <a class="collapse-item" href="register.html">Register</a>
                        <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Other Pages:</h6>
                        <a class="collapse-item" href="404.html">404 Page</a>
                        <a class="collapse-item" href="blank.html">Blank Page</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item active">
                <a class="nav-link" href="productlist.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>商品列表</span></a>
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
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>



                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_1.svg" alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_2.svg" alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_3.svg" alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">


                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold " style="color:rgb(113, 154, 139);">商品資訊</h6>
                            <a href="./add.php" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-plus"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- Search Form -->
                            <form action="./productlist.php" method="get" class="mb-4">
                                <div class="row ">
                                    <div class="col-md-3 p-1">
                                        <select name="qType" class="form-control">
                                            <option value="name" <?= $searchType == "name" ? "selected" : "" ?>>商品名稱
                                            </option>
                                            <option value="description" <?= $searchType == "description" ? "selected" : "" ?>>商品描述</option>
                                            <option value="style" <?= $searchType == "style" ? "selected" : "" ?>>風格</option>
                                            <option value="color" <?= $searchType == "color" ? "selected" : "" ?>>顏色</option>
                                            <option value="create_at" <?= $searchType == "create_at" ? "selected" : "" ?>>
                                                建立日期</option>


                                        </select>
                                    </div>
                                    <div class="col-md-2 p-1">
                                        <input type="text" name="search" class="form-control" value="<?= $search ?>"
                                            placeholder="請輸入關鍵字">
                                    </div>
                                    <div class="col-md-2 p-1">
                                        <input type="date" name="date1" class="form-control" value="<?= $date1 ?>">
                                    </div>
                                    <div class="col-md-2 p-1">
                                        <input type="date" name="date2" class="form-control" value="<?= $date2 ?>">
                                    </div>
                                    <div class="ms-auto p-1">
                                        <button type="submit" class="btn btn-primary ">
                                            <i class="fas fa-search"></i> 搜尋
                                        </button>
                                        <a href="./productlist.php" class="btn btn-secondary ">
                                            <i class="fas fa-redo"></i> 重置
                                        </a>
                                    </div>
                                </div>



                            </form>

                            <!-- Category Navigation -->
                            <div class="category-nav">
                                <a href="./productlist.php" class="<?= $cid == 0 ? "active" : "" ?>">全部</a>
                                <?php foreach ($rowsCate as $rowCate): ?>
                                    <a href="./productlist.php?cid=<?= $rowCate["category_id"] ?>"
                                        class="<?= $cid == $rowCate["category_id"] ? "active" : "" ?>">
                                        <?= $rowCate["category_name"] ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>圖片</th>
                                            <th>名稱</th>
                                            <th>商品描述</th>
                                            <th>風格</th>
                                            <th>顏色</th>
                                            <th>分類</th>
                                            <th>數量</th>
                                            <th>價格</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rows as $index => $row): ?>
                                            <?php
                                            $product_id = $row['id'];
                                            $stmtImg = $pdo->prepare("SELECT img FROM product_img WHERE product_id = ? LIMIT 1");
                                            $stmtImg->execute([$product_id]);
                                            $imgFileName = $stmtImg->fetchColumn();
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php if ($imgFileName): ?>
                                                        <?php if (strpos($imgFileName, 'http') === 0): ?>
                                                            <img src="<?php echo htmlspecialchars($imgFileName); ?>" alt="商品圖片"
                                                                style="max-width:100px; height:auto">
                                                        <?php else: ?>
                                                            <img src="./uploads/<?php echo htmlspecialchars($imgFileName); ?>"
                                                                alt="商品圖片" style="max-width:100px; height:auto">
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span>無圖片</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $row["name"] ?></td>
                                                <td class="description"><?= $row["description"] ?></td>
                                                <td class="style"><?= $row["style"] ?></td>
                                                <td class="color"><?= $row["color"] ?></td>
                                                <td><?= $row["category_name"] ?></td>
                                                <td class="quantity"><?= $row["quantity"] ?></td>
                                                <td><?= '$' . $row["price"] ?></td>
                                                <td>
                                                    <div class="btn-group-horizontal ">
                                                        <button class="btn btn-sm btn-primary btn-del"
                                                            data-id="<?= $row["id"] ?>"><i
                                                                class="fa-solid fa-trash"></i></button>
                                                        <a href="./Update.php?id=<?= $row["id"] ?>"
                                                            class="btn btn-sm btn-primary"><i
                                                                class="fa-solid fa-pen"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="text-right mt-0" style="min-width: 150px;"> 共 <?= $msgLength ?> 筆資料</div>

                            <div class="d-flex justify-content-between align-items-center ">
                                <nav aria-label="Page navigation" class="flex-grow-1">
                                    <ul class="pagination justify-content-center mb-0">
                                        <?php
                                        $maxVisiblePages = 5; // 最多顯示的分頁按鈕數量
                                        $startPage = max(1, $page - floor($maxVisiblePages / 2));
                                        $endPage = min($totalPage, $startPage + $maxVisiblePages - 1);

                                        // 上一頁按鈕
                                        if ($page > 1): ?>
                                            <li class="page-item">
                                                <?php
                                                $prevLink = "./productlist.php?page=" . ($page - 1);
                                                if ($cid > 0)
                                                    $prevLink .= "&cid={$cid}";
                                                if ($searchType != "")
                                                    $prevLink .= "&search={$search}&qType={$searchType}";
                                                if ($date1 != "")
                                                    $prevLink .= "&date1={$date1}";
                                                if ($date2 != "")
                                                    $prevLink .= "&date2={$date2}";
                                                ?>
                                                <a class="page-link" href="<?= $prevLink ?>">上一頁</a>
                                            </li>
                                        <?php endif; ?>

                                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                            <li class="page-item <?= $page == $i ? "active" : "" ?>">
                                                <?php
                                                $link = "./productlist.php?page={$i}";
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

                                        <!-- 下一頁按鈕 -->
                                        <?php if ($page < $totalPage): ?>
                                            <li class="page-item">
                                                <?php
                                                $nextLink = "./productlist.php?page=" . ($page + 1);
                                                if ($cid > 0)
                                                    $nextLink .= "&cid={$cid}";
                                                if ($searchType != "")
                                                    $nextLink .= "&search={$search}&qType={$searchType}";
                                                if ($date1 != "")
                                                    $nextLink .= "&date1={$date1}";
                                                if ($date2 != "")
                                                    $nextLink .= "&date2={$date2}";
                                                ?>
                                                <a class="page-link" href="<?= $nextLink ?>">下一頁</a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>

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

    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({
                "paging": false,  // 禁用 DataTables 的分页
                "searching": false,  // 禁用搜索
                "info": false,  // 禁用信息显示
                "ordering": true  // 保持排序功能
            });
        });
    </script>

    <script>
        const btnDels = document.querySelectorAll(".btn-del");
        btnDels.forEach(function (btn) {
            btn.addEventListener("click", delConfirm);
        });

        function delConfirm(event) {
            const btn = event.target;
            if (window.confirm("確定刪除?")) {
                window.location.href = `./doDelete.php?id=${btn.dataset.id}`;
            }
        }
    </script>
</body>

</html>