<?php

if(session_id() == '') {
    session_start();
}

//POST - fbID -- facebook user ID
//POST - dbID -- Google Drive auth

include("database.php");

if(!empty($_POST['dbID']) && !empty($_POST['dbID'])){
	$query = "UPDATE main SET dbID = '".$_POST['dbID']."' WHERE fbID = '".$_POST['fbID']."'";
	$_SESSION['dbID'] = $_POST['dbID'];
	mysql_query($query) or die("Query Error");
	echo "true";
}else if(!empty($fbID) && !empty($dbID)){
	$query = "UPDATE main SET dbID = '".$dbID."' WHERE fbID = '".$fbID."'";
	$_SESSION['dbID'] = $dbID;
	mysql_query($query) or die("Query Error");
	echo "true";
}else{
	echo "Error: Wrong vars";
}



?>