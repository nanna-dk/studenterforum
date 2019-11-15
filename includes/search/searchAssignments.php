<?php

// Searching the database
include realpath(__DIR__.'/../db.php');

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $search = filter_var($search, FILTER_SANITIZE_STRING);
    $search = filter_var($search, FILTER_SANITIZE_SPECIAL_CHARS);

    $sql = 'SELECT * FROM '.$DBtable.' WHERE title LIKE :search OR description LIKE :search OR content LIKE :search ORDER BY title LIKE :search DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':search', '%'.$search.'%', PDO::PARAM_STR);
    $stmt->execute();
    //set counter variable
    $counter = 1;
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetchAll();
        $total = $stmt->rowCount();
        echo '<div id="searchResults">';
        echo '<div class="totalResults">Antal resultater: '.$total.'</div>';
        foreach ($result as $row) {
            include realpath(__DIR__.'/../tpl/assignment.php');
            ++$counter;
        }
    } else {
        echo '<div class="alert alert-warning" role="alert">Ingen resultater fundet.</div>';
    }
    echo '</div>';

    // Closing
    $stmt = null;
    $pdo = null;
}
?>
