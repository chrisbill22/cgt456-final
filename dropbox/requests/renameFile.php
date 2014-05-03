<?php 
session_start();
$subDir = "../";
include("../requests/authorize.php");
$newName = $_POST["newName"];
$dbxClient->move($_GET['path'], "/".$newName);
header("Location: ../dropbox.php");
?>