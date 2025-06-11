CREATE DATABASE `furniture`;

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

