<?php
/*
Script to host urls, and display the item based on its ID:
Usage: /downloads.php?id=x
*/
include realpath(__DIR__.'/../db.php');
if (0 !== (int) $_GET['id']) {
    $id = (int) $_GET['id'];
    $sql = 'SELECT id, clicks, url FROM '.$DBtable.' WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetchAll();
        foreach ($result as $row) {
          $location = $global_path.$row['url'];
          //header('Location: ' .$location);
          //$mm_type = mime_content_type($location);
          header('Pragma: public');
          header('Expires: 0');
          header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
          header('Cache-Control: public');
          header('Content-Description: File Transfer');
          // header('Content-Type: '.$mm_type);
          header('Content-Length: '.(string) (filesize($location)));
          header('Content-Disposition: attachment; filename="'.basename($location).'"');
          // header("Content-Transfer-Encoding: binary\n");
          readfile($location); // outputs the content of the file
          exit();
        }

        // Update counter by one and add a timestamp (plus 1 hour to get correct time zone on remote db)
        $sql2 = 'UPDATE '.$DBtable.' SET clicks = clicks + 1, dl_time = DATE_ADD(now(), INTERVAL 1 HOUR) WHERE id= :id';

        $stmt2 = $pdo->prepare($sql2);
        $stmt2->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt2->execute();
    }
    $stmt = null;
    $pdo = null;
} else {
    echo 'Invalid request';
}
?>
