<?php
session_start();
$servername = "localhost";  
$username = "root";          
$password = "root";              
$dbname = "comp1044_database";  

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn -> connect_error){
    die("Connection failed: " . $conn -> connect_error);
}

if (!isset($_SESSION['userID'])) {
    header("Location: registration.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $role = $_POST["role"];
    if (!empty($role)) {
        $_SESSION["roleID"] = $role;
        $newUserID = $_SESSION["userID"];
        $stmt = $conn -> prepare("UPDATE user SET role_id = ? WHERE user_id = ?");
        $stmt->bind_param("ii", $role, $newUserID);
        $stmt->execute();
        header("Location: successful-register.php");
        exit();
    } else {
        header("Location: registration.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Role Selection</title>
    <link rel="stylesheet" href="styles/roleselection.css">
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
            <h2>Please select a role</h2>
        </div>

        <form method="POST" action="role-selection.php">
            <div class="new-data-input">
                <label for="role">Choose Role:</label>
                <select id="role" name="role" required>
                    <option value="" disabled selected hidden>Select Role</option>
                    <option value="1">Admin</option>
                    <option value="2">Sales Representative</option>
                </select>
            </div>
            <div class="continue-container">
                <button type="submit" class="continue-button">Continue</button>
            </div>
        </form>
    </div>
</body>
</html>

