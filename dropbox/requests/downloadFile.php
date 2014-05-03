<?php
 
session_start();
$subDir = "../";
include("../requests/authorize.php");

$f = tmpfile();
$metadata = $dbxClient->getFile($_GET['path'], $f);

header("Content-Type: $metadata[mime_type]");
header("Content-disposition: attachment; filename='".substr($metadata['path'], 1)."'");

fseek($f, 0);
fpassthru($f);
fclose($f);


//header("Location: ../dropbox.php");

?>