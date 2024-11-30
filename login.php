<?php
session_start();
require_once 'db_connection.php';

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check credentials
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Store user_id and username in session
        $_SESSION['user_id'] = $user['id']; // Assuming 'id' is the column storing the user ID
        $_SESSION['username'] = $user['username'];

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>

<form method="POST">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>

<a href="index.php">Back to Home</a>
