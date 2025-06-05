<?php
require_once '../includes/app.php';
require_once '../includes/mongodb.php';

$collection = $client->spotify->spotify;

// Handle user inputs
$genre = $_GET['genre'] ?? 'All';
$emotion = $_GET['emotion'] ?? 'All';
$explicit = $_GET['explicit'] ?? 'Any';
$tempoMax = isset($_GET['tempo-max']) ? (int) $_GET['tempo-max'] : 200;
$durationFilter = $_GET['duration'] ?? 'Any';

$match = [];
if ($genre !== 'All') $match['Genre'] = $genre;
if ($emotion !== 'All') $match['emotion'] = $emotion;
if ($explicit === 'Yes') $match['Explicit'] = 'Yes';
if ($explicit === 'No') $match['Explicit'] = 'No';
$match['Tempo'] = ['$lte' => $tempoMax];

if ($durationFilter === '< 3 min') {
    $match['Length'] = ['$lt' => '00:03:00'];
} elseif ($durationFilter === '> 3 min') {
    $match['Length'] = ['$gte' => '00:03:00'];
}

$activityFields = [
  'Good for Party', 'Good for Work/Study', 'Good for Relaxation/Meditation',
  'Good for Exercise', 'Good for Running', 'Good for Yoga/Stretching',
  'Good for Driving', 'Good for Social Gatherings', 'Good for Morning Routine'
];

$pipeline = [
  ['$match' => $match],
  ['$group' => [
    '_id' => null,
    'count' => ['$sum' => 1],
    'avg_energy' => ['$avg' => '$Energy'],
    ...array_reduce($activityFields, function ($acc, $field) {
      $key = str_replace(' ', '_', $field);
      $acc[$key] = ['$sum' => ['$cond' => [['$eq' => ['$' . $field, 1]], 1, 0]]];
      return $acc;
    }, [])
  ]],
];

$result = $collection->aggregate($pipeline)->toArray();
$data = $result[0] ?? null;

$bestActivity = 'Unknown';
$maxActivity = 0;
if ($data) {
  foreach ($activityFields as $field) {
    $key = str_replace(' ', '_', $field);
    if ($data[$key] > $maxActivity) {
      $maxActivity = $data[$key];
      $bestActivity = $field;
    }
  }
}

$topSongs = $collection->find($match, ['limit' => 5])->toArray();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Activity Predictor</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Get personalized recommendations</h1>
    <form method="GET">
      <div class="grid grid-cols-12 gap-6">
        <aside class="col-span-3 bg-white p-4 rounded-2xl shadow-md space-y-4">
          <h2 class="text-xl font-semibold mb-2">Filters</h2>
          <div>
            <label class="block text-sm font-medium">Genre</label>
            <select name="genre" class="w-full border rounded px-2 py-1">
              <option>All</option><option>Hip Hop</option><option>Rock</option><option>Pop</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium">Emotion</label>
            <select name="emotion" class="w-full border rounded px-2 py-1">
              <option>All</option><option>Joy</option><option>Sadness</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium">Good For</label>
            <select class="w-full border rounded px-2 py-1" disabled>
              <option>Detected Automatically</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium">Explicit</label>
            <select name="explicit" class="w-full border rounded px-2 py-1">
              <option>Any</option><option>Yes</option><option>No</option>
            </select>
          </div>
          <div>
            <label for="tempo-max" class="block text-sm font-medium mb-1">
              Max Tempo (BPM): <span id="tempo-max-display"><?= htmlspecialchars($tempoMax) ?></span>
            </label>
            <input type="range" name="tempo-max" id="tempo-max" min="60" max="200" value="<?= htmlspecialchars($tempoMax) ?>" class="w-full accent-indigo-600" oninput="document.getElementById('tempo-max-display').textContent = this.value">
          </div>
          <div>
            <label class="block text-sm font-medium">Duration (min)</label>
            <select name="duration" class="w-full border rounded px-2 py-1">
              <option>Any</option><option>< 3 min</option><option>> 3 min</option>
            </select>
          </div>
          <button class="w-full bg-indigo-600 text-white py-2 rounded mt-4 hover:bg-indigo-700">Apply</button>
        </aside>

        <section class="col-span-9 space-y-6">
          <div class="grid grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-2xl shadow-md">
              <p class="text-sm text-gray-500">Songs Matching Filters</p>
              <p class="text-2xl font-bold text-blue-600"><?= $data['count'] ?? 0 ?></p>
            </div>
            <div class="bg-white p-4 rounded-2xl shadow-md">
              <p class="text-sm text-gray-500">Avg. Energy</p>
              <p class="text-2xl font-bold text-indigo-600"><?= round($data['avg_energy'] ?? 0) ?></p>
            </div>
            <div class="bg-white p-4 rounded-2xl shadow-md">
              <p class="text-sm text-gray-500">Best Activity</p>
              <p class="text-2xl font-bold text-pink-500"><?= $bestActivity ?></p>
            </div>
          </div>

          <div class="bg-white p-6 rounded-2xl shadow-md">
            <h2 class="text-xl font-semibold mb-4">
              Top 5 Recommended Songs <span class="text-sm text-gray-500">(based on filters)</span>
            </h2>
            <ul class="space-y-2">
              <?php foreach ($topSongs as $song): ?>
                <li class="bg-gray-100 p-3 rounded">🎧 <strong><?= htmlspecialchars($song['song']) ?></strong> - <?= htmlspecialchars($song['Artist(s)']) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </section>
      </div>
    </form>
  </div>
</body>
</html>