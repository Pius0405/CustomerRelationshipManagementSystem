<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: ../signin.php");
    exit;
}

// Check if customerID was provided
if (!isset($_POST['leadID'])) {
    echo json_encode(["error" => "No lead ID provided"]);
    exit;
}

$leadID = $_POST['leadID'];

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
$sql = "SELECT * FROM lead WHERE lead_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $leadID, $_SESSION['userID']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $leadData = $result->fetch_assoc();
    echo json_encode($leadData);
} else {
    echo json_encode(["error" => "Lead not found"]);
}

$conn->close();
?>