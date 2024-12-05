<?php
session_start();
include 'connect_db.php'; // Bao gồm file connect_db.php

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Truy vấn giỏ hàng
$query = "SELECT cart.cart_id, cart.quantity, 
                 product.product_name, 
                 product.product_price, 
                 product.product_image 
          FROM cart 
          JOIN product ON cart.product_id = product.product_id 
          WHERE cart.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); // "i" cho kiểu số nguyên
$stmt->execute();
$result = $stmt->get_result();

// Lấy danh sách sản phẩm trong giỏ hàng
$cart_items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="styleCart.css">
</head>

<body>
    <h1>Hello <?php echo htmlspecialchars($_SESSION['username']); ?>, this is your cart!</h1>

    <?php if (!empty($cart_items)) : ?>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item) : ?>
            <tr id="cart-item-<?php echo $item['cart_id']; ?>">
                <td><img src="images/<?php echo htmlspecialchars($item['product_image']); ?>"
                        alt="<?php echo htmlspecialchars($item['product_name']); ?>"></td>
                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                <td><?php echo number_format($item['product_price']); ?> VND</td>
                <td><?php echo number_format($item['product_price'] * $item['quantity']); ?> VND</td>
                <td>
                    <button class="remove-btn" data-cart-id="<?php echo $item['cart_id']; ?>">Remove</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else : ?>
    <p>Your cart is empty.</p>
    <?php endif; ?>

    <script>
    // Gán sự kiện cho các nút "Remove"
    document.querySelectorAll(".remove-btn").forEach((button) => {
        button.addEventListener("click", async function() {
            const cartId = this.getAttribute("data-cart-id");

            const response = await fetch("remove_from_cart.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `cart_id=${cartId}`,
            });

            const result = await response.json();

            if (result.status === "success") {
                // Ẩn dòng sản phẩm trong bảng
                document.getElementById("cart-item-" + cartId).style.display = "none";

                // Hiển thị modal thông báo xóa thành công
                const modal = document.getElementById("success-modal");
                const message = document.getElementById("success-message");
                message.textContent = result.message;
                modal.style.display = "flex";
            } else {
                alert(result.message);
            }
        });
    });
    </script>
</body>

</html>