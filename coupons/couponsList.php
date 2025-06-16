<?php
require_once "../connect.php";
$current_page = basename($_SERVER['PHP_SELF']);
$values = [];


//會員
$memberLevel = $_GET["member_level"] ?? "";
// 商品
$category = $_GET["category"] ?? "";

// 排序
// 預設 ASC
$order = $_GET['order'] ?? '';
$orderDir = $_GET['orderDir'] ?? 'ASC';


function getOrderQueryString($field, $order, $orderDir)
{
    $params = $_GET;
    $params['order'] = $field;
    $params['orderDir'] = ($order === $field && $orderDir === 'ASC') ? 'DESC' : 'ASC';
    return http_build_query($params);
}




// 搜尋
$search = $_GET["search"] ?? "";
$searchType = "";

if ($search == "") {
    $searchSQL = "";
} else {
    $searchSQL = "(`name` LIKE :search OR `code` LIKE :search) AND ";
    $values["search"] = "%$search%";
}

// 日期
$date1 = $_GET["date1"] ?? "";
$date2 = $_GET["date2"] ?? "";
$dateSQL = "";

if ($date1 != "" && $date2 != "") {
    $startDateTime = "{$date1} 00:00:00";
    $endDateTime = "{$date2} 23:59:59";
    $dateSQL = "(
        (`start_at` <= :endDateTime) AND
        (`end_at` >= :startDateTime)
    ) AND ";
    $values["startDateTime"] = $startDateTime;
    $values["endDateTime"] = $endDateTime;

} elseif ($date1 != "") {
    $startDateTime = "{$date1} 00:00:00";
    $dateSQL = "(`end_at` >= :startDateTime) AND ";
    $values["startDateTime"] = $startDateTime;

} elseif ($date2 != "") {
    $endDateTime = "{$date2} 23:59:59";
    $dateSQL = "(`start_at` <= :endDateTime) AND";
    $values["endDateTime"] = $endDateTime;
}





$perPage = 10;
$page = intval($_GET["page"] ?? 1);
$pageStart = ($page - 1) * $perPage;


$sqlMember = "";
if ($memberLevel !== "") {
    $sqlMember = "`id` IN (SELECT coupon_id FROM coupon_levels WHERE level_id = :memberLevel) AND ";
    $values["memberLevel"] = $memberLevel;
}

$sqlCategory = "";
if ($category !== "") {
    $sqlCategory = "`id` IN (SELECT coupon_id FROM coupon_categories WHERE category_id = :category) AND ";
    $values["category"] = $category;
}

$mapSelectedCategories = [];
$mapSelectedLevels = [];


$sql = "SELECT * FROM `coupons` WHERE $sqlCategory $sqlMember $searchSQL $dateSQL `is_valid` = 1";
// 總頁數
$sqlAll = "SELECT * FROM `coupons` WHERE $sqlCategory $sqlMember $searchSQL $dateSQL `is_valid` = 1";
$sqlCate = "SELECT * FROM `products_category`";
$sqlLv = "SELECT * FROM `member_levels`";

$sqlCateSelect = "SELECT `coupon_id`, `category_id` FROM `coupon_categories` WHERE `coupon_id` = ?";
$sqlLvSelect = "SELECT `coupon_id`, `level_id` FROM `coupon_levels` WHERE `coupon_id` = ?";

$allowedColumns = ['name', 'min_discount', 'max_amount', 'discount'];

if (!empty($order) && !empty($orderDir)) {
    if ($order === 'date' && in_array($orderDir, ['ASC', 'DESC'])) {
        $sql .= " ORDER BY 
            CASE WHEN valid_days IS NULL THEN 0 ELSE 1 END ASC,
            CASE 
                WHEN valid_days IS NULL THEN UNIX_TIMESTAMP(`start_at`) 
                ELSE valid_days
            END $orderDir";
    } elseif (in_array($order, $allowedColumns) && in_array($orderDir, ['ASC', 'DESC'])) {
        if ($order === 'max_amount') {
            $sql .= " ORDER BY COALESCE(`$order`, 999999) $orderDir";
        } else {
            $sql .= " ORDER BY `$order` $orderDir";
        }
    }
}



$sql .= " LIMIT $perPage OFFSET $pageStart";


