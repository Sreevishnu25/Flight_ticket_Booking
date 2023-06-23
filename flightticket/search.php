<?php
session_start(); // Start the session

// Handle flight search
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Perform flight search based on date and time
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

        // Prepare the SQL statement to search for flights
        $stmt = $conn->prepare("SELECT * FROM flights WHERE departure_date = :date AND departure_time = :time");
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->execute();

        // Fetch all rows as an associative array
        $flights = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Display search results
        if (count($flights) > 0) {
            echo "<h2>Search Results:</h2>";
            foreach ($flights as $flight) {
                echo "Flight Number: " . $flight['flight_number'] . "<br>";
                echo "Departure Date: " . $flight['departure_date'] . "<br>";
                echo "Departure Time: " . $flight['departure_time'] . "<br>";
                // Add a booking option
                echo "<form method='POST' action='booking.php'>";
                echo "<input type='hidden' name='flight_number' value='" . $flight['flight_number'] . "'>";
                echo "<input type='hidden' name='departure_date' value='" . $flight['departure_date'] . "'>";
                echo "<input type='hidden' name='departure_time' value='" . $flight['departure_time'] . "'>";
                echo "<button type='submit'>Book</button>";
                echo "</form>";
                echo "<br>";
            }
        } else {
            echo "<p>No flights found for the specified date and time.</p>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $conn = null;
}
?>
