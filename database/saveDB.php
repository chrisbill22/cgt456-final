<?php

if(session_id() == '') {
    session_start();
}

//POST - dbID -- facebook user ID
//POST - gdID -- Google Drive auth

include("database.php");

if(!empty($_POST['dbID']) && !empty($_POST['gdID'])){
	$query = "UPDATE main SET gdID = '".$_POST['gdID']."' WHERE dbID = '".$_POST['dbID']."'";
	$_SESSION['gdID'] = $_POST['gdID'];
	mysql_query($query) or die("Query Error");
	echo "true";
}else if(!empty($dbID) && !empty($dbID)){
	$query = "UPDATE main SET gdID = '".$gdID."' WHERE dbID = '".$dbID."'";
	$_SESSION['gdID'] = $gdID;
	mysql_query($query) or die("Query Error");
	echo "true";
}else{
	echo "Error: Wrong vars";
}



?>