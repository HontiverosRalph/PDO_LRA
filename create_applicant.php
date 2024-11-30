<?php
session_start();
require_once 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position_applied'];

    // Prepare and execute insert query
    $stmt = $conn->prepare("INSERT INTO applicants (name, email, phone, position_applied) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $position);

    if ($stmt->execute()) {
        // Get the insert ID immediately after the execution
        $inserted_id = $conn->insert_id;

        // Log the activity
        $user_id = $_SESSION['user_id']; // Get the user ID from the session
        $action = "created applicant";
        $target_table = "applicants";
        $details = "Created applicant: $name, Position: $position";
        $log_stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action, target_table, target_id, details) VALUES (?, ?, ?, ?, ?)");
        $log_stmt->bind_param("issis", $user_id, $action, $target_table, $inserted_id, $details);
        $log_stmt->execute();

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error creating applicant.";
    }
}
?>

<h1>Create New Applicant</h1>
<form method="post">
    <label>Name: </label><input type="text" name="name" required><br>
    <label>Email: </label><input type="email" name="email" required><br>
    <label>Phone: </label><input type="text" name="phone"><br>
    <label>Position Applied: </label><input type="text" name="position_applied" required><br>
    <input type="submit" value="Create Applicant">
</form>

<a href="dashboard.php">Back to Dashboard</a>
