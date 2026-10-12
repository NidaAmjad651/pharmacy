<?php
session_start();

$host   = "localhost";
$user   = "root";
$pass   = "";
$dbname = "pharmacy_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Agar user login nahi hai to login page par bhej do
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}
?>
