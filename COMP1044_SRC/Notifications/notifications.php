<?php 

include 'notifications-utility.php';

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
$userID = $_SESSION["userID"];

$totalUnreadNotifications = getNotificationCount($conn, $userID, "customerreminder") + getNotificationCount($conn, $userID, "leadreminder");

$customerNotifications = getNotifications($conn, $userID, "customerreminder");
$leadNotifications = getNotifications($conn, $userID, "leadreminder");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $colname = $_POST["table"] === "customerreminder" ? "customer_reminder_id" : "lead_reminder_id";
    $sql = "UPDATE " . $_POST["table"] . " SET status = 0 WHERE " . $colname . " = " . $_POST["reminder_id"];
    $conn -> query($sql);
    header("Location: notifications.php"); 
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Notifications</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../general/styles/header.css">
        <link rel="stylesheet" href="../general/styles/style.css">
        <link rel="stylesheet" href="../general/styles/table.css">
        <link rel="stylesheet" href="../general/styles/back-button.css">
        <link rel="stylesheet" href="styles/notifications.css">
    </head>
    <body>
        <div class="back-button">
            <a href="../SalesRepresentative/<?php echo isset($_SESSION['previous_page']) ? $_SESSION['previous_page'] : 'dashboard.php'; ?>">
                <img class="return-icon" src="../general/menu-images/return-icon.png">
            </a>
        </div>


        <div class="header">

        </div>

        <div class="notification-center-banner  welcome">
            <h1>Notification Center</h1>
        </div>

        <div class="unread-notifications-banner">
            <?php
                if ($totalUnreadNotifications > 0) {
                    echo "<p>You have $totalUnreadNotifications unread notifications</p>";
                } else {
                    echo "<p>No new notifications</p>";
                }
            ?>
        </div>

        <div class="notifications-container">
            <?php 
                if ($customerNotifications -> num_rows > 0) {
                    $no = 1;
                    while ($row = $customerNotifications -> fetch_assoc()) {                 
                            echo "
                            <div class ='notification-wrapper'>
                                <div class='notification-block'>
                                    <div class='top-section'>
                                        <p>" . getName($conn, $row['customer_id'], 'customer', 'customer_id') . "</p>
                                        <p class='date'>" . $row['date'] . "</p>
                                    </div>
                                    <p>" . $row['reminder'] . "</p>
                                </div>
                                <form method='POST' action='notifications.php'>
                                <input type='hidden' name='table' value='customerreminder'>
                                <button type='submit' name='reminder_id' value=" . $row['customer_reminder_id'] . " class='mark-as-read-button'>Mark as read</button>
                                </form>
                                </div>";
                    }
                }

                if ($leadNotifications -> num_rows > 0) {
                    $no = 1;
                    while ($row = $leadNotifications -> fetch_assoc()) {                 
                            echo "
                            <div class ='notification-wrapper'>
                                <div class='notification-block'>
                                    <div class='top-section'>
                                        <p>" . getName($conn, $row['lead_id'], 'lead', 'lead_id') . "</p>
                                        <p class='date'>" . $row['date'] . "</p>
                                    </div>
                                    <p>" . $row['reminder'] . "</p>
                                </div>
                                <form method='POST' action='notifications.php'>
                                <input type='hidden' name='table' value='leadreminder'>
                                <button type='submit' name='reminder_id' value=" . $row['lead_reminder_id'] . " class='mark-as-read-button'>Mark as read</button>
                                </form>
                                </div>";
                    }
                }
            ?>
        </div>


        <div class="notifications-info-banner">
            <?php 
                if ($totalUnreadNotifications > 0) {
                    echo "<p>Only notifications within the current week will be shown</p>";
                }
            ?>
        </div>

        <script src="../general/scripts/header-notifications.js"></script>
        <script src="scripts/notifications.js"></script>
    </body>
</html>

<?php 
$conn -> close();
?>