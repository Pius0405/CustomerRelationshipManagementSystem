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
    $description = trim($_POST['description']);

    if (! validateNotEmpty($description)) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Please ensure that all fields are filled in!</p>";
    } elseif (! validateLength($description, 255)) {
        $error = true;
        $error_message = "<p style='color: red; text-align: center;'>Maximum number of characters (255) exceeded!</p>";
    } else {
        if ($_SESSION["currentIntr"] === "c") {
            $stmt = $conn -> prepare("INSERT INTO customerinteraction (user_id, customer_id, interaction_type, description, date) VALUES(?, ?, ?, ?, ?)");
            $stmt -> bind_param("iisss", $_SESSION["userID"], $_SESSION["customerIntrID"], $_POST["interaction-type"], $description, $_POST["date"]);
            $stmt -> execute();
            $stmt -> close();
            $conn -> close();
            header("Location: cust-interaction.php");
        } else {
            $stmt = $conn -> prepare("INSERT INTO leadinteraction (user_id, lead_id, interaction_type, description, date) VALUES(?, ?, ?, ?, ?)");
            $stmt -> bind_param("iisss", $_SESSION["userID"], $_SESSION["leadIntrID"], $_POST["interaction-type"], $description, $_POST["date"]);
            $stmt -> execute();
            $stmt -> close();
            $conn -> close();
            header("Location: lead-interaction.php");
        }
        exit();
    }
}
$conn -> close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add New Interaction</title>
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
            <a href="<?php echo isset($_SESSION['intrBackTo']) ? $_SESSION['intrBackTo'] : 'dashboard.php'; ?>">
                <img class="return-icon" src="../general/menu-images/return-icon.png">
            </a>
        </div>

        <div class="header">

        </div>

        <div class="add-user-form-container">
            <h2>Add New interaction</h2>
            <form action="add-new-interaction.php" method="POST">

                <select name="interaction-type" required>
                    <option value="" disabled selected hidden>Select Interaction Type</option>
                    <option value="Pending">Pending</option>
                    <option value="Physical">Physical</option>
                    <option value="Virtual">Virtual</option>
                </select>
        
                <input placeholder="Description" type="text" id="description" name="description" required>

                <input type="date" name="date" required>

                <?php
                if ($error) {
                    echo $error_message;
                }
                ?>

                <button class="add-user-button" type="submit">Add New Interaction</button>
            </form>
        </div>

        <script src="../general/scripts/header-customer.js"></script>
    </body>
</html>