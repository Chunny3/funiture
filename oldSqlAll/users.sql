
CREATE DATABASE my_db02;

USE my_db02;


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

SELECT * FROM `users`;

CREATE TABLE `city_cates`(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100)
);

CREATE TABLE `area_cates`(
  id INT AUTO_INCREMENT PRIMARY KEY,
  city_cate_id INT NOT NULL,
  name VARCHAR(100)
);

SELECT * FROM `city_cates`;

CREATE TABLE `levels` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL,
  FOREIGN KEY (`level_id`) REFERENCES `levels`(`id`);
);

INSERT INTO `levels` (`name`) VALUES
('木芽會員'),
('原木會員'),
('森林會員');

SELECT users.id, users.name, users.level_id, levels.name AS level_name
FROM users
LEFT JOIN levels ON users.level_id = levels.id;