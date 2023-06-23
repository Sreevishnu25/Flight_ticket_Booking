<?php
session_start(); // Start the session

// Check if the admin is already logged in
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    // Admin is already logged in, redirect to admin page
    header("Location: admin.php");
    exit();
}

// Handle admin login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check admin credentials (you can customize this part)
    if ($username === 'admin' && $password === 'admin123') {
        // Admin credentials are correct, set admin session
        $_SESSION['admin'] = true;
        // Redirect to admin page
        header("Location: admin.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Flight Ticket Booking - Admin Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Admin Login</h1>

        <?php if (isset($error)) { ?>
            <p><?php echo $error; ?></p>
        <?php } ?>

        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
