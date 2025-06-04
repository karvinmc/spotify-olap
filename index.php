<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Spotify OLAP Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800 font-sans">

  <!-- Navbar -->
  <nav class="bg-white shadow p-4 flex justify-center items-center">
    <ul class="flex gap-4">
      <li><a href="/" class="p-4 hover:bg-gray-200 transition">Home</a></li>
      <li><a href="#" class="p-4 hover:bg-gray-200 transition">Analysis</a></li>
      <li><a href="/pages/recommendation.php" class="p-4 hover:bg-gray-200 transition">Recommendation</a></li>
    </ul>
  </nav>

  <!-- Hero / Intro -->
  <section class="text-center py-10 bg-blue-100">
    <h2 class="text-3xl font-semibold mb-2">Welcome to the Spotify OLAP Project</h2>
    <p class="text-gray-700">Analyze Spotify data and get personalized recommendations.</p>
  </section>

  <!-- Dataset Summary -->
  <section class="bg-gray-100 py-8 px-6 max-w-5xl mx-auto rounded mt-6">
    <div class="mt-6 text-center text-sm text-gray-600">
      The dataset includes over 30 features such as popularity, genre, tempo, key, energy, valence, duration, and more, enabling in-depth music analysis.
    </div>
  </section>


  <!-- Feature Links -->
  <section class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6 max-w-5xl mx-auto">
    <a href="#" class="bg-white p-8 rounded shadow hover:shadow-md text-center">
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