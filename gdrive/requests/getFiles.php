<?php
//POST - access_token
//POST - folder

$subdir = "../";

include($subdir."gdrive.php");

$client = startGdrive($subdir);

$client = authenticate($client, $_POST['access_token']);

//$client = new Google_Client();


$service = new Google_Service_Drive($client);

$allFiles = retrieveFiles($service, $_POST['folderID']);
echo json_encode($allFiles);

?>