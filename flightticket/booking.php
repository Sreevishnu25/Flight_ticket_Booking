<?php
session_start(); // Start the session
include("config.php");
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // User is not logged in, redirect to login page
    header("Location: index.html");
    exit();
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the flight details from the form
    $flightNumber = $_POST['flight_number'];
    $departureDate = $_POST['departure_date'];
    $departureTime = $_POST['departure_time'];

    // Insert the booking details into the database
    // Database connection parameters
    $servername = "localhost";
    $dbname = "flight";
    $dbusername = "root";
    $dbpassword = "";

    try {
        // Create a new PDO instance
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL statement to insert the booking
        $stmt = $conn->prepare("INSERT INTO bookings (username, flight_number, departure_date, departure_time) VALUES (:username, :flight_number, :departure_date, :departure_time)");
        $stmt->bindParam(':username', $_SESSION['username']);
        $stmt->bindParam(':flight_number', $flightNumber);
        $stmt->bindParam(':departure_date', $departureDate);
        $stmt->bindParam(':departure_time', $departureTime);
        $stmt->execute();

        // Display success message
        $message = "Booking successfully added.";

        // Redirect to the bookings page
        header("Location: mybookings.php?message=" . urlencode($message));
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $conn = null;
}
?>
