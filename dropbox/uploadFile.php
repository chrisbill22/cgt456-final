<?php

echo "Hi";
exit;

include("requests/authorize.php");

/*
require_once "/Applications/XAMPP/xamppfiles/htdocs/456/dropbox/sdk/lib/Dropbox/autoload.php";
use \Dropbox as dbx;

if (empty($_FILES['file']['name'])) {
	echo renderHtmlPage("Error", "Please choose a file to upload");
	exit;
}

if (!empty($_FILES['file']['error'])) {
	echo renderHtmlPage("Error", "Error ".$_FILES['file']['error']." uploading file.  See <a href='http://php.net/manual/en/features.file-upload.errors.php'>the docs</a> for details");
	exit;
}


$remoteDir = "/";
if (isset($_POST['folder'])) $remoteDir = $_POST['folder'];

$remotePath = rtrim($remoteDir, "/")."/".$_FILES['file']['name'];

$fp = fopen($_FILES['file']['tmp_name'], "rb");
$result = $dbxClient->uploadFile($remotePath, dbx\WriteMode::add(), $fp);
fclose($fp);
$str = print_r($result, TRUE);
echo renderHtmlPage("Uploading File", "Result: <pre>$str</pre>");

function renderHtmlPage($title, $body)
{
    return <<<HTML
    <html>
        <head>
            <title>$title</title>
        </head>
        <body>
            <h1>$title</h1>
            $body
        </body>
    </html>
HTML;
}
*/

$remoteDir = "/";
if (isset($_POST['folder'])) $remoteDir = $_POST['folder'];
$fp = fopen($_FILES['file']['tmp_name'], "rb");
$remotePath = rtrim($remoteDir, "/")."/".$_FILES['file']['name'];
$result = $dbxClient->uploadFile($remotePath, dbx\WriteMode::add(), $fp);
fclose($fp);
$str = print_r($result, TRUE);
//header("Location: dropbox.php");

/*
// upload files
$f = fopen("requests/working-draft.txt", "rb");
$result = $dbxClient->uploadFile("/requests/working-draft.txt", dbx\WriteMode::add(), $f);
fclose($f);
print_r($result);

$folderMetadata = $dbxClient->getMetadataWithChildren("/");
print_r($folderMetadata);
*/
?>