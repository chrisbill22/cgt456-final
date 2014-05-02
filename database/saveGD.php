<?php session_start();

//POST - fbID -- facebook user ID
//POST - gdID -- Google Drive auth

include("database.php");

if((!empty($_POST['fbID']) && !empty($_POST['gdID'])) || (!empty($fbID) && !empty($gdID))){
	$query = "UPDATE main SET gdID = '".$_POST['gdID']."' WHERE fbID = '".$_POST['fbID']."'";
	if(!empty($_POST['gdID'])){
		$_SESSION['gdID'] = $_POST['gdID'];
	}else if(!empty($gdID)){
		$_SESSION['gdID'] = $gdID;
	}
	mysql_query($query) or die("Query Error");
	echo "true";
}else{
	echo "Error: Wrong vars";
}



?>