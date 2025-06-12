CREATE DATABASE `furniture`;

select database();
USE `furniture`;

DROP DATABASE `furniture`;

DROP TABLE `article`;
DROP TABLE `article_img`;

SET FOREIGN_KEY_CHECKS = 0;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE article (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100),
    content longtext,
    article_category_id INT NOT NULL,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    upload_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_valid TINYINT NOT NULL DEFAULT 1,
    FOREIGN KEY (`article_category_id`) REFERENCES article_category (`id`)
);

SELECT * FROM article;
DESC article;

SELECT * FROM article_img;
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

-- CREATE TABLE `article_img` (
--     id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
--     img VARCHAR(500)
-- );

CREATE TABLE `tag` (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(300),
    create_at DATETIME DEFAULT CURRENT_TIMESTAMP
)

CREATE TABLE `article_tag` (
    article_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (article_id, tag_id),
    FOREIGN KEY (`article_id`) REFERENCES article (`id`),
    FOREIGN KEY (`tag_id`) REFERENCES tag (`id`)
);

SELECT * FROM `tag`;

CREATE TABLE `levels` (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(20)
);

DROP TABLE `article_tag`;

SELECT * FROM article_tag;

INSERT INTO `article_category`(`name`) VALUES
('風格'),
('有趣小知識'),
('收納小方法'),
('美感生活誌');

SELECT 
`article`.*,
GROUP_CONCAT(`article_category`.`name`) AS `cateName`,
GROUP_CONCAT(`tag`.`name`) AS `tagName`
FROM `article`
LEFT JOIN `article_category`
ON `article`.`article_category_id` = `article_category`.`id`
LEFT JOIN `article_tag`
ON `article`.`id` = `article_tag`.`article_id`
LEFT JOIN `tag`
ON `article_tag`.`tag_id` = `tag`.`id`
WHERE `article`.`id` = 12
GROUP BY `article`.`id`;

SELECT 
GROUP_CONCAT(`article_category`.`name`) AS `cateName`
FROM `article`
LEFT JOIN `article_category`
ON `article`.`article_category_id` = `article_category`.`id`
WHERE `article`.`id` = 12
GROUP BY `article`.`id`;

SELECT 
GROUP_CONCAT(`tag`.`name` SEPARATOR ',') AS `tagName`
FROM `article`
LEFT JOIN `article_tag`
ON `article`.`id` = `article_tag`.`article_id`
LEFT JOIN `tag`
ON `article_tag`.`tag_id` = `tag`.`id`
WHERE `article`.`id` = 12
GROUP BY `article`.`id`;

SELECT `article`.* FROM `article` WHERE `article`.`id` = 12;

SELECT 
GROUP_CONCAT(`tag`.`name`) AS `tagName`
FROM `article`
LEFT JOIN `article_tag`
ON `article`.`id` = `article_tag`.`article_id`
LEFT JOIN `tag`
ON `article_tag`.`tag_id` = `tag`.`id`
WHERE `article`.`id` = 12
GROUP BY `article`.`id`;

SELECT 
 `article`.`article_category_id` AS category_id,
GROUP_CONCAT(`article_category`.`name`) AS `cateName`
FROM `article`
LEFT JOIN `article_category`
ON `article`.`article_category_id` = `article_category`.`id`
WHERE `article`.`id` = 12
GROUP BY `article`.`id`;

SELECT 
 `article`.`article_category_id` AS category_id,
GROUP_CONCAT(`article_category`.`name`) AS `cateName`
FROM `article`
LEFT JOIN `article_category`
ON `article`.`article_category_id` = `article_category`.`id`
WHERE `article`.`id` = 12
GROUP BY `article`.`id`;