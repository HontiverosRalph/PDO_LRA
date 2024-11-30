<?php
session_start();
require_once 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle search request
if (isset($_POST['search'])) {
    $search_term = $_POST['search_term'];

    // Prepare and execute search query
    $search_stmt = $conn->prepare("SELECT * FROM applicants WHERE name LIKE ? OR email LIKE ? OR position_applied LIKE ?");
    $search_param = "%" . $search_term . "%";
    $search_stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $search_stmt->execute();
    $result = $search_stmt->get_result();

    // Log the search activity
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session
    $action = "searched applicants";
    $target_table = "applicants";
    $details = "Search term: $search_term";
    $log_stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action, target_table, details) VALUES (?, ?, ?, ?)");
    $log_stmt->bind_param("isss", $user_id, $action, $target_table, $details);
    $log_stmt->execute();
}
?>

<h1>Search Applicants</h1>
<form method="post">
    <label>Search: </label><input type="text" name="search_term" required><br>
    <input type="submit" name="search" value="Search">
</form>

<?php if (isset($result) && $result->num_rows > 0) { ?>
    <h2>Search Results</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Position Applied</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['position_applied']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <p>No applicants found.</p>
<?php } ?>

<a href="dashboard.php">Back to Dashboard</a>
