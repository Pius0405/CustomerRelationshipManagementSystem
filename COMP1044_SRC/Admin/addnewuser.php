<?php
require_once '../data-validation.php';
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: ../signin.php"); // Redirect to login if not logged in
    exit;
}

$servername = "localhost";
$username = "root";
$password = "root"; // Change if needed
$dbname = "comp1044_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $role = trim($_POST["role"]);
    $username = trim($_POST["username"]);
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password = trim($_POST["password"]);

    if (! (validateNotEmpty($username) && validateNotEmpty($name) && validateNotEmpty($email) && validateNotEmpty($phone) && validateNotEmpty($password) && validateNotEmpty($role))) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please ensure that all fields are filled in!</p>";
    } elseif (!validateEmail($email)) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please enter a valid email!</p>";
    } elseif (!validatePhoneNumber($phone)) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please enter a valid phone number!</p>";
    } elseif (! checkUserExists($conn, $username, $email)) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Username or email already exists!</p>";
    }else if (! validateName($name)){
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please enter a valid name! Name only contains A-Za-z and space character</p>";
    } else {
        $stmt = $conn -> prepare("INSERT INTO user (role_id, username, name, email, phone_num, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $role, $username, $name, $email, $phone, $password);
        $stmt -> execute();
        $stmt -> close();
        $conn -> close();
        header("Location: admin.php");
        exit();
    }
}

$conn ->close();
?>




<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add New User</title>
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
            <a onclick="window.location.href = 'admin.php';">
                <img class="return-icon" src="../general/menu-images/return-icon.png">
            </a>
        </div>

        <div class="header">

        </div>

        <div class="add-user-form-container">
            <h2>Add New User</h2>
            <form action="addnewuser.php" method="POST">
                <input placeholder="Username" type="text" id="username" name="username" required>

                <input placeholder="Password" type="password" id="password" name="password" required>

                <input placeholder="Name" type="text" id="name" name="name" required>
        
                <input placeholder="Email" type="text" id="email" name="email" required>
        
                <input placeholder="Phone Number" type="text" id="phone" name="phone" required>

                <select id="role" name="role" required>
                    <option value="" disabled selected hidden>Select role</option>
                    <option value="1">Admin</option>
                    <option value="2">Sales Representative</option>
                </select>

                <?php 
                if ($error) {
                    echo $error_message;
                }
                ?>
        
                <button class="add-user-button" type="submit">Add User</button>
            </form>
        </div>

        <script src="../general/scripts/header-admin.js"></script>
    </body>
</html>