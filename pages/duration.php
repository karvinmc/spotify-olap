<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "root"; // your password
$db_name = "spotify";

$conn = null;

try {
  $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
  if (!$conn) {
    throw new Exception("Connection failed: " . mysqli_connect_error());
  }
} catch (Exception $e) {
  die("❌ Could not connect! " . $e->getMessage());
}

ini_set('memory_limit', '-1');
include '../database.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Duration vs Popularity & Energy</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900">
  <!-- Navbar -->
  <nav class="bg-white shadow px-6 py-4">
    <div class="max-w-7xl mx-auto flex justify-center items-center">
      <ul class="flex gap-6 items-center relative">
        <li>
          <a href="/" class="text-gray-700 font-medium px-4 py-2 rounded hover:bg-gray-100 transition">
            Home
          </a>
        </li>
        <li class="relative">
          <button id="dropdownBtn" class="flex items-center gap-1 text-gray-700 font-medium px-4 py-2 rounded hover:bg-gray-100 transition">
            Analysis
            <svg class="w-4 h-4 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <ul id="dropdownMenu" class="absolute left-0 mt-2 w-56 bg-white shadow-lg rounded border border-gray-200 hidden z-50">
            <li>
              <a href="/pages/activity_percentage.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 transition">
                Activity Percentage
              </a>
            </li>
            <li>
              <a href="/pages/duration.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 transition">
                Duration
              </a>
            </li>
            <li>
              <a href="/pages/emotion_explicit_compare.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 transition">
                Emotion vs Explicit
              </a>
            </li>
            <li>
              <a href="/pages/genre_prediction.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 transition">
                Genre Prediction
              </a>
            </li>
          </ul>
        </li>
        <li>
          <a href="/pages/recommendation.php" class="text-gray-700 font-medium px-4 py-2 rounded hover:bg-gray-100 transition">
            Recommendation
          </a>
        </li>
      </ul>
    </div>
  </nav>
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