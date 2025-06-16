-- 建置新的資料庫 furniture
CREATE DATABASE `furniture`;

select database();

USE `furniture`;

SET FOREIGN_KEY_CHECKS = 0;

SET FOREIGN_KEY_CHECKS = 1;

-- 開始新增資料庫表單
-------------------------------------------建置 users 表單

CREATE TABLE `users`(
`id`  INT PRIMARY KEY AUTO_INCREMENT,
`name` VARCHAR(30),
`birthday` DATE DATE NOT NULL,
`email`  VARCHAR(30),
`password`  VARCHAR(100),
`phone`  VARCHAR(30),
`postcode`  VARCHAR(10),
`city`  VARCHAR(255),
`area`  VARCHAR(255),
`address`  VARCHAR(50),
`img` VARCHAR(30),
`level_id`  INT,
`created_at` DATE DEFAULT CURRENT_TIMESTAMP,
`is_valid` TINYINT(1) DEFAULT 1
);

CREATE TABLE `city_cates`(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100)
);

CREATE TABLE `area_cates`(
  id INT AUTO_INCREMENT PRIMARY KEY,
  city_cate_id INT NOT NULL,
  name VARCHAR(100)
);

-- !!!可能有問題!!!
CREATE TABLE `levels` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL,
  FOREIGN KEY (`level_id`) REFERENCES `levels`(`id`);
);

----------------------------------------建置 products 表單
CREATE TABLE `products` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `name` VARCHAR(100),
    `category_id` INT NOT NULL,
    `description` TEXT,
    `price` INT NOT NULL,
    `quantity` INT NOT NULL,
    `create_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `update_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `is_valid` TINYINT DEFAULT 1,
    `style` VARCHAR(255),
    `color` VARCHAR(50),
    FOREIGN KEY (`user_id`) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`category_id`) REFERENCES products_category (category_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `products_category` (
    `category_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `category_name` VARCHAR(30)
);

CREATE Table `product_img` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `img` VARCHAR(500),
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `style` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `des` VARCHAR(255),
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
)

---------------------------------------- 建置 coupons 表單
CREATE TABLE coupons (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  code VARCHAR(20) NOT NULL UNIQUE,
  discount_type TINYINT(1) NOT NULL,
  discount DECIMAL(7,2) NOT NULL,
  min_discount INT DEFAULT 0,
  max_amount INT DEFAULT NULL,
  start_at DATE DEFAULT NULL,
  end_at DATE DEFAULT NULL,
  valid_days INT DEFAULT NULL,
  is_valid TINYINT(1) DEFAULT 1 NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE coupon_categories (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  coupon_id INT NOT NULL,
  category_id INT NOT NULL,
  FOREIGN KEY (coupon_id) REFERENCES coupons(id),
  FOREIGN KEY (category_id) REFERENCES products_category(category_id)
);

CREATE TABLE coupon_levels (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  coupon_id INT NOT NULL,
  level_id INT NOT NULL,
  FOREIGN KEY (coupon_id) REFERENCES coupons(id),
  FOREIGN KEY (level_id) REFERENCES member_levels(id)
);

CREATE TABLE user_coupons (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  user_id INT NOT NULL,
  coupon_id INT NOT NULL,
  get_at DATETIME,
  used_at DATETIME,
  expire_at DATETIME,
  status TINYINT(1) DEFAULT 0,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (coupon_id) REFERENCES coupons(id)
);

-----------------------------------------建置 article 表單
CREATE TABLE article (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100),
    content longtext,
    article_category_id INT NOT NULL,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    upload_at DATETIME ,
    published_date DATETIME ,
    is_valid TINYINT NOT NULL DEFAULT 1,
    FOREIGN KEY (`article_category_id`) REFERENCES article_category (`id`)
);

CREATE TABLE `article_img` (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    img VARCHAR(500),
    FOREIGN KEY (`article_id`) REFERENCES article (`id`)
);

CREATE TABLE `article_category` (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20)
);

CREATE TABLE `tag` (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(300),
    create_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `article_tag` (
    article_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (article_id, tag_id),
    FOREIGN KEY (`article_id`) REFERENCES article (`id`),
    FOREIGN KEY (`tag_id`) REFERENCES tag (`id`)
);
