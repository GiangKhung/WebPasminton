<?php
/**
 * API Chat Search - Tìm kiếm sản phẩm thông minh
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../backend/config/config.php';

// Chỉ chấp nhận POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$message = trim($input['message'] ?? '');

if (empty($message)) {
    echo json_encode(['error' => 'Vui lòng nhập nội dung tìm kiếm']);
    exit;
}

try {
    $conn = getConnection();
    $response = processMessage($conn, $message);
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
}

/**
 * Xử lý tin nhắn và trả về kết quả
 */
function processMessage($conn, $message) {
    $messageLower = mb_strtolower($message, 'UTF-8');
    
    // Phân tích ý định người dùng
    $intent = analyzeIntent($messageLower);
    
    switch ($intent['type']) {
        case 'greeting':
            return greetingResponse();
        case 'help':
            return helpResponse();
        case 'category':
            return searchByCategory($conn, $intent['category']);
        case 'price':
            return searchByPrice($conn, $intent['min'], $intent['max']);
        case 'sale':
            return searchSaleProducts($conn);
        case 'featured':
            return searchFeaturedProducts($conn);
        case 'search':
        default:
            return searchProducts($conn, $message);
    }
}

/**
 * Phân tích ý định từ tin nhắn
 */
function analyzeIntent($message) {
    // Chào hỏi
    $greetings = ['xin chào', 'hello', 'hi', 'chào', 'hey', 'alo'];
    foreach ($greetings as $g) {
        if (strpos($message, $g) !== false) {
            return ['type' => 'greeting'];
        }
    }
    
    // Trợ giúp
    $helpWords = ['giúp', 'help', 'hướng dẫn', 'làm sao', 'cách'];
    foreach ($helpWords as $h) {
        if (strpos($message, $h) !== false) {
            return ['type' => 'help'];
        }
    }
    
    // Tìm theo danh mục
    $categories = [
        'vợt' => 1, 'vot' => 1,
        'giày' => 2, 'giay' => 2,
        'áo' => 3, 'ao' => 3,
        'váy' => 4, 'vay' => 4,
        'quần' => 5, 'quan' => 5,
        'túi' => 6, 'tui' => 6,
        'balo' => 7,
        'phụ kiện' => 8, 'phu kien' => 8
    ];
    
    foreach ($categories as $keyword => $catId) {
        if (strpos($message, $keyword) !== false) {
            return ['type' => 'category', 'category' => $catId];
        }
    }
    
    // Tìm sản phẩm giảm giá
    $saleWords = ['giảm giá', 'sale', 'khuyến mãi', 'giảm', 'rẻ'];
    foreach ($saleWords as $s) {
        if (strpos($message, $s) !== false) {
            return ['type' => 'sale'];
        }
    }
    
    // Tìm sản phẩm nổi bật
    $featuredWords = ['nổi bật', 'hot', 'bán chạy', 'phổ biến', 'đề xuất'];
    foreach ($featuredWords as $f) {
        if (strpos($message, $f) !== false) {
            return ['type' => 'featured'];
        }
    }
    
    // Tìm theo khoảng giá
    if (preg_match('/dưới\s*(\d+)/u', $message, $matches)) {
        return ['type' => 'price', 'min' => 0, 'max' => intval($matches[1]) * 1000];
    }
    if (preg_match('/trên\s*(\d+)/u', $message, $matches)) {
        return ['type' => 'price', 'min' => intval($matches[1]) * 1000, 'max' => 999999999];
    }
    if (preg_match('/từ\s*(\d+)\s*đến\s*(\d+)/u', $message, $matches)) {
        return ['type' => 'price', 'min' => intval($matches[1]) * 1000, 'max' => intval($matches[2]) * 1000];
    }
    
    return ['type' => 'search'];
}

/**
 * Phản hồi chào hỏi
 */
function greetingResponse() {
    return [
        'type' => 'text',
        'message' => "Xin chào! 👋 Tôi là trợ lý AI của VNB Sports. Tôi có thể giúp bạn:\n\n" .
                    "🔍 Tìm kiếm sản phẩm (vợt, giày, áo...)\n" .
                    "💰 Tìm theo khoảng giá\n" .
                    "🏷️ Xem sản phẩm giảm giá\n" .
                    "⭐ Xem sản phẩm nổi bật\n\n" .
                    "Bạn muốn tìm gì hôm nay?"
    ];
}

/**
 * Phản hồi trợ giúp
 */
function helpResponse() {
    return [
        'type' => 'text',
        'message' => "📖 Hướng dẫn sử dụng:\n\n" .
                    "• Gõ tên sản phẩm: \"vợt VNB V200\"\n" .
                    "• Tìm theo loại: \"tìm giày cầu lông\"\n" .
                    "• Tìm theo giá: \"vợt dưới 500\", \"giày từ 500 đến 1000\"\n" .
                    "• Xem giảm giá: \"sản phẩm sale\"\n" .
                    "• Xem nổi bật: \"sản phẩm hot\"\n\n" .
                    "Hãy thử ngay nhé! 😊"
    ];
}

/**
 * Tìm kiếm sản phẩm theo từ khóa
 */
