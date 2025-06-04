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
  <nav class="bg-white shadow px-6 py-4">
    <div class="max-w-7xl mx-auto flex justify-center items-center">
      <ul class="flex gap-6 items-center relative">
        <li>
          <a href="/" class="text-gray-700 font-medium px-4 py-2 rounded hover:bg-gray-100 transition">
            Home
          </a>
        </li>
        <li class="relative">
          <button id="dropdownBtn" class="flex items-center gap-1 text-gray-700 font-medium px-4 py-2 rounded hover:bg-gray-100 transition">
            Analysis
            <svg class="w-4 h-4 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <ul id="dropdownMenu" class="absolute left-0 mt-2 w-56 bg-white shadow-lg rounded border border-gray-200 hidden z-50">
            <li>
              <a href="/pages/activity_percentage.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 transition">
                Activity Percentage
              </a>
            </li>
            <li>
              <a href="/pages/duration.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 transition">
                Duration
              </a>
            </li>
            <li>
              <a href="/pages/emotion_explicit_compare.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 transition">
                Emotion vs Explicit
              </a>
            </li>
            <li>
              <a href="/pages/genre_prediction.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 transition">
                Genre Prediction
              </a>
            </li>
          </ul>
        </li>
        <li>
          <a href="/pages/recommendation.php" class="text-gray-700 font-medium px-4 py-2 rounded hover:bg-gray-100 transition">
            Recommendation
          </a>
        </li>
      </ul>
    </div>
  </nav>
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
    <a href="#" class="bg-white p-8 rounded shadow hover:shadow-md text-center">
      <h4 class="font-semibold text-blue-600">OLAP Analysis</h4>
      <p class="text-sm mt-2">Explore trends by genre, artist, and year.</p>
    </a>
    <a href="/pages/recommendation.php" class="bg-white p-8 rounded shadow hover:shadow-md text-center">
      <h4 class="font-semibold text-blue-600">Recommendations</h4>
      <p class="text-sm mt-2">Get song suggestions based on input filters.</p>
    </a>
  </section>

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