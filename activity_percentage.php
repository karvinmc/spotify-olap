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

<h1>🎵 Persentase Lagu untuk Aktivitas Tertentu</h1>

<form method="GET">
  <label for="activity">Pilih Aktivitas:</label>
  <select name="activity" id="activity">
    <?php foreach ($activities as $field => $label): ?>
      <option value="<?= $field ?>" <?= ($selected === $field) ? 'selected' : '' ?>><?= $label ?></option>
    <?php endforeach; ?>
  </select>
  <button type="submit">Lihat Persentase</button>
</form>

<?php
if ($selected) {
  $query = "
    SELECT 
      COUNT(*) AS total,
      SUM($selected) AS support,
      ROUND(SUM($selected) / COUNT(*) * 100, 2) AS percentage
    FROM Songs
  ";
  $result = mysqli_query($conn, $query);
  $data = mysqli_fetch_assoc($result);
  echo "<h2>Hasil untuk aktivitas: {$activities[$selected]}</h2>";
  echo "<p>Total lagu: {$data['total']}</p>";
  echo "<p>Lagu mendukung: {$data['support']}</p>";
  echo "<p><strong>Persentase: {$data['percentage']}%</strong></p>";
}
?>
