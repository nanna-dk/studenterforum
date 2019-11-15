<?php
    include_once realpath(__DIR__.'/../db.php');
    if (isset($_POST['vote'])) {
        $id = (int) $_POST['item'];
        $points = (int) $_POST['point'];
        setcookie('Rating', 1, time() + 3600, '/'); // One hour
        $query = $pdo->prepare('SELECT title, votes, rating FROM '.$DBtable.' WHERE `id` = ?');
        $query->execute([$id]);
        while ($row = $query->fetchObject()) {
            $pointsUpdate = ($row->rating + $points);
            $voteUpdate = ($row->votes + 1);
            $title = $row->title;
            $updateQuery = $pdo->prepare('UPDATE '.$DBtable.' SET `votes` = ?, `rating` = ? WHERE `id` = ?');
            if ($updateQuery->execute([$voteUpdate, $pointsUpdate, $id])) {
                $avg = round(($pointsUpdate / $voteUpdate), 1);
                // Send mail
                // $headers = "MIME-Version: 1.0" . "\r\n";
                // $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                // $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                // $headers .= 'From: Admin <'. $adminMail .'>' . "\r\n";
                // $headers .= 'Reply-To: Admin <'. $adminMail .'>' . "\r\n";
                // $ip = $_SERVER['REMOTE_ADDR'];
                // $domain = $_SERVER['SERVER_NAME'];
                // $subject = $domain . ': Rating af opgaven "' . $title . '".';
                // $body = "<p>" . $subject . " Gennemnitlig score er nu " . $avg . " baseret på ". $voteUpdate ." stemme(r).</p>";
                // $body .= "<p><strong>IP-adresse:</strong> ". $ip ."</p>";
                // if (mail(". $adminMail .", $subject, $body, $headers)) {
                // 	// Success
                // 	http_response_code(200);
                // }
                // else {
                // 	// Error
                // 	http_response_code(500);
                // }
                // Return data
                die(json_encode(['average' => $avg, 'votes' => $voteUpdate]));
            } else {
                echo 'Could not update votes';
            }
        }
    }
?>
