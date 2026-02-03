<?php
require_once 'data-validation.php';

session_start();
$servername = "localhost";  
$username = "root";          
$password = "root";              
$dbname = "comp1044_database";  

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn -> connect_error) {
    die("Connection failed: " . $conn -> connect_error);
}

$error = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    if (! (validateNotEmpty($username) && validateNotEmpty($name) && validateNotEmpty($email) && validateNotEmpty($phone) && validateNotEmpty($password))) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please ensure that all fields are filled in!</p>";
    } else if (!validateEmail($email)) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please enter a valid email!</p>";
    } else if (!validatePhoneNumber($phone)) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please enter a valid phone number!</p>";
    } else if (!validateUsername($username)) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please enter a valid username! Username only contains A-Za-z0-9 and _</p>";
    } else if (! validateName($name)){
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please enter a valid name! Name only contains A-Za-z and space character</p>";
    } else {
        $sql = "SELECT * FROM user WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $error = true;
            $error_message = "<p style='color: red; text-align: center;'>Username or email already exists!</p>";
        } else {
            $stmt = $conn -> prepare("INSERT INTO user (username, name, email, password, phone_num, role_id) VALUES (?, ?, ?, ?, ?, NULL)");
            $stmt -> bind_param("sssss", $username, $name, $email, $password, $phone);
            $stmt -> execute();
            $_SESSION["userID"] = $stmt->insert_id; 
            header("Location: role-selection.php"); 
            $stmt->close(); 
            exit();
        }
    } 
} 
$conn->close(); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registration</title>
    <link rel="stylesheet" href="styles/registration.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="crm-logo-container">
            <img class="crm-logo" src="hp-reg-images/hp-logo.png">
        </div>
        <div class="instruction-container">
            <h2>Register a new account with your name, phone number and email to use the service</h2>
        </div>
        
        <form id="registrationForm" method="POST" action="registration.php">
            <div class="new-data-input">
                <input class="input-field" type="username" name="username" placeholder="Username" required>
                <input class="input-field" type="name" name="name" placeholder="Name" required>
                <input class="input-field" type="text" name="email" placeholder="Email" required>
                <input class="input-field" type="text" name="phone" placeholder="Phone Number" required>
                <input class="input-field" type="password" name="password" placeholder="Password" required>
            </div>
            <?php 
            if ($error) {
                echo $error_message;
            }
            ?>
            <div class="continue-container">
                <button type="submit" class="continue-button-inactive">Continue</button>
            </div>
        </form>
    </div>

    <script src="scripts/registration.js"></script>
</body>
</html>