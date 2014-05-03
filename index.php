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
    <script type="text/javascript" src="actions.js"></script>
	<script type="text/javascript" src="loader/loader.js"></script>
	<script type="text/javascript" src="gdrive/gdrive.js"></script>
	<script type="text/javascript" src="facebook/facebook.js"></script>
	<script type="text/javascript" src="dropbox/dropbox.js"></script>	
	<script type="text/javascript">
		var gdID = '<?php if(!empty($_SESSION['gdID'])){ echo $_SESSION['gdID']; } ?>';
		var dbID = '<?php if(!empty($_SESSION['dbID'])){ echo $_SESSION['dbID']; } ?>';
		//var accessObj = eval(<?php if(!empty($_SESSION['gdID'])){ echo $_SESSION['gdID']; } ?>);
		gDriveSubdir = "gdrive/";
		dBoxSubdir = "dropbox/";
		google_folderDiv = "#googleFolders .folders";
		google_fileDiv = "#googleFiles .files";
		dropbox_folderDiv = "#dropboxFolders .folders";
		dropbox_fileDiv = "#dropboxFiles .files";
	</script>
    
</head>
<body>
	
    <div id="header">
        <img src='images/NavBG.png' />
        <a href="#" onclick="openFileUpload();"><img class="nav-AddCloudDevice" src="images/AddCloudDevice.png"></a>
        <a href="#" onclick="openNewFolderPopup();"><img class="nav-AddFolder" src="images/AddFolder.png" /></a>
        <a href="#" class="nav-Logout">Logout</a>
        
        <img id="logo" src="images/CumulusLogo.png" alt="Cumulus Drive" />
    </div>
    <div class="columncontainer menu">
        <div class="colalign">
            <div class="col1">
                
                <!-- Column 1 start -->
                
                <div id="startup">
                	<h1>Startup Message</h1>
                </div>
                
                <div id="googleFiles" style="display: none;">
                	<img class="CloudAccount" src="images/googledrivelogo.png" width="45" border="0" height="39" alt="Google Drive Logo" />
                	<h3 class="name"></h3>
                	<div class="files">
		                <ul>
		                	<hr>
		                    <li><img class="image" src="images/SharedFolder.png" width="30" border="0" height="30" alt="Shared Folder" />Ben and Jess
			                    <div id="FileOptions">
			                    	<img class="Download" src="images/additionalOptions.png" width="25" border="0" height="25" alt="Download" />	
			                    	<img class="fadein" src="images/Delete.png" width="20" border="0" height="30" alt="Delete" />
			                    	<img class="fadein" src="images/additionalOptions.png" width="25" border="0" height="25" alt="Open"/>
			                    	<img class="fadein" src="images/additionalOptions.png" width="25" border="0" height="25" alt="Edit"/>
			      				</div>
		                    </li>
		                    <hr>
		                    <li><img class="image" src="images/DefaultFolder.png" width="30" border="0" height="30" alt="Folder" />CGT</li>
		                    <hr>
		                    <li><img class="image" src="images/DefaultFolder.png" width="30" border="0" height="30" alt="Folder" />EBOOK</li>
		                    <hr>
		                    <li><img class="image" src="images/DefaultFolder.png" width="30" border="0" height="30" alt="Folder" />Spring Break</li>
		                    <hr>
		                    <li><img class="image" src="images/SharedFolder.png" width="30" border="0" height="30" alt="Folder" />Treasurer</li>
		                    <hr>
		                    <li>easter.jpg</li>
		                    <hr>
		                    <li>breakfast.doc</li>
		                    <hr>
		                    <li>WeddingInvites.doc</li>
		                </ul>
		        	</div>
                </div>
                
                <div id="dropboxFiles" style="display: none;">
                	<img class="CloudAccount" src="images/dropboxlogo.png" width="45" border="0" height="39" alt="Dropbox Logo" />
                	<h3 class="name"></h3>
                	<div class="files"></div>
                </div>

                <!-- Column 1 end -->
            </div>

            <div class="col2">
                <!-- Column 2 start -->
				
				<div id="dropboxAdd" style="display: none;">
					<!--<a href="https://cgt456.genyapps.com/dropbox/pages/login.php">-->
					<a href="dropbox/pages/login.php">
		                <img class="CloudNotConnected" src="images/dropboxlogo.png" width="40" border="0" height="37" alt="DropBox Logo" />
		                <h3 class="CloudNotConnected">Connect your account!</h3>
	                </a>
                </div>
                <div id="gdriveAdd" style="display: none;">
                	<a href="gdrive/pages/login.php">
			            <img class="CloudAccount" src="images/googledrivelogo.png" width="35" border="0" height="31" alt="Google Drive Logo" />
			            <h3>Connect your account!</h3>
			        </a>
	            </div>
                <div id="googleFolders" style="display: none;">
                	<img class="CloudAccount" src="images/googledrivelogo.png" width="35" border="0" height="31" alt="Google Drive Logo" />
		            <a href="#" onclick="gd_getFiles('')" class="gdrive_folder">
			            <h3>Your Drive</h3>
			            <div class="folders">
			         </a>
	                	<button onclick="gd_getFiles('')">Get Files</button>
	                </div>
                </div>
                <div id="dropboxFolders" style="display: none;">
                	<a href="#" onclick="db_getFiles('')" class="dbox_folder">
	                	<img class="CloudNotConnected" src="images/dropboxlogo.png" width="40" border="0" height="37" alt="DropBox Logo" />
		                <h3 class="CloudNotConnected">Your Dropbox</h3>
	                </a>
	                <div class="folders">
	                	<button onclick="db_getFiles('')">Get Files</button>
	                	<!--<ul>
		 					<li><img class="image" src="images/SharedFolder.png" width="30" border="0" height="30" alt="Shared Folder" />Ben and Jess</li>
		                    <li><img class="image" src="images/DefaultFolder.png" width="30" border="0" height="30" alt="Folder" />CGT</li>
		                    <li><img class="image" src="images/DefaultFolder.png" width="30" border="0" height="30" alt="Folder" />EBOOK</li>
		                    <li><img class="image" src="images/DefaultFolder.png" width="30" border="0" height="30" alt="Folder" />Spring Break</li>
		                    <li><img class="image" src="images/SharedFolder.png" width="30" border="0" height="30" alt="Folder" />Treasurer</li>
		                </ul>-->
	                </div>
                </div>
                
                <!-- Column 2 end -->
            </div>
        </div>
    </div>
    <div id="footer">
        <p>CGT 456 Project 3 By: Chris Bill, Jessica Theuerl, Josh Hewitt, and Sean Heckman-Davis<br /></p>
    </div>
    
    <!------------------->
    <!------POPUPS------->
    <!------------------->
    
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
    
    <!-- RENAME POPUP -->
    <div id="renamePopup" style="display: none;">New Name: <br /><input type='text' /></div>
    
    <!-- NEW FOLDER POPUP -->
    <div id="newFolderPopup" style="display: none;">Folder Name: <br /><input type='text' /></div>
    
    <!-- UPLOAD FILE POPUP -->
    <div id="newFilePopup" style="display: none;">
    	<form enctype="multipart/form-data" id="uploadFileForm">
	    	<input type="file" id="uploadFile" name="uploadFile" />
	    	<input type='button' id="uploadFileBtn" value="Upload!" onclick="runUpload();">
			<input type="hidden" name="folderID" id="data_folderID" />
			<input type="hidden" name="access_token" id="gdriveAuth"/>
		</form>
	</div>
    
</body>
</html>