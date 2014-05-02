<?php
session_start();
$subDir = "../";
include("../requests/authorize.php");
$dbxClient->createFolder('/new folder');
header("Location: ../dropbox.php");
?>