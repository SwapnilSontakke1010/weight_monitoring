<?php
include('db_config.php');

// Default value for empty cylinder weight (it will be updated from the user input)
$empty_cylinder_weight = isset($_POST['empty_cylinder_weight']) ? floatval($_POST['empty_cylinder_weight']) : 0;

// Fetch historical weight and time data from the 'test' table
$sql = "SELECT weight, time FROM test ORDER BY time ASC";  // Order by time for chronological order
$result = mysqli_query($conn, $sql);

$weights = [];
$times = [];
$last_weight = null;  // To store the last weight value
$warn_message = '';

// Fetch the rows and store the data in arrays, subtract empty cylinder weight
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $adjusted_weight = $row['weight'] - $empty_cylinder_weight;  // Subtract empty cylinder weight
        $weights[] = $adjusted_weight;
        $times[] = $row['time'];
    }
    
    // Get the last adjusted weight value
    $last_weight = end($weights);  // Fetch the last value from the weights array
    
    // Check if the last weight is below 30
    if ($last_weight < 30) {
        $warn_message = 'Cylinder is going to empty, please refill it!';
    }
} else {
    // Handle case when no data is available
    $weights = [0];  // Default value when no data
    $times = ['No data available'];
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historical Weight Data</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Global Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        h1 {
            text-align: center;
            font-size: 2rem;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            width: 100%;
            max-width: 400px;
        }

        label {
            font-size: 1rem;
            color: #333;
        }

        input[type="number"] {
            padding: 10px;
            width: 100%;
            max-width: 300px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        button:hover {
            background-color: #45a049;
        }

        canvas {
            width: 100%;
            max-width: 800px;
            height: 400px;
        }

        .warning {
            color: red;
            font-size: 1.2rem;
            font-weight: bold;
            text-align: center;
            border: 2px solid red;
            padding: 10px;
            border-radius: 5px;
            background-color: #ffe6e6;
            margin-top: 10px;
            width: 90%;
            max-width: 600px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            h1 {
                font-size: 1.5rem;
            }

            form {
                width: 90%;
            }

            button {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.2rem;
            }

            input[type="number"] {
                font-size: 0.9rem;
            }

            button {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Historical Weight Data</h1>

        <!-- Display warning message if applicable -->
        <?php if ($warn_message): ?>
            <div class="warning">
                <?php echo $warn_message; ?>
            </div>
        <?php endif; ?>

        <!-- Container for entering the empty cylinder weight -->
        <form method="POST" action="">
            <label for="empty_cylinder_weight">Enter Empty Cylinder Weight (grams): </label>
            <input type="number" id="empty_cylinder_weight" name="empty_cylinder_weight" step="0.1" value="<?php echo $empty_cylinder_weight; ?>" required>
            <button type="submit">Update</button>
        </form>

        <!-- Display the chart -->
        <canvas id="weightChart"></canvas>

        <script>
            var ctx = document.getElementById('weightChart').getContext('2d');
            var weightChart = new Chart(ctx, {
                type: 'line',  // Line chart to show weight trend
                data: {
                    labels: <?php echo json_encode($times); ?>,  // Time labels for the X-axis
                    datasets: [{
                        label: 'Weight (grams)',  // Label for the Y-axis
                        data: <?php echo json_encode($weights); ?>,  // Adjusted weight values for the Y-axis
                        borderColor: 'rgba(75, 192, 192, 1)',  // Line color
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',  // Area under the line color
                        fill: true,  // Fill the area under the line
                        tension: 0.1  // Smoothness of the line
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: false  // Allow weight to start from the first value
                        }
                    }
                }
            });
        </script>
    </div>
</body>
</html>
