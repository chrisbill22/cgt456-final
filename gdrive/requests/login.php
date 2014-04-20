<?php session_start();


include("../gdrive.php");

//After authentication google will direct back to this page and send the GET variable for that user.
if (isset($_GET['code'])) {
	$client = startGdrive("../");
	$client->authenticate($_GET['code']);
	$_SESSION['access_token'] = $client->getAccessToken(); //Need to save this access token
	$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	//header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
	header('Locaiton:../index.php');
}


//If we have the user logged in, set that access token in the class
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
	//We've got everything setup so let's redirect to the main page
	header('Location:../index.php');
} else {
	$client = startGdrive("../");
	$authUrl = $client->createAuthUrl();
	//If we need to initiate the login, head over to the google login page
	header("Location:".$authUrl);
}


?>