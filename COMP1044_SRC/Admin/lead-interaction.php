<?php
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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn -> prepare("SELECT * FROM leadinteraction WHERE lead_id = ?");
$stmt -> bind_param("i", $_SESSION["leadIntrID"]);
$stmt -> execute();
$result = $stmt -> get_result();
$conn -> close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Lead Interaction</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../general/styles/style.css">
        <link rel="stylesheet" href="../general/styles/header.css">
        <link rel="stylesheet" href="../general/styles/table.css">
        <link rel="stylesheet" href="../general/styles/back-button.css">
    </head>
    <body>
        <div class="back-button">
            <a href="all-leads.php">
                <img class="return-icon" src="../general/menu-images/return-icon.png">
            </a>
        </div>

        <div class="header">

        </div>

        <div class="welcome">
            <p>
                Lead Interactions
            </p>
        </div>

        <div class = "container">
            <div class="table-header">
                <div class="search-bar">
                    <input type="text" id="searchInput" placeholder="Search interactions" onkeyup="filterInteractions()">
                    <img class="search-icon" src="../general/searchbar-images/search-icon.png">
                </div>
            </div>


            <table id="customerTable">
                <thead>
                    <tr>
                        <th>
                            No
                        </th>
                        <th>
                            Interaction type
                        </th>
                        <th>
                            Description
                        </th>
                        <th>
                            Date
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result -> num_rows > 0) {
                        $no = 1;
                        while ($row = $result -> fetch_assoc()) {
                            echo"<tr>" ;
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['interaction_type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No interactions found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <script src="../general/scripts/header-admin.js"></script>
        <script src="../general/scripts/filter-table.js"></script>
        <script src="../general/scripts/sort-table.js"></script>            
    </body>
</html>