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
    </style>
</head>

<body>
    <div class="container">
        <h1>CKEditor 5 + php upload 範例</h1>
        <div class="input-group mb-1">
            <div class="input-group-text">標題</div>
            <input type="text" name="title" class="form-control">
        </div>
        <div id="editor">
            <p>這裡是內容</p>
        </div>
        <div class="d-flex mt-1">
            <div class="btn btn-sm btn-primary ms-auto btn-send">送出</div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
    <script>
        let editorInstance;
        const btnSend = document.querySelector(".btn-send");
        const saveURL = "./doAdd.php";
        const inputTitle = document.querySelector("[name=title]")

        btnSend.addEventListener("click", e => {
            const formData = new FormData();
            formData.append("title", inputTitle.value);
            formData.append("content", editorInstance.getData());

            fetch(saveURL, {
                method: "POST",
                body: formData
            }).then(res => res.json()).then(result => {
                console.log(result);
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