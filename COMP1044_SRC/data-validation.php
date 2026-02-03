<?php 
function validateNotEmpty($input) {
    return !empty($input);
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhoneNumber($phone) {
    if (!preg_match('/^[0-9]+$/', $phone)) {
        return false;
    }

    if (strlen($phone) !== 10 && strlen($phone) !== 11) {
        return false;
    }
    
    if (substr($phone, 0, 2) !== '01') {
        return false;
    }

    return true;
}

function validateLength($input, $maxLength) {
    return strlen($input) <= $maxLength;
}

function checkUserExists($conn, $username, $email) {
    $stmt = $conn->prepare("SELECT username, email FROM user WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return false;
    }
    return true;
}

function checkUserExistsII($conn, $username, $email, $user_id) {
    $stmt = $conn->prepare("SELECT username, email FROM user WHERE (username = ? OR email = ?) AND user_id != ?");
    $stmt->bind_param("ssi", $username, $email, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return false;
    }
    return true;
}

function validateUsername($username) {
    return preg_match('/^[A-Za-z0-9_]+$/', $username) && strlen($username) <= 50;
}

function validateName($name) {
    return preg_match('/^[A-Za-z ]+$/', $name) && strlen($name) <= 50;
}
?>