try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
    $rows = $stmt->fetchAll();

    $stmtAll = $pdo->prepare($sqlAll);
    $stmtAll->execute($values);
    $couponLength = $stmtAll->rowCount();

    $stmtCate = $pdo->prepare($sqlCate);
    $stmtCate->execute();
    $rowsCate = $stmtCate->fetchAll(PDO::FETCH_ASSOC);

    $stmtLv = $pdo->prepare($sqlLv);
    $stmtLv->execute();
    $rowsLv = $stmtLv->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $stmtCateSelect = $pdo->prepare($sqlCateSelect);
        $stmtCateSelect->execute([$row['id']]);
        $mapSelectedCategories[$row['id']] = $stmtCateSelect->fetchAll(PDO::FETCH_COLUMN, 1);

        $stmtLvSelect = $pdo->prepare($sqlLvSelect);
        $stmtLvSelect->execute([$row['id']]);
        $mapSelectedLevels[$row['id']] = $stmtLvSelect->fetchAll(PDO::FETCH_COLUMN, 1);
    }
} catch (PDOException $e) {
    echo "錯誤: {{$e->getMessage()}}";
    exit;
}

$endNumber = min($pageStart + $perPage, $couponLength);
$totalPage = ceil($couponLength / $perPage);

// 商品名稱顯示
$categoryName = "全部商品類別";
if (isset($_GET["category"]) && $_GET["category"] !== "") {
    foreach ($rowsCate as $cate) {
        if ($cate["category_id"] == $_GET["category"]) {
            $categoryName = $cate["category_name"];
            break;
        }
    }
}

