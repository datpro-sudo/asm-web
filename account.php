<?php
session_start();

// Kết nối đến cơ sở dữ liệu
include 'connect_db.php';
// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color: red; text-align: center;'>You must be logged in to access this page. Redirecting to login...</p>";
    header("Refresh: 3; url=login.php"); // Điều hướng về trang đăng nhập sau 3 giây
    exit();
}

// Lấy ID người dùng từ session
$user_id = $_SESSION['user_id'];

// Lấy thông tin người dùng từ cơ sở dữ liệu
$sql = "SELECT email, username, password FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<p style='color: red; text-align: center;'>User not found. Please contact support.</p>";
    exit();
}

// Xử lý yêu cầu đổi mật khẩu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Kiểm tra mật khẩu cũ
        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                // Mã hóa mật khẩu mới
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                // Cập nhật mật khẩu trong cơ sở dữ liệu
                $update_sql = "UPDATE users SET password = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $hashed_password, $user_id);

                if ($update_stmt->execute()) {
                    echo "<p style='color: green; text-align: center;'>Password updated successfully!</p>";
                } else {
                    echo "<p style='color: red; text-align: center;'>Error updating password. Please try again later.</p>";
                }
            } else {
                echo "<p style='color: red; text-align: center;'>New passwords do not match.</p>";
            }
        } else {
            echo "<p style='color: red; text-align: center;'>Current password is incorrect.</p>";
        }
    }
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    h1 {
        text-align: center;
        color: #333;
    }

    .profile-info,
    .change-password {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #555;
    }

    input[type="text"],
    input[type="password"],
    input[type="email"],
    button {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }

    .home-link {
        text-align: center;
        margin-top: 20px;
    }

    .home-link a {
        color: #007bff;
        text-decoration: none;
    }

    .home-link a:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>User Profile</h1>
        <div class="profile-info">
            <h3>Profile Information</h3>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Password:</strong> ********</p>
        </div>

        <div class="change-password">
            <h3>Change Password</h3>
            <form method="POST">
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" id="current_password" required>

                <label for="new_password">New Password</label>
                <input type="password" name="new_password" id="new_password" required>

                <label for="confirm_password">Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required>

                <button type="submit">Update Password</button>
            </form>
        </div>

        <div class="home-link">
            <a href="home.php">Back to Home</a>
        </div>
    </div>
</body>

</html>