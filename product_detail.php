<?php

// Kết nối CSDL
session_start();

include 'connect_db.php';


// Kiểm tra tham số 'id' từ URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Truy vấn sản phẩm theo ID
    $sql = "SELECT product_id, product_name, product_price, product_image, product_description, product_quantity, product_supplier FROM product WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        // Trả về thông tin sản phẩm dưới dạng JSON
        header('Content-Type: application/json');
        echo json_encode($product);
    } else {
        // Nếu không tìm thấy sản phẩm
        echo json_encode(["error" => "Product not found"]);
    }
} else {
    // Nếu không có tham số ID hợp lệ
    echo json_encode(["error" => "Invalid product ID"]);
}

$conn->close();
?>