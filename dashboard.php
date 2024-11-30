<?php
session_start();
require_once 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle the search query
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

// Prepare the query to fetch applicants based on the search query
$sql = "SELECT * FROM applicants WHERE name LIKE ? OR email LIKE ? OR phone LIKE ? OR position_applied LIKE ?";
$stmt = $conn->prepare($sql);
$search_term = "%" . $search_query . "%"; // Add wildcard for LIKE search
$stmt->bind_param("ssss", $search_term, $search_term, $search_term, $search_term);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>

<h1>Dashboard</h1>

<!-- Search Form -->
<form method="post" action="dashboard.php">
    <input type="text" name="search" placeholder="Search applicants..." value="<?php echo htmlspecialchars($search_query); ?>">
    <button type="submit">Search</button>
</form>

<!-- Create Applicant Button -->
<a href="create_applicant.php"><button>Create Applicant</button></a>

<!-- Applicant Table -->
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Position Applied</th>
            <th>Actions</th>
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
                <td>
                    <a href="edit_applicant.php?id=<?php echo $row['id']; ?>">Edit</a> |
                    <a href="delete_applicant.php?id=<?php echo $row['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<!-- Back to Dashboard Link -->
<a href="index.php">Back to Home</a>
<a href="logout.php">Logout</a>
</body>
</html>
