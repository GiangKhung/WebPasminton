-- Thêm các cột mới cho sản phẩm thể thao
-- Chạy file này để cập nhật bảng products

-- Thêm cột mới (bỏ qua nếu đã tồn tại)
ALTER TABLE products ADD COLUMN IF NOT EXISTS brand VARCHAR(100) DEFAULT NULL;
ALTER TABLE products ADD COLUMN IF NOT EXISTS color VARCHAR(100) DEFAULT NULL;
ALTER TABLE products ADD COLUMN IF NOT EXISTS weight VARCHAR(50) DEFAULT NULL;
ALTER TABLE products ADD COLUMN IF NOT EXISTS balance_point VARCHAR(50) DEFAULT NULL;
ALTER TABLE products ADD COLUMN IF NOT EXISTS shaft_hardness VARCHAR(50) DEFAULT NULL;
ALTER TABLE products ADD COLUMN IF NOT EXISTS player_level VARCHAR(50) DEFAULT NULL;
ALTER TABLE products ADD COLUMN IF NOT EXISTS sizes VARCHAR(255) DEFAULT NULL;
ALTER TABLE products ADD COLUMN IF NOT EXISTS material VARCHAR(100) DEFAULT NULL;

-- =====================================================
-- CẬP NHẬT DANH MỤC SẢN PHẨM (Gọn gàng)
-- =====================================================

DELETE FROM categories;
ALTER TABLE categories AUTO_INCREMENT = 1;

-- DANH MỤC CHA
INSERT INTO categories (id, name, slug, image, parent_id, status) VALUES
(1, 'Cầu Lông', 'cau-long', 'cat-caulong.png', NULL, 'active'),
(2, 'Bóng Đá', 'bong-da', 'cat-bongda.png', NULL, 'active'),
(3, 'Tennis', 'tennis', 'cat-tennis.png', NULL, 'active'),
(4, 'Gym - Fitness', 'gym-fitness', 'cat-gym.png', NULL, 'active'),
(5, 'Phụ Kiện', 'phu-kien', 'cat-phukien.png', NULL, 'active');

-- CẦU LÔNG (parent_id = 1)
INSERT INTO categories (name, slug, image, parent_id, status) VALUES
('Vợt Cầu Lông', 'vot-cau-long', 'cat-vot.png', 1, 'active'),
('Giày Cầu Lông', 'giay-cau-long', 'cat-giay.png', 1, 'active'),
('Quần Áo Cầu Lông', 'ao-cau-long', 'cat-ao.png', 1, 'active'),
('Phụ Kiện Cầu Lông', 'phu-kien-cau-long', 'cat-pk-cl.png', 1, 'active');

-- BÓNG ĐÁ (parent_id = 2)
INSERT INTO categories (name, slug, image, parent_id, status) VALUES
('Giày Bóng Đá', 'giay-bong-da', 'cat-giay-bd.png', 2, 'active'),
('Quần Áo Bóng Đá', 'ao-bong-da', 'cat-ao-bd.png', 2, 'active'),
('Bóng Đá', 'bong-da-qua', 'cat-bong.png', 2, 'active'),
('Phụ Kiện Bóng Đá', 'phu-kien-bong-da', 'cat-pk-bd.png', 2, 'active');

-- TENNIS (parent_id = 3)
INSERT INTO categories (name, slug, image, parent_id, status) VALUES
('Vợt Tennis', 'vot-tennis', 'cat-vot-tennis.png', 3, 'active'),
('Giày Tennis', 'giay-tennis', 'cat-giay-tennis.png', 3, 'active'),
('Quần Áo Tennis', 'ao-tennis', 'cat-ao-tennis.png', 3, 'active'),
('Phụ Kiện Tennis', 'phu-kien-tennis', 'cat-pk-tennis.png', 3, 'active');

-- GYM - FITNESS (parent_id = 4)
INSERT INTO categories (name, slug, image, parent_id, status) VALUES
('Quần Áo Gym', 'quan-ao-gym', 'cat-ao-gym.png', 4, 'active'),
('Giày Tập Gym', 'giay-tap-gym', 'cat-giay-gym.png', 4, 'active'),
('Dụng Cụ Tập', 'dung-cu-tap-gym', 'cat-dungcu.png', 4, 'active'),
('Phụ Kiện Gym', 'phu-kien-gym', 'cat-pk-gym.png', 4, 'active');

-- PHỤ KIỆN CHUNG (parent_id = 5)
INSERT INTO categories (name, slug, image, parent_id, status) VALUES
('Túi & Balo', 'tui-balo', 'cat-tui.png', 5, 'active'),
('Vớ & Căng Cước', 'vo-can-cuoc', 'cat-vo.png', 5, 'active'),
('Băng Bảo Vệ', 'bang-bao-ve', 'cat-bang.png', 5, 'active'),
('Phụ Kiện Khác', 'phu-kien-khac', 'cat-pk.png', 5, 'active');
