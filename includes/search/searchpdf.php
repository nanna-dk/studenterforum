<?php

// Not for production use - still in progress...
// Parse all pdf files from a folder
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Query the database
include realpath(__DIR__.'/../db.php');
include '../../vendor/autoload.php';

$sql = 'SELECT * FROM '.$DBtable.' ORDER BY clicks DESC';

if (isset($_POST['search'])) {
    $search = $_POST['search'];

    $pdf_files = glob(dirname(__FILE__).'/../../opgaver/*.pdf', GLOB_BRACE);
    // Parse pdf file and build necessary objects.
    $parser = new \Smalot\PdfParser\Parser();

    foreach ($pdf_files as $file) {
        $pdf = $parser->parseFile($file);
        // Retrieve all pages from the pdf file.
        $pages = $pdf->getPages();

        // Get filename without extension
        $info = pathinfo($file);
        $filename = basename($file, '.'.$info['extension']);
        echo '<h3>'.$filename.'</h3>';

        // Loop over each page to extract text.
        foreach ($pages as $page) {
            if (false !== strpos($page->getText(), $search, 0)) {
                $text = $page->getText();
                $text = preg_replace("/($search)/i", '<mark>$1</mark>', $text);
                echo $text;
            }
        }

        echo '<br><br><hr>';
    }
}
?>
