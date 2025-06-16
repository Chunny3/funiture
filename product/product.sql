CREATE DATABASE product;

USE `product`;

CREATE TABLE `users` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(30),
    `birthday` DATE NOT NULL,
    `email` VARCHAR(30),
    `password` VARCHAR(100),
    `phone` VARCHAR(30),
    `postcode` VARCHAR(10),
    `city` VARCHAR(255),
    `area` VARCHAR(255),
    `address` VARCHAR(50),
    `img` VARCHAR(30),
    `level_id` INT,
    `created_at` DATE DEFAULT CURRENT_TIMESTAMP,
    `is_valid` TINYINT(1) DEFAULT 1
);

CREATE TABLE `products_category` (
    `category_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `category_name` VARCHAR(30)
);

INSERT INTO
    products_category (category_name)
VALUES ('客廳'),
    ('餐廳/廚房'),
    ('臥室'),
    ('兒童房'),
    ('辦公空間'),
    ('收納用品');

SET foreign_key_checks = 0;

SET foreign_key_checks = 1;

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

INSERT INTO
    `products` (
        `id`,
        `user_id`,
        `name`,
        `category_id`,
        `description`,
        `price`,
        `quantity`,
        `create_at`,
        `update_at`,
        `is_valid`
    )
VALUES (
        1,
        1,
        '無印風化妝鏡',
        '3',
        '木
  框圓鏡搭配小抽屜，簡約又兼具機能性。',
        100,
        10,
        NULL,
        NULL,
        1
    ),
    (
        2,
        2,
        '北歐風多功能茶几',
        '1',
        '內藏抽屜與升降桌面，收納與實用性兼具。',
        200,
        20,
        NULL,
        NULL,
        1
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

INSERT INTO
    style (`product_id`, `des`)
VALUES (1, '北歐簡約風'),
    (2, '現代極簡風'),
    (3, '工業復古風'),
    (4, '日式禪園風'),
    (5, '美式鄉村風'),
    (6, '法式優雅風'),
    (7, '輕奢設計風'),
    (8, '無印自然風');

DROP TABLE `product_img`;

SELECT `products`.*, GROUP_CONCAT(
        `product_img`.`img` SEPARATOR ','
    ) AS `imgs`
FROM `products`
    LEFT JOIN `product_img` ON `products`.`id` = `product_img`.`product_id`
WHERE
    `products`.`id` = 130
GROUP BY
    `products`.`id`;

SELECT * FROM `products`;

SELECT * FROM `product_img`;

INSERT INTO
    `users` (
        `id`,
        `name`,
        `birthday`,
        `email`,
        `password`,
        `phone`,
        `postcode`,
        `city`,
        `area`,
        `address`,
        `img`,
        `level_id`,
        `created_at`,
        `is_valid`
    )
VALUES (
        1,
        'Admin',
        '1990-01-01',
        'admin@example.com',
        'password123',
        '123456789',
        '12345',
        'City',
        'Area',
        'Address',
        'default.jpg',
        1,
        NOW(),
        1
    );

         SELECT * FROM products WHERE is_valid = 0