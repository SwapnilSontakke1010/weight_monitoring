<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");



include('db_config.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$sql = "SELECT weight, time FROM test ORDER BY time DESC LIMIT 20";
$result = mysqli_query($conn, $sql);

$weight_history = [];
while ($row = mysqli_fetch_assoc($result)) {
    $weight_history[] = [
        "time" => $row['time'],
        "weight" => (float)$row["weight"]
    ];
}

$response = [
    "current_weight" => $weight_history[0]["weight"] ?? null,
    "weight_history" => $weight_history
];

echo json_encode($response, JSON_PRETTY_PRINT); // Prettifies output for debugging
mysqli_close($conn);
?>
