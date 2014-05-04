<?php

if(session_id() == '') {
    session_start();
}

//POST - fbID -- facebook user ID
//POST - dbID -- Google Drive auth

include("database.php");

if(!empty($_POST['fbID']) && !empty($_POST['gdID'])){
	$query = "UPDATE main SET gdID = '".$_POST['gdID']."' WHERE fbID = '".$_POST['fbID']."'";
	$_SESSION['gdID'] = $_POST['gdID'];
	mysql_query($query) or die("Query Error");
	echo "true";
}else if(!empty($gdID) && !empty($fbID)){
	$query = "UPDATE main SET gdID = '".$gdID."' WHERE fbID = '".$fbID."'";
	$_SESSION['gdID'] = $gdID;
	mysql_query($query) or die("Query Error");
	echo "true";
}else{
	echo "Error: Wrong vars";
}



?>