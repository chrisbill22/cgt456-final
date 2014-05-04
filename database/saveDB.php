<?php

if(session_id() == '') {
    session_start();
}

//POST - dbID -- facebook user ID
//POST - gdID -- Google Drive auth

include("database.php");

if(!empty($_POST['dbID']) && !empty($_POST['fbID'])){
	$query = "UPDATE main SET dbID = '".$_POST['dbID']."' WHERE fbID = '".$_POST['fbID']."'";
	$_SESSION['dbID'] = $_POST['dbID'];
	mysql_query($query) or die("Query Error");
	echo "true";
}else if(!empty($_SESSION['fbID']) && !empty($_SESSION['dbID'])){
	$query = "UPDATE main SET dbID = '".$_SESSION['dbID']."' WHERE fbID = '".$_SESSION['fbID']."'";
	$_SESSION['dbID'] = $_SESSION['dbID'];
	mysql_query($query) or die("Query Error");
	echo "true";
}else{
	echo "Error: Wrong vars";
}



?>