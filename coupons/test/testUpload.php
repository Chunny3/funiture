<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>檔案上傳</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-3">
        <form action="./doUpload01.php" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <span class="input-group-text">名稱</span>
                <input name="name" type="text" class="form-control" placeholder="發文者名稱">
            </div>
            <div class="input-group mt-1">
                <input class="form-control" type="file" name="myFile" accept=".png,.jpg,.jpeg">
            </div>
            <div class="mt-1 text-end">
                <button type="submit" class="btn btn-info">送出</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
</body>

</html>