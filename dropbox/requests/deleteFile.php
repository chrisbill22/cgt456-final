<?php
session_start();
$subDir = "../";
include("../requests/authorize.php");
$dbxClient->delete($_GET['path']);
header("Location: ../dropbox.php");
?>