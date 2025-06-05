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
</body>

</html>