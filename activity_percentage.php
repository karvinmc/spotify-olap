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
  <title>🎵 Persentase Lagu untuk Aktivitas</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">
  <div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold text-center mb-6">🎵 Persentase Lagu untuk Aktivitas Tertentu</h1>

    <form method="GET" class="mb-6">
      <label for="activity" class="block text-sm font-medium text-gray-700 mb-2">Pilih Aktivitas:</label>
      <select name="activity" id="activity" class="w-full p-2 border border-gray-300 rounded mb-4">
        <?php foreach ($activities as $field => $label): ?>
          <option value="<?= $field ?>" <?= ($selected === $field) ? 'selected' : '' ?>><?= $label ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 transition">
        Lihat Persentase
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
        <h2 class="text-lg font-semibold mb-2">📊 Hasil untuk aktivitas: <span class="text-blue-600"><?= $activities[$selected] ?></span></h2>
        <p class="mb-1">🎼 <strong>Total lagu:</strong> <?= $data['total'] ?></p>
        <p class="mb-1">✅ <strong>Lagu mendukung:</strong> <?= $data['support'] ?></p>
        <p class="text-xl font-bold mt-2">📈 Persentase: <span class="text-green-600"><?= $data['percentage'] ?>%</span></p>
      </div>
    <?php endif; ?>
  </div>
</body>

</html>