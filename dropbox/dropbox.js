var db_filePath = Array(Array("root","root"));
var dBoxSubdir = "";

var dbox_folders;
var dbox_files;




function db_getFiles(folderID){
	if(folderID == "root" || folderID == ""){
		folderID = "/";
	}
	dropboxAction();
	startLoading("Getting Files");
	//displayMsg("Getting folder "+folderID);
	if(dbID == ""){
		$(dropbox_fileDiv).html("No Token. <a href='requests/login.php'>Please Log In</a>");
		stopLoading();
		return false;
	}
	updateLoadingProgress(50, "Asking Dropbox For Files");
	$.ajax({
		url:dBoxSubdir+"requests/getFile.php",
		type:"POST",
		data:{access_token:dbID, path:folderID}
	}).done(function(result){
		updateLoadingProgress(70, "Rendering Results");
		try{
			files = eval("(" + result + ")").contents;
			console.log(files);
			db_displayFiles(dropbox_fileDiv, false);
			db_displayFiles(dropbox_folderDiv, true);
			stopLoading();
			//gd_linkFolders();
			//gd_linkDeletes();
			//gd_linkRename();
			//gd_linkMove();
		}catch(e){
			if(result.indexOf("token has expired") != -1){
				$(dropbox_fileDiv).html("");
				$(dropbox_fileDiv).html("Token Has Expired<br />"+gdID);
			}else{
				alert("ERROR: "+e.message);
				console.log(result);
			}
		}
	}).error(function(XHR, string, error){
		alert("ERROR: "+string);
		console.log(XHR);
		console.log(string);
		console.log(error);
	});
}
function db_displayFiles(displayDivID, foldersOnly){
	dropboxAction();
	console.log(displayDivID);
	$(displayDivID).html("");
	var html = "";
	dbox_folders = Array();
	dbox_files = Array();
	
	for(i=0; i!=files.length; i++){
		if(files[i].icon == "folder"){
			dbox_folders.push(files[i]);
		}else{
			dbox_files.push(files[i]);
		}
	}
	
	dbox_folders.sort(function(a, b){
	    if(a.path < b.path) return -1;
	    if(a.path > b.path) return 1;
	    return 0;
	});
	dbox_files.sort(function(a, b){
	    if(a.path < b.path) return -1;
	    if(a.path > b.path) return 1;
	    return 0;
	});

	
	if(db_filePath.length > 1){
		html += "<div class='breadcrumbs'>";
		for(x=0; x!=db_filePath.length; x++){
			if(x!=0){
				html += " > ";
			}
			if(x == db_filePath.length-1){
				html += "<strong>";
			}
			html += "<span class='breadcrums_item'>"+capitaliseFirstLetter(db_filePath[x][0])+"</span>";
			if(x == db_filePath.length-1){
				html += "</strong>";
			}
		}
		html += "</div>";
	}
	html += "<ul>";
	
	if(db_filePath.length > 1){
		html += "<li><a class='gdrive_folder' href='"+(db_filePath[db_filePath.length-2][1])+"'>Back</a></li>";
	}
	
	for(i=0; i!=dbox_folders.length; i++){
		html += "<li>";
			if(!foldersOnly){
				html += "<hr />";
			}
			html += "<a class='dbox_folder' href='"+dbox_folders[i].path+"'>";
				html += "<img width='30' border='0' height='30' alt='folder' src='images/fileIcons/48x48/"+dbox_folders[i].icon+"48.gif'>";
				html += "<span class='fileName'>"+(dbox_folders[i].path).substr(1)+"</span>";
			html += "</a>";
			
			if(!foldersOnly){
				html += '<div id="FileOptions">';
				
				//html += '<a href="'+dbox_folders[i].path+'" class="renameBt"><img class="fadein" src="images/additionalOptions.png" width="25" border="0" height="25" alt="Edit"></a> ';
				
				html += '<a href="'+dbox_folders[i].path+'" class="deleteBt"><img class="fadein" src="images/Delete.png" width="20" border="0" height="30" alt="Delete"></a> ';
				html += '</div>';
			}
		html += "</li>";
	}
	
	if(!foldersOnly){
		for(i=0; i!=dbox_files.length; i++){
			html += "<li>";
			html += "<hr />";
			html += "<img width='30' border='0' height='30' alt='file' src='images/fileIcons/48x48/"+dbox_folders[i].icon+"48.gif'>";
			html += (dbox_files[i].path).substr(1)+"<br />";
			
			if(!foldersOnly){
				html += '<div id="FileOptions">';
				
				/*if(dbox_files[i].alternateLink){
					html += '<a href="'+dbox_files[i].alternateLink+'" target="blank"><img class="fadein" src="images/additionalOptions.png" width="25" border="0" height="25" alt="Open"></a> ';
				}*/
				
				html += '<a href="dropbox/requests/downloadFile.php?path='+dbox_files[i].path+'" target="blank"><img class="Download" src="images/additionalOptions.png" width="25" border="0" height="25" alt="Download"></a> ';
				
				html += '<a href="'+dbox_files[i].id+'" class="deleteBt"><img class="fadein" src="images/Delete.png" width="20" border="0" height="30" alt="Delete"></a> ';
				
				//html += '<a href="'+dbox_files[i].id+'" class="renameBt"><img class="fadein" src="images/additionalOptions.png" width="25" border="0" height="25" alt="Edit"></a> ';
				
				//html += "<a href='"+dbox_files[i].id+"' class='moveBt'>Move</a> ";
				html += "</div>";
			}
			html += "</li>";
		}
	}
	html += "</ul>";
	
	$(displayDivID).html(html);
}




function db_uploadFile(formID){
	startLoading("Starting Upload...");
	//displayMsg("Starting Upload...");
	
	//Find the current folder directory
	var folderID = filePath[filePath.length-1][1];
	
	//If these hidden forms don't exist add them
	if(!$("#data_folderID").length){
		$("#"+formID).append('<input type="hidden" name="folderID" id="data_folderID" />');
	}
	if(!$("#gdriveAuth").length){
		$("#"+formID).append('<input type="hidden" name="access_token" id="gdriveAuth"/>');
	}
	
	//We need to set the value to actual form items because the ajax can only send the form data from the page
	$("#data_folderID").val(folderID);
	$("#gdriveAuth").val(accessToken);
	
	function errorHandler(XHR, msg, errorMsg){
		alert("ERROR: "+errorMsg);
	}
	function progressHandlingFunction(e){
	    if(e.lengthComputable){
	    	if(Math.round((e.loaded / e.total)*100) == 100){
	    		//displayMsg("Sending To Google Drive...");
	    	}else{
	    		var loadAmount = Math.round((e.loaded / e.total)*100);
	    		updateLoadingProgress(loadAmount, loadAmount+"% uploaded", false);
	    		//displayMsg("Progress = "+loadAmount+"%");
	    	}
	    }
	}
	function successHandler(msg){
		//displayMsg("Upload of file "+msg+" complete.");
		
    	//Remove the hidden form items for security reasons
    	$("#data_folderID").remove();
    	$("#gdriveAuth").remove();
    	console.log("DONE "+msg);
    	//Refresh the files list
    	//getFiles(filePath[filePath.length-1][1]);
	}
	var formData = new FormData($('#'+formID)[0]);
    $.ajax({
        url: dBoxSubdir+'requests/uploadFile.php',  //Server script to process data
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
    	successHandler(msg);
    });
}