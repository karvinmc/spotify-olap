<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "root";
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

$query = "
  SELECT 
    Emotion,
    Explicit,
    COUNT(*) AS Total
  FROM Songs
  GROUP BY Emotion, Explicit
  ORDER BY Emotion, Explicit
";

$result = mysqli_query($conn, $query);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
  $emotion = $row['Emotion'];
  $explicit = $row['Explicit'] == 1 ? 'Explicit' : 'Non-Explicit';
  $data[$emotion][$explicit] = $row['Total'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Emotion vs Explicitness</title>
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
    <h1 class="text-2xl font-bold mb-6 text-center">Comparison of Dominant Emotions Between Explicit and Non-Explicit Songs</h1>

    <div class="overflow-x-auto">
      <table class="min-w-full border border-gray-300 bg-white text-sm text-left">
        <thead class="bg-gray-200 text-gray-700 uppercase">
          <tr>
            <th class="px-6 py-3">Emotion</th>
            <th class="px-6 py-3">Explicit</th>
            <th class="px-6 py-3">Non-Explicit</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php foreach ($data as $emotion => $counts): ?>
            <tr>
              <td class="px-6 py-4"><?= htmlspecialchars($emotion) ?></td>
              <td class="px-6 py-4"><?= $counts['Explicit'] ?? 0 ?></td>
              <td class="px-6 py-4"><?= $counts['Non-Explicit'] ?? 0 ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>