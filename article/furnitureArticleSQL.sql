CREATE DATABASE `furniture`;

SET FOREIGN_KEY_CHECKS = 0;
SET FOREIGN_KEY_CHECKS = 1;


CREATE TABLE article (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
user_id INT NOT NULL,
title VARCHAR(100),
content longtext,
article_category_id INT NOT NULL,
article_tag_id INT NOT NULL,
created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
upload_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
published_date DATETIME DEFAULT CURRENT_TIMESTAMP,
is_valid TINYINT NOT NULL DEFAULT 1,
FOREIGN KEY (`article_category_id`) REFERENCES article_category(`id`),
FOREIGN KEY (`user_id`) REFERENCES users(`id`),
FOREIGN KEY (`article_tag_id`) REFERENCES article_tag(`id`)
);

CREATE TABLE `article_category`(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20)
);

CREATE TABLE `article_img`(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    img VARCHAR(500),
    FOREIGN KEY(`article_id`) REFERENCES article(`id`)
);

CREATE TABLE `article_tag`(
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  article_id INT NOT NULL,
  name VARCHAR(300)
)

CREATE TABLE `levels`(
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(20)
);

