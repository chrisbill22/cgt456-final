<?php
session_start();
$subDir = "../";
include("../requests/authorize.php");
$dbxClient->delete($_POST['path']);

echo "True";

//header("Location: ../dropbox.php");
?>