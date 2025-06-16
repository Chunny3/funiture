<?php
// require_once("../connect.php");
require_once "./connect.php";

$sql = "CREATE TABLE `products`(
    `id` INT NOT NULL PRIMARY KEY,
    `name`  VARCHAR(100),
    `category_id` INT,
    `description`  TEXT,
    `price` INT NOT NULL,
    `quantity` INT NOT NULL,
    `created_at` DATETIME,
    `upload_at`  DATETIME,
    `is_valid` TINYINT,
    `product_img`  VARCHAR(500),
    `style`  VARCHAR(500),
    FOREIGN KEY (`category_id`) REFERENCES users(id) 
);";

try{
    $pdo->exec($sql);
}catch(PDOException $e){
    echo "建立資料表失敗<br>";
    echo $e->getMessage();
    exit;
}

echo "建立資料表成功";