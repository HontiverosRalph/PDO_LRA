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
    // Fetch the current details of the applicant
    $stmt = $conn->prepare("SELECT * FROM applicants WHERE id = ?");
    $stmt->bind_param("i", $applicant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $applicant = $result->fetch_assoc();

    if (!$applicant) {
        echo "Applicant not found.";
        exit();
    }
} else {
    echo "No applicant ID provided.";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position_applied'];

    // Prepare and execute update query
    $update_stmt = $conn->prepare("UPDATE applicants SET name = ?, email = ?, phone = ?, position_applied = ? WHERE id = ?");
    $update_stmt->bind_param("ssssi", $name, $email, $phone, $position, $applicant_id);
    
    if ($update_stmt->execute()) {
        // Log the activity
        $user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session
        $action = "updated applicant";
        $target_table = "applicants";
        $details = "Updated applicant ID: $applicant_id, New Position: $position";
        $log_stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action, target_table, target_id, details) VALUES (?, ?, ?, ?, ?)");
        $log_stmt->bind_param("issis", $user_id, $action, $target_table, $applicant_id, $details);
        $log_stmt->execute();

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating applicant.";
    }
}
?>

<h1>Edit Applicant</h1>
<form method="post">
    <label>Name: </label><input type="text" name="name" value="<?php echo htmlspecialchars($applicant['name']); ?>" required><br>
    <label>Email: </label><input type="email" name="email" value="<?php echo htmlspecialchars($applicant['email']); ?>" required><br>
    <label>Phone: </label><input type="text" name="phone" value="<?php echo htmlspecialchars($applicant['phone']); ?>"><br>
    <label>Position Applied: </label><input type="text" name="position_applied" value="<?php echo htmlspecialchars($applicant['position_applied']); ?>" required><br>
    <input type="submit" value="Update Applicant">
</form>

<a href="dashboard.php">Back to Dashboard</a>
