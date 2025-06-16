<?php
session_start();
if(!isset($_SESSION["user"])){
  header("location: /users/login.php");
  exit;
}

require_once "./connect.php";

// $cid = isset($_GET["cid"]) ? $_GET["cid"] :0 :
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
    } elseif ($date1 == "" && $date2 != "") {
        $startDateTime = "{$date2} 00:00:00";
        $endDateTime = "{$date2} 23:59:59";
    } elseif ($date2 == "" && $date1 != "") {
        $startDateTime = "{$date1} 00:00:00";
        $endDateTime = "{$date1} 23:59:59";
    }
    $dateSQL = "(`createTime` BETWEEN :startDateTime AND :endDateTime) AND";
    $values["startDateTime"] = $startDateTime;
    $values["endDateTime"] = $endDateTime;
}

$perPage = 10;
$page = intval($_GET["page"] ?? 1);
$pageStart = ($page - 1) * $perPage;

// $sql = "SELECT * FROM `msgs` WHERE `is_valid` = 1";
$sql = "SELECT * FROM `msgs` WHERE $cateSQL $searchSQL  $dateSQL (`end_at` IS NULL OR `end_at` > NOW()) LIMIT $perPage OFFSET $pageStart";
$sqlAll = "SELECT * FROM `msgs` WHERE $cateSQL $searchSQL $dateSQL (`end_at` IS NULL OR `end_at` > NOW())";
$sqlCate = "SELECT * FROM `category` ";

// 使用 prepare 加 execute
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
    $rows = $stmt->fetchAll();

    $stmtCate = $pdo->prepare($sqlCate);
    $stmtCate->execute();
    $rowsCate = $stmtCate->fetchAll();

    $stmtAll = $pdo->prepare($sqlAll);
    $stmtAll->execute($values);
    $msgLength = $stmtAll->rowCount();
} catch (PDOException $e) {
    echo "錯誤: {{$e->getMessage()}}";
    exit;
}
$totalPage = ceil($msgLength / $perPage);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <style>
        .msg {
            display: flex;
        }

        .id {
            width: 30px;
        }

        .name {
            width: 100px;
        }

        .content {
            flex: 1;
        }

        .time {
            width: 100px;
        }

        .head{
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 2px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="container my-3">
        <h1>訊息列表</h1>
        <div class="d-flex">
            <img class="head" src="./uploads/<?=$_SESSION["user"]["img"]?>" alt="">
            <div><?=$_SESSION["user"]["name"]?></div>
            <a href="./users/doLogout.php" class="btn btn-primary btn-sm ms-3">登出</a>
        </div>
        <div class="my-2 d-lg-flex align-items-center">
            <div>目前共 <?= $msgLength ?> 筆資料</div>

            <div class="me-lg-1 mb-1 mb-lg-0 ms-auto">
                <div class="input-group input-group-sm">

                    <div class="input-group-text">
                        <input name="searchType" id="searchType3" type="radio" class="form-check-input"
                            value="createTime">
                        <label for="searchType3">日期</label>
                    </div>
                    <input name="date1" type="date" class="form-control">
                    <div class="input-group-text"> ~ </div>
                    <input name="date2" type="date" class="form-control">

                    <div class="input-group-text">
                        <input name="searchType" id="searchType1" type="radio" class="form-check-input" value="name"
                            checked>
                        <label for="searchType1" class="me-2">名字</label>
                        <input name="searchType" id="searchType2" type="radio" class="form-check-input" value="content">
                        <label for="searchType2">內文</label>
                    </div>
                    <input name="search" type="text" class="form-control form-control-sm" placeholder="搜尋">
                    <div class="btn btn-primary btn-sm btn-search">送出搜尋</div>
                </div>
            </div>

            <a href="./testForm03.php" class="btn btn-primary btn-sm">增加資料</a>
        </div>

        <div class="nav nav-tabs">
            <a class="nav-link <?= $cid == 0 ? "active" : "" ?>" href="./pageMsgsList.php">全部</a>
            <?php foreach ($rowsCate as $rowCate): ?>
                <a class="nav-link <?= $cid == $rowCate["id"] ? "active" : "" ?>"
                    href="./pageMsgsList.php?cid=<?= $rowCate["id"] ?>"><?= $rowCate["name"] ?></a>
            <?php endforeach; ?>
        </div>
        <div class="border border-top-0 p-2 pt-1 rounded-2 rounded-top-0">

            <ul class="pagination pagination-sm justify-content-center mb-1">
                <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                    <li class="page-item <?= $page == ($i) ? "active" : "" ?>">
                        <?php
                        $link = "./pageMsgsList.php?page={$i}";
                        if ($cid > 0)
                            $link .= "&cid={$cid}";
                        if ($searchType != "")
                            $link .= "&search={$search}&qType={$searchType}";
                        if($date1 != "") $link .= "&date1={$date1}";
                        if($date2 != "") $link .= "&date1={$date2}";
                        ?>
                        <a class="page-link" href="<?= $link ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>

            <div class="msg text-bg-dark">
                <div class="id ps-1">#</div>
                <div class="name">Name</div>
                <div class="content">content</div>
                <div class="time">control</div>
            </div>

            <?php foreach ($rows as $index => $row): ?>
                <div class="msg mb-1">
                    <div class="id"><?= $index + 1 ?></div>
                    <div class="name"><?= $row["name"] ?></div>
                    <div class="content"><?= $row["content"] ?></div>
                    <div class="time">
                        <!-- <a href="./doDelete01.php?id=<?= $row["id"] ?>" class="btn btn-danger btn-sm">刪除</a> -->
                        <button class="btn btn-danger btn-sm btn-del" data-id="<?= $row["id"] ?>">刪除</button>
                        <a href="./pageMsg.php?id=<?= $row["id"] ?>" class="btn btn-warning btn-sm">修改</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
    <script>
        const btnDels = document.querySelectorAll(".btn-del");
        const btnSearch = document.querySelector(".btn-search");
        const inputDate1 = document.querySelector("input[name=date1]");
        const inputDate2 = document.querySelector("input[name=date2]");
        const searchTypeAll = document.querySelectorAll("input[name=searchType]");
        const inputText = document.querySelector("input[name=search]");


        btnDels.forEach(function (btn) {
            btn.addEventListener("click", delConfirm);
        });

        btnSearch.addEventListener("click", function () {
            const queryType = document.querySelector("input[name=searchType]:checked").value;
            if (queryType == "createTime") {
                const date1 = inputDate1.value;
                const date2 = inputDate2.value;
                window.location.href = `./pageMsgsList.php?date1=${date1}&date2=${date2}&qType=${queryType}`;
            } else {
                const query = inputText.value;
                window.location.href = `./pageMsgsList.php?search=${query}&qType=${queryType}`;
            }
        });


        // const query = document.querySelector("input[name=search]").value;

        inputDate1.addEventListener("focus", function () {
            searchTypeAll[0].checked = true;
        });

        inputDate2.addEventListener("focus", function () {
            searchTypeAll[0].checked = true;
        });

        inputText.addEventListener("focus", function () {
            if (searchTypeAll[0].checked) {
                searchTypeAll[1].checked = true;
            }
        });

        function delConfirm(event) {
            const btn = event.target;
            if (window.confirm("確定要刪除嗎?")) {
                window.location.href = `./doDelete01.php?id=${btn.dataset.id}`;
            }
        }
    </script>
</body>

</html>