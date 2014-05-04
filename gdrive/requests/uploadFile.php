<?php
//POST - access_token
//POST - folderID
//POST - filePath
//POST - mime


if ($_FILES["uploadFile"]["error"] > 0) {
	echo "Error: " . $_FILES["uploadFile"]["error"] . "\n";
} else {
	$folderID = $_POST['folderID'];
	$title = $_FILES["uploadFile"]["name"];
	$MIME = $_FILES["uploadFile"]["type"];
	$filepath = $_FILES["uploadFile"]["tmp_name"];
	$description = "This file was uploaded by the Purdue CGT 456 final project";
	$filepath = "../../tempUpload/".$_FILES["uploadFile"]["name"];
	/*echo "FolderID: ".$folderID."\n";
	echo "Title: " . $title . "\n";
	echo "MIME: " . $MIME . "\n";
	echo "File Path: " .$filepath."\n";
	echo "Description: ".$description."\n";*/
	
	//Move file
	move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $filepath);
	
	
	$subdir = "../";
	include($subdir."gdrive.php");
	$client = startGdrive($subdir);
	$client = authenticate($client, $_POST['access_token']);
	$service = new Google_Service_Drive($client);
	$insertedFile = uploadFile($service, $title, $description, $folderID, $MIME, $filepath);
	
	echo $filepath;
}

function removeExtension($string){
	return preg_replace("/\\.[^.\\s]{2,5}$/", "", $string);
}


?>