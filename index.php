<?php session_start(); include("database/database.php"); $fbID = "test"; $_SESSION['fbID'] = $fbID; ?>
<?php //session_destroy(); exit;?>
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
        <div id="NavLayout">
			<a href="#" onclick="openFileUpload();"><img class="nav-Upload" src="images/Upload.png"></a>
        	<a href="#" onclick="openNewFolderPopup();"><img class="nav-AddFolder" src="images/AddFolder.png" /></a>
        	<!--<a href="#" class="nav-Logout">Logout</a>-->                 	
		</div>

        <img id="logo" src="images/CumulusLogo.png" alt="Cumulus Drive" />
    </div>
    <div class="columncontainer menu">
        <div class="colalign">
            

            <div class="col2">
                <!-- Column 2 start -->
				<ul>
					
				<li style="display: none;">
					<div id="dropboxAdd">
						<a href="https://cgt456.genyapps.com/dropbox/pages/login.php">
						<!--<a href="dropbox/pages/login.php"></a>-->
			                <img class="CloudNotConnected" src="images/dropboxlogo.png" width="40" border="0" height="37" alt="DropBox Logo" />
			                <h3 class="CloudNotConnected">Connect your account!</h3>
		                </a>
	                </div>
                </li>
                <li style="display: none;">
                	<div id="googleAdd">
	                	<a href="gdrive/pages/login.php">
				            <img class="CloudNotConnected" src="images/googledrivelogo.png" width="35" border="0" height="31" alt="Google Drive Logo" />
				            <h3 class="CloudNotConnected">Connect your account!</h3>
				        </a>
		            </div>
	            </li>
                <li style="display: none;">
                	<div id="googleFolders">
	                	<a href="#" onclick="gd_getFiles('')">
		                	<img class="CloudAccount" src="images/googledrivelogo.png" width="35" border="0" height="31" alt="Google Drive Logo" />
				            <h3>Your Drive</h3>
			            </a>
			            <div class="folders">
		                	<!--<button onclick="gd_getFiles('')">Get Files</button>-->
		                </div>
	                </div>
                </li>
                <li style="display: none;">
                	<div id="dropboxFolders">
	                	<a href="#" onclick="db_getFiles('')">
		                	<img class="CloudAccount" src="images/dropboxlogo.png" width="40" border="0" height="37" alt="DropBox Logo" />
			                <h3>Your Dropbox</h3>
			            </a>
		                <div class="folders">
		                	<!--<button onclick="db_getFiles('')">Get Files</button>-->
		                	<!--<ul>
			 					<li><img class="image" src="images/SharedFolder.png" width="30" border="0" height="30" alt="Shared Folder" />Ben and Jess</li>
			                    <li><img class="image" src="images/DefaultFolder.png" width="30" border="0" height="30" alt="Folder" />CGT</li>
			                    <li><img class="image" src="images/DefaultFolder.png" width="30" border="0" height="30" alt="Folder" />EBOOK</li>
			                    <li><img class="image" src="images/DefaultFolder.png" width="30" border="0" height="30" alt="Folder" />Spring Break</li>
			                    <li><img class="image" src="images/SharedFolder.png" width="30" border="0" height="30" alt="Folder" />Treasurer</li>
			                </ul>-->
		                </div>
	                </div>
                </li>
                </ul>
                <!-- Column 2 end -->
            </div>
            <div class="col1">
                
                <!-- Column 1 start -->
                
                <div id="startup">
                	<h1>Welcome!</h1>
                	<h3>First Time?</h3>
                	<p>If it is your first time to the site welcome! Cumulus Drive is a service dedicated to making your life easier. Accessing all of your cloud based storage through one portal has never been easier!</p>
                	<p>To add your devices, click on the link on the right sidebar.</p>
                	<h3>Fetures</h3>
                	<ul>
                		<li>Dropbox - </li>
                	</ul>
                	<h3>Future</h3>
                </div>
                
                <div id="googleFiles" style="display: none;">
                	<div class="nameHolder">
	                	<img class="CloudAccount" src="images/googledrivelogo.png" width="45" border="0" height="39" alt="Google Drive Logo" />
	                	<h3 class="name"></h3>
	                </div>
                	<div class="files">
                		<h3>Loading...</h3>
		                <!--<ul>
		                	<hr>
		                    <li><img class="image" src="images/SharedFolder.png" width="30" border="0" height="30" alt="Shared Folder" />Ben and Jess
			                    <div id="FileOptions">
			                    	<img class="Download" src="images/Download.png" width="25" border="0" height="25" alt="Download" />	
			                    	<img class="fadein" src="images/Delete.png" width="20" border="0" height="30" alt="Delete" />
			                    	<img class="fadein" src="images/Rename.png" width="25" border="0" height="25" alt="Rename"/>
			                    	<img class="fadein" src="images/Openingoogle.png" width="25" border="0" height="25" alt="Open"/>
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
		                </ul>-->
		        	</div>
                </div>
                
                <div id="dropboxFiles" style="display: none;">
                	<div class="nameHolder">
	                	<img class="CloudAccount" src="images/dropboxlogo.png" width="45" border="0" height="39" alt="Dropbox Logo" />
	                	<h3 class="name"></h3>
	                </div>
                	<div class="files">
                		<h3>Loading...</h3>
                	</div>
                </div>

                <!-- Column 1 end -->
            </div>
        </div>
    </div>
    <div id="footer">
        <p>CGT 456 Project 3 By: Chris Bill, Jessica Theuerl, Josh Hewitt, and Sean Heckman-Davis<br /></p>
    </div>
    
    <!------------------->
    <!------POPUPS------->
    <!------------------->
    
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
	    	<input type='button' id="uploadFileBtn" value="Upload!" onclick="mainUploadFile();">
			<input type="hidden" name="folderID" id="data_folderID" />
			<input type="hidden" name="access_token" id="gdriveAuth"/>
		</form>
	</div>
    
</body>
</html>