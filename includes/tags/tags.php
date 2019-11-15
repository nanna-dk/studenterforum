<?php

// Functions to create tag cloud from db row of keywords
// Query the database
include realpath(__DIR__.'/../db.php');
include_once realpath(__DIR__.'/../functions.php');

$sql = 'SELECT title, description, content FROM '.$DBtable.' ORDER BY id DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute();
if ($stmt->rowCount() > 0) {
    $result = $stmt->fetchAll();
    $freqData = [];
    foreach ($result as $row) {
        $keywords = $row['description'];
        //$keywords .= $row['title'];
        //$keywords .= $row['content'];
        // Set letter count to exclude words like 'and'/'or', etc.
        $letterCount = 5;

        // Get individual words and build a frequency table - allow special chars
        foreach (str_word_count($keywords, 1, 'øæåé1233456789') as $word) {
            // If the word has more than x letters
            if (mb_strlen($word, 'UTF-8') >= $letterCount) {
                // For each word found in the frequency table, increment its value by one
                array_key_exists($word, $freqData) ? $freqData[$word]++ : $freqData[$word] = 1;
            }
        }

        // Custom words and their frequency - always include
        $mandatory_words = [
            'cscw' => 4,
            'hci' => 3,
            'opgave' => 4,
            'nationalisme' => 3,
            'speciale' => 3,
            'multimedier' => 4,
            'ETA' => 4,
        ];

        // Words to filter out
        $remove_words = [
          'disse',
          'spiller',
          'herunder',
          'hvorfor',
          'data-target',
          'data-toggle',
        ];

        // Merge the two arrays
        $freqData = array_merge($freqData, $mandatory_words);
        // Filter out the words
        $freqData = array_diff_key($freqData, array_flip($remove_words));
        ksort($freqData);
    }
} else {
    echo 'Unable to fetch keywords';
}

function getCloud($data = [], $minFontSize = 12, $maxFontSize = 22, $totalOutput = 20) {
    $minimumCount = min(array_values($data));
    $maximumCount = max(array_values($data));
    $spread = $maximumCount - $minimumCount;
    $cloudTags = [];
    0 == $spread && $spread = 1;
    $max = 1; // or default 0

    foreach ($data as $tag => $count) {
        if ($count > $max) {
            $size = $minFontSize + ($count - $minimumCount) * ($maxFontSize - $minFontSize) / $spread;
            $tag = strtolower($tag);
            $cloudTags[] = '<a style="font-size: '.floor($size).'px'.'" class="tags" data-tag="'.$tag.'" href="#" title="\''.$tag.'\' er fundet '.$count.' gange">'.htmlentities(stripslashes($tag)).'</a>';
        }
    }

    return join("\n", $cloudTags)."\n";
}

// Closing
$stmt = null;
$pdo = null;

echo getCloud($freqData);
?>
