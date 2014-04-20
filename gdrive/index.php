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
		<hr />
		<div id="results"></div>
		<script type="text/javascript">
		var files = "";
		$(document).ready(function(){
			$("#getFiles").click(function(){
				getFiles('');
			});
			$("#getRoot").click(function(){
				getFolderFiles();
			});
		});
		function getFiles(folderID){
			console.log("Start - getFiles ("+folderID+")");
			$.ajax({
				url:"requests/getFiles.php",
				type:"POST",
				data:{access_token:'<?php echo $_SESSION['access_token']; ?>', folderID:folderID}
			}).done(function(result){
				console.log(result);
				files = eval(result);
				console.log(files);
				console.log("Complete");
				displayFiles();
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

