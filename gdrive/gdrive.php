<?php


function startGdrive($subdirToGdrive){
	include("database.php");
	
	include_once $subdirToGdrive."sdk/examples/templates/base.php";
	set_include_path($subdirToGdrive."sdk/src/" . PATH_SEPARATOR . get_include_path());
	require_once 'Google/Client.php';
	require_once 'Google/Service/Drive.php';
	
	$client_id = "296417278308-i0htc2qfmng2i06ee31iqlq9l9ru045s.apps.googleusercontent.com";
	$client_secret = "5xGBi-Ta3CaoP6ZJbaRODw2e";
	$redirect_uri = "http://web.ics.purdue.edu/~bill0/pages/login.php"; // This must be the same as the Google Drive API Open URL
	
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
	$client->addScope("https://www.googleapis.com/auth/drive.scripts");
	$client->addScope("https://www.googleapis.com/auth/drive.apps.readonly");
	$client->addScope("https://www.googleapis.com/auth/drive.metadata.readonly");
	$client->addScope("https://www.googleapis.com/auth/drive.readonly");
	
	if(empty($_SESSION['access_token'])){
		$result = mysql_query("SELECT gID FROM cgt456_final WHERE fbID = '".$_SESSION['fbID']."'");
		$row = mysql_fetch_array($result);
		$_SESSION['access_token'] = $row['gid'];
	}
	
	return $client;
}



function authenticate($client, $accessToken, $refreshToken = ""){
	//Check if access token needs renewed
	if($client->isAccessTokenExpired() && $refreshToken != ""){
		$accessToken = $client->refreshToken($accessToken);
	}
	
	$client->setAccessToken($accessToken);
	return $client;
}

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
		$parameters['q'] .= " and trashed = false";
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



function uploadFile($service, $title, $description, $parentId, $mimeType, $filepath) {
  $file = new Google_Service_Drive_DriveFile();
  $file->setTitle($title);
  $file->setDescription($description);
  $file->setMimeType($mimeType);

  // Set the parent folder.
  if ($parentId != null) {
    $parent = new Google_Service_Drive_ParentReference();
    $parent->setId($parentId);
    $file->setParents(array($parent));
  }

  try {
    $data = file_get_contents($filepath);

    $createdFile = $service->files->insert($file, array(
      'data' => $data,
      'mimeType' => $mimeType,
    ));

    // Uncomment the following line to print the File ID
    // print 'File ID: %s' % $createdFile->getId();

    return $createdFile;
  } catch (Exception $e) {
    print "An error occurred: " . $e->getMessage();
  }
}


function deleteFile($service, $fileId){
	try{
		$service->files->delete($fileId);
		return "True";
	}catch (Exception $e){
		print "An error occurred: " . $e->getMessage();
	}
}

function trashFile($service, $fileId){
	try{
		$service->files->trash($fileId);
		return "True";
	}catch (Exception $e){
		print "An error occurred: " . $e->getMessage();
	}
}

function renameFile($service, $fileId, $newTitle){
	try{
		$file = new Google_Service_Drive_DriveFile();
	    $file->setTitle($newTitle);
	
	    $updatedFile = $service->files->patch($fileId, $file, array(
	      'fields' => 'title'
	    ));
	
	    return $updatedFile;
	}catch (Exception $e){
		print "An error occurred: " . $e->getMessage();
	}
}

if(isset($authUrl)){ ?>
	<!--<a href="<?php echo $authUrl; ?>">You must connect an account first.</a>-->
<?php } ?>