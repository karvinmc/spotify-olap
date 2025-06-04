<?php
$db_server= "localhost";
$db_user= "root";
$db_pass= "root"; // your password
$db_name= "spotify";

$conn = null;

try {
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
    if (!$conn) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }
} catch (Exception $e) {
    echo "❌ Could not connect! " . $e->getMessage();
}
ini_set('memory_limit', '-1');
include 'database.php';
echo "<h1>🎵 Duration vs Popularity & Energy</h1>";

// Step 1: Get all durations and convert to seconds
$durationQuery = "SELECT Length FROM Songs ORDER BY Length";
$durationResult = mysqli_query($conn, $durationQuery);

$durations = [];

while ($row = mysqli_fetch_assoc($durationResult)) {
    // Convert TIME to seconds
    $timeParts = explode(':', $row['Length']);
    if (count($timeParts) === 2) {
        $seconds = intval($timeParts[0]) * 60 + intval($timeParts[1]);
    } elseif (count($timeParts) === 3) {
        $seconds = intval($timeParts[0]) * 3600 + intval($timeParts[1]) * 60 + intval($timeParts[2]);
    } else {
        continue; // Skip invalid time format
    }
    $durations[] = $seconds;
}

// Step 2: Calculate median
sort($durations);
$count = count($durations);

if ($count === 0) {
    die("❌ No valid durations found.");
}

$medianSeconds = ($count % 2 == 0)
    ? ($durations[$count/2 - 1] + $durations[$count/2]) / 2
    : $durations[floor($count/2)];

$medianTimeFormatted = gmdate("i:s", $medianSeconds);

echo "<p>🧮 Median Duration: <strong>$medianTimeFormatted</strong> (in seconds: $medianSeconds)</p>";

// Step 3: Group songs above and below median
$mainQuery = "
    SELECT 
        CASE WHEN TIME_TO_SEC(Length) > $medianSeconds THEN 'Above Median' ELSE 'Below or Equal Median' END AS DurationGroup,
        ROUND(AVG(Popularity), 2) AS AvgPopularity,
        ROUND(AVG(Energy), 2) AS AvgEnergy,
        COUNT(*) AS TotalSongs
    FROM Songs
    GROUP BY DurationGroup
";

$mainResult = mysqli_query($conn, $mainQuery);

// Step 4: Display the result
echo "<table border='1' cellpadding='8' cellspacing='0'>
        <tr style='background-color:#eee;'>
            <th>Duration Group</th>
            <th>Avg Popularity</th>
            <th>Avg Energy</th>
            <th>Total Songs</th>
        </tr>";

while ($row = mysqli_fetch_assoc($mainResult)) {
    echo "<tr>
            <td>{$row['DurationGroup']}</td>
            <td>{$row['AvgPopularity']}</td>
            <td>{$row['AvgEnergy']}</td>
            <td>{$row['TotalSongs']}</td>
          </tr>";
}

echo "</table>";

mysqli_close($conn);
?>






