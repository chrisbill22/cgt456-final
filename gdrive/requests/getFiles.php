<?php
//POST - access_token
//POST - folderID

$subdir = "../";

include($subdir."gdrive.php");

$client = startGdrive($subdir);

$client = authenticate($client, $_POST['access_token']);

$service = new Google_Service_Drive($client);

$allFiles = retrieveFiles($service, $_POST['folderID']);
echo json_encode($allFiles);

?>