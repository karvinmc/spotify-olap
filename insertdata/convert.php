<?php
set_time_limit(0); // ⏱ No time limit
// Set unlimited memory in case the file is large
ini_set('memory_limit', '-1');

// Input and output file paths
$inputFile = '900k Definitive Spotify Dataset.json';
$outputFile = 'converted_songs.json';

// Open the input file
$handle = fopen($inputFile, 'r');
if (!$handle) {
  die("❌ Failed to open input file.");
}

// Start writing the valid JSON array
file_put_contents($outputFile, "[\n");

$lineCount = 0;
while (($line = fgets($handle)) !== false) {
  $line = trim($line);
  if ($line === "") continue;

  // Validate JSON line before adding
  $data = json_decode($line, true);
  if ($data === null) {
    echo "⚠️ Skipping invalid line (line " . ($lineCount + 1) . "): " . json_last_error_msg() . "\n";
    continue;
  }

  // Add comma if it's not the first valid line
  if ($lineCount > 0) {
    file_put_contents($outputFile, ",\n", FILE_APPEND);
  }

  // Write the valid JSON object
  file_put_contents($outputFile, $line, FILE_APPEND);
  $lineCount++;
}

fclose($handle);

// Close the JSON array
file_put_contents($outputFile, "\n]\n", FILE_APPEND);

echo "✅ Converted $lineCount songs to valid JSON array.\n";
echo "📄 Output file: $outputFile\n";
