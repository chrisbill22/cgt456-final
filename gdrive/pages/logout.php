<?php session_start();

unset($_SESSION['gdID']);
header("Location:../index.php");

?>