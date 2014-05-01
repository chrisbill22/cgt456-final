﻿<?php session_start(); include("database.php"); $fbID = "test"; $_SESSION['fbID'] = $fbID; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
    <title>Cumulus Drive.</title>
    <link rel="stylesheet" type="text/css" href="StyleSheet.css" media="screen" />
     <link href="loader/loader.css" type="text/css" rel="stylesheet" />
     <link href="jqueryUI/css/flick/jquery-ui-1.10.4.custom.min.css" type="text/css" rel="stylesheet" />
    
   
    <script type="text/javascript" src="jqueryUI/js/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="jqueryUI/js/jquery-ui-1.10.4.custom.min.js"></script>
	<script type="text/javascript" src="loader/loader.js"></script>
	<script type="text/javascript" src="gdrive/gdrive.js"></script>
	
	<script type="text/javascript">
		var accessToken = '<?php if(!empty($_SESSION['access_token'])){ echo $_SESSION['access_token']; } ?>';
		var accessObj = eval(<?php if(!empty($_SESSION['access_token'])){ echo $_SESSION['access_token']; } ?>);
	</script>
    
</head>
<body>
    <div id="header">
        <img src='images/NavBG.png' />
        <img class="nav-AddCloudDevice" src="images/AddCloudDevice.png">
        <img class="nav-AddFolder" src="images/AddFolder.png">
        <a href="#" class="nav-Logout">Logout</a>
        
        <img id="logo" src="images/CumulusLogo.png" alt="Cumulus Drive" />
    </div>
    <div class="columncontainer menu">
        <div class="colalign">
            <div class="col1">
                
                <!-- Column 1 start -->
                <img class="" src="images/googledrivelogo.png" width="45" border="0" height="39" alt="Google Drive Logo" />
                <h3>jtheuerl92</h3>
                <div id="googleFiles">
	                <ul>
	                	<hr>
	                    <li><img src="images/SharedFolder.png" width="30" border="0" height="30" alt="Shared Folder" />Ben and Jess</li>
	                    <img src="images/Delete.png" width="20" border="0" height="30" alt="Delete" />
	                    <img src="images/Move.png" width="25" border="0" height="25" alt="Move Document" />
	                    <img src="images/additionalOptions.png" width="25" border="0" height="25" alt="Additional Options" />
	                    <hr>
	                    <li><img src="images/DefaultFolder.png" width="30" border="0" height="30" alt="Folder" />CGT</li>
	                    <hr>
	                    <li><img src="images/DefaultFolder.png" width="30" border="0" height="30" alt="Folder" />EBOOK</li>
	                    <hr>
	                    <li><img src="images/DefaultFolder.png" width="30" border="0" height="30" alt="Folder" />Spring Break</li>
	                    <hr>
	                    <li><img src="images/SharedFolder.png" width="30" border="0" height="30" alt="Folder" />Treasurer</li>
	                    <hr>
	                    <li>easter.jpg</li>
	                    <hr>
	                    <li>breakfast.doc</li>
	                    <hr>
	                    <li>WeddingInvites.doc</li>
	                </ul>
                </div>

                <!-- Column 1 end -->
            </div>

            <div class="col2">
                <!-- Column 2 start -->

                <img src="images/dropboxlogo.png" width="40" border="0" height="37" alt="DropBox Logo" />
                <h3>jtheuerl</h3>
                <br />
                <img src="images/googledrivelogo.png" width="35" border="0" height="31" alt="Google Drive Logo" />
                <h3>jtheuerl92</h3>
                <ul>
 					<li><img src="images/WhiteFolder.png" width="30" border="0" height="30" alt="Shared Folder" />Ben and Jess</li>
                    <li><img src="images/WhiteFolder.png" width="30" border="0" height="30" alt="Folder" />CGT</li>
                    <li><img src="images/WhiteFolder.png" width="30" border="0" height="30" alt="Folder" />EBOOK</li>
                    <li><img src="images/WhiteFolder.png" width="30" border="0" height="30" alt="Folder" />Spring Break</li>
                    <li><img src="images/WhiteFolder.png" width="30" border="0" height="30" alt="Folder" />Treasurer</li>
                </ul>
                
                <!-- Column 2 end -->
            </div>
        </div>
    </div>
    <div id="footer">
        <p>CGT 456 Project 3 By: Chris Bill, Jessica Theuerl, Josh Hewitt, and Sean Heckman-Davis<br /></p>

    </div>
</body>
</html>