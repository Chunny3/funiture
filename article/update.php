<?php
require_once "../connect.php";
$current_page = basename($_SERVER['PHP_SELF']);

$articleId = $_GET["id"];

$sql = "SELECT `article`.* FROM `article` WHERE `article`.`id` = ?";

$sqlCategory = "SELECT * FROM `article_category`";

$sqlCate = "SELECT 
 `article`.`article_category_id` AS category_id,
GROUP_CONCAT(`article_category`.`name`) AS `cateName`
FROM `article`
LEFT JOIN `article_category`
ON `article`.`article_category_id` = `article_category`.`id`
WHERE `article`.`id` = ?
GROUP BY `article`.`id`";

$sqlTag = "SELECT 
`tag`.`name` AS `tagName`
FROM `article`
LEFT JOIN `article_tag`
ON `article`.`id` = `article_tag`.`article_id`
LEFT JOIN `tag`
ON `article_tag`.`tag_id` = `tag`.`id`
WHERE `article`.`id` = ?";

$errorMsg = "";


try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$articleId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmtCateAll = $pdo->prepare($sqlCategory);
    $stmtCateAll->execute();
    $rowsCateAll = $stmtCateAll->fetchAll(PDO::FETCH_ASSOC);

    $stmtCate = $pdo->prepare($sqlCate);
    $stmtCate->execute([$articleId]);
    $rowCate = $stmtCate->fetch(PDO::FETCH_ASSOC);

    $stmtTag = $pdo->prepare($sqlTag);
    $stmtTag->execute([$articleId]);
    $rowsTag = $stmtTag->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // echo "錯誤: {{$e->getMessage()}}";
    // exit;
    $errorMsg = $e->getMessage();
}

$tagsArray = [];
foreach ($rowsTag as $rowTag) {
    if ($rowTag["tagName"] !== null && $rowTag["tagName"] !== "") {
        $tagsArray[] = $rowTag["tagName"];
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>修改文章表單</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" referrerpolicy="no-referrer" />

    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/article.css">
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

                <?php include "../index/topBar.php"; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <input type="hidden" name="id" value="<?= $row["id"] ?>">


                    <!-- Basic Card Example -->
                    <div class="card shadow max-width1200 mx-auto">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-white">修改文章表單</h6>
                        </div>
                        <form action="./doAdd.php" method="post" enctype="multipart/form-data">
                            <div class="container mt-3 max-width800">
                                <div class="input-group mb-2">
                                    <div class="input-group-text">標題</div>
                                    <input required type="text" name="title" class="form-control"
                                        value="<?= $row["title"] ?>">
                                </div>

                                <!-- ckeditor -->
                                <div id="editor">
                                    <?= $row["content"] ?>
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
                                        <?php foreach ($rowsCateAll as $rowCateAll): ?>
                                            <option value="<?= $rowCateAll["id"] ?>" <?= $rowCateAll["id"] == $row["article_category_id"] ? "selected" : "" ?>>
                                                <?= $rowCateAll["name"] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex mt-1 mb-3">
                                <div class="btn btn-info ms-auto btn-send ">送出</div>
                                <a href="./articleList.php" class="btn btn-info ms-2 me-5">取消</a>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
    <script>
        const tags = new Set(<?= json_encode($tagsArray) ?>);

    </script>
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    
    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="./js/articleUpdate.js"></script>

</body>

</html>