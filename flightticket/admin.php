<?php
session_start(); // Start the session

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    // User is not logged in as an admin, redirect to login page
    header("Location: adminlogin.php");
    exit();
}

// Database connection parameters
$servername = "localhost";
$dbname = "flight";
$dbusername = "root";
$dbpassword = "";

// Handle actions based on the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add Booking
    if (isset($_POST['add_booking'])) {
        $username = $_POST['username'];
        $flightNumber = $_POST['flight_number'];
        $departureDate = $_POST['departure_date'];
        $departureTime = $_POST['departure_time'];

        try {
            // Create a new PDO instance
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
            // Set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare the SQL statement to insert the booking
            $stmt = $conn->prepare("INSERT INTO bookings (username, flight_number, departure_date, departure_time) VALUES (:username, :flight_number, :departure_date, :departure_time)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':flight_number', $flightNumber);
            $stmt->bindParam(':departure_date', $departureDate);
            $stmt->bindParam(':departure_time', $departureTime);
            $stmt->execute();

            $message = "Booking successfully added.";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }

        // Close the database connection
        $conn = null;
    }

    // Delete Booking
    if (isset($_POST['delete_booking'])) {
        $bookingId = $_POST['booking_id'];

        try {
            // Create a new PDO instance
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
            // Set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare the SQL statement to delete the booking
            $stmt = $conn->prepare("DELETE FROM bookings WHERE id = :id");
            $stmt->bindParam(':id', $bookingId);
            $stmt->execute();

            $message = "Booking successfully deleted.";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }

        // Close the database connection
        $conn = null;
    }

    // Accept Booking
    if (isset($_POST['accept_booking'])) {
        $bookingId = $_POST['booking_id'];

        try {
            // Create a new PDO instance
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
            // Set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare the SQL statement to update the booking status to accepted
            $stmt = $conn->prepare("UPDATE bookings SET status = 'Accepted' WHERE id = :id");
            $stmt->bindParam(':id', $bookingId);
            $stmt->execute();

            $message = "Booking accepted.";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }

        // Close the database connection
        $conn = null;
    }

    // Reject Booking
    if (isset($_POST['reject_booking'])) {
        $bookingId = $_POST['booking_id'];

        try {
            // Create a new PDO instance
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
            // Set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare the SQL statement to update the booking status to rejected
            $stmt = $conn->prepare("UPDATE bookings SET status = 'Rejected' WHERE id = :id");
            $stmt->bindParam(':id', $bookingId);
            $stmt->execute();

            $message = "Booking rejected.";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }

        // Close the database connection
        $conn = null;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Flight Ticket Booking - Admin</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>

        <?php if (isset($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>

        <h2>Add Booking</h2>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>

            <label for="flight_number">Flight Number:</label>
            <input type="text" id="flight_number" name="flight_number" required><br>

            <label for="departure_date">Departure Date:</label>
            <input type="date" id="departure_date" name="departure_date" required><br>

            <label for="departure_time">Departure Time:</label>
            <input type="time" id="departure_time" name="departure_time" required><br>

            <button type="submit" name="add_booking">Add Booking</button>
        </form>

        <h2>Delete Booking</h2>
        <form method="POST" action="">
            <label for="booking_id">Booking ID:</label>
            <input type="text" id="booking_id" name="booking_id" required><br>

            <button type="submit" name="delete_booking">Delete Booking</button>
        </form>

        <h2>Manage Bookings</h2>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>Username</th>
                <th>Flight Number</th>
                <th>Departure Date</th>
                <th>Departure Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
            try {
                // Create a new PDO instance
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
                // Set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Retrieve all bookings from the database
                $stmt = $conn->query("SELECT * FROM bookings");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['flight_number'] . "</td>";
                    echo "<td>" . $row['departure_date'] . "</td>";
                    echo "<td>" . $row['departure_time'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>";
                    echo "<form method='POST' action=''>";
                    echo "<input type='hidden' name='booking_id' value='" . $row['id'] . "'>";
                    echo "<button type='submit' name='accept_booking'>Accept</button>";
                    echo "<button type='submit' name='reject_booking'>Reject</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }

            // Close the database connection
            $conn = null;
            ?>
        </table>
    </div>
</body>
</html>
