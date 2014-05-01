<?php

require_once "/Applications/XAMPP/xamppfiles/htdocs/456/dropbox/sdk/lib/Dropbox/autoload.php";
use \Dropbox as dbx;

if (!isset($_GET['path'])) {
	header("Location: ".getPath(""));
	exit;
}
$path = $_GET['path'];

$fd = tmpfile();
$metadata = $dbxClient->getFile($path, $fd);

header("Content-Type: $metadata[mime_type]");
fseek($fd, 0);
fpassthru($fd);
fclose($fd);

?>