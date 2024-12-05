<?php
// Bật hiển thị lỗi (chỉ dùng khi debug)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kết nối CSDL
session_start();
include 'connect_db.php';

//$conn = new mysqli("localhost", "root", "", "asm_web");

//if ($conn->connect_error) {
   // die("Connection failed: " . $conn->connect_error);
//}

// Truy vấn sản phẩm
$sql = "SELECT product_id, product_name, product_price, product_image, product_description, product_quantity, product_supplier FROM product";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Trả về JSON
header('Content-Type: application/json');
echo json_encode($products);

$conn->close();
?>