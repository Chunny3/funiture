let editorInstance;
const btnSend = document.querySelector(".btn-send");
const saveURL = "./doAdd.php";
const inputTitle = document.querySelector("[name=title]")

const form = document.querySelector("form");
// const contentDIV = document.querySelector(".content-div");
const tagList = document.querySelector(".tag-list");
const tagInput = document.querySelector("input[name=tag]");
const tagsInput = document.querySelector("input[name=tags]");
const datalist = document.querySelector("datalist");
const tags = new Set();
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
                window.location.href = "./articleList.php";
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