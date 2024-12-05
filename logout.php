<?php
session_start(); // Bắt đầu session
session_unset(); // Xóa tất cả session
session_destroy(); // Hủy session
header("Location: login.php"); // Chuyển hướng về trang login
exit();
?>