<?php
session_start(); 

if (!isset($_SESSION['username'])) {
    header("Location: signin.php"); 
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <p>You are logged in.</p>
</body>
<a href="logout.php">Logout</a>
</html>
