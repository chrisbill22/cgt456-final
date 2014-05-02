<?php 
session_start();
$subDir = "../";
include("../requests/authorize.php");
$dbxClient->move($_GET['path'], 'dog.png');
header("Location: ../dropbox.php");
?>