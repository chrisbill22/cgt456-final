<?php 
session_start();
/*
// download file
$f = fopen("requests/working-draft.txt", "w+b");
$fileMetadata = $dbxClient->getFile("/requests/working-draft.txt", $f);
fclose($f);
print_r($fileMetadata);
*/

include("requests/authorize.php");
//include("requests/downloadFile.php");

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>DB</title>
</head>
<body>
<a href-"downloadFiles.php"><button>Get Files</button></a>
<a href="requests/logout.php"><button>Logout</button></a>
<a href="requests/authorize.php"><button>Login</button></a>
<form action="requests/uploadFile.php" method="post" enctype="multipart/form-data">
  <input type="file" id="uploadFile" name="dbFile" />
  <input type="submit" value="Upload">
  <input name="folder" type="hidden" value="$path"/>
</form>
</body>
</html>