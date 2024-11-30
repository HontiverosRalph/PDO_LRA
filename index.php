<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Display a welcome message and a logout link
    echo "<h1>Welcome, " . $_SESSION['username'] . "!</h1>";
    echo "<p>You are logged in.</p>";
    echo "<a href='dashboard.php'>Go to Dashboard</a><br>";
    echo "<a href='logout.php'>Logout</a>";
} else {
    // If the user is not logged in, show a login link
    echo "<h1>Welcome to the Job Application System</h1>";
    echo "<p>You are not logged in. Please login to continue.</p>";
    echo "<a href='login.php'>Login</a><br>";
    echo "<a href='registration.php'>Register</a>";
}
?>
