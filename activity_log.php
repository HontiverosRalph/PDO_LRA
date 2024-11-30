<?php
require_once 'db_connection.php';

function logActivity($username, $action) {
    global $conn;
    $date = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO activity_logs (username, action, date_added) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $username, $action, $date);
    $stmt->execute();
}
?>
