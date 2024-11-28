<?php
include('db_config.php');

$sql = "SELECT weight, time FROM test ORDER BY time DESC LIMIT 1";
$result = mysqli_query($conn, $sql);
$current_weight = "No data found";

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $current_weight = $row['weight'] . " grams";
    $time = $row['time'];
} else {
    $time = "No data available";
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weight Monitoring Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Weight Monitoring Dashboard</h1>
        <p class="current-weight">Current Weight: <strong><?php echo $current_weight; ?></strong></p>
        <p class="time">Last updated: <?php echo $time; ?></p>
        <a href="history.php" class="view-history">View Historical Data</a>
    </div>
</body>
</html>