// 會員階級顯示
$lvName = "全部會員階級";
if (isset($_GET["member_level"]) && $_GET["member_level"] !== "") {
    foreach ($rowsLv as $lv) {
        if ($lv["id"] == $_GET["member_level"]) {
            $lvName = $lv["name"];
            break;
        }
    }
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

    <title>優惠券列表</title>

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

    <!-- 我的css -->
    <link rel="stylesheet" href="../css/mycss.css">

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

                    <!-- Page Heading -->
                    <div class="d-flex justify-content-between mb-1">
                        <h1 class="h3 text-gray-800 ml-3">優惠券管理系統</h1>
                        <div class="mr-3">
                            <a href="./insert.php" class="btn btn-info btn-sm">新增優惠券&nbsp;&nbsp;<i
                                    class="fa-solid fa-plus"></i></a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3 justify-content-between flex-wrap">
                        <div class="d-flex gap-12 ml-3">
                            <a href="./couponsList.php" class="btn btn-secondary btn-sm">清除篩選&nbsp;&nbsp;<i
                                    class="fa-solid fa-rotate"></i></a>
                            <div class="btn-group">
                                <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button"
                                    data-toggle="dropdown">
                                    <?= $lvName ?>
                                </button>
                                <ul class="dropdown-menu" id="member-level-dropdown">
                                    <li>
                                        <button type="button" class="dropdown-item" data-value="">全部會員</button>
                                    </li>
                                    <?php foreach ($rowsLv as $lv): ?>
                                        <li>
                                            <button type="button" class="dropdown-item"
                                                data-value="<?= $lv["id"] ?>"><?= $lv["name"] ?></button>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button"
                                    data-toggle="dropdown">
                                    <?= $categoryName ?>
                                </button>
                                <ul class="dropdown-menu" id="category-dropdown">
                                    <li><button type="button" class="dropdown-item" data-value="">全部類別</button></li>
                                    <?php foreach ($rowsCate as $cate): ?>
                                        <li><button type="button" class="dropdown-item"
                                                data-value="<?= $cate["category_id"] ?>"><?= $cate["category_name"] ?></button>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>

                        <div class="d-flex gap-8 mr-3 align-items-center">
                            <!-- 搜尋日期 -->
                            <span>有效日期範圍</span>
                            <input id="input-date1" name="date1" type="date"
                                class=" form-control input-date form-control-sm w-150"
                                value="<?= $_GET["date1"] ?? "" ?>">
                            <span> ~ </span>
                            <input id="input-date2" name="date2" type="date"
                                class="form-control input-date form-control-sm w-150"
                                value="<?= $_GET["date2"] ?? "" ?>" min="<?= $_GET["date1"] ?? "" ?>">

                            <!-- 搜尋 -->
                            <form class="input-group w-250 ml-4 d-flex">
                                <input id="search-input" name="search" type="text" class="form-control form-control-sm"
                                    placeholder="搜尋優惠券名稱或優惠碼">
                                <button id="search-btn" type="button" class="btn btn-sm btn-search"><i
                                        class="fa-solid fa-magnifying-glass"></i></button>
                            </form>
                        </div>

                    </div>

                    <!-- 優惠券列表 -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-white">優惠券列表</h6>
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
                                        <col class="col-8">
                                        <col class="col-9">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>index</th>

                                            <th>
                                                <a href="./couponsList.php?<?= getOrderQueryString('name', $order, $orderDir) ?>"
                                                    class="a-reset">
                                                    名稱&nbsp;&nbsp;
                                                    <?php if ($order !== 'name'): ?>
                                                        <i class="fa-solid fa-sort"></i>
                                                    <?php elseif ($orderDir === 'ASC'): ?>
                                                        <i class="fa-solid fa-sort-up"></i>
                                                    <?php else: ?>
                                                        <i class="fa-solid fa-sort-down"></i>
                                                    <?php endif; ?>
                                                </a>
                                            </th>


                                            <th>折扣碼</th>

                                            <th>
                                                <a href="./couponsList.php?<?= getOrderQueryString('min_discount', $order, $orderDir) ?>"
                                                    class="a-reset">
                                                    最低消費門檻&nbsp;&nbsp;
                                                    <?php if ($order !== 'min_discount'): ?>
                                                        <i class="fa-solid fa-sort"></i>
                                                    <?php elseif ($orderDir === 'ASC'): ?>
                                                        <i class="fa-solid fa-sort-up"></i>
                                                    <?php else: ?>
                                                        <i class="fa-solid fa-sort-down"></i>
                                                    <?php endif; ?>
                                                </a>
                                            </th>

                                            <th>
                                                <a href="./couponsList.php?<?= getOrderQueryString('discount', $order, $orderDir) ?>"
                                                    class="a-reset">
                                                    折扣&nbsp;&nbsp;
                                                    <?php if ($order !== 'discount'): ?>
                                                        <i class="fa-solid fa-sort"></i>
                                                    <?php elseif ($orderDir === 'ASC'): ?>
                                                        <i class="fa-solid fa-sort-up"></i>
                                                    <?php else: ?>
                                                        <i class="fa-solid fa-sort-down"></i>
                                                    <?php endif; ?>
                                                </a>
                                            </th>

                                            <th>
                                                <a href="./couponsList.php?<?= getOrderQueryString('max_amount', $order, $orderDir) ?>"
                                                    class="a-reset">
                                                    數量&nbsp;&nbsp;
                                                    <?php if ($order !== 'max_amount'): ?>
                                                        <i class="fa-solid fa-sort"></i>
                                                    <?php elseif ($orderDir === 'ASC'): ?>
                                                        <i class="fa-solid fa-sort-up"></i>
                                                    <?php else: ?>
                                                        <i class="fa-solid fa-sort-down"></i>
                                                    <?php endif; ?>
                                                </a>
                                            </th>

                                            <th>狀態</th>
                                            <th>
                                                <a href="./couponsList.php?<?= getOrderQueryString('date', $order, $orderDir) ?>"
                                                    class="a-reset">
                                                    有效期限&nbsp;&nbsp;
                                                    <?php if ($order !== 'date'): ?>
                                                        <i class="fa-solid fa-sort"></i>
                                                    <?php elseif ($orderDir === 'ASC'): ?>
                                                        <i class="fa-solid fa-sort-up"></i>
                                                    <?php else: ?>
                                                        <i class="fa-solid fa-sort-down"></i>
                                                    <?php endif; ?>
                                                </a>
                                            </th>
                                            <!-- <th>有效期限&nbsp;&nbsp;<i class="fa-solid fa-sort"></i></th> -->
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rows as $index => $row): ?>
                                            <tr class="mb-1">
                                                <td><?= $index + 1 + $pageStart ?></td>
                                                <td><?= $row["name"] ?></td>
                                                <td><?= $row["code"] ?></td>
                                                <td><?= "$ " . $row["min_discount"] ?></td>
                                                <td>
                                                    <?= $row["discount_type"] == 1 ? "$ " . (int) $row['discount'] : ($row['discount'] * 100) . " %"; ?>
                                                </td>
                                                <td>
                                                    <?= $row["max_amount"] === null ? "∞" : $row['max_amount']; ?>
                                                </td>
                                                <td><?php
                                                if (!empty($row["end_at"]) && $row["end_at"] < date("Y-m-d H:i:s")) {
                                                    echo "<span class='date-status date-expired'>過期</span>";
                                                } else if (!empty($row["start_at"]) && $row["start_at"] > date("Y-m-d H:i:s")) {
                                                    echo "<span class='date-status date-pending'>未生效</span>";
                                                } else {
                                                    echo "<span class='date-status date-active'>有效</span>";
                                                } ?></td>
                                                <td>
                                                    <?= $row["valid_days"] === null ? "{$row['start_at']} ~ {$row['end_at']}" : "領取後 {$row['valid_days']} 天內有效"; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn-view" data-toggle="modal"
                                                        data-target="#viewModal123<?= $row['id'] ?>">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>
                                                    <a class="a-edit" href="./update.php?id=<?= $row["id"] ?>"><i
                                                            class="fa-solid fa-pencil"></i></a>
                                                    <button class="btn-del" data-id="<?= $row["id"] ?>"><i
                                                            class="fa-solid fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex mt-3 mb-2 justify-content-between align-items-center">
                                <div>顯示第 <?= $pageStart + 1 ?> 到第 <?= $endNumber ?> 筆，共 <?= $couponLength ?> 筆資料</div>
                                <ul class="pagination pagination-sm justify-content-center mb-1">
                                    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                                        <li class="page-item <?= $page == ($i) ? "active" : "" ?>">
                                            <?php
                                            $link = "./couponsList.php?page={$i}";
                                            if ($search != "")
                                                $link .= "&search={$search}";
                                            if ($date1 != "")
                                                $link .= "&date1={$date1}";
                                            if ($date2 != "")
                                                $link .= "&date2={$date2}";
                                            if ($category != "")
                                                $link .= "&category={$category}";
                                            if ($memberLevel != "")
                                                $link .= "&member_level={$memberLevel}";
                                            if ($order != "")
                                                $link .= "&order={$order}&orderDir={$orderDir}";
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


                <!-- Modal 結構 -->
                <?php foreach ($rows as $index => $row): ?>
                    <?php
                    $selectedCategories = $mapSelectedCategories[$row['id']] ?? [];
                    $selectedLevels = $mapSelectedLevels[$row['id']] ?? [];
                    ?>
                    <div class="modal fade" id="viewModal123<?= $row['id'] ?>" tabindex="-1" role="dialog">
                        <div class="modal-dialog  modal-dialog-centered custom-modal-dialog" role="document">
                            <div class="modal-content custom-coupon-bg">


                                <div class="modal-body d-flex justify-content-end align-items-center">
                                    <div class="coupon-text-area">
                                        <div class="modal-header mb-3">
                                            <h5 class="modal-title" id="viewModalLabel">優惠券詳情</h5>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                優惠券名稱：<?= $row["name"] ?>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                折扣碼：<?= $row["code"] ?>
                                            </div>
                                            <div class="col">
                                                折扣內容：<?= $row["discount_type"] == 1 ? "$ " . (int) $row['discount'] : ($row['discount'] * 100) . " %"; ?>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col">
                                                最低消費金額：<?= "$ " . $row["min_discount"] ?>
                                            </div>
                                            <div class="col">
                                                發行數量：<?= $row["max_amount"] === null ? "∞" : $row['max_amount']; ?>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col">
                                                會員限制：
                                                <?php foreach ($rowsLv as $rowLv): ?>
                                                    <span
                                                        class="badge badge-green"><?= in_array($rowLv["id"], $selectedLevels) ? $rowLv['name'] : "" ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col">
                                                適用商品類別：
                                                <?php foreach ($rowsCate as $rowCate): ?>
                                                    <span
                                                        class="badge badge-blue"><?= in_array($rowCate["category_id"], $selectedCategories) ? $rowCate['category_name'] : "" ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col">
                                                有效期限：<?= $row["valid_days"] === null ? "{$row['start_at']} ~ {$row['end_at']}" : "領取後 {$row['valid_days']} 天內有效"; ?>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                建立時間：<?= $row["created_at"] ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

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




    <!-- Bootstrap core JavaScript-->
    <script src="../svendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/datatables-demo.js"></script>


    <!-- 我的JS -->
    <script src="./js/coupon.js"></script>
    <script>

    </script>

</body>

</html>