<?php
require_once 'vendor/autoload.php';

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
<html>
<head>
  <title>Genre Prediction by Activity</title>
</head>
<body>
  <h1>🎧 Genre Prediction by Activity</h1>

  <form method="GET">
    <label for="activity">Select an activity:</label>
    <select name="activity" id="activity">
      <?php foreach ($activities as $activity): ?>
        <option value="<?= htmlspecialchars($activity) ?>" <?= ($selected === $activity) ? 'selected' : '' ?>>
          <?= htmlspecialchars($activity) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <button type="submit">Predict</button>
  </form>

  <?php if ($selected): ?>
    <h2>🎯 Genre Prediction for: <?= htmlspecialchars($selected) ?></h2>

    <?php
    // 🧪 MongoDB Aggregation
    $pipeline = [
      ['$match' => [$selected => 1]],
      ['$group' => ['_id' => '$Genre', 'count' => ['$sum' => 1]]],
      ['$sort' => ['count' => -1]]
    ];

    try {
      $results = $collection->aggregate($pipeline);
      echo "<table border='1' cellpadding='8'>
              <tr><th>Genre</th><th>Song Count</th></tr>";
      foreach ($results as $genre) {
        echo "<tr><td>{$genre->_id}</td><td>{$genre->count}</td></tr>";
      }
      echo "</table>";
    } catch (Exception $e) {
      echo "❌ Query failed: " . $e->getMessage();
    }
    ?>
  <?php endif; ?>
</body>
</html>
