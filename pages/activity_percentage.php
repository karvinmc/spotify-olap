<?php
include '../includes/app.php';
include '../includes/database.php';
include '../includes/mongodb.php';

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

$totalQuery = "SELECT COUNT(*) AS total FROM Songs";
$totalResult = mysqli_query($conn, $totalQuery);
$totalData = mysqli_fetch_assoc($totalResult);
$total = $totalData['total'];

$percentages = [];
$supportMap = []; // to fetch per-activity support

foreach ($activities as $field => $label) {
  $query = "SELECT SUM($field) AS support FROM Songs";
  $result = mysqli_query($conn, $query);
  $data = mysqli_fetch_assoc($result);
  $support = $data['support'] ?? 0;
  $percentage = $total > 0 ? round(($support / $total) * 100, 2) : 0;

  $percentages[] = [
    'label' => $label,
    'percentage' => $percentage
  ];
  $supportMap[$field] = [
    'support' => $support,
    'percentage' => $percentage
  ];
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Song Percentage by Activity</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100 text-gray-800">
  <!-- Navbar -->
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
<canvas id="activityPieChart" class="mt-6 w-full max-w-lg mx-auto"></canvas>
    <?php if ($selected): ?>
      <?php
$totalQuery = "SELECT COUNT(*) AS total FROM Songs";
$totalResult = mysqli_query($conn, $totalQuery);
$totalData = mysqli_fetch_assoc($totalResult);
$total = $totalData['total'];

$percentages = [];
foreach ($activities as $field => $label) {
  $query = "SELECT SUM($field) AS support FROM Songs";
  $result = mysqli_query($conn, $query);
  $data = mysqli_fetch_assoc($result);
  $support = $data['support'] ?? 0;
  $percentage = $total > 0 ? round(($support / $total) * 100, 2) : 0;
  $percentages[] = [
    'label' => $label,
    'percentage' => $percentage
  ];
}
?>


      <div class="bg-blue-50 border border-blue-200 rounded p-4">
        <h2 class="text-lg font-semibold mb-2">Result for activity: <span class="text-blue-600"><?= $activities[$selected] ?></span></h2>
        <p class="mb-1"><strong>Total songs:</strong> <?= $total ?></p>
<p class="mb-1"><strong>Supporting songs:</strong> <?= $supportMap[$selected]['support'] ?></p>
<p class="text-xl font-bold mt-2">Percentage: <span class="text-green-600"><?= $supportMap[$selected]['percentage'] ?>%</span></p>
      </div>
    <?php endif; ?>
  </div>
</body>
<script>
  const pieCtx = document.getElementById('activityPieChart').getContext('2d');
  const activityLabels = <?= json_encode(array_column($percentages, 'label')) ?>;
  const activityData = <?= json_encode(array_column($percentages, 'percentage')) ?>;

  new Chart(pieCtx, {
    type: 'pie',
    data: {
      labels: activityLabels,
      datasets: [{
        label: 'Activity Suitability (%)',
        data: activityData,
        backgroundColor: [
          '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#6366f1',
          '#14b8a6', '#eab308', '#8b5cf6', '#ec4899'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom'
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return `${context.label}: ${context.parsed}%`;
            }
          }
        }
      }
    }
  });
</script>
</html>