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

<h1>😈 Perbandingan Dominan Emotion antara Lagu Explicit dan Tidak</h1>
<table border="1" cellpadding="8">
  <tr>
    <th>Emotion</th>
    <th>Explicit</th>
    <th>Non-Explicit</th>
  </tr>

  <?php foreach ($data as $emotion => $counts): ?>
    <tr>
      <td><?= htmlspecialchars($emotion) ?></td>
      <td><?= $counts['Explicit'] ?? 0 ?></td>
      <td><?= $counts['Non-Explicit'] ?? 0 ?></td>
    </tr>
  <?php endforeach; ?>
</table>
