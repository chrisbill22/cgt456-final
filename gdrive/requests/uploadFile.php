<?php
//POST - access_token
//POST - folderID
//POST - filePath
//POST - mime


if ($_FILES["gdriveFile"]["error"] > 0) {
	echo "Error: " . $_FILES["gdriveFile"]["error"] . "\n";
} else {
	$folderID = $_POST['folderID'];
	$title = $_FILES["gdriveFile"]["name"];
	$MIME = $_FILES["gdriveFile"]["type"];
	$filepath = $_FILES["gdriveFile"]["tmp_name"];
	$description = "This file was uploaded by the Purdue CGT 456 final project";
	/*echo "FolderID: ".$folderID."\n";
	echo "Title: " . $title . "\n";
	echo "MIME: " . $MIME . "\n";
	echo "File Path: " .$filepath."\n";
	echo "Description: ".$description."\n";*/
	$subdir = "../";
	include($subdir."gdrive.php");
	$client = startGdrive($subdir);
	$client = authenticate($client, $_POST['access_token']);
	$service = new Google_Service_Drive($client);
	$insertedFile = uploadFile($service, $title, $description, $folderID, $MIME, $filepath);
	echo $insertedFile->title;
}

function removeExtension($string){
	return preg_replace("/\\.[^.\\s]{2,5}$/", "", $string);
}


?>