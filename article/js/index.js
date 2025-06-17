const sortBtn = document.querySelector('#sortBtn');
const sortIcon = document.querySelector('#sortIcon');
const tbody = document.querySelector('tbody');
let sortState = 0; // 0: 無, 1: 升冪, 2: 降冪
const btnSearch = document.querySelector(".btn-search");
const inputDate1 = document.querySelector("input[name=start_date]");
const inputDate2 = document.querySelector("input[name=end_date]");
const inputText = document.querySelector("input[name=search]");

btnSearch.addEventListener("click", function () {
    const queryType = document.querySelector("#searchType").value;
    const date1 = inputDate1.value;
    const date2 = inputDate2.value;
    const keyword = inputText.value.trim();

 const sUrl = new URL("../article/articleList.php", window.location.origin);

// 判斷是否有輸入關鍵字
if (keyword !== "") {
    sUrl.searchParams.set("searchType", queryType); // 只有關鍵字搜尋才需要設定 searchType
    sUrl.searchParams.set("search", keyword);
}

if (date1) sUrl.searchParams.set("start_date", date1);
if (date2) sUrl.searchParams.set("end_date", date2);

// 跳轉網址
window.location.href = sUrl.toString();


});
// document.addEventListener('DOMContentLoaded', () => {
//     document.querySelectorAll("#category-dropdown .dropdown-item").forEach(item => {
//         item.addEventListener("click", function () {
//             const type = this.getAttribute("data-value");
//             document.getElementById("searchType").value = type;
 
//             // 修改按鈕文字顯示
//             this.closest('.btn-group').querySelector('.dropdown-toggle').textContent = this.textContent;
//         });
//     });

// })
document.querySelector('thead').addEventListener('click', (e) => {
    const btn = e.target.closest('.sort-btn');
    if (!btn) return;

    const sortKey = btn.dataset.sort;
    const url = new URL(window.location.href);
    const currentSortBy = url.searchParams.get("sortBy");
    const currentOrder = url.searchParams.get("order") || "none";

    let newOrder;

    // 🔁 依目前狀態決定下一個狀態
    if (currentSortBy === sortKey) {
        if (currentOrder === "asc") {
            newOrder = "desc";
        } else if (currentOrder === "desc") {
            newOrder = null; // 清除排序（回預設）
        } else {
            newOrder = "asc";
        }
    } else {
        newOrder = "asc"; // 換新欄位就從 asc 開始
    }

    // 🧹 清除所有 icon 樣式
    document.querySelectorAll('.sort-icon').forEach(icon => {
        icon.classList.remove("fa-sort-up", "fa-sort-down");
        icon.classList.add("fa-sort");
    });

    //  更新當前 icon 樣式
    const icon = btn.querySelector('.sort-icon');
    if (icon) {
        icon.classList.remove("fa-sort", "fa-sort-up", "fa-sort-down");
        if (newOrder === "asc") {
            icon.classList.add("fa-sort-up");
        } else if (newOrder === "desc") {
            icon.classList.add("fa-sort-down");
        } else {
            icon.classList.add("fa-sort"); // 回預設
        }
    }

    //  更新 URL
    if (newOrder) {
        url.searchParams.set("sortBy", sortKey);
        url.searchParams.set("order", newOrder);
    } else {
        url.searchParams.delete("sortBy");
        url.searchParams.delete("order");
    }
    url.searchParams.set("page", 1); // 回第一頁

    window.location.href = url.toString();
    });


//  頁面載入時初始化圖示
const url = new URL(window.location.href);
const sortBy = url.searchParams.get("sortBy");
const order = url.searchParams.get("order");

if (sortBy && order) {
    const activeBtn = document.querySelector(`.sort-btn[data-sort="${sortBy}"]`);
    const icon = activeBtn?.querySelector('.sort-icon');
    if (icon) {
        icon.classList.remove("fa-sort");
        icon.classList.add(order === "asc" ? "fa-sort-up" : "fa-sort-down");
    }
}








document.getElementById('filterInput').addEventListener('input', function () {
    const keyword = this.value.trim();
    const rows = document.querySelectorAll('#myTable tbody tr');
    rows.forEach(row => {
        const text = row.textContent;
        row.style.display = text.includes(keyword) ? '' : 'none';
    });
});

