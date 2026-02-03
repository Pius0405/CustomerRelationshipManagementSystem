<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../data-validation.php';

session_start();
$_SESSION['previous_page'] = basename($_SERVER['PHP_SELF']);

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

$error = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $details = trim($_POST['details']);

    if (! validateNotEmpty($details)) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please ensure that all fields are filled in!</p>";
    } elseif (! validateLength($details, 255)) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Details must be less than 255 characters!</p>";
    } else {
        if ($_SESSION["currentReminder"] === "c") {
            $stmt = $conn -> prepare("INSERT INTO customerreminder (user_id, customer_id, reminder, date) VALUES(?, ?, ?, ?)");
            $stmt -> bind_param("iiss", $_SESSION["userID"], $_SESSION["customerRemID"], $details, $_POST["date"]);
            $stmt -> execute();
            $stmt -> close();
            $conn -> close();
            header("Location: salesrep-hp.php");
        } else {
            $stmt = $conn -> prepare("INSERT INTO leadreminder (user_id, lead_id, reminder, date) VALUES(?, ?, ?, ?)");
            $stmt -> bind_param("iiss", $_SESSION["userID"], $_SESSION["leadRemID"], $details, $_POST["date"]);
            $stmt -> execute();
            $stmt -> close();
            $conn -> close();
            header("Location: salesrep-lead.php");
        }
        exit();
    }
}
$conn -> close();
?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add New Reminder</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../general/styles/header.css">
        <link rel="stylesheet" href="../general/styles/style.css">
        <link rel="stylesheet" href="../general/styles/table.css">
        <link rel="stylesheet" href="../general/styles/back-button.css">
        <link rel="stylesheet" href="styles/form.css">
    </head>
    <body>
        <div class="back-button">
            <?php 
                if ($_SESSION["currentReminder"] === "c") {
                    echo "
                    <a href='salesrep-hp.php'>
                        <img class='return-icon' src='../general/menu-images/return-icon.png'>
                    </a>";
                } else {
                    echo "
                    <a href='salesrep-lead.php'>
                        <img class='return-icon' src='../general/menu-images/return-icon.png'>
                    </a>";
                }
            ?>
        </div>

        <div class="header">

        </div>

        <div class="add-user-form-container">
            <h2>Add New Reminder</h2>
            <form action="" method="POST">
                <input placeholder="Details" type="text" id="name" name="details" required>

                <input type="date" name="date" required>

                <?php 
                if ($error) {
                    echo $error_message;
                }
                ?>

                <button class="add-user-button" type="submit">Add New Reminder</button>
            </form>
        </div>

        <script src="../general/scripts/header-customer.js"></script>
    </body>
</html>