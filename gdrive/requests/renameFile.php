<?php
//POST - access_token
//POST - fileID
//POST - newFileName

$subdir = "../";

include($subdir."gdrive.php");

$client = startGdrive($subdir);

$client = authenticate($client, $_POST['access_token']);

$service = new Google_Service_Drive($client);

$updateFile = renameFile($service, $_POST['fileID'], $_POST['newFileName']);
echo $updateFile->title;

?>