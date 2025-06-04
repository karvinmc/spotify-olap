<?php
require_once '../vendor/autoload.php';

// 🧠 Connect to MongoDB with credentials
try {
  $client = new MongoDB\Client('mongodb://localhost:27017', [
    'username' => 'mongo',
    'password' => 'mongo',
    'authSource' => 'admin'
  ]);
  $collection = $client->spotify->spotify;
} catch (Exception $e) {
  die("❌ Database error: " . $e->getMessage());
}

// 📝 Define available activity fields
$activities = [
  'Good for Party',
  'Good for Work/Study',
  'Good for Relaxation/Meditation',
  'Good for Exercise',
  'Good for Running',
  'Good for Yoga/Stretching',
  'Good for Driving',
  'Good for Social Gatherings',
  'Good for Morning Routine'
];

// 📥 Handle user input
$selected = $_GET['activity'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Genre Prediction by Activity</title>
  <!-- Tailwind CDN -->
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
  <div class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
    <h1 class="text-3xl font-bold mb-6 text-center">Genre Prediction by Activity</h1>

    <form method="GET" class="mb-8">
      <label for="activity" class="block text-lg font-medium mb-2">Select an activity:</label>
      <div class="flex gap-4">
        <select name="activity" id="activity" class="w-full border border-gray-300 rounded-lg p-2">
          <?php foreach ($activities as $activity): ?>
            <option value="<?= htmlspecialchars($activity) ?>" <?= ($selected === $activity) ? 'selected' : '' ?>>
              <?= htmlspecialchars($activity) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
          Predict
        </button>
      </div>
    </form>

    <?php if ($selected): ?>
      <h2 class="text-2xl font-semibold mb-4">Genre Prediction for: <span class="text-blue-600"><?= htmlspecialchars($selected) ?></span></h2>

      <?php
      // 🧪 MongoDB Aggregation
      $pipeline = [
        ['$match' => [$selected => 1]],
        ['$group' => ['_id' => '$Genre', 'count' => ['$sum' => 1]]],
        ['$sort' => ['count' => -1]]
      ];

      try {
        $results = $collection->aggregate($pipeline);
        echo "<div class='overflow-x-auto'><table class='min-w-full bg-white border border-gray-200 rounded-lg'>
                <thead>
                  <tr class='bg-gray-200 text-left'>
                    <th class='px-6 py-3 text-sm font-semibold'>Genre</th>
                    <th class='px-6 py-3 text-sm font-semibold'>Song Count</th>
                  </tr>
                </thead>
                <tbody>";
        foreach ($results as $genre) {
          echo "<tr class='border-t'>
                  <td class='px-6 py-4'>" . htmlspecialchars($genre->_id) . "</td>
                  <td class='px-6 py-4'>" . htmlspecialchars($genre->count) . "</td>
                </tr>";
        }
        echo "</tbody></table></div>";
      } catch (Exception $e) {
        echo "<p class='text-red-600 font-semibold'>❌ Query failed: " . htmlspecialchars($e->getMessage()) . "</p>";
      }
      ?>
    <?php endif; ?>
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