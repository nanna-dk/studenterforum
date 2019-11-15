<?php

// Database statistics
include realpath(__DIR__.'/../db.php');

// Most clicked items
$sql = 'SELECT * FROM '.$DBtable.' ORDER BY clicks DESC'; // LIMIT 5

$stmt = $pdo->prepare($sql);
$stmt->execute();
  if ($stmt->rowCount() > 0) {
      $result = $stmt->fetchAll();
      echo '<div id="searchResults">';
      countDownloads();
      foreach ($result as $row) {
          $rating = $row['rating'];
          $votes = $row['votes'];
          $avg = (0 == $rating) ? 0 : round(($rating / $votes), 1);
          $totalVotes = (1 == $votes) ? 'stemme' : 'stemmer';
          $rateStats = (0 == $rating) ? 'Ingen stemmer afgivet' : "$avg/5 baseret på $votes $totalVotes";
          $id = $row['id'];
          $title = $row['title'];
          $clicks = $row['clicks'];
          $hits = number_format($clicks, 0, '', '.');
          $dlDate = date('d. m. Y', strtotime($row['dl_time']));
          $percentage = round($clicks / 100);
          echo '<p class="card-text"><a href="includes/downloads/downloads.php?id='.$id.'" target="_blank">'.$title.'</a></p>';
          echo '<div class="progress" title="'.$title.', downloaded '.$hits.' gange">';
          echo '<div class="progress-bar" role="progressbar" style="width:'.$percentage.'%" aria-valuenow="'.$percentage.'" aria-valuemin="0" aria-valuemax="100">'.$hits.'</div>';
          echo '</div>';
          echo '<div class="small">Sidst downloaded: '.$dlDate.'.</div>';
          echo '<div class="small">Bedømmelse: '.$rateStats.'.</div><br />';
      }
  } else {
      echo 'Ingen resultater fundet.';
  }
  // echo '<div><h5>GitHub-statistik for dette website</h5><p>Commits:</p>';
  // echo '<div id="gitHubStats"></div>';
  echo '</div>';
// Closing
$stmt = null;
$pdo = null;
?>
