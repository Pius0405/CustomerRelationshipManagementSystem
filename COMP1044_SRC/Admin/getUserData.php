<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: ../signin.php"); // Redirect to login if not logged in
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "comp1044_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT username, name, email, phone_num FROM user WHERE user_id = ?");
$stmt->bind_param("i", $_POST["userID"]);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode([
        'username' => $user['username'],
        'name' => $user['name'],
        'email' => $user['email'],
        'phone' => $user['phone_num']
    ]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'User not found']);
}

$stmt->close();
$conn->close();
exit;
?>