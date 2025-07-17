<?php
// Connect to MySQL
include 'db.php';
// not recommended 

// Create database if not exists
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $dbname");
mysqli_select_db($conn, $dbname);

// Create users table
$table_query = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL,
        password VARCHAR(255) NOT NULL
    )
";
mysqli_query($conn, $table_query);

$errors = [];
$name = "";
$raw_pass = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim(htmlspecialchars($_POST["username"] ?? ""));
    $raw_pass = trim($_POST["pass"] ?? "");

    // Validation
    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (strlen($name) < 3) {
        $errors[] = "Minimum 3 characters required for name.";
    }

    if (empty($raw_pass)) {
        $errors[] = "Password is required.";
    } elseif (strlen($raw_pass) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // Insert if no errors
    if (empty($errors)) {
        $pass = password_hash($raw_pass, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $name, $pass);

        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Account created successfully!";
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2 class="Headtext">Create Account</h2>
    <div class="formbox">
        <?php if (!empty($success_message)): ?>
            <p style="color: green;"><?= $success_message ?></p>
            <a href="signin.php">Sign In</a>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <h3>There were errors:</h3>
            <ul style="color: red;">
                <?php foreach ($errors as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form class="formms" method="POST">
            <label class="namebox">
                Name: <input type="text" name="username" class="name" value="<?= htmlspecialchars($name) ?>">
            </label><br>
            <label class="passbox">
                Password: <input type="password" name="pass" class="text">
            </label><br>
            <button class="btn" type="submit">Submit</button>
        </form>
    </div>
</body>
</html>