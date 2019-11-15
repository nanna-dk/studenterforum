<?php

header('Content-Type: text/xml; charset=utf-8', true);

// Query the database
include realpath(__DIR__.'/../db.php');

// General functions
include realpath(__DIR__.'/../functions.php');

//Server timezone
date_default_timezone_set('Europe/Copenhagen');

$rss = new SimpleXMLElement('<rss xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom"></rss>');
$rss->addAttribute('version', '2.0');

$channel = $rss->addChild('channel');

// $atom = $channel->addChild('atom:atom:link');
// $atom->addAttribute('href', siteUrl());
// $atom->addAttribute('rel', 'self');
// $atom->addAttribute('type', 'application/rss+xml');

$title = $channel->addChild('title', 'Nanna Ellegaard');
$description = $channel->addChild('description', 'Nanna Ellegaard, Cand.it i Multimedier. Find opgaver fra Spansk og Cand.it i Multimedier fra Aarhus Universitet.');
$link = $channel->addChild('link', fullSiteUrl());
$language = $channel->addChild('language', 'da');

//Create RFC822 Date format to comply with RFC822
$date_f = date('D, d M Y H:i:s T', time());
$build_date = gmdate(DATE_RFC2822, strtotime($date_f));
$lastBuildDate = $channel->addChild('lastBuildDate', $build_date);

$sql = 'SELECT * FROM '.$DBtable.' ORDER BY dates DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $result = $stmt->fetchAll();
    foreach ($result as $row) {
        $titles = $row['title'];
        $full_desc = $row['description'];
        $desc = preg_replace('/<a[^>]*>.*?<\/a>/i', '', $full_desc);
        $id = $row['id'];
        $url = siteUrl().'/includes/downloads/downloads.php?id='.$id;
        $dates = $row['dates'];

        $item = $channel->addChild('item');
        $title = $item->addChild('title', $titles);
        $link = $item->addChild('link', $url);
        $guid = $item->addChild('guid', $url);
        $guid->addAttribute('isPermaLink', 'true');
        $description = $item->addChild('description', $desc);
        $date_rfc = gmdate(DATE_RFC2822, strtotime($dates));
        $item = $item->addChild('pubDate', $date_rfc);
    }
} else {
    echo 'Ingen resultater fundet.';
}

echo $rss->asXML();

// Closing
$stmt = null;
$pdo = null;
?>
