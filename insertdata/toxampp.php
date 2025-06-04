<?php
// 🚀 Allow unlimited memory and time
set_time_limit(0);
ini_set('memory_limit', '-1');

// 🔗 Connect to MySQL (XAMPP default user/pass)
$conn = new mysqli("localhost", "root", "", "spotify");
if ($conn->connect_error) {
  die("❌ Connection failed: " . $conn->connect_error);
}

// 📂 Load converted JSON
$json = file_get_contents("converted_songs.json");
$songs = json_decode($json, true);

if ($songs === null) {
  die("❌ JSON Error: " . json_last_error_msg());
}

echo "✅ JSON loaded, total songs: " . count($songs) . "<br><br>";

// 🔁 Loop through each song
foreach ($songs as $index => $song) {
  // 🧼 Escape strings
  $title = $conn->real_escape_string($song['song']);
  $artist = $conn->real_escape_string($song['Artist(s)']);
  $lyrics = $conn->real_escape_string($song['text']);
  $length = $song['Length'];
  $emotion = $conn->real_escape_string($song['emotion']);
  $genre = $conn->real_escape_string($song['Genre']);
  $album = $conn->real_escape_string($song['Album']);
  $releaseDate = date('Y-m-d', strtotime($song['Release Date']));
  $key = $conn->real_escape_string($song['Key']);
  $tempo = floatval($song['Tempo']);
  $loudness = floatval($song['Loudness (db)']);
  $timeSig = $conn->real_escape_string($song['Time signature']);
  $explicit = strtolower($song['Explicit']) === 'yes' ? 1 : 0;
  $popularity = intval($song['Popularity']);
  $energy = intval($song['Energy']);
  $danceability = intval($song['Danceability']);
  $positiveness = intval($song['Positiveness']);
  $speechiness = intval($song['Speechiness']);
  $liveness = intval($song['Liveness']);
  $acousticness = intval($song['Acousticness']);
  $instrumentalness = intval($song['Instrumentalness']);
  $goodParty = intval($song['Good for Party']);
  $goodWork = intval($song['Good for Work/Study']);
  $goodRelax = intval($song['Good for Relaxation/Meditation']);
  $goodExercise = intval($song['Good for Exercise']);
  $goodRunning = intval($song['Good for Running']);
  $goodYoga = intval($song['Good for Yoga/Stretching']);
  $goodDrive = intval($song['Good for Driving']);
  $goodSocial = intval($song['Good for Social Gatherings']);
  $goodMorning = intval($song['Good for Morning Routine']);

  // 🧾 Insert into Songs
  $sql = "INSERT INTO Songs (
        Title, Artist, Lyrics, Length, Emotion, Genre, Album, ReleaseDate, SongKey,
        Tempo, LoudnessDb, TimeSignature, Explicit, Popularity, Energy, Danceability,
        Positiveness, Speechiness, Liveness, Acousticness, Instrumentalness,
        GoodForParty, GoodForWorkStudy, GoodForRelaxationMeditation, GoodForExercise,
        GoodForRunning, GoodForYogaStretching, GoodForDriving, GoodForSocialGatherings, GoodForMorningRoutine
    ) VALUES (
        '$title', '$artist', '$lyrics', '$length', '$emotion', '$genre', '$album', '$releaseDate', '$key',
        $tempo, $loudness, '$timeSig', $explicit, $popularity, $energy, $danceability,
        $positiveness, $speechiness, $liveness, $acousticness, $instrumentalness,
        $goodParty, $goodWork, $goodRelax, $goodExercise,
        $goodRunning, $goodYoga, $goodDrive, $goodSocial, $goodMorning
    )";

  if ($conn->query($sql)) {
    $songId = $conn->insert_id;

    // 🔁 Insert Similar Songs (if exists)
    if (isset($song['Similar Songs']) && is_array($song['Similar Songs'])) {
      foreach ($song['Similar Songs'] as $similar) {
        $artistKey = array_keys($similar)[0];
        $songKey = array_keys($similar)[1];

        $similarArtist = $conn->real_escape_string($similar[$artistKey]);
        $similarSong = $conn->real_escape_string($similar[$songKey]);
        $similarity = floatval($similar['Similarity Score']);

        $simSql = "INSERT INTO SimilarSongs (SongID, SimilarArtist, SimilarSongTitle, SimilarityScore)
                           VALUES ($songId, '$similarArtist', '$similarSong', $similarity)";
        $conn->query($simSql);
      }
    }

    echo "✅ Inserted song #$index: $title<br>";
  } else {
    echo "❌ Error on song #$index: " . $conn->error . "<br>";
  }
}

$conn->close();
echo "<br>🎉 Import complete!";
