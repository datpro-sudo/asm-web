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
?>