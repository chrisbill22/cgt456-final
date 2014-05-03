<?php
session_start();
$subDir = "../";
include("../requests/authorize.php");

$path = $_POST['path'];
if($path == ""){
	$path = "/";
}

$dbxClient->createFolder($path."/".$_POST['newName']);
echo "true";
//header("Location: ../dropbox.php");
?>