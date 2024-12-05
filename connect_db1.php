<?php
$host = 'sql200.infinityfree.com';
$db = 'if0_37803877_asm_web';
$user = 'if0_37803877';
$pass = 'DqOysUTLgTFZd ';

$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}
else{
    echo'';
}
?>