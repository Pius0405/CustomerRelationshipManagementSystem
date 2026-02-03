<?php
include 'Notifications/notifications-utility.php';
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: signin.php"); // Redirect to login if not logged in
    exit;
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "comp1044_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header('Content-Type: application/json');
echo json_encode([
    'count' => getNotificationCount($conn, $_SESSION["userID"], "customerreminder") + getNotificationCount($conn,  $_SESSION["userID"], "leadreminder")
]);

$conn -> close();
?>