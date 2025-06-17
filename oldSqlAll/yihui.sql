CREATE DATABASE `my_db2`;
USE `my_db2`;

SET FOREIGN_KEY_CHECKS = 0;
SET FOREIGN_KEY_CHECKS = 1;


DESC `coupons`;
DESC `coupon_categories`;
DESC `coupon_levels`;
DESC `user_coupons`;

SELECT * FROM `coupons`;
SELECT * FROM `coupon_categories`;
SELECT * FROM `coupon_levels`;


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




DROP TABLE `coupons`;
DROP TABLE `coupon_levels`;
DROP TABLE `coupon_categories`;


----------------------------------------------商品類別的SQL
CREATE TABLE `products_category`(
 `category_id`  INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
 `category_name`  VARCHAR(30)
);

INSERT INTO products_category (category_name) VALUES
('客廳'),
('餐廳/廚房'),
('臥室'),
('兒童房'),
('辦公空間'),
('收納用品');



DESC `products_category`;

SELECT * FROM `products_category`;
DROP TABLE `products_category`;

-------------------------------------------會員的SQL
CREATE TABLE `member_levels`(
    `id` INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `name` VARCHAR(20)
);

INSERT INTO `member_levels` (name) VALUES
('木芽會員'),
('原木會員'),
('森林會員');

SELECT * FROM `member_levels`;


