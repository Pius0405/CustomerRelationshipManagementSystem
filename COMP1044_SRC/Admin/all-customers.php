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

$sql = "SELECT * FROM customer"; 
$result = $conn->query($sql);

if (isset($_POST["customerIntrID"])) {
    $_SESSION["customerIntrID"] = $_POST["customerIntrID"];
    header("Location: cust-interaction.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Customers</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../general/styles/header.css">
        <link rel="stylesheet" href="../general/styles/style.css">
        <link rel="stylesheet" href="../general/styles/table.css">
        <link rel="stylesheet" href="../general/styles/back-button.css">
        <link rel="stylesheet" href="../general/styles/modal.css">
    </head>
    <body>

        <div class="header">

        </div>

        <div class="welcome">
            <p>
                Customers
            </p>
        </div>

        <div class = "container">
            <div class="table-header">
                <div class="search-bar">
                    <input type="text" id="searchInput" placeholder="Search customers" onkeyup="filterCustomers()">
                    <img class="search-icon" src="../general/searchbar-images/search-icon.png">
                </div>
            </div>


            <table id="customerTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Interactions</th>
                        <th>Managed By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result -> num_rows > 0) {
                        $no = 1;
                        while ($row = $result -> fetch_assoc()) {
                            echo"<tr>" ;
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['company']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['phone_num']) . "</td>";
                            echo "
                                <td>
                                    <form action='all-customers.php' method='POST'>
                                        <button type='submit' name='customerIntrID' value='{$row['customer_id']}'  class='viewButton'>View</button>
                                    </form>
                                </td>";
                            echo "
                                <td>
                                    <button class='viewButton' onclick='showUserInfoModal(\"{$row['user_id']}\")'>View</button>
                                </td>";
                            echo"</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No customers found</td></tr>";
                    } 
                    ?> 
                </tbody>
            </table>
        </div>

        <div class="modal userInfoModal">
            <div class="user-info-block"></div>
            <div class="modal-buttons-container">
                <button class="cancel-button">Close</button>
            </div>
        </div>

        <script src="../general/scripts/header-admin.js"></script>
        <script src="../general/scripts/filter-table.js"></script>
        <script src="../general/scripts/modal.js"></script>
        <script src="../general/scripts/sort-table.js"></script>
    </body>
</html>