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

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>DB</title>
</head>
<body>
<button id="getFiles">Get Files</button>
<a href="requests/logout.php"><button>Logout</button></a>
<a href="requests/authorize.php"><button>Login</button></a>
<form enctype="multipart/form-data" id="uploadFileForm" action="uploadFile.php" method="post">
    <input type="file" id="uploadFile" name="gdriveFile" />
    <input type='button' id="uploadFileBtn" value="Upload">
</form>
</body>
</html>