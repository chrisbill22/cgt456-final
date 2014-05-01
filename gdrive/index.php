<?php session_start(); include("database.php"); $fbID = "test"; $_SESSION['fbID'] = $fbID; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>New Web Project</title>
		
		<link href="loader/loader.css" type="text/css" rel="stylesheet" />
		<link href="jqueryUI/css/flick/jquery-ui-1.10.4.custom.min.css" type="text/css" rel="stylesheet" />
		
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="gdrive.js"></script>
		<script type="text/javascript" src="loader/loader.js"></script>
		<script type="text/javascript" src="jqueryUI/js/jquery-ui-1.10.4.custom.min.js"></script>
		
		<script type="text/javascript">
			var accessToken = '<?php if(!empty($_SESSION['access_token'])){ echo $_SESSION['access_token']; } ?>';
			var accessObj = eval(<?php if(!empty($_SESSION['access_token'])){ echo $_SESSION['access_token']; } ?>);
			$(document).ready(function(){
				$("#getFiles").click(function(){
					getFiles('', "results");
				});
				$("#uploadFileBtn").click(function(){
					uploadFile("uploadFileForm", "results");
				});
			});
		</script>
	</head>
	<body>
		<button id="getFiles">Get Files</button>
		<a href="pages/logout.php"><button>Logout</button></a>
		<a href="pages/login.php"><button>Login</button></a>
		<form enctype="multipart/form-data" id="uploadFileForm">
			<input type="file" id="uploadFile" name="gdriveFile" /><input type='button' id="uploadFileBtn" value="Upload!">
			<input type="hidden" name="folderID" id="data_folderID" />
			<input type="hidden" name="access_token" id="gdriveAuth"/>
		</form>
		<hr />
		<small id="authToken"></small>
		<div id="results"></div>
		<!--
		<div id="message" style="z-index:101; position:fixed; bottom:0px; left:0px; right:0px; width:100%; padding:10px; text-align: center; background-color: #333; color:#DDD;"></div>
		-->
		<div id="renamePopup" style="display: none;">New Name: <br /><input type='text' /></div>
		<div id="movePopup" style="display: none;">
			<small>Choose which folder to move your document into</small>
			<div class="breadcrumbs"></div>
			<div class="folders"></div>
			
		</div>
	</body>
</html>

