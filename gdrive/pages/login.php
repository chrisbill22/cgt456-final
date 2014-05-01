<?php session_start();


include("../gdrive.php");
include("../database.php");

//After authentication google will direct back to this page and send the GET variable for that user.
if (isset($_GET['code'])) {
	$client = startGdrive("../");
	$client->setAccessType("offline");
	$client->authenticate($_GET['code']);
	
	$_SESSION['access_token'] = $client->getAccessToken(); //Save as session for easy access
	mysql_query("UPDATE cgt456_final SET gID = '".$client->getAccessToken()."' WHERE fbID = '".$_SESSION['fbID']."'"); //Save to DB for permanent access
	
	$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	//header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
	header('Locaiton:../../index.php');
}


//If we have the user logged in, set that access token in the class
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
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