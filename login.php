<?php

$host = 'localhost';
$db = 'asm_web';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}
else{
    echo'';
}


$message = ""; // Biến để lưu thông báo

// Xử lý form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"]; // Lấy giá trị từ nút submit (Signup hoặc Login)

    if ($action === "Signup") {
        // Xử lý đăng ký
        $username = trim($_POST["username"]);
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        // Kiểm tra dữ liệu đầu vào
        if (empty($username) || empty($email) || empty($password)) {
            $message = "All fields are required!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid email format!";
        } else {
            // Kiểm tra email hoặc username đã tồn tại chưa
            $check_sql = "SELECT * FROM users WHERE email = ? OR username = ?";
           $stmt = mysqli_prepare($conn, $check_sql);
            mysqli_stmt_bind_param($stmt, "ss", $email, $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                $message = "Email or username already exists!";
            } else {
                // Thêm người dùng mới
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sss", $username, $hashed_password, $email);

                if (mysqli_stmt_execute($stmt)) {
                    $message = "Registration successful!";
                } else {
                    $message = "Error during registration. Please try again.";
                }
            }
        }
    } elseif ($action === "Login") {
        // Xử lý đăng nhập
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Kiểm tra mật khẩu
            if (password_verify($password, $row["password"])) {
                session_start(); // Bắt đầu session để lưu thông tin người dùng
                $_SESSION["user_id"] = $row["id"]; // Lưu ID của người dùng
                $_SESSION["username"] = $username; // Lưu username vào session
                header("Location: home.php"); // Chuyển hướng đến trang home
                exit();
            } else {
                $message = "Incorrect password.";
            }
        } else {
            $message = "Username does not exist.";
        }
    }
}

// Đóng kết nối

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup Form</title>
    <link rel="stylesheet" href="styLogin.css">
    <script>
    function validateSignupForm() {
        var username = document.getElementById("signupUsername").value;
        if (username === null || username === "") {
            alert("Username must be filled out");
            return false;
        }
        return true;
    }

    function validateLoginForm() {
        var username = document.getElementById("loginUsername").value;
        if (username === null || username === "") {
            alert("Username must be filled out");
            return false;
        }
        return true;
    }
    </script>
</head>

<body>
    <section class="wrapper">
        <!-- Signup Form -->
        <div class="form signup">
            <header>Signup</header>
            <form action="#" method="POST" onsubmit="return validateSignupForm()">
                <input type="text" name="username" id="signupUsername" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email address" required>
                <input type="password" name="password" placeholder="Password" required>
                <div class="checkbox">
                    <input type="checkbox" id="signupCheck" required>
                    <label for="signupCheck">I accept all terms & conditions</label>
                </div>
                <input type="submit" name="action" value="Signup">
            </form>
            <?php if (!empty($message)) : ?>
            <p style="color: green; text-align: center;"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
        </div>

        <!-- Login Form -->
        <div class="form login">
            <header>Login</header>
            <form action="#" method="POST" onsubmit="return validateLoginForm()">
                <input type="text" name="username" id="loginUsername" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <a href="#">Forgot password?</a>
                <input type="submit" name="action" value="Login">
            </form>
            <?php if (!empty($message)) : ?>
            <p style="color: red; text-align: center;"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
        </div>
    </section>
</body>

<script>
const wrapper = document.querySelector(".wrapper"),
    signupHeader = document.querySelector(".signup header"),
    loginHeader = document.querySelector(".login header");

loginHeader.addEventListener("click", () => {
    wrapper.classList.add("active");
});
signupHeader.addEventListener("click", () => {
    wrapper.classList.remove("active");
});
</script>


</html>