 <?php
//POST - access_token
//POST - fileID

$subdir = "../";

include($subdir."gdrive.php");

$client = startGdrive($subdir);

$client = authenticate($client, $_POST['access_token']);

$service = new Google_Service_Drive($client);


if(!empty($_POST['delete']) && $_POST['delete']){
	$deleteResult = deleteFile($service, $_POST['fileID']);
}else{
	$deleteResult = trashFile($service, $_POST['fileID']);
}

echo $deleteResult;

?>