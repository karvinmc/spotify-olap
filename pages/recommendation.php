<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Recommendations</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans">
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
  <div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Page Title -->
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Get personalized recommendations</h1>

    <div class="grid grid-cols-12 gap-6">
      <!-- Sidebar Filters -->
      <aside class="col-span-3 bg-white p-4 rounded-2xl shadow-md space-y-4">
        <h2 class="text-xl font-semibold mb-2">Filters</h2>
        <div>
          <label class="block text-sm font-medium">Genre</label>
          <select class="w-full border rounded px-2 py-1">
            <option>All</option>
            <option>Hip Hop</option>
            <option>Rock</option>
            <option>Pop</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium">Emotion</label>
          <select class="w-full border rounded px-2 py-1">
            <option>All</option>
            <option>Joy</option>
            <option>Sadness</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium">Good For</label>
          <select class="w-full border rounded px-2 py-1">
            <option>Any</option>
            <option>Party</option>
            <option>Work/Study</option>
            <option>Relaxation/Meditation</option>
            <option>Exercise</option>
            <option>Running</option>
            <option>Yoga/Stretching</option>
            <option>Driving</option>
            <option>Social Gatherings</option>
            <option>Morning Routine</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium">Explicit</label>
          <select class="w-full border rounded px-2 py-1">
            <option>Any</option>
            <option>Yes</option>
            <option>No</option>
          </select>
        </div>
        <div>
          <label for="tempo-max" class="block text-sm font-medium mb-1">
            Max Tempo (BPM): <span id="tempo-max-display">200</span>
          </label>
          <input
            type="range"
            id="tempo-max"
            name="tempo-max"
            min="60"
            max="200"
            value="200"
            step="1"
            class="w-full accent-indigo-600">
        </div>
        <div>
          <label class="block text-sm font-medium">Duration (min)</label>
          <select class="w-full border rounded px-2 py-1">
            <option>Any</option>
            <option>&lt; 3 min</option>
            <option>&gt; 3 min</option>
          </select>
        </div>
        <button class="w-full bg-indigo-600 text-white py-2 rounded mt-4 hover:bg-indigo-700">Apply</button>
      </aside>

      <!-- Main Content -->
      <section class="col-span-9 space-y-6">
        <!-- KPIs (Reflect Filtered Results) -->
        <div class="grid grid-cols-3 gap-4">
          <div class="bg-white p-4 rounded-2xl shadow-md">
            <p class="text-sm text-gray-500">Songs Matching Filters</p>
            <p class="text-2xl font-bold text-blue-600">128</p>
          </div>
          <div class="bg-white p-4 rounded-2xl shadow-md">
            <p class="text-sm text-gray-500">Avg. Energy</p>
            <p class="text-2xl font-bold text-indigo-600">75</p>
          </div>
          <div class="bg-white p-4 rounded-2xl shadow-md">
            <p class="text-sm text-gray-500">Best Activity</p>
            <p class="text-2xl font-bold text-pink-500">Driving</p>
          </div>
        </div>

        <!-- Recommendation Result -->
        <div class="bg-white p-6 rounded-2xl shadow-md">
          <h2 class="text-xl font-semibold mb-4">
            Top 5 Recommended Songs <span class="text-sm text-gray-500">(based on filters)</span>
          </h2>
          <ul class="space-y-2">
            <li class="bg-gray-100 p-3 rounded">🎧 <strong>Song Title</strong> - Artist</li>
            <li class="bg-gray-100 p-3 rounded">🎧 Song 2 - Artist</li>
            <li class="bg-gray-100 p-3 rounded">🎧 Song 3 - Artist</li>
            <li class="bg-gray-100 p-3 rounded">🎧 Song 4 - Artist</li>
            <li class="bg-gray-100 p-3 rounded">🎧 Song 5 - Artist</li>
          </ul>
        </div>
      </section>
    </div>
  </div>

  <script>
    const tempoSlider = document.getElementById('tempo-max');
    const tempoDisplay = document.getElementById('tempo-max-display');

    tempoSlider.addEventListener('input', () => {
      tempoDisplay.textContent = tempoSlider.value;
    });
  </script>
</body>

</html>