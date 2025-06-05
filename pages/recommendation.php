<?php
include '../includes/app.php';
include '../includes/mongodb.php';

use MongoDB\BSON\Regex;

$tempoSlider = isset($_GET['tempo']) ? (float) $_GET['tempo'] : 100;
$energySlider = isset($_GET['energy']) ? (float) $_GET['energy'] : 100;
$danceabilitySlider = isset($_GET['danceability']) ? (float) $_GET['danceability'] : 100;

$collection = $client->spotify->spotify;

$cursor = $collection->find([
  'Tempo' => ['$exists' => true],
  'Energy' => ['$exists' => true],
  'Danceability' => ['$exists' => true],
], [
  'projection' => [
    'song' => 1,
    'Artist(s)' => 1,
    'Tempo' => 1,
    'Energy' => 1,
    'Danceability' => 1
  ]
]);

$songs = iterator_to_array($cursor);
if (count($songs) === 0) {
  die(" No songs found with required features.");
}
$features = array_map(function ($s) {
  return [(float) $s['Tempo'], (float) $s['Energy'], (float) $s['Danceability']];
}, $songs);

function normalize($data) {
  $min = $max = [];
  $cols = count($data[0]);

  for ($i = 0; $i < $cols; $i++) {
    $column = array_column($data, $i);
    $min[$i] = min($column);
    $max[$i] = max($column);
  }

  $normalized = [];
  foreach ($data as $row) {
    $normRow = [];
    for ($i = 0; $i < $cols; $i++) {
      $denom = $max[$i] - $min[$i] ?: 1;
      $normRow[] = ($row[$i] - $min[$i]) / $denom;
    }
    $normalized[] = $normRow;
  }

  return [$normalized, $min, $max];
}
list($normFeatures, $minVals, $maxVals) = normalize($features);


$sliderInput = [$tempoSlider, $energySlider, $danceabilitySlider]; 
$realInput = [];
for ($i = 0; $i < 3; $i++) {
  $percentage = $sliderInput[$i] / 100;
  $realValue = $minVals[$i] + $percentage * ($maxVals[$i] - $minVals[$i]);
  $realInput[] = $realValue;
}

$normInput = [];
for ($i = 0; $i < 3; $i++) {
  $denom = $maxVals[$i] - $minVals[$i] ?: 1;
  $normInput[] = ($realInput[$i] - $minVals[$i]) / $denom;
}

$distances = [];
foreach ($normFeatures as $i => $songVec) {
  $dist = 0;
  for ($j = 0; $j < 3; $j++) {
    $dist += pow($normInput[$j] - $songVec[$j], 2);
  }
  $distances[] = ['index' => $i, 'distance' => sqrt($dist)];
}

usort($distances, fn($a, $b) => $a['distance'] <=> $b['distance']);
$top5 = array_slice($distances, 0, 5);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>KNN Recommendation</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">
  <div class="max-w-4xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6"> Top 5 Song Recommendations</h1>

    <form method="GET" class="bg-white p-4 rounded shadow space-y-6 mb-6">
  <div class="space-y-4">
    
    <div>
      <label class="block text-sm font-medium">Tempo</label>
      <input type="range" min="0" max="100" name="tempo" value="<?= $tempoSlider ?>" oninput="tempoOut.value = this.value" class="w-full">
      <div class="text-sm mt-1">Selected: <output id="tempoOut"><?= $tempoSlider ?></output></div>
    </div>

    <div>
      <label class="block text-sm font-medium">Energy (%)</label>
      <input type="range" min="0" max="100" name="energy" value="<?= $energySlider ?>" oninput="energyOut.value = this.value" class="w-full">
      <div class="text-sm mt-1">Selected: <output id="energyOut"><?= $energySlider ?></output></div>
    </div>

    <div>
      <label class="block text-sm font-medium">Danceability (%)</label>
      <input type="range" min="0" max="100" name="danceability" value="<?= $danceabilitySlider ?>" oninput="danceOut.value = this.value" class="w-full">
      <div class="text-sm mt-1">Selected: <output id="danceOut"><?= $danceabilitySlider ?></output></div>
    </div>

  </div>
  
  <button type="submit" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Apply Filters</button>
</form>


    <div class="bg-white p-6 rounded shadow">
      <h2 class="text-xl font-semibold mb-4">Recommended Songs</h2>
      <ul class="space-y-2">
        <?php foreach ($top5 as $item): 
          $song = $songs[$item['index']];
        ?>
          <li class="bg-gray-100 p-3 rounded">
            🎵 <strong><?= htmlspecialchars($song['song'] ?? 'Untitled') ?></strong> - <?= htmlspecialchars($song['Artist(s)'] ?? 'Unknown') ?>
            <span class="text-sm text-gray-500 ml-2">(distance: <?= round($item['distance'], 3) ?>)</span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</body>
</html>
