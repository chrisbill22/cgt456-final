<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>New Web Project</title>
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	</head>
	<body>
		<button id="getFiles">Get Files</button>
		<a href="requests/logout.php"><button>Logout</button></a>
		<a href="requests/login.php"><button>Login</button></a>
		<form enctype="multipart/form-data" id="uploadFileForm">
			<input type="file" id="uploadFile" name="gdriveFile" /><input type='button' id="uploadFileBtn" value="Upload!">
			<input type="hidden" name="folderID" id="data_folderID" />
			<input type="hidden" name="access_token" id="gdriveAuth"/>
		</form>
		<hr />
		<small id="authToken"></small>
		<div id="results"></div>
		<script type="text/javascript">
		var files = "";
		var filePath = Array("root");
		var accessToken = '<?php if(!empty($_SESSION['access_token'])){ echo $_SESSION['access_token']; } ?>';
		var accessObj = eval(<?php if(!empty($_SESSION['access_token'])){ echo $_SESSION['access_token']; } ?>);
		
		
		//$("#authToken").html("Access Token = "+accessToken);
		
		$(document).ready(function(){
			$("#getFiles").click(function(){
				getFiles('');
			});
			$("#uploadFileBtn").click(function(){
				uploadFile(accessToken);
			});
		});
		
		function uploadFile(accessToken){
			console.log("START!");
			
			var folderID = filePath[filePath.length-1];
			$("#data_folderID").val(folderID);
			
			$("#gdriveAuth").val(accessToken);
			
			
			function errorHandler(XHR, msg, errorMsg){
				console.log("ERROR = "+errorMsg);
			}
			function progressHandlingFunction(e){
			    if(e.lengthComputable){
			        console.log("Progress = "+(e.loaded / e.total)*100+"%");
			    }
			}

			var formData = new FormData($('#uploadFileForm')[0]);
		    $.ajax({
		        url: 'requests/uploadFile.php',  //Server script to process data
		        type: 'POST',
		        xhr: function() {  // Custom XMLHttpRequest
		            var myXhr = $.ajaxSettings.xhr();
		            if(myXhr.upload){ // Check if upload property exists
		                myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
		            }
		            return myXhr;
		        },
		        error: errorHandler,
		        data: formData,
		        //Options to tell jQuery not to process data or worry about content-type.
		        cache: false,
		        contentType: false,
		        processData: false
		    }).done(function(msg) {
		    	msg = msg.replace(/(\r\n|\n|\r)/gm,'');
		    	console.log("DONE = "+msg);
		    });
			
			
			
			//title, description, folder, mime, filePath
		}
		
		
		
		function getFiles(folderID){
			console.log("Start - getFiles ("+folderID+")");
			if(accessToken == ""){
				$("#results").html("No Token. <a href='requests/login.php'>Please Log In</a>");
				return false;
			}
			$.ajax({
				url:"requests/getFiles.php",
				type:"POST",
				data:{access_token:accessToken, folderID:folderID}
			}).done(function(result){
				try{
					files = eval(result);
					console.log(files);
					console.log("Complete");
					displayFiles();
				}catch(e){
					if(result.indexOf("token has expired") != -1){
						$("#results").html("");
						$("#results").html("Token Has Expired<br />"+accessToken);
					}else{
						console.log(result);
					}
				}
			}).error(function(XHR, string, error){
				console.log("ERROR");
				console.log(XHR);
				console.log(string);
				console.log(error);
			});
		}
		function linkFolders(){
			$(".gdrive_folder").click(function(){
				var id = $(this).attr("id");
				if(filePath.indexOf(id) == -1){
					filePath.push(id);
				}else{
					while(filePath[filePath.length-1] != id){
						filePath.pop();
					}
				}
				getFiles(id);
			});
		}
		function displayFiles(){
			$("#results").html("");
			var html = "<h1>Files</h1>";
			var gdrive_folders = Array();
			var gdrive_files = Array();
			
			for(i=0; i!=files.length; i++){
				if(files[i].mimeType == "application/vnd.google-apps.folder"){
					gdrive_folders.push(files[i]);
				}else{
					gdrive_files.push(files[i]);
				}
			}
			gdrive_folders.sort(function(a, b){
			    if(a.title < b.title) return -1;
			    if(a.title > b.title) return 1;
			    return 0;
			});
			gdrive_files.sort(function(a, b){
			    if(a.title < b.title) return -1;
			    if(a.title > b.title) return 1;
			    return 0;
			});

			
			if(filePath.length > 1){
				for(i=0; i!=filePath.length; i++){
					html += filePath[i]+" > ";
				}
			}
			html += "<br /><br />";
			
			if(filePath.length > 1){
				html += "<a class='gdrive_folder' id='"+(filePath[filePath.length-2])+"'>back</a><br />";
			}
			
			for(i=0; i!=gdrive_folders.length; i++){
				html += "<img src='"+gdrive_folders[i].iconLink+"'>";
				html += "<a class='gdrive_folder' id='"+gdrive_folders[i].id+"'>"+gdrive_folders[i].title+"</a><br />";
			}
			for(i=0; i!=gdrive_files.length; i++){
				html += "<img src='"+gdrive_files[i].iconLink+"'>";
				html += gdrive_files[i].title+"<br />";
			}
			
			$("#results").html(html);
			linkFolders();
		}
		</script>
	</body>
</html>

