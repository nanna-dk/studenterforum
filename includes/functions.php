<?php
/*
* Global functions
*/

// Print out server name in a safe way
function siteUrl() {
    $base_url = "https://{$_SERVER['SERVER_NAME']}";
    $site = htmlspecialchars($base_url, ENT_QUOTES, 'UTF-8');
    return $site;
}

// Print out full site url in a safe way
function fullSiteUrl() {
    $base_url = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    $site = htmlspecialchars($base_url, ENT_QUOTES, 'UTF-8');
    return $site;
}

// Formats filesize in corrects units
function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2, ',', ' ').' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2, ',', ' ').' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 1, ',', ' ').' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes.' bytes';
    } elseif (1 == $bytes) {
        $bytes = $bytes.' byte';
    } else {
        $bytes = '0 bytes';
    }
    return $bytes;
}

?>
