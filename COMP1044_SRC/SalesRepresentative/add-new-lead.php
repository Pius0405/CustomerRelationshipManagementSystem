<?php

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
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $company = trim($_POST['company']);
    $notes = trim($_POST['notes']);

    if (! (validateNotEmpty($name) && validateNotEmpty($email) && validateNotEmpty($phone) && validateNotEmpty($company) && validateNotEmpty($notes))) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please ensure that all fields are filled in!</p>";
    } elseif (!validateEmail($email)) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please enter a valid email!</p>";
    } elseif (!validatePhoneNumber($phone)) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please enter a valid phone number!</p>";
    } elseif (! validateLength($notes, 255)) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Maximum number of characters (255) exceeded!</p>";  
    } else if (! validateName($name)){
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please enter a valid name! Name only contains A-Za-z and space character</p>";    
    } else {
        $stmt = $conn -> prepare("INSERT INTO lead (user_id, name, company, email, phone_num, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt -> bind_param("issssss", $_SESSION["userID"], $_POST["name"], $_POST["company"], $_POST["email"], $_POST["phone"], $_POST["status"], $_POST["notes"]);
        $stmt -> execute();
        $stmt -> close();
        $conn -> close();
        header("Location: salesrep-lead.php");
        exit();
    }
}

$conn -> close();
?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add New Lead</title>
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
            <a href="salesrep-lead.php">
                <img class="return-icon" src="../general/menu-images/return-icon.png">
            </a>
        </div>

        <div class="header">

        </div>

        <div class="add-user-form-container">
            <h2>Add New Lead</h2>
            <form action="add-new-lead.php" method="POST">
                
                <input placeholder="Name" type="text" id="name" name="name" required>

                <input placeholder="Company" type="text" id="company" name="company" required>
        
                <input placeholder="Email" type="text" id="email" name="email" required>
        
                <input placeholder="Phone Number" type="text" id="phone" name="phone" required>

                <select id="status" name="status" required>
                    <option value="" disabled selected hidden>Select status</option>
                    <option value="New">New</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Contacted">Contacted</option>
                    <option value="Closed">Closed</option>
                </select>

                <input placeholder="Notes" type="text" id="notes" name="notes" required>

                <?php 
                if ($error) {
                    echo $error_message;
                }
                
                ?>

                <button class="add-user-button" type="submit">Add New Lead</button>
            </form>
        </div>

        <script src="../general/scripts/header-customer.js"></script>
    </body>
</html>