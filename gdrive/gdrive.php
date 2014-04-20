<?php


function startGdrive($subdirToGdrive){
	include_once $subdirToGdrive."sdk/examples/templates/base.php";
	set_include_path($subdirToGdrive."sdk/src/" . PATH_SEPARATOR . get_include_path());
	require_once 'Google/Client.php';
	require_once 'Google/Service/Drive.php';
	
	$client_id = "296417278308-n0m0gh34tb6pklepdrmkbgdjjcft7ib9.apps.googleusercontent.com";
	$client_secret = "6fMR2Jltu_uQLeeJxq2IPuRI";
	$redirect_uri = "http://cgtweb1.tech.purdue.edu/456/cgt456web1a/Project3/requests/login.php"; // This must be the same as the Google Drive API Open URL
	
	//Setup the google login client
	$client = new Google_Client();
	$client->setAccessType("offline");
	$client->setClientId($client_id);
	$client->setClientSecret($client_secret);
	$client->setRedirectUri($redirect_uri);
	
	//Not sure if we really need these
	$client->addScope("https://www.googleapis.com/auth/drive");
	$client->addScope("https://www.googleapis.com/auth/drive.file");
	$client->addScope("https://www.googleapis.com/auth/drive.appdata");
	$client->addScope("https://www.googleapis.com/auth/drive.apps.readonly");
	$client->addScope("https://www.googleapis.com/auth/drive.metadata.readonly");
	$client->addScope("https://www.googleapis.com/auth/drive.readonly");
	
	return $client;
}


//After authentication google will direct back to this page and send the GET variable for that user.
/*if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken(); //Need to save this access token
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

//If we have the user logged in, set that access token in the class
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
} else {
  $authUrl = $client->createAuthUrl();
}

//If logging out

*/	


function retrieveFiles($service, $folderId = "root") {
	$result = array();
	$pageToken = NULL;
	
	if(empty($folderId) || $folderId == ""){
		$folderId = "root";
	}
	
	try {
		$parameters = array();
		$parameters['q'] = "'".$folderId."' in parents";
		$parameters['q'] .= " and not title contains '~'";
		//echo $parameters['q'];
		$parameters['maxResults'] = 300;
		if ($pageToken) {
			$parameters['pageToken'] = $pageToken;
		}
		$files = $service->files->listFiles($parameters);
		$result = array_merge($result, $files->getItems());
		$pageToken = $files->getNextPageToken();
	} catch (Exception $e) {
		print "An error occurred: " . $e->getMessage();
		$pageToken = NULL;
	}
	return $result;
}


function printFile($service, $fileId) {
  try {
    $file = $service->files->get($fileId);

    print "<br />Title: " . $file->getTitle();
    print "<br />Description: " . $file->getDescription();
    print "<br />MIME type: " . $file->getMimeType();
  } catch (Exception $e) {
    print "<br />An error occurred: " . $e->getMessage();
  }
}

?>




<?php 
if(isset($authUrl)){ ?>
	<a href="<?php echo $authUrl; ?>">You must connect an account first.</a>
<?php 
}else{
	//if ($client->getAccessToken()) {
		//$service = new Google_Service_Drive($client);
	//}
	//echo $client->getAccessToken();
	//$allFiles = retrieveAllFiles($service);
	/*
	echo "<hr />";
	foreach($allFiles as $file){
		echo $file->title."<br />";
	}
	echo $allFiles[0]->owners[0]->displayName;
	echo "<hr />";*/
	
	//echo json_encode($allFiles);
	
	
	//1printFile($service, "0B--_J4Jg24DTMlQ4RDhDTjU2a28");
}	
?>




