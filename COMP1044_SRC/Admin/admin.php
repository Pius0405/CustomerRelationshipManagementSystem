<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: ../signin.php"); // Redirect to login if not logged in
    exit;
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

$sql = "SELECT user_id, role_id, username, name, email, phone_num, password FROM user"; // Removed password
$result = $conn->query($sql);

if (isset($_POST["userUpdateID"])) {
    $_SESSION["userUpdateID"] = $_POST["userUpdateID"];
    header("Location: update-user.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Admin Home</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../general/styles/style.css">
        <link rel="stylesheet" href="../general/styles/header.css">
        <link rel="stylesheet" href="../general/styles/table.css">
        <link rel="stylesheet" href="../general/styles/modal.css">
    </head>
    <body>

        <div class="header">

        </div>

        <div class="welcome">
            <p>
                Users
            </p>
        </div>

        <div class = "container">
            <div class="table-header">
                <div class="search-bar">
                    <input type="text" id="searchInput" placeholder="Search users" onkeyup="filterUsers()">
                    <img class="search-icon" src="../general/searchbar-images/search-icon.png">
                </div>
            </div>


            <table id="customerTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Role</th>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if ($result -> num_rows > 0) {
                        while($row = $result -> fetch_assoc()) {
                            $_SESSION["currentUserID"] = $row["user_id"];
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            if ($row["role_id"] == 1) {
                                echo "<td>Admin</td>";
                            } else {
                                echo "<td>Sales Representative</td>";
                            }
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['phone_num']) . "</td>";
                            if ($row["role_id"] == 1 && $_SESSION["userID"] != $row["user_id"]) {
                                echo "
                                    <td>
                                        <button class='viewButton restrictViewButton' onclick='showUpdateRestrictModal()'>Update</button>
                                    </td>";
                            } else {
                                echo "
                                    <td>
                                        <form action='admin.php' method='POST'>
                                            <button type='submit' name='userUpdateID' value='{$row['user_id']}'  class='viewButton'>Update</button>
                                        </form>
                                    </td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No users found</td></tr>";
                    }                
                    ?>
                </tbody>
            </table>

            <div class="table-actions">
                <a onclick="window.location.href = 'addnewuser.php';" class="addButton">Add</a>
            </div>
        </div>

        <div class="modal restrictUpdateModal">
            <p class="question">You cannot update other admins</p>
            <p class="warning">Admin users can only update themselves and all other sales representatives</p>
            <div class="modal-buttons-container">
                <button class="cancel-button">OK</button>
            </div>
        </div>

        <script src="../general/scripts/modal.js"></script>
        <script src="../general/scripts/header-admin.js"></script>
        <script src="../general/scripts/filter-table.js"></script>
        <script src="../general/scripts/sort-table.js"></script>
    </body>
</html>