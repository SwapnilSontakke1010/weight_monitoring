<?php
// Database connection
$hostname = "localhost";
$username = "root";
$password = "";  // Replace with your root password
$database = "weighing";  // Replace with your database name

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if weight and voltage are received via POST
if (isset($_POST['current_weight']) && isset($_POST['voltage'])) {
    $weight = $_POST['current_weight'];
    $voltage = $_POST['voltage'];

    // Insert the weight and voltage into the database
    $sql = "INSERT INTO test (weight, voltage) VALUES ('$weight', '$voltage')";
    
    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
} else {
    echo "Invalid data received!";
}

mysqli_close($conn);
?>
