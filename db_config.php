<?php
header("Access-Control-Allow-Origin: *");

$hostname = "localhost";
$username = "root";
$password = ""; // Replace with your MySQL password
$database = "weighing"; // Replace with your database name

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
