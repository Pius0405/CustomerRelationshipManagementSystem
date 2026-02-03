<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: registration.php");
    exit();
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

$conn->close();

$role = $_SESSION["roleID"];
if ($role == 1) {
    $redirectURL = "Admin/dashboard.php";
} else {
    $redirectURL = "SalesRepresentative/dashboard.php";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>New User</title>
        <link rel="stylesheet" href="styles/successregister.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="crm-logo-container">
                <img class="crm-logo" src="hp-reg-images/hp-logo.png">
            </div>
            <div class="welcome-container">
                <h2 class="welcome-message">Welcome!</h2>
            </div>
            <div class="get-started-button-container">
                <button class="get-started-button", onclick="window.location.href = '<?php echo $redirectURL?>'">Get Started</button>
            </div>
        </div>
    </body>
</html>
