<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: ../signin.php");
    exit;
}

// Check if customerID was provided
if (!isset($_POST['customerID'])) {
    echo json_encode(["error" => "No customer ID provided"]);
    exit;
}

$customerID = $_POST['customerID'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "comp1044_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get customer data
$sql = "SELECT * FROM customer WHERE customer_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $customerID, $_SESSION['userID']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $customerData = $result->fetch_assoc();
    echo json_encode($customerData);
} else {
    echo json_encode(["error" => "Customer not found"]);
}

$conn->close();
?>