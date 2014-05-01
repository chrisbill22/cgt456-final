<?php
//POST - access_token
//POST - fileID
//POST - oldParent
//POST - newParent

$subdir = "../";

include($subdir."gdrive.php");

$client = startGdrive($subdir);

$client = authenticate($client, $_POST['access_token']);

$service = new Google_Service_Drive($client);

$allFiles = moveFile($service, $_POST['fileID'], $_POST['oldParent'], $_POST['mewParent']);
echo $allFiles;

?>