<?php
// Handle user login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    header("Location: dashboard.php");
    // Perform authentication (you need to implement this logic)
    // For example, you can check the credentials against the database
    $servername = "localhost";
    $dbname = "flight";
    $dbusername = "root";
    $dbpassword = "";

    try {
        // Create a new PDO instance
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL statement to fetch user data based on the username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch the user record
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if ($user && password_verify($password, $user['password'])) {
            // Successful login
            // Set user session or other login actions
            // ...

            // Redirect to user dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Invalid login credentials
            $error = "Invalid username or password";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $conn = null;
}
?>