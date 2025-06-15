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