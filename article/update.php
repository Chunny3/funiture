<?php
require_once "../connect.php";


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
foreach($rowsTag as $rowTag){
    if($rowTag["tagName"] !== null && $rowTag["tagName"] !== ""){
        $tagsArray[] = $rowTag["tagName"];
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>新增文章表單</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>

    <style>
        .ck-editor__editable_inline {
            min-height: 400px !important;
            height: 400px !important;
        }

        .f-title {
            border-left: 10px solid #a0a599;

        }

        .tag {
            margin-right: 6px;
            background-color: #f6e8e8;
            color: #000;
            border-radius: 4px;
            padding: 1px;
            margin-bottom: 4px;
            cursor: pointer;
            transition: background-color 0.2s;

            &:hover {
                background-color: rgb(235, 96, 96);
                color: #fff;
            }

            &::before {
                content: "#";
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="f-title">
            <h1 class="ms-2 mt-2">修改文章</h1>
        </div>

        <form action="./doAdd.php" method="post" enctype="multipart/form-data">
            <div class="input-group mb-1">
                <div class="input-group-text">標題</div>
                <input type="text" name="title" class="form-control" value="<?= $row["title"] ?>">
            </div>
            <div id="editor">
                <?= $row["content"] ?>
            </div>
            <div class="input-group mb-1 mt-2">
                <span class="input-group-text">標籤</span>
                <div class="form-control bg-white input-group-text tag-list text-start text-wrap flex-wrap"></div>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text">標籤輸入</span>
                <input type="text" name="tag" list="tag-options" class="form-control"
                    placeholder="不需要#，輸入後按 ctrl + Enter 結束一組的輸入">
                <datalist id="tag-options"></datalist>
                <input type="hidden" name="tags">
            </div>
            <div class="input-group mt-1 mb-2">
                <span class="input-group-text">分類</span>
                <select name="category" class="form-select">

                    <?php foreach ($rowsCateAll as $rowCateAll): ?>
                        <option value="<?= $rowCateAll["id"] ?>" <?= $rowCateAll["id"] == $rowCate["category_id"] ? "selected" : "" ?> >
                            <?= $rowCateAll["name"] ?></option>
                    <?php endforeach; ?>

                </select>
            </div>
            <input type="hidden" name="id" value="<?= $articleId ?>">
            <div class="d-flex mt-1">
                <div class="btn btn-primary ms-auto btn-send">送出</div>
                <a href="./index.php" class="btn btn-warning ms-1">取消</a>
            </div>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
    <script>
        let editorInstance;
        const btnSend = document.querySelector(".btn-send");
        const saveURL = "./doUpdate.php";
        const inputTitle = document.querySelector("[name=title]")

        const inputId = document.querySelector("[name=id]");
        const form = document.querySelector("form");
        const tagList = document.querySelector(".tag-list");
        const tagInput = document.querySelector("input[name=tag]");
        const tagsInput = document.querySelector("input[name=tags]");
        const datalist = document.querySelector("datalist");
        const tags = new Set(<?= json_encode($tagsArray) ?>);
        tags.forEach(tag=>createTag(tag));
        const categoryInput = document.querySelector("select[name='category']")


        tagInput.addEventListener('keydown', async (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
            const keyword = e.target.value.trim();
            if (!keyword) return; // 避免空字串查詢
            try {
                const res = await fetch(`./tags.php?keyword=${encodeURIComponent(keyword)}`);
                if (!res.ok) throw new Error("伺服器錯誤");
                const contentType = res.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    const text = await res.text();
                    console.error("tags.php 回傳非 JSON：", text);
                    return;
                }
                const suggestions = await res.json();
                datalist.innerHTML = "";
                suggestions.forEach(tag => {
                    const option = document.createElement("option");
                    option.textContent = tag;
                    datalist.appendChild(option);
                });
            } catch (err) {
                console.error("標籤自動完成發生錯誤：", err);
            }

            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault()
                const tag = tagInput.value.trim().replace(/^#/, '')
                if (tag && !tags.has(tag)) {
                    tags.add(tag)
                    createTag(tag);
                }
                tagInput.value = '';
                tagsInput.value = [...tags].join(',')
            }
        })


        function createTag(name) {
            const span = document.createElement('span');
            span.classList.add("tag");
            span.textContent = name;
            tagList.appendChild(span);
            span.addEventListener("click", () => {
                tags.delete(name);
                span.remove();
                tagsInput.value = [...tags].join(',');
            });


        }


        btnSend.addEventListener("click", e => {
            const formData = new FormData();
            formData.append("id", inputId.value);
            formData.append("title", inputTitle.value);
            formData.append("content", editorInstance.getData());
            formData.append("tag", tagsInput.value);
            formData.append("category", categoryInput.value);

            fetch(saveURL, {
                method: "POST",
                body: formData
            }).then(res => res.text())
                .then(text => {
                    console.log(text); // 觀察實際回傳
                    return JSON.parse(text);
                })
                .then(result => {
                    if (result.status === "success") {
                        window.location.href = "./index.php";
                    } else {
                        throw new Error(result.message);
                    }
                }).catch(error => {
                    console.log(error);
                    alert(error.message)
                });


        })

        class MyUploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }

            upload() {
                return this.loader.file
                    .then(file => new Promise((resolve, reject) => {
                        const data = new FormData();
                        data.append('upload', file);

                        fetch('upload.php', {
                            method: 'POST',
                            body: data
                        })
                            .then(response => response.json())
                            .then(data => {
                                resolve({
                                    default: data.url
                                });
                            })
                            .catch(err => {
                                reject(err);
                            });
                    }));
            }

            abort() {
                // If the user aborts the upload, this method is called.
            }
        }

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MyUploadAdapter(loader);
            };
        }

        ClassicEditor
            .create(document.querySelector('#editor'), {
                extraPlugins: [MyCustomUploadAdapterPlugin]

            })
            .then(editor => {
                editorInstance = editor;
            })
            .catch(error => {
                console.error(error);
            });

    </script>

</body>

</html>