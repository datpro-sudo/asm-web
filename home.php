<?php
// // Kết nối cơ sở dữ liệu
// session_start();
// include 'connect_db.php';

// // Lấy từ khóa tìm kiếm nếu có
// $search = isset($_GET['search']) ? $_GET['search'] : '';

// // Nếu có từ khóa tìm kiếm, thực hiện truy vấn
// if ($search != '') {
//     // Truy vấn cơ sở dữ liệu với từ khóa tìm kiếm
//     $query = "SELECT * FROM product WHERE product_name LIKE :search OR product_description LIKE :search";
//     $stmt = $pdo->prepare($query);
//     $stmt->execute(['search' => '%' . $search . '%']);
//     $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
// } else {
//     // Nếu không có từ khóa tìm kiếm, hiển thị tất cả sản phẩm nổi bật
//     $products = [];
// }

session_start();
include 'connect_db.php';  // Include the database connection file

// Get the search keyword if available
$search = isset($_GET['search']) ? $_GET['search'] : '';

// If there's a search term, execute the query
if ($search != '') {
    // Prepare the query to search for products based on name or description
    $query = "SELECT * FROM product WHERE product_name LIKE ? OR product_description LIKE ?";
    $stmt = $conn->prepare($query);
    
    // Bind parameters to the prepared statement
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    
    // Execute the query
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    // Fetch all products matching the search term
    $products = [];
    while ($product = $result->fetch_assoc()) {
        $products[] = $product;
    }
    
    $stmt->close();
} else {
    // If no search term is provided, return an empty array
    $products = [];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Trang Sức</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleHome.css">
    <link rel="stylesheet" href="styleDetail.css">
    <link rel="stylesheet" href="styleAddCart.css">

    <style>
    /* CSS Styling (same as before) */
    .search-container {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
        padding: 6px 10px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s;
    }

    .search-container input[type="text"] {
        border: none;
        border-radius: 6px;
        padding: 10px 14px;
        font-size: 16px;
        width: 240px;
        outline: none;
        transition: box-shadow 0.3s, transform 0.3s;
    }

    .search-container input[type="text"]:focus {
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.5);
        transform: scale(1.02);
    }

    .search-container button {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        font-size: 18px;
        background-color: #4a90e2;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.3s;
    }

    .search-container button:hover {
        background-color: #357abd;
        transform: translateY(-2px);
    }

    .search-container:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Ẩn hoàn toàn sản phẩm nổi bật khi tìm kiếm */
    .featured-products.hidden {
        display: none;
        /* Ẩn sản phẩm nổi bật khi có tìm kiếm */
    }

    .search-results {
        display: block;
        /* Chỉ hiển thị kết quả tìm kiếm */
    }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="logo">
            <img src="btec_1710525288.png" alt="Logo Shop Trang Sức">
        </div>
        <nav class="navbar">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="https://www.giaodien.blog/2023/02/template-blogger-ban-hang-trang-suc.html">Blog</a></li>
                <li><a href="account.php">Account</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="login.php">Log Out</a></li>
            </ul>
        </nav>
        <div class="search-container">
            <form method="GET" action="home.php">
                <input type="text" name="search" placeholder="Search for products..."
                    value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit"><i class="fas fa-search"></i> Search</button>
            </form>
        </div>
        <div class="cart">
            <a href="cart.php">🛒</a>
        </div>
    </header>

    <!-- Banner -->
    <section class="banner"></section>


    <!-- Featured Products (Hidden when searching) -->
    <main>
        <section class="products featured-products" <?php echo $search ? 'hidden' : ''; ?> id="featured-products">
            <h2>Best Seller</h2>
            <div class="product-list" id="product-list">
            </div>
        </section>

        <!-- Search Results (Display only when searching) -->
        <section class="products search-results" id="search-results">
            <div class="product-list" id="product-list">
                <?php
                if ($search != '') {
                    // Nếu có kết quả tìm kiếm, hiển thị sản phẩm tìm được
                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            echo '<div class="product">';
                            echo '<img src="images/' . $product['product_image'] . '" alt="' . $product['product_name'] . '">';
                            echo '<h3>' . $product['product_name'] . '</h3>';
                            echo '<p>' . $product['product_price'] . ' VND</p>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No products found matching your search.</p>';
                    }
                }
                ?>
            </div>
        </section>

        <div class="modal-container" id="modal-container">
            <div class="modal">
                <button class="close-btn" onclick="closeModal()">×</button>
                <img id="modal-image" src="" alt="Product Image"
                    style="width: 100%; max-height: 300px; object-fit: contain;">
                <h2 id="modal-title"></h2>
                <p id="modal-description"></p>
                <p id="modal-price"></p>
                <p id="modal-quantity"></p>
                <p id="modal-supplier"></p>
                <div
                    style="display: flex; justify-content: center; align-items: center; height: 100%; flex-direction: column;">
                    <button class="add-to-cart" id="add-to-cart" onclick="" style="
            background-color: #4CAF50; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 16px; 
            font-weight: bold; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
            transition: background-color 0.3s ease, transform 0.2s ease;
        " onmouseover="this.style.backgroundColor='#45a049'; this.style.transform='scale(1.05)';"
                        onmouseout="this.style.backgroundColor='#4CAF50'; this.style.transform='scale(1)';">
                        Add to Cart
                    </button>
                </div>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>Bản quyền © 2024 - Shop Trang Sức Cao Cấp. Mọi quyền được bảo lưu.</p>
    </footer>

    <!-- Include the external JS file -->
    <script src="product.js"></script>

</body>

</html>