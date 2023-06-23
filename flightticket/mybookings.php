<?php
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

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM flights");
    $stmt->execute();

    // Fetch all rows as an associative array
    $flights = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Process and display the data
    foreach ($flights as $flight) {
        // Access the flight data using $flight['column_name']
        echo "Flight Number: " . $flight['flight_number'] . "<br>";
        echo "Departure Date: " . $flight['departure_date'] . "<br>";
        echo "Departure Time: " . $flight['departure_time'] . "<br>";
        // ...
        echo "<br>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>


<!DOCTYPE html>
<html>
<head>
    <title>Flight Ticket Booking - My Bookings</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>My Bookings</h1>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Flight Number</th>
                    <th>Departure Date</th>
                    <th>Departure Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking) { ?>
                <tr>
                    <td><?php echo $booking['id']; ?></td>
                    <td><?php echo $booking['flight_number']; ?></td>
                    <td><?php echo $booking['departure_date']; ?></td>
                    <td><?php echo $booking['departure_time']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
