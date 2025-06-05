<?php
include '../includes/app.php';
include '../includes/database.php';
include '../includes/mongodb.php';


ini_set('memory_limit', '-1');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Duration vs Popularity & Energy</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900">
  <div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-lg shadow">
    <h1 class="text-3xl font-bold mb-6 text-center">Duration vs Popularity & Energy</h1>

    <?php
    // Step 1: Get all durations and convert to seconds
    $durationQuery = "SELECT Length FROM Songs ORDER BY Length";
    $durationResult = mysqli_query($conn, $durationQuery);

    $durations = [];

    while ($row = mysqli_fetch_assoc($durationResult)) {
      $timeParts = explode(':', $row['Length']);
      if (count($timeParts) === 2) {
        $seconds = intval($timeParts[0]) * 60 + intval($timeParts[1]);
      } elseif (count($timeParts) === 3) {
        $seconds = intval($timeParts[0]) * 3600 + intval($timeParts[1]) * 60 + intval($timeParts[2]);
      } else {
        continue;
      }
      $durations[] = $seconds;
    }

    sort($durations);
    $count = count($durations);

    if ($count === 0) {
      die("<p class='text-red-600 font-semibold'>❌ No valid durations found.</p>");
    }

    $medianSeconds = ($count % 2 == 0)
      ? ($durations[$count / 2 - 1] + $durations[$count / 2]) / 2
      : $durations[floor($count / 2)];

    $medianTimeFormatted = gmdate("i:s", $medianSeconds);

    echo "<p class='mb-6 text-lg'>🧮 Median Duration: <strong class='text-blue-600'>$medianTimeFormatted</strong> (in seconds: <strong class='text-blue-600'>$medianSeconds</strong>)</p>";

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
    echo "<div class='overflow-x-auto'>
            <table class='min-w-full bg-white border border-gray-200 rounded-lg'>
                <thead class='bg-gray-200 text-left'>
                    <tr>
                        <th class='px-6 py-3 text-sm font-semibold'>Duration Group</th>
                        <th class='px-6 py-3 text-sm font-semibold'>Avg Popularity</th>
                        <th class='px-6 py-3 text-sm font-semibold'>Avg Energy</th>
                        <th class='px-6 py-3 text-sm font-semibold'>Total Songs</th>
                    </tr>
                </thead>
                <tbody>";

    while ($row = mysqli_fetch_assoc($mainResult)) {
      echo "<tr class='border-t'>
                <td class='px-6 py-4'>" . htmlspecialchars($row['DurationGroup']) . "</td>
                <td class='px-6 py-4'>" . htmlspecialchars($row['AvgPopularity']) . "</td>
                <td class='px-6 py-4'>" . htmlspecialchars($row['AvgEnergy']) . "</td>
                <td class='px-6 py-4'>" . htmlspecialchars($row['TotalSongs']) . "</td>
              </tr>";
    }

    echo "  </tbody>
          </table>
        </div>";

    mysqli_close($conn);
    ?>
  </div>

  <!-- Custom JS -->
  <script>
    const dropdownBtn = document.getElementById('dropdownBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');

    dropdownBtn.addEventListener('click', (e) => {
      e.stopPropagation(); // prevent event from bubbling to document
      dropdownMenu.classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
      if (!dropdownMenu.contains(e.target) && !dropdownBtn.contains(e.target)) {
        dropdownMenu.classList.add('hidden');
      }
    });
  </script>
</body>

</html>