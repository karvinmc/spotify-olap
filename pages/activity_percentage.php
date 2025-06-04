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

$activities = [
  'GoodForParty' => 'Party',
  'GoodForWorkStudy' => 'Work/Study',
  'GoodForRelaxationMeditation' => 'Relaxation/Meditation',
  'GoodForExercise' => 'Exercise',
  'GoodForRunning' => 'Running',
  'GoodForYogaStretching' => 'Yoga/Stretching',
  'GoodForDriving' => 'Driving',
  'GoodForSocialGatherings' => 'Social Gatherings',
  'GoodForMorningRoutine' => 'Morning Routine'
];

$selected = $_GET['activity'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Song Percentage by Activity</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">
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
  <div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold text-center mb-6">Song Percentage by Activity</h1>

    <form method="GET" class="mb-6">
      <label for="activity" class="block text-sm font-medium text-gray-700 mb-2">Choose an activity:</label>
      <select name="activity" id="activity" class="w-full p-2 border border-gray-300 rounded mb-4">
        <?php foreach ($activities as $field => $label): ?>
          <option value="<?= $field ?>" <?= ($selected === $field) ? 'selected' : '' ?>><?= $label ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 transition">
        View Percentage
      </button>
    </form>

    <?php if ($selected): ?>
      <?php
      $query = "
          SELECT 
            COUNT(*) AS total,
            SUM($selected) AS support,
            ROUND(SUM($selected) / COUNT(*) * 100, 2) AS percentage
          FROM Songs
        ";
      $result = mysqli_query($conn, $query);
      $data = mysqli_fetch_assoc($result);
      ?>

      <div class="bg-blue-50 border border-blue-200 rounded p-4">
        <h2 class="text-lg font-semibold mb-2">Result for activity: <span class="text-blue-600"><?= $activities[$selected] ?></span></h2>
        <p class="mb-1"><strong>Total songs:</strong> <?= $data['total'] ?></p>
        <p class="mb-1"><strong>Supporting songs:</strong> <?= $data['support'] ?></p>
        <p class="text-xl font-bold mt-2">Percentage: <span class="text-green-600"><?= $data['percentage'] ?>%</span></p>
      </div>
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