USE `furniture`;

SELECT 
article.*,
GROUP_CONCAT(DISTINCT article_category.name) AS categoryName
FROM `article` 
LEFT JOIN `article_category`
ON `article`.`article_category_id` = `article_category`.`id`
WHERE `is_valid` = 1 
GROUP BY `article`.`id`
ORDER BY `categoryName`;

SELECT 
article.*,
GROUP_CONCAT(DISTINCT article_category.name) AS categoryName,
GROUP_CONCAT(tag.name) AS tagName
FROM `article` 
LEFT JOIN `article_tag`
ON `article`.`id` = `article_tag`.`article_id`
LEFT JOIN `tag`
ON `tag`.`id` = `article_tag`.`tag_id`
LEFT JOIN `article_category`
ON `article`.`article_category_id` = `article_category`.`id`
WHERE is_valid = 1
GROUP BY `article`.`id`
ORDER BY `article`.`id` ASC;

SELECT 
article.*,
GROUP_CONCAT(DISTINCT article_category.name) AS categoryName,
GROUP_CONCAT(tag.name) AS tagName
FROM article 
LEFT JOIN article_tag ON article.id = article_tag.article_id
LEFT JOIN tag ON tag.id = article_tag.tag_id
LEFT JOIN article_category ON article.article_category_id = article_category.id
WHERE article.created_date >= '2025-06-02'
GROUP BY article.id
ORDER BY article.created_date desc;
-- LIMIT 10 OFFSET 1;


SELECT `article`.*, GROUP_CONCAT(`article_category`.`name`) AS `cateName`, GROUP_CONCAT(`tag`.`name`) AS `tagName`
FROM
    `article`
    LEFT JOIN `article_category` ON `article`.`article_category_id` = `article_category`.`id`
    LEFT JOIN `article_tag` ON `article`.`id` = `article_tag`.`article_id`
    LEFT JOIN `tag` ON `article_tag`.`tag_id` = `tag`.`id`
WHERE
    `article`.`id` = 12
GROUP BY
    `article`.`id`;

SELECT GROUP_CONCAT(`article_category`.`name`) AS `cateName`
FROM
    `article`
    LEFT JOIN `article_category` ON `article`.`article_category_id` = `article_category`.`id`
WHERE
    `article`.`id` = 12
GROUP BY
    `article`.`id`;

SELECT GROUP_CONCAT(`tag`.`name` SEPARATOR ',') AS `tagName`
FROM
    `article`
    LEFT JOIN `article_tag` ON `article`.`id` = `article_tag`.`article_id`
    LEFT JOIN `tag` ON `article_tag`.`tag_id` = `tag`.`id`
WHERE
    `article`.`id` = 12
GROUP BY
    `article`.`id`;

SELECT `article`.* FROM `article` WHERE `article`.`id` = 12;

SELECT GROUP_CONCAT(`tag`.`name`) AS `tagName`
FROM
    `article`
    LEFT JOIN `article_tag` ON `article`.`id` = `article_tag`.`article_id`
    LEFT JOIN `tag` ON `article_tag`.`tag_id` = `tag`.`id`
WHERE
    `article`.`id` = 12
GROUP BY
    `article`.`id`;

SELECT `article`.`article_category_id` AS category_id, GROUP_CONCAT(`article_category`.`name`) AS `cateName`
FROM
    `article`
    LEFT JOIN `article_category` ON `article`.`article_category_id` = `article_category`.`id`
WHERE
    `article`.`id` = 12
GROUP BY
    `article`.`id`;

SELECT `article`.`article_category_id` AS category_id, GROUP_CONCAT(`article_category`.`name`) AS `cateName`
FROM
    `article`
    LEFT JOIN `article_category` ON `article`.`article_category_id` = `article_category`.`id`
WHERE
    `article`.`id` = 12
GROUP BY
    `article`.`id`;

SELECT 
article.*,
GROUP_CONCAT(DISTINCT article_category.name) AS categoryName,
GROUP_CONCAT(tag.name) AS tagName
FROM `article` 
LEFT JOIN `article_tag`
ON `article`.`id` = `article_tag`.`article_id`
LEFT JOIN `tag`
ON `tag`.`id` = `article_tag`.`tag_id`
LEFT JOIN `article_category`
ON `article`.`article_category_id` = `article_category`.`id`
WHERE `is_valid` = 1 
GROUP BY `article`.`id`
ORDER BY `id` DESC;

SELECT 
article.*,
MAX(article.created_date) AS created_date,
GROUP_CONCAT(DISTINCT article_category.name) AS categoryName,
GROUP_CONCAT(tag.name) AS tagName
FROM `article`
LEFT JOIN `article_tag`
ON `article`.`id` = `article_tag`.`article_id`
LEFT JOIN `tag`
ON `tag`.`id` = `article_tag`.`tag_id`
LEFT JOIN `article_category`
ON `article`.`article_category_id` = `article_category`.`id`
-- 改這下面
WHERE article.created_date >= '2025-05-01' AND article.created_date <= '2025-06-11'
GROUP BY `article`.`id`
ORDER BY categoryName DESC
LIMIT 10 OFFSET 0;