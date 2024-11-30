<html>
<head>
   <title>Music Player</title>
   
   <style>
      body {
         display: flex; 
         flex-direction: column;
         align-items: center;
         justify-content: center;
      }
      table {
         width: 100%;
         border-collapse: collapse;
         margin-bottom: 20px;
       }
       table, th, td {
         border: 1px solid #ddd;
       }
       th, td {
         padding: 12px;
         text-align: left;
       }
       th {
         background-color: #f2f2f2;
       }
       tr {
         background-color: #f9f9f9;
       }
   </style>
</head>
  
<body>
   <h1>Music Player</h1>

   <?php
   $db = new SQLite3 ("music.db");
   
   $db->exec ("CREATE TABLE IF NOT EXISTS artists (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT)");
   
   $db->exec ("CREATE TABLE IF NOT EXISTS songs (id INTEGER PRIMARY KEY AUTOINCREMENT, artist_id INTEGER, title TEXT, link TEXT, FOREIGN KEY (artist_id) REFERENCES artists (id))");

   if (isset ($_POST ['add_artist'])) {
      $name = $_POST ['artist_name'];
      $db->exec ("INSERT INTO artists (name) VALUES ('$name')");
   }

   if (isset ($_POST ['add_song'])) {
      $name = $_POST ['song_name'];
      $artist = $_POST ['artist_id'];
      $link = $_POST ['song_link'];
      $db->exec ("INSERT INTO songs (artist_id, title, link) VALUES ('$artist', '$name', '$link')");
   }
   
   ?>

   <h2>Add Artist</h2>
   <form action = "index.php" method = "POST">
      <label for = "artist"> Artist:</label>
      <input type = "text" name = "artist_name">

      <button type = "submit" name = "add_artist">Add Artist</button>
   </form>

   <h2>Add Song</h2>
   <form action = "index.php" method = "POST">
      <label for = "song"> Song:</label>
      <input type = "text" name = "song_name">

      <label for = "artist"> Artist ID:</label>
      <input type = "text" name = "artist_id">

      <label for = "link"> Link:</label>
      <input type = "text" name = "song_link">
      
      <button type = "submit" name = "add_song">Add Song</button>
   </form>

   <h2>All Songs and Artists</h2>
   <?php
   $result = $db->query ("SELECT artists.name, songs.title, songs.link, songs.artist_id FROM artists INNER JOIN songs ON artists.id = songs.artist_id");

   echo "<table>";
   echo "<tr>";
   echo "<th> Artist </th>";
   echo "<th> Song </th>";
   echo "<th> Link </th>";
   echo "</tr>";

   while ($row = $result->fetchArray (SQLITE3_ASSOC)) {
      echo "<tr>";
      echo "<td>" . $row ['name'] . "</td>";
      echo "<td>" . $row ['title'] . "</td>";
      echo "<td>";
      parse_str(parse_url($row['link'], PHP_URL_QUERY), $query);
      $video_id = $query['v'] ?? '';
      echo "<iframe width='200' height='150' src='https://www.youtube.com/embed/$video_id' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
      echo "</td>";
      echo "<tr>";
   }

   echo "</table>";
   ?>

</body>
</html>
  
