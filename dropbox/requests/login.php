<?php
require_once "/Applications/XAMPP/xamppfiles/htdocs/456/dropbox/sdk/lib/Dropbox/autoload.php";
use \Dropbox as dbx;

$appInfo = dbx\AppInfo::loadFromJsonFile("/Applications/XAMPP/xamppfiles/htdocs/456/dropbox/requests/authorization.json");
$webAuth = new dbx\WebAuthNoRedirect($appInfo, "PHP-Example/1.0");

$authorizeUrl = $webAuth->start();

echo "1. Go to: " . $authorizeUrl . "\n";
echo "2. Click \"Allow\" (you might have to log in first).\n";
echo "3. Copy the authorization code.\n";
$authCode = readline("Enter the authorization code here: ");
//$authCode = "BVzYJHiCfM4AAAAAAAAAEc3MUQEporJGZbh5I0fHTM4";

list($accessToken, $dropboxUserId) = $webAuth->finish($authCode);
print "Access Token: " . $accessToken . "\n";

$dbxClient = new dbx\Client($accessToken, "PHP-Example/1.0");
$accountInfo = $dbxClient->getAccountInfo();

print_r($accountInfo);

?>