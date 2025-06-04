<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Spotify OLAP</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans">
  <div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Page Title -->
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Spotify OLAP Dashboard</h1>

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
            <option>Happiness</option>
            <option>Sadness</option>
            <option>Energetic</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium">Good For</label>
          <select class="w-full border rounded px-2 py-1">
            <option>Any</option>
            <option>Party</option>
            <option>Workout</option>
            <option>Study</option>
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
          <label class="block text-sm font-medium">Tempo</label>
          <input type="range" min="60" max="200" class="w-full">
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
      <main class="col-span-9 space-y-6">
        <!-- KPIs -->
        <div class="grid grid-cols-3 gap-4">
          <div class="bg-white p-4 rounded-2xl shadow-md">
            <p class="text-sm text-gray-500">Average Energy</p>
            <p class="text-2xl font-bold text-indigo-600">75</p>
          </div>
          <div class="bg-white p-4 rounded-2xl shadow-md">
            <p class="text-sm text-gray-500">% Good for Party</p>
            <p class="text-2xl font-bold text-pink-500">42%</p>
          </div>
          <div class="bg-white p-4 rounded-2xl shadow-md">
            <p class="text-sm text-gray-500">Top Genre</p>
            <p class="text-2xl font-bold text-green-600">Hip Hop</p>
          </div>
        </div>

        <!-- Song Table -->
        <div class="bg-white p-6 rounded-2xl shadow-md">
          <h2 class="text-xl font-semibold mb-4">Top Songs</h2>
          <table class="w-full text-sm text-left table-auto">
            <thead>
              <tr class="text-gray-600 border-b">
                <th class="py-2">Title</th>
                <th>Artist</th>
                <th>Genre</th>
                <th>Energy</th>
                <th>Emotion</th>
              </tr>
            </thead>
            <tbody>
              <tr class="hover:bg-gray-50">
                <td>Even When the Water's Cold</td>
                <td>!!!</td>
                <td>Hip Hop</td>
                <td>83</td>
                <td>Sadness</td>
              </tr>
              <!-- More rows dynamically inserted -->
            </tbody>
          </table>
        </div>

        <!-- Activity-based Genre Ranking -->
        <div class="bg-white p-6 rounded-2xl shadow-md">
          <h2 class="text-xl font-semibold mb-4">Top Genres by Activity</h2>
          <div class="h-64 bg-gray-100 rounded flex items-center justify-center text-gray-400">[Horizontal Bar Chart]</div>
        </div>

        <!-- Explicit vs Non-explicit Emotion -->
        <div class="bg-white p-6 rounded-2xl shadow-md">
          <h2 class="text-xl font-semibold mb-4">Explicit vs Non-Explicit Emotion Comparison</h2>
          <div class="h-64 bg-gray-100 rounded flex items-center justify-center text-gray-400">[Grouped Bar Chart]</div>
        </div>

        <!-- Recommendation Result -->
        <div class="bg-white p-6 rounded-2xl shadow-md">
          <h2 class="text-xl font-semibold mb-4">Top 5 Recommended Songs</h2>
          <ul class="space-y-2">
            <li class="bg-gray-100 p-3 rounded">🎧 <strong>Song Title</strong> - Artist (Score: 0.97)</li>
            <li class="bg-gray-100 p-3 rounded">🎧 Song 2 - Artist</li>
            <li class="bg-gray-100 p-3 rounded">🎧 Song 3 - Artist</li>
            <li class="bg-gray-100 p-3 rounded">🎧 Song 4 - Artist</li>
            <li class="bg-gray-100 p-3 rounded">🎧 Song 5 - Artist</li>
          </ul>
        </div>

        <!-- Predicted Genres by Activity -->
        <div class="bg-white p-6 rounded-2xl shadow-md">
          <h2 class="text-xl font-semibold mb-4">Predicted Genres for Activity</h2>
          <div class="h-64 bg-gray-100 rounded flex items-center justify-center text-gray-400">[Prediction Output]</div>
        </div>

        <!-- Factors for Activity Suitability -->
        <div class="bg-white p-6 rounded-2xl shadow-md">
          <h2 class="text-xl font-semibold mb-4">Factors that Make Songs Suitable for Specific Activities</h2>
          <div class="h-64 bg-gray-100 rounded flex items-center justify-center text-gray-400">
            [Radar Chart or Feature Importance Visualization]
          </div>
        </div>


        <!-- Duration Impact -->
        <div class="bg-white p-6 rounded-2xl shadow-md">
          <h2 class="text-xl font-semibold mb-4">Duration vs Popularity & Energy</h2>
          <div class="h-64 bg-gray-100 rounded flex items-center justify-center text-gray-400">[Boxplot or Dual Bar]</div>
        </div>
      </main>
    </div>
  </div>
</body>

</html>