<?php 
session_start();
$_SESSION['previous_page'] = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['userID'])) {
    header("Location: ../signin.php"); // Redirect to login if not logged in
    exit;
}

date_default_timezone_set('Asia/Kuala_Lumpur'); 

$servername = "localhost";  
$username = "root";          
$password = "root";              
$dbname = "comp1044_database";  

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn -> connect_error){
    die("Connection failed: " . $conn -> connect_error);
}

$dateToday = date('l, F d, Y');

$stmt = $conn->prepare("SELECT name FROM user WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION["userID"]);
$stmt->execute();
$result = $stmt->get_result();
$name = $result->fetch_assoc()["name"];

$cust_result = $conn->query("SELECT COUNT(*) AS count FROM customer WHERE user_id = " . $_SESSION["userID"]);
$lead_result = $conn->query("SELECT COUNT(*) AS count FROM lead WHERE user_id = " . $_SESSION["userID"]);
$cust_count = $cust_result->fetch_assoc()["count"];
$lead_count = $lead_result->fetch_assoc()["count"];
$user_count += $cust_count + $lead_count;


// here onwards is the code for the calendar
$customerReminders = $conn->prepare("
    SELECT cr.date, cr.reminder, c.name, c.customer_id 
    FROM customerreminder cr
    JOIN customer c ON cr.customer_id = c.customer_id 
    WHERE cr.user_id = ?");
$customerReminders->bind_param("i", $_SESSION["userID"]);
$customerReminders->execute();
$customerResult = $customerReminders->get_result();


$leadReminders = $conn->prepare("
    SELECT lr.date, lr.reminder, l.name, l.lead_id
    FROM leadreminder lr
    JOIN lead l ON lr.lead_id = l.lead_id 
    WHERE lr.user_id = ?");
$leadReminders->bind_param("i", $_SESSION["userID"]);
$leadReminders->execute();
$leadResult = $leadReminders->get_result();


$reminders = [];
while($row = $customerResult->fetch_assoc()) {
    $date = date('Y-m-d', strtotime($row['date']));
    if(!isset($reminders[$date])) {
        $reminders[$date] = [];
    }
    $reminders[$date][] = [
        'type' => 'customer',
        'name' => $row['name'],
        'reminder' => $row['reminder'],
        'id' => $row['customer_id']
    ];
}
while($row = $leadResult->fetch_assoc()) {
    $date = date('Y-m-d', strtotime($row['date']));
    if(!isset($reminders[$date])) {
        $reminders[$date] = [];
    }
    $reminders[$date][] = [
        'type' => 'lead',
        'name' => $row['name'],
        'reminder' => $row['reminder'],
        'id' => $row['lead_id']
    ];
}

$remindersJSON = json_encode($reminders);



$recentInteractions = $conn->prepare("
    (SELECT 
        c.name,
        'customer' as type,
        ci.interaction_type,
        ci.description,
        ci.date
    FROM customerinteraction ci
    JOIN customer c ON ci.customer_id = c.customer_id
    WHERE ci.user_id = ?)
    UNION ALL
    (SELECT 
        l.name,
        'lead' as type,
        li.interaction_type,
        li.description,
        li.date
    FROM leadinteraction li
    JOIN lead l ON li.lead_id = l.lead_id
    WHERE li.user_id = ?)
    ORDER BY date DESC");

$recentInteractions->bind_param("ii", $_SESSION["userID"], $_SESSION["userID"]);
$recentInteractions->execute();
$interactionsResult = $recentInteractions->get_result();

$totalInteractions = $interactionsResult->num_rows;

$conn -> close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../general/styles/style.css">
        <link rel="stylesheet" href="../general/styles/header.css">
        <link rel="stylesheet" href="../general/styles/table.css">
        <link rel="stylesheet" href="../general/styles/modal.css">
        <link rel="stylesheet" href="../general/styles/dashboard.css">
        <link rel="stylesheet" href="../general/styles/calender.css">
    </head>
    <body>
        <div class="header">
           
        </div>

        <div class="welcome-user-animation welcome">
            <p>
                Welcome, <?php echo $name; ?>!
            </p>
        </div>

        <div class="date-banner">
            <?php 
                echo "<p>" . $dateToday . "</p>";
            ?>
        </div>

        <div class="dashboard-grid-container">
            <div class="dashboard-left-column">
                <div class="left-column-container">
                    <div class="dashboard-block-container">
                        <div class="total-user-block">
                            <div class="people-icon-container">
                                <img class="people-icon" src="../general/dashboard-images/people-icon.png">
                            </div>
                            <p class="total-user-title">Total Users Managed</p>
                            <?php echo "<p class='user-count'>" . $user_count . "</p>"; ?>
                        </div>

                        <div class="total-user-block" onclick="window.location.href='salesrep-hp.php'">
                            <div class="people-icon-container">
                                <img class="people-icon" src="../general/dashboard-images/people-icon.png">
                            </div>
                            <p class="total-user-title">Total Customers Managed</p>
                            <?php echo "<p class='user-count'>" . $cust_count . "</p>"; ?>
                        </div>

                        <div class="total-user-block" onclick="window.location.href='salesrep-lead.php'">
                            <div class="people-icon-container">
                                <img class="people-icon" src="../general/dashboard-images/people-icon.png">
                            </div>
                            <p class="total-user-title">Total Leads Managed</p>
                            <?php echo "<p class='user-count'>" . $lead_count . "</p>"; ?>
                        </div>
                    </div>

                    <div class="calendar-container">
                        <div class="calendar-header">
                            <button id="prev-month" onclick="changeMonth(-1)">&#8249;</button>
                            <h2 id="month-year"></h2>
                            <button id="next-month" onclick="changeMonth(1)">&#8250;</button>
                        </div>
                        <table class="calendar">
                            <thead>
                                <tr>
                                    <th>Sun</th>
                                    <th>Mon</th>
                                    <th>Tue</th>
                                    <th>Wed</th>
                                    <th>Thu</th>
                                    <th>Fri</th>
                                    <th>Sat</th>
                                </tr>
                            </thead>
                            <tbody id="calendar-body">
                                <!-- Calendar days will be dynamically generated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="dashboard-right-column">
                <div class="recent-interaction-block">
                    <p class="recent-interaction-header">Recent Interactions</p>
                    <div class="recent-interactions-container">
                        <?php 
                        if ($totalInteractions > 0) {
                            while($row = $interactionsResult->fetch_assoc()) {
                                $words = explode(' ', $row['description']);
                                $description = count($words) > 8 ? implode(' ', array_slice($words, 0, 8)) . '...' : $row['description'];
                                $date = date('d M Y', strtotime($row['date']));
                                
                                echo "<div class='interaction-entry'>
                                        <div class='interaction-header'>
                                            <span class='name'>{$row['name']} ({$row['type']})</span>
                                            <span class='date'>$date</span>
                                        </div>
                                        <div class='interaction-type'>{$row['interaction_type']}</div>
                                        <div class='interaction-desc'>$description</div>
                                    </div>";
                            }
                        } else {
                            echo "<div class='no-interactions'>
                                    <p>No interactions found</p>
                                </div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="sign-in-info-block">  
            <p>You are signed in as sales representative</p>
        </div>

        <div class="modal calendar-modal">
            <div class="modal-content">
                <h2 class="modal-date"></h2>
                <div class="modal-reminders"></div>
            </div>
            <div class="modal-buttons-container">
                    <button class="cancel-button">Close</button>
            </div>
        </div>

        <script>
            const userReminders = <?php echo $remindersJSON; ?>;
        </script>


        <script src="../general/scripts/header-customer.js"></script>
        <script src="../general/scripts/modal.js"></script>
        <script src="../general/scripts/filter-table.js"></script>
        <script src="../general/scripts/dashboard.js"></script>
        <script src="../general/scripts/calender.js"></script>
    </body>
</html>