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

?>
