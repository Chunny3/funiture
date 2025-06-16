-- 清空現有分類
TRUNCATE TABLE products_category;

-- 插入新的分類
INSERT INTO products_category (category_name) VALUES 
('客廳'),
('餐廳/廚房'),
('臥室'),
('兒童房'),
('辦公空間'),
('收納用品'); 