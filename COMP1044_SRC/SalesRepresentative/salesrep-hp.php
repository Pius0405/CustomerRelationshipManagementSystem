<?php
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

$sql = "SELECT * FROM customer WHERE user_id = ?";
$stmt = $conn -> prepare($sql);
$stmt -> bind_param("i", $_SESSION["userID"]);
$stmt -> execute();
$result = $stmt -> get_result();

if (isset($_POST["customerIntrID"])) {
    $_SESSION["customerIntrID"] = $_POST["customerIntrID"];
    header("Location: cust-interaction.php");
    exit();
}

if (isset($_POST["customerUpdateID"])) {
    $_SESSION["customerUpdateID"] = $_POST["customerUpdateID"];
    header("Location: update-customer.php");
    exit();
}

if (isset($_POST["customerRemID"])) {
    $_SESSION["customerRemID"] = $_POST["customerRemID"];
    $_SESSION["currentReminder"] = "c"; 
    header("Location: add-new-reminder.php");
    exit();
}
$conn -> close();
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Customer Management</title>
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
                        <th>Full Profile</th>
                        <th>Delete</th>
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
                                    <button class='viewButton' onclick='showCustomerProfileModal(" . $_SESSION['userID'] . ", " . $row['customer_id'] . ")'>View</button>
                                </td>";
                            echo "
                                <td>
                                    <button class='viewButton' onclick='showModal(\"customer\", " . $row['customer_id'] . ")'>Delete</button>
                                </td>";
                            echo"</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No customers found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="table-actions">
                <a class="addButton" href="add-new-cust.php">Add</a>
            </div>
        </div>

        <div class="customer-profile-modal">
        </div>
        

        <div class="modal">
            <p class="question">Do you want to delete this customer?</p>
            <p class="warning">You cannot undo this action</p>
            <div class="modal-buttons-container">
                <button class="cancel-button">Cancel</button>
                <button class="delete-button">Delete</button> 
            </div>
        </div>

        <script src="../general/scripts/header-customer.js"></script>
        <script src="../general/scripts/modal.js"></script>
        <script src="../general/scripts/filter-table.js"></script>
        <script src="../general/scripts/sort-table.js"></script>
    </body>
</html>