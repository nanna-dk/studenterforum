<?php
// continue only if $_POST is set and it is a Ajax request
if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  require_once(realpath(__DIR__ . '/../db.php'));
  //error_reporting(E_ALL);
  $limit = (intval($_GET['limit']) != 0 ) ? $_GET['limit'] : 10;
  $offset = (intval($_GET['offset']) != 0 ) ? $_GET['offset'] : 0;

  $sql = "SELECT * FROM " . $DBtable . " WHERE 1 ORDER BY dates DESC LIMIT :limit OFFSET :offset";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
  $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
  $stmt->execute();
  $results = $stmt->fetchAll();

  if (count($results) > 0) {
    foreach ($results as $row) {
      $id = $row['id'];
      $title = $row['title'];
      $desc = $row['description'];
      $content = $row['content'];
      $url = $row['url'];
      $clicks = number_format($row['clicks'], 0, '', '.');
      $dates = (date('d.m.Y', strtotime($row['dates'])));
      echo '<div class="card">';
      echo '<h4 class="card-header"><a href="includes/downloads/downloads.php?id=' . $id . '" target="_blank" rel="noopener" title="Download">' . $title . '</a></h4>';
      echo '<div class="card-block"><p class="card-text">' . $desc . '</p></div>';
      echo '<div class="card-footer"><div class="footer-left">Oprettet: ' . $dates . '</div><div class="footer-right">Downloads: ' . $clicks . '</div></div>';
      echo '</div>';
    }
  }
}
?>
