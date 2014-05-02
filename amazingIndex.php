<?php session_start(); include("database/database.php"); $fbID = "test"; $_SESSION['fbID'] = $fbID; ?>
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
	<script type="text/javascript" src="facebook/facebook.js"></script>
	
	<script type="text/javascript">
		var accessToken = '<?php if(!empty($_SESSION['gdID'])){ echo $_SESSION['gdID']; } ?>';
		var accessObj = eval(<?php if(!empty($_SESSION['gdID'])){ echo $_SESSION['gdID']; } ?>);
		gDriveSubdir = "gdrive/";
		google_folderDiv = "#googleFolders";
		google_fileDiv = "#googleFiles";
	</script>
    
</head>
<body>
	<!-- LOGIN POPUP -->
	<div id="mainLogin">
		<div id="mainLogin_loading">
			<center>
				<br /><br />
				Please Wait...
			</center>
		</div>
		<div id="mainLogin_login" style="display: none;">
			<center>
				<small>Login with your Facebook ID</small>
				<br /><br />
				<fb:login-button scope="public_profile,email" size="xlarge" onlogin="checkLoginState();">
				</fb:login-button>
			</center>
		</div>
	</div>
	
    <div id="header">
        <img src='images/NavBG.png' />
        <a href="gdrive/pages/login.php"><img class="nav-AddCloudDevice" src="images/AddCloudDevice.png"></a>
        <img class="nav-AddFolder" src="images/AddFolder.png">
        <a href="#" class="nav-Logout">Logout</a>
        
        <img id="logo" src="images/CumulusLogo.png" alt="Cumulus Drive" />
    </div>
    <div class="columncontainer menu">
        <div class="colalign">
            <div class="col1">
                
                <!-- Column 1 start -->
                <img class="CloudAccount" src="images/googledrivelogo.png" width="45" border="0" height="39" alt="Google Drive Logo" />
                <h3 class="name"></h3>
                <div id="googleFiles">
	                <ul>
	                	<hr>
	                    <li><img src="images/SharedFolder.png" width="30" border="0" height="30" alt="Shared Folder" />Ben and Jess
		                    <div id="FileOptions">
		                    <img src="images/Delete.png" width="20" border="0" height="30" alt="Delete" />
		                    <img src="images/Move.png" width="25" border="0" height="25" alt="Move Document"/>
		                    <img src="images/additionalOptions.png" width="25" border="0" height="25" alt="Additional Options" />
		      				</div>
	                    </li>
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
                
                <div id="dropboxFiles">
                	
                </div>

                <!-- Column 1 end -->
            </div>

            <div class="col2">
                <!-- Column 2 start -->

                <img class="CloudAccount" src="images/dropboxlogo.png" width="40" border="0" height="37" alt="DropBox Logo" />
                <h3 class="name">Connect your account!</h3>
                <img class="CloudAccount" src="images/googledrivelogo.png" width="35" border="0" height="31" alt="Google Drive Logo" />
                <h3 id="selected" class="name">Connect your account!</h3>
                <div id="googleFolders">
                	<ul>
	 					<li><img src="images/SharedFolder.png" width="30" border="0" height="30" alt="Shared Folder" />Ben and Jess</li>
	                    <li><img src="images/DefaultFolder.png" width="30" border="0" height="30" alt="Folder" />CGT</li>
	                    <li><img src="images/DefaultFolder.png" width="30" border="0" height="30" alt="Folder" />EBOOK</li>
	                    <li><img src="images/DefaultFolder.png" width="30" border="0" height="30" alt="Folder" />Spring Break</li>
	                    <li><img src="images/SharedFolder.png" width="30" border="0" height="30" alt="Folder" />Treasurer</li>
	                </ul>
                </div>
                
                <div id="dropboxFiles">
                	
                </div>
                
                
                <!-- Column 2 end -->
            </div>
        </div>
    </div>
    <div id="footer">
        <p>CGT 456 Project 3 By: Chris Bill, Jessica Theuerl, Josh Hewitt, and Sean Heckman-Davis<br /></p>

    </div>
</body>
</html>