<?php

require_once "/Applications/XAMPP/xamppfiles/htdocs/456/dropbox/sdk/lib/Dropbox/autoload.php";
use \Dropbox as dbx;

$dbxClient = getClient();

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

function getPath($relative_path)
{
    if (PHP_SAPI === 'cli-server') {
        return "/".$relative_path;
    } else {
        return $_SERVER["SCRIPT_NAME"]."/".$relative_path;
    }
}

function getClient()
{
    if(!isset($_SESSION['access-token'])) {
        return false;
    }

    list($appInfo, $clientIdentifier, $userLocale) = getAppConfig();
    $accessToken = $_SESSION['access-token'];
    return new dbx\Client($accessToken, $clientIdentifier, $userLocale, $appInfo->getHost());
}

?>