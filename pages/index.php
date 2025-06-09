<?php
include '../includes/app.php';
include '../includes/database.php';
include '../includes/mongodb.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Spotify OLAP Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800 font-sans">
  <section class="text-center py-10 bg-blue-100">
    <h2 class="text-3xl font-semibold mb-2">Welcome to the Spotify OLAP Project</h2>
    <p class="text-gray-700">Analyze Spotify data and get personalized recommendations.</p>
  </section>
  <section class="bg-gray-100 py-8 px-6 max-w-5xl mx-auto rounded mt-6">
    <div class="mt-6 text-center text-sm text-gray-600">
      The dataset includes over 30 features such as popularity, genre, tempo, key, energy, valence, duration, and more, enabling in-depth music analysis.
    </div>
  </section>
  <section class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6 max-w-5xl mx-auto">
    <a href="/pages/olap_analysis.php" class="bg-white p-8 rounded shadow hover:shadow-md text-center">
      <h4 class="font-semibold text-blue-600">OLAP Analysis</h4>
      <p class="text-sm mt-2">Explore trends by genre, artist, and year.</p>
    </a>
    <a href="/pages/recommendation.php" class="bg-white p-8 rounded shadow hover:shadow-md text-center">
      <h4 class="font-semibold text-blue-600">Recommendations</h4>
      <p class="text-sm mt-2">Get song suggestions based on input filters.</p>
    </a>
  </section>
</body>

</html>