-- Script xóa danh mục Gym - Fitness và các danh mục con
-- Chạy script này trong phpMyAdmin hoặc MySQL client

USE vnb_sports;

-- Bước 1: Cập nhật các sản phẩm thuộc danh mục Gym về NULL (nếu có)
UPDATE products 
SET category_id = NULL 
WHERE category_id IN (
    SELECT id FROM categories WHERE parent_id = 4 OR id = 4
);

-- Bước 2: Xóa các danh mục con của Gym - Fitness (parent_id = 4)
DELETE FROM categories WHERE parent_id = 4;

-- Bước 3: Xóa danh mục Gym - Fitness chính (id = 4)
DELETE FROM categories WHERE id = 4;

-- Kiểm tra kết quả
SELECT * FROM categories ORDER BY id;
