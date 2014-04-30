<?php
require_once "/Applications/XAMPP/xamppfiles/htdocs/456/dropbox/sdk/lib/Dropbox/autoload.php";
use \Dropbox as dbx;

$appInfoFile = ("/Applications/XAMPP/xamppfiles/htdocs/456/dropbox/requests/authorization.json");

$dbxClient = getClient();

if ($dbxClient === false) {
	$authorizeUrl = getWebAuth()->start();
    header("Location: $authorizeUrl");
	exit;
}

function getAppConfig()
{
    global $appInfoFile;

    try {
        $appInfo = dbx\AppInfo::loadFromJsonFile($appInfoFile);
    }
    catch (dbx\AppInfoLoadException $ex) {
        throw new Exception("Unable to load \"$appInfoFile\": " . $ex->getMessage());
    }

    $clientIdentifier = "examples-web-file-browser";
    $userLocale = null;

    return array($appInfo, $clientIdentifier, $userLocale);
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