<?php 
session_start();
$subDir = "../";
include("../requests/authorize.php");
$dbxClient->move($_GET['path'], "/".$_GET['newName']);
header("Location: ../dropbox.php");
?>