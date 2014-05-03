<?php 
session_start();
$subDir = "../";
include("../requests/authorize.php");
$newName = $_POST["newName"];
$dbxClient->move($_POST['oldPath'], $_POST['path']."/".$newName);

echo "True";

//header("Location: ../dropbox.php");
?>