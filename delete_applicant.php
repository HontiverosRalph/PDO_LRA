<?php
session_start();
require_once 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get the applicant ID from URL
if (isset($_GET['id'])) {
    $applicant_id = $_GET['id'];

    // Prepare and execute delete query
    $delete_stmt = $conn->prepare("DELETE FROM applicants WHERE id = ?");
    $delete_stmt->bind_param("i", $applicant_id);
    
    if ($delete_stmt->execute()) {
        // Log the activity
        $user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session
        $action = "deleted applicant";
        $target_table = "applicants";
        $details = "Deleted applicant ID: $applicant_id";
        $log_stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action, target_table, target_id, details) VALUES (?, ?, ?, ?, ?)");
        $log_stmt->bind_param("issis", $user_id, $action, $target_table, $applicant_id, $details);
        $log_stmt->execute();

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error deleting applicant.";
    }
} else {
    echo "No applicant ID provided.";
    exit();
}
?>

<a href="dashboard.php">Back to Dashboard</a>
