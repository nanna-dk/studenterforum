<?php
/*
* Function to display info from db: <?php displayHits(1) ?> etc.
*/
//error_reporting(E_ALL);
function displayHits($id) {
    include realpath(__DIR__.'/../db.php');
    $sql = 'SELECT clicks FROM '.$DBtable.' WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetchAll();
        foreach ($result as $row) {
            $clicks = number_format($row['clicks'], 0, '', '.');
            echo $clicks;
        }
    }
    $stmt = null;
    $pdo = null;
}

function displayTitle($id) {
    include realpath(__DIR__.'/../db.php');
    $sql = 'SELECT title FROM '.$DBtable.' WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetchAll();
        foreach ($result as $row) {
            $titles = $row['title'];
            echo $titles;
        }
    }
    $stmt = null;
    $pdo = null;
}

function displayDesc($id) {
    include realpath(__DIR__.'/../db.php');
    $sql = 'SELECT description FROM '.$DBtable.' WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetchAll();
        foreach ($result as $row) {
            $desc = $row['description'];
            echo $desc;
        }
    }
    $stmt = null;
    $pdo = null;
}

function displayDate($id) {
    include realpath(__DIR__.'/../db.php');
    $sql = 'SELECT dates FROM '.$DBtable.' WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetchAll();
        foreach ($result as $row) {
            $dates = (date('d. m. Y', strtotime($row['dates'])));
            echo $dates;
        }
    }
    $stmt = null;
    $pdo = null;
}

function countDownloads() {
    include realpath(__DIR__.'/../db.php');
    $sql = 'SELECT SUM(`clicks`) FROM '.$DBtable.'';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $total = $stmt->fetch(PDO::FETCH_NUM);
        $clicks = number_format($total[0], 0, '', '.');
        echo 'Opgaverne er tilsammen downloadet i alt '.$clicks.' gange';
    } else {
        echo '';
    }
    $stmt = null;
    $pdo = null;
}

function displayFilesize($id) {
  include realpath(__DIR__.'/../db.php');
  $sql = 'SELECT url FROM '.$DBtable.' WHERE id = :id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  if ($stmt->rowCount() > 0) {
      $result = $stmt->fetchAll();
      foreach ($result as $row) {
          $url = $row['url'];
          $file = $global_path.$url;
          if (file_exists($file) || (file_exists($file) && filesize($file) < 5000)) {
            $filesize = filesize($file);
            $filesize = round($filesize / 1024 / 1024, 1); // megabytes with 1 digit.
            echo "$filesize MB";
        }
      }
  }
  $stmt = null;
  $pdo = null;
}
?>
