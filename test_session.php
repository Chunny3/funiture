<?php
session_start();

echo "<h2>Session 測試</h2>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>Session 狀態: " . session_status() . "</p>";

if(isset($_SESSION["user"])){
    echo "<p>✅ 使用者已登入</p>";
    echo "<p>使用者資料: " . print_r($_SESSION["user"], true) . "</p>";
} else {
    echo "<p>❌ 使用者未登入</p>";
}

echo "<p><a href='users/login.php'>前往登入頁面</a></p>";
echo "<p><a href='users/index.php'>前往會員列表</a></p>";
?> 