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

if ($conn -> connect_error){
    die("Connection failed: " . $conn -> connect_error);
}

$sql = "SELECT * FROM customer WHERE customer_id = " . $_SESSION["customerUpdateID"];
$row= $conn -> query($sql) -> fetch_assoc();

$error = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $company = trim($_POST['company']);

    if (! (validateNotEmpty($name) && validateNotEmpty($email) && validateNotEmpty($phone) && validateNotEmpty($company))) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please ensure that all fields are filled in!</p>";
    } else if (!validateEmail($email)) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please enter a valid email!</p>";
    } else if (!validatePhoneNumber($phone)) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please enter a valid phone number!</p>";
    }else if (! validateName($name)){
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please enter a valid name! Name only contains A-Za-z and space character</p>";
    } else {
        $stmt = $conn -> prepare("UPDATE customer SET name = ?, company = ?, email = ?, phone_num = ? WHERE customer_id = ?");
        $stmt -> bind_param("ssssi",$name, $company, $email, $phone, $_SESSION["customerUpdateID"]);
        $stmt -> execute();
        $stmt -> close();
        $conn -> close();
        header("Location: salesrep-hp.php");
        exit();
    }
}

$conn -> close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Update Customer</title>
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
            <a href="salesrep-hp.php">
                <img class="return-icon" src="../general/menu-images/return-icon.png">
            </a>
        </div>

        <div class="header">

        </div>

        <div class="add-user-form-container">
            <h2>Update Customer</h2>
            <form action="update-customer.php" method="POST">
                <?php 
                    echo "<input placeholder='Name' type='text' id='name' name='name' value='{$row['name']}'  required>";
        
                    echo "<input placeholder='Email' type='text' id='email' name='email' value='{$row['email']}' required>";
            
                    echo "<input placeholder='Phone Number' type='text' id='phone' name='phone' value='{$row['phone_num']}' required>";
    
                    echo "<input placeholder='Company' type='text' id='company' name='company' value='{$row['company']}' required>";

                    if($error) {
                        echo $error_message;
                    }
    
                    echo "<button class='add-user-button' type='submit'>Update Customer</button>";
                ?>
            </form>
        </div>

        <script src="../general/scripts/header-customer.js"></script>
    </body>
</html>