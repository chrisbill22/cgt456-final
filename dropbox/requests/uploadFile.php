<?php session_start();

require_once "../sdk/lib/Dropbox/autoload.php";
use \Dropbox as dbx;

if ($_FILES["uploadFile"]["error"] > 0) {
	echo "Error: " . $_FILES["uploadFile"]["error"] . "\n";
} else {
	$folderID = $_POST['folderID'];
	$title = $_FILES["uploadFile"]["name"];
	$MIME = $_FILES["uploadFile"]["type"];
	$filepath = $_FILES["uploadFile"]["tmp_name"];
	$description = "File was uploaded by the Purdue CGT 456 final project";

	$subDir = "../";
	include("../requests/authorize.php");
	
	
	$remoteDir = "/";
	if (isset($_POST['folderID'])) $remoteDir = $_POST['folderID'];
	if($_POST['folderID'] == "root"){ $remoteDir = "/"; }
	
	$fp = fopen($_FILES['uploadFile']['tmp_name'], "rb");
	$remotePath = rtrim($remoteDir, "/")."/".$_FILES['uploadFile']['name'];
	$result = $dbxClient->uploadFile($remotePath, dbx\WriteMode::add(), $fp);
	fclose($fp);
	$str = print_r($result, TRUE);
	echo $str;
}

function removeExtension($string){
	return preg_replace("/\\.[^.\\s]{2,5}$/", "", $string);
}


?>