<?php 
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: ../signin.php"); // Redirect to login if not logged in
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["table"]) && isset($_POST["deleteID"])) {
        $table = $_POST["table"];
        $column = $table === "customer" ? "customer_id" : "lead_id";
        $id = $_POST["deleteID"];
        $sql = "DELETE FROM " . $table . " WHERE " . $column . " = " . $id;
        if ($conn -> query($sql)) {
            echo "Record deleted successfully";
        } else {
            echo "Failed to delete record";
        }
    }
}
$conn -> close();
?>
