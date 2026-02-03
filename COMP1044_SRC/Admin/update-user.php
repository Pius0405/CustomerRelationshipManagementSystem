<?php 
require_once '../data-validation.php';
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

if ($conn -> connect_error){
    die("Connection failed: " . $conn -> connect_error);
}

$sql = "SELECT * FROM user WHERE user_id = " . $_SESSION["userUpdateID"];
$row= $conn -> query($sql) -> fetch_assoc();

$error = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $role_id = trim($_POST["role_id"]);
    $username = trim($_POST["username"]);
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password = trim($_POST["password"]);

    if (! (validateNotEmpty($username) && validateNotEmpty($name) && validateNotEmpty($email) && validateNotEmpty($phone) && validateNotEmpty($password) && validateNotEmpty($role_id))) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please ensure that all fields are filled in!</p>";
    } elseif (!validateEmail($email)) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please enter a valid email!</p>";
    } elseif (!validatePhoneNumber($phone)) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please enter a valid phone number!</p>";
    } elseif (! checkUserExistsII($conn, $username, $email, $_SESSION["userUpdateID"])) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Username or email already exists!</p>";
    } else if (! validateName($name)){
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please enter a valid name! Name only contains A-Za-z and space character</p>";
    } else {
        $stmt = $conn -> prepare("UPDATE user SET role_id = ?, username = ?, name = ?, email = ?, phone_num = ?, password = ? WHERE user_id = ?");
        $stmt -> bind_param("isssssi",$role_id, $username, $name, $email, $phone, $password, $_SESSION["userUpdateID"]);
        $stmt -> execute();
        $stmt -> close();
        header("Location: admin.php");
        exit();
    }
}
$conn -> close();
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add New Customer</title>
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
            <a href="admin.php">
                <img class="return-icon" src="../general/menu-images/return-icon.png">
            </a>
        </div>

        <div class="header">

        </div>

        <div class="add-user-form-container">
            <h2>Update User</h2>
            <form action="update-user.php" method="POST">
                <?php 
                    $currentRole = $row["role_id"];
                    echo "<input placeholder='Username' type='text' id='username' name='username' value='{$row['username']}'  required>";

                    echo "<input placeholder='Name' type='text' id='name' name='name' value='{$row['name']}'  required>";
        
                    echo "<input placeholder='Email' type='text' id='email' name='email' value='{$row['email']}' required>";
            
                    echo "<input placeholder='Phone Number' type='text' id='phone' name='phone' value='{$row['phone_num']}' required>";

                    echo "<input placeholder='Password' type='text' id='password' name='password' value='{$row['password']}' required>";

                    echo "
                        <select id='role' name='role_id' required>
                            <option value='1' ".($currentRole == 1 ? 'selected' : '').">Admin</option>
                            <option value='2' ".($currentRole == 2 ? 'selected' : '').">Sales Representative</option>
                        </select>
                        ";
                    if ($error) {
                        echo $error_message;
                    }
                    echo "<button class='add-user-button' type='submit'>Update User</button>";
                ?>
            </form>
        </div>

        <script src="../general/scripts/header-admin.js"></script>
    </body>
</html>