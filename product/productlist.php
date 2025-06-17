<?php
// session_start();
// if(!isset($_SESSION["user"])){
//   header("location: /users/login.php");
//   exit;
// }

require_once "../connect.php";
$current_page = basename($_SERVER['PHP_SELF']);

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
        WHERE $cateSQL $searchSQL $dateSQL $deleteSQL  ORDER BY p.create_at DESC 
        LIMIT $perPage OFFSET $pageStart";;

$sqlAll = "SELECT p.*, c.category_name 
           FROM `products` p
           LEFT JOIN `products_category` c ON p.category_id = c.category_id
           WHERE $cateSQL $searchSQL $dateSQL $deleteSQL
           ORDER BY p.create_at DESC";;

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
// 計算目前顯示的起始和結束筆數
$startItem = $msgLength > 0 ? min(($page - 1) * $perPage + 1, $msgLength) : 0;
$endItem = min($page * $perPage, $msgLength);
// 改結束
function buildPageLink($targetPage, $cid = 0, $search = "", $searchType = "", $date1 = "", $date2 = "")
{
    $link = "./productlist.php?page=" . $targetPage;
    if ($cid > 0)
        $link .= "&cid={$cid}";
    if (!empty($searchType))
        $link .= "&search={$search}&qType={$searchType}";
    if (!empty($date1))
        $link .= "&date1={$date1}";
    if (!empty($date2))
        $link .= "&date2={$date2}";
    return $link;
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
    <link href="../css/mycss.css" rel="stylesheet">
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
            width: 70%;
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
    <div id="wrapper" class="d-flex min-vh-100">
        <div id="sidebar" class="bg-light" style="min-width:220px; min-height:100vh; height:100%;">
            <?php include "../index/sideBar.php"; ?>
        </div>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <?php include "../index/topBar.html"; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">


                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-white">商品資訊</h6>
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
                                            <option value="style" <?= $searchType == "style" ? "selected" : "" ?>>風格
                                            </option>
                                            <option value="color" <?= $searchType == "color" ? "selected" : "" ?>>顏色
                                            </option>
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
                                <table class="table table-bordered" width="100%" cellspacing="0">
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
                                                        <button id="btnDel" class="btn btn-sm btn-primary"
                                                            data-id="<?= $row["id"] ?>">
                                                            <i class="fa-solid fa-trash"></i></button>
                                                        <a href="./Update.php?id=<?= $row["id"] ?>&page=<?= $page ?>"
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
                            <!-- 改-->
                            <div class="mb-2" style="text-align: right;">
                                第 <?= $startItem ?> 筆到第 <?= $endItem ?> 筆，共 <?= $msgLength ?> 筆資料
                            </div>
                            <!-- 改結束-->
                            <div class="d-flex justify-content-between align-items-center ">
                                <nav aria-label="Page navigation" class="flex-grow-1">
                                    <ul class="pagination justify-content-center mb-0">
                                        <?php if ($page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?= buildPageLink(1, $cid, $search, $searchType, $date1, $date2) ?>">首頁</a>
                                            </li>
                                        <?php endif; ?>

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
                                                <a class="page-link" href="<?= $prevLink ?>">
                                                    <i class="fa-solid fa-arrow-left"></i>
                                                </a>
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
                                                <a class="page-link" href="<?= $link ?>"><?= $i ?>
                                            </a>
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
                                                <a class="page-link" href="<?= $nextLink ?>"><i class="fa-solid fa-arrow-right"></i></a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ($page < $totalPage): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?= buildPageLink($totalPage, $cid, $search, $searchType, $date1, $date2) ?>">末頁</a>
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
                "paging": false,
                "searching": false,
                "info": false,
                "ordering": true,
                "columnDefs": [
                    { "orderable": false, "targets": [0, 1, 2, 3, 4, 5, 8] } // 禁用這些欄位排序
                ]
            });
        });
    </script>

    <script>
        const btnDels = document.querySelectorAll("#btnDel");
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