 <?php
//POST - access_token
//POST - title
//POST - parentID


$subdir = "../";

include($subdir."gdrive.php");

$client = startGdrive($subdir);

$client = authenticate($client, $_POST['access_token']);

$service = new Google_Service_Drive($client);

$description = "This folder was uploaded by the Purdue CGT 456 final project";

$folder = createFolder($service, $_POST['title'], $description, $_POST['parentID']);

echo $folder->title;

?>