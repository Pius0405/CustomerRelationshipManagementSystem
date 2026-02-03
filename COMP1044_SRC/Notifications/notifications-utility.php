<?php 
function getNotificationCount($conn, $userID, $table) {
    $sql = "SELECT COUNT(*) FROM " . $table . " WHERE user_id = " . $userID . " AND status = 1 AND date >= CURDATE() AND date <= CURDATE() + INTERVAL 6 DAY";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_row();
        return $row[0];  
    }
    return 0;  
}

function getNotifications($conn, $userID, $table) {
    $sql = "SELECT * FROM " . $table . " WHERE user_id = " . $userID . " AND status = 1 AND date >= CURDATE() AND date <= CURDATE() + INTERVAL 6 DAY ORDER by date";
    $result = $conn->query($sql);
    return $result;
}

function getName($conn, $id, $table, $colname) {
    $sql = "SELECT name FROM " . $table . " WHERE " . $colname . " = " . $id;
    $result = $conn->query($sql);
    
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['name'];
    }
    return null;
}
?>