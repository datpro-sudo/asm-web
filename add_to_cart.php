<?php
// Bật hiển thị lỗi (chỉ dùng khi debug)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'connect_db.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$user_id = intval($_SESSION['user_id']);
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

// Kiểm tra dữ liệu đầu vào
if ($product_id <= 0 || $quantity <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

try {
    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $query = "SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_item = $result->fetch_assoc();

    if ($cart_item) {
        // Nếu sản phẩm đã có, cập nhật số lượng
        $new_quantity = $cart_item['quantity'] + $quantity;
        $update_query = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ii", $new_quantity, $cart_item['cart_id']);
        $update_stmt->execute();
    } else {
        // Nếu sản phẩm chưa có, thêm mới
        $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $insert_stmt->execute();
    }

    // Phản hồi thành công
    echo json_encode(['status' => 'success', 'message' => 'Product added to cart']);
} catch (Exception $e) {
    // Xử lý lỗi
    echo json_encode(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage()]);
}

// Đóng kết nối
$conn->close();
?>