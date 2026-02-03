<?php
$servername = "localhost";  
$username = "root";          
$password = "root";              
$dbname = "comp1044_database";  

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn -> connect_error){
    die("Connection failed: " . $conn -> connect_error);
} else {
    session_start();
}

$error = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $stmt = $conn -> prepare("SELECT user.user_id, user.username, user.password, role.role_id FROM user INNER JOIN role ON user.role_id = role.role_id where user.username = ? and user.password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt -> get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $_SESSION["userID"] = $row["user_id"];
        if ($row["role_id"] === 1) {
            header("Location: Admin/dashboard.php");
        } else {
            header("Location: SalesRepresentative/dashboard.php");
        }
        exit();
    } else {
        $error = true;
    }
    $stmt -> close();
    $conn -> close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>CRM Sign In</title>
    <link rel="stylesheet" href="styles/hp.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="homepage-logo-container">
            <img class="homepage-logo" src="hp-reg-images/hp-logo.png">
        </div>
        
        <div class="sign-in-container">
            <form id="signinForm" method="POST" action="signin.php">
                <input type="text" name="username" id="userName" placeholder="Username" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <button type="submit">Sign in</button>
            </form>
        </div>
        <?php 
        if ($error) {
            echo "<p style='color : red; text-align: center;'>Invalid username or password!</p>";
        }
        ?>
        <div class="create-account">
            <p>Don't have an account? <a class="create-account-link" href="registration.php">Create yours now</a></p>
        </div>
    </div>

    <script src="general/scripts/exit-control.js"></script>
</body>
</html>