function searchProducts($conn, $keyword) {
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.status = 'active' 
            AND (p.name LIKE :keyword OR p.description LIKE :keyword)
            ORDER BY p.featured DESC, p.created_at DESC
            LIMIT 6";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute(['keyword' => '%' . $keyword . '%']);
    $products = $stmt->fetchAll();
    
    if (empty($products)) {
        return [
            'type' => 'text',
            'message' => "Không tìm thấy sản phẩm nào với từ khóa \"$keyword\". 😔\n\nBạn có thể thử:\n• Tìm theo danh mục: vợt, giày, áo...\n• Xem sản phẩm nổi bật\n• Xem sản phẩm giảm giá"
        ];
    }
    
    return [
        'type' => 'products',
        'message' => "🔍 Tìm thấy " . count($products) . " sản phẩm cho \"$keyword\":",
        'products' => formatProducts($products)
    ];
}

/**
 * Tìm theo danh mục
 */
function searchByCategory($conn, $categoryId) {
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.status = 'active' AND p.category_id = :cat_id
            ORDER BY p.featured DESC, p.created_at DESC
            LIMIT 6";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute(['cat_id' => $categoryId]);
    $products = $stmt->fetchAll();
    
    // Lấy tên danh mục
    $catStmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $catStmt->execute([$categoryId]);
    $catName = $catStmt->fetchColumn() ?: 'Danh mục';
    
    if (empty($products)) {
        return [
            'type' => 'text',
            'message' => "Hiện chưa có sản phẩm nào trong danh mục $catName. 😔"
        ];
    }
    
    return [
        'type' => 'products',
        'message' => "📦 $catName (" . count($products) . " sản phẩm):",
        'products' => formatProducts($products)
    ];
}

/**
 * Tìm theo khoảng giá
 */
function searchByPrice($conn, $min, $max) {
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.status = 'active' 
            AND COALESCE(p.sale_price, p.price) >= :min 
            AND COALESCE(p.sale_price, p.price) <= :max
            ORDER BY COALESCE(p.sale_price, p.price) ASC
            LIMIT 6";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute(['min' => $min, 'max' => $max]);
    $products = $stmt->fetchAll();
    
    $priceText = formatPriceRange($min, $max);
    
    if (empty($products)) {
        return [
            'type' => 'text',
            'message' => "Không tìm thấy sản phẩm nào trong khoảng giá $priceText. 😔"
        ];
    }
    
    return [
        'type' => 'products',
        'message' => "💰 Sản phẩm $priceText (" . count($products) . " sản phẩm):",
        'products' => formatProducts($products)
    ];
}

/**
 * Tìm sản phẩm giảm giá
 */
function searchSaleProducts($conn) {
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.status = 'active' AND p.sale_price IS NOT NULL AND p.sale_price < p.price
            ORDER BY (p.price - p.sale_price) DESC
            LIMIT 6";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll();
    
    if (empty($products)) {
        return [
            'type' => 'text',
            'message' => "Hiện chưa có sản phẩm giảm giá nào. 😔\nHãy quay lại sau nhé!"
        ];
    }
    
    return [
        'type' => 'products',
        'message' => "🏷️ Sản phẩm đang giảm giá (" . count($products) . " sản phẩm):",
        'products' => formatProducts($products)
    ];
}

/**
 * Tìm sản phẩm nổi bật
 */
function searchFeaturedProducts($conn) {
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.status = 'active' AND p.featured = 1
            ORDER BY p.created_at DESC
            LIMIT 6";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll();
    
    if (empty($products)) {
        return [
            'type' => 'text',
            'message' => "Hiện chưa có sản phẩm nổi bật nào. 😔"
        ];
    }
    
    return [
        'type' => 'products',
        'message' => "⭐ Sản phẩm nổi bật (" . count($products) . " sản phẩm):",
        'products' => formatProducts($products)
    ];
}

/**
 * Format danh sách sản phẩm
 */
function formatProducts($products) {
    return array_map(function($p) {
        $price = $p['sale_price'] ?? $p['price'];
        $oldPrice = $p['sale_price'] ? $p['price'] : null;
        $discount = $oldPrice ? round((($oldPrice - $price) / $oldPrice) * 100) : 0;
        
        return [
            'id' => $p['id'],
            'name' => $p['name'],
            'slug' => $p['slug'],
            'image' => $p['image'],
            'price' => $price,
            'price_formatted' => number_format($price, 0, ',', '.') . 'đ',
            'old_price' => $oldPrice,
            'old_price_formatted' => $oldPrice ? number_format($oldPrice, 0, ',', '.') . 'đ' : null,
            'discount' => $discount,
            'category' => $p['category_name'] ?? ''
        ];
    }, $products);
}

/**
 * Format khoảng giá
 */
function formatPriceRange($min, $max) {
    if ($min == 0) {
        return "dưới " . number_format($max, 0, ',', '.') . "đ";
    }
    if ($max >= 999999999) {
        return "trên " . number_format($min, 0, ',', '.') . "đ";
    }
    return "từ " . number_format($min, 0, ',', '.') . "đ đến " . number_format($max, 0, ',', '.') . "đ";
}
