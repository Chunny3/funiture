<?php
require_once "../connect.php";
require_once "../Utilities.php";


?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Article List 頁面</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
        <style>
            .article{
                display: flex;
                margin-bottom: 2px;
                align-items: center;
                text-align: center;
                /* border: 1px solid white; */
            }
           
            .id{
                width: 30px;
            }
            .title{
                flex: 1;
            }
            .category{
                width: 100px;
            }
            .tag{
                width: 200px;
            }
            .user{
                width: 100px;
            }
            .publishedDate{
                width: 200px;
            }
            .control{
                width: 200px;
            }
        </style>
    </head>
    <body>
        <div class="container mt-3">
            <h1>文章列表</h1>

            <div >
                <div class="article text-bg-dark ps-1">
                    <div class="article id">#</div>
                    <div class="article title">標題名稱</div>
                    <div class="article category">分類</div>
                    <div class="article tag">標籤</div>
                    <div class="article user">作者</div>
                    <div class="article publishedDate">發布時間</div>
                    <div class="article control">操作</div>

                </div>

                <div class="article">
                    <div class="article id">#</div>
                    <div class="article title">標題名稱</div>
                    <div class="article category">分類</div>
                    <div class="article tag">標籤</div>
                    <div class="article user">作者</div>
                    <div class="article publishedDate">發布時間</div>
                    <div class="article control">操作</div>

                </div>
                
            </div>
        </div>
       
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    </body>
</html>