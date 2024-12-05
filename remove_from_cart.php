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
$cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;

// Kiểm tra dữ liệu đầu vào
if ($cart_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid cart ID']);
    exit;
}

try {
    // Xóa sản phẩm khỏi giỏ hàng
    $query = "DELETE FROM cart WHERE cart_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ii", $cart_id, $user_id);
        $stmt->execute();

        // Kiểm tra xem có sản phẩm nào bị xóa không
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Product removed successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to remove product']);
        }

        $stmt->close();
    } else {
        // Lỗi khi chuẩn bị truy vấn
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement']);
    }
} catch (Exception $e) {
    // Xử lý lỗi
    echo json_encode(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage()]);
}

// Đóng kết nối
$conn->close();
?>