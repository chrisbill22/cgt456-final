<?php ini_set('error_reporting', E_ALL); error_reporting(-1);
if(session_id() == '') {
    session_start();
}


include("../gdrive.php");
include("../../database/database.php");

//After authentication google will direct back to this page and send the GET variable for that user.
if (isset($_GET['code'])) {
	echo "1";
	$client = startGdrive("../");
	echo "2";
	$client->setAccessType("offline");
	echo "3";
	$client->authenticate($_GET['code']);
	
	//$_SESSION['access_token'] = $client->getAccessToken(); //Save as session for easy access
	//mysql_query("UPDATE main SET gID = '".$client->getAccessToken()."' WHERE fbID = '".$_SESSION['fbID']."'"); //Save to DB for permanent access
	echo "4";
	$fbID = $_SESSION['fbID'];
	echo "5";
	$gdID = $client->getAccessToken();
	include("../../saveGD.php");
	
	$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	//header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
	header('Locaiton:../../index.php');
}


//If we have the user logged in, set that access token in the class
if (isset($_SESSION['gdID']) && $_SESSION['gdID']) {
	//We've got everything setup so let's redirect to the main page
	header('Location:../../index.php');
} else {
	$client = startGdrive("../");
	$client->setAccessType("offline");
	$authUrl = $client->createAuthUrl();
	//If we need to initiate the login, head over to the google login page
	header("Location:".$authUrl);
}


?>