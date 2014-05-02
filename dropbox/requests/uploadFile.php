<?php session_start();

require_once "../sdk/lib/Dropbox/autoload.php";
use \Dropbox as dbx;

if ($_FILES["dropboxFile"]["error"] > 0) {
	echo "Error: " . $_FILES["dropboxFile"]["error"] . "\n";
} else {
	$folderID = $_POST['folderID'];
	$title = $_FILES["dropboxFile"]["name"];
	$MIME = $_FILES["dropboxFile"]["type"];
	$filepath = $_FILES["dropboxFile"]["tmp_name"];
	$description = "File was uploaded by the Purdue CGT 456 final project";

	$subDir = "../";
	include("../requests/authorize.php");
	
	
	$remoteDir = "/";
	if (isset($_POST['folderID'])) $remoteDir = $_POST['folderID'];
	if($_POST['folderID'] == "root"){ $remoteDir = "/"; }
	
	$fp = fopen($_FILES['dropboxFile']['tmp_name'], "rb");
	$remotePath = rtrim($remoteDir, "/")."/".$_FILES['dropboxFile']['name'];
	$result = $dbxClient->uploadFile($remotePath, dbx\WriteMode::add(), $fp);
	fclose($fp);
	$str = print_r($result, TRUE);
	echo $str;
}

function removeExtension($string){
	return preg_replace("/\\.[^.\\s]{2,5}$/", "", $string);
}


?>