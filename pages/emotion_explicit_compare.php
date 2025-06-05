<?php
include '../includes/app.php';
include '../includes/database.php';
include '../includes/mongodb.php';


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