var db_filePath = Array(Array("/","/"));
var dBoxSubdir = "";

var dbox_folders;
var dbox_files;





function db_linkRename(){
	dropboxAction();
	$(".db_renameBt").click(function(event){
		event.preventDefault();
		dropboxAction();
		var oldPath = $(this).attr("href");
		var currentPath = db_filePath[db_filePath.length-1][1];
		$( "#renamePopup" ).dialog({
		    resizable: false,
		    modal: true,
		    title:"New Name",
		    buttons: {
		        Ok: function() {
		        	db_renameFile(currentPath, $("#renamePopup input").val(), oldPath);
		        	$( this ).dialog( "close" );
		        	$("#renamePopup input").val("");
		        }
		    }
	    });
	});
}
function db_renameFile(fileID, newName, oldPath){
	dropboxAction();
	startLoading("Renaming File "+fileID);
	$.ajax({
        url: dBoxSubdir+'requests/renameFile.php',  //Server script to process data
        type: 'POST',
        data: {access_token:gdID, path:fileID, newName:newName, oldPath:oldPath},
    }).done(function(msg) {
    	if(msg != "True"){
    		alert("ERROR");
    		console.log(msg);
    	}
		db_getFiles(db_filePath[db_filePath.length-1][1]);
    }).error(function(XHR, string, error){
		alert("ERROR: "+string);
		console.log(XHR);
		console.log(string);
		console.log(error);
	});
}


function db_linkDeletes(){
	dropboxAction();
	$("a.db_deleteBt").click(function(event){
		dropboxAction();
		event.preventDefault();
		db_deleteFile($(this).attr("href"));
	});
}
function db_deleteFile(fileID){
	dropboxAction();
	var confirmResult = confirm("Are you sure you would like to delete this file?"); 
	if(confirmResult){
		startLoading("Deleting File");
		$.ajax({
	        url: dBoxSubdir+'requests/deleteFile.php',  //Server script to process data
	        type: 'POST',
	        data: {access_token:gdID, path:fileID},
	    }).done(function(msg) {
	    	if(msg.indexOf("True") == -1){
	    		alert("ERROR: "+msg);
	    		stopLoading();
	    	}else{
	    		db_getFiles(db_filePath[db_filePath.length-1][1]);
	    	}
	    }).error(function(XHR, string, error){
			alert("ERROR: "+string);
			console.log(XHR);
			console.log(string);
			console.log(error);
		});
	}
}



function db_createFolder(){
	dropboxAction();
	startLoading("Creating Folder");
	$.ajax({
        url: dBoxSubdir+'requests/createFolder.php',  //Server script to process data
        type: 'POST',
        data: {access_token:dbID, newName:$("#newFolderPopup input").val(), path:db_filePath[db_filePath.length-1][1]},
    }).done(function(msg) {
    	if(msg != "true"){
    		alert("an error occured");
    	}
    	console.log(msg);
		db_getFiles(db_filePath[db_filePath.length-1][1]);
    }).error(function(XHR, string, error){
		alert("ERROR: "+string);
		console.log(XHR);
		console.log(string);
		console.log(error);
	});
}

function db_linkFolders(){
	$(".dbox_folder").click(function(event){
		dropboxAction();
		event.preventDefault();
		var id = $(this).attr("href");
		var title = $(this).children(".fileName").html();
		
		var contains = false;
		for(var i = 0; i != db_filePath.length; i++) {
			console.log(db_filePath[i][1]+" == "+id);
		   if(db_filePath[i][1] == id) {
		     contains = true;
		   }
		}
		
		if(!contains){
			db_filePath.push(Array(title, id));
		}else{
			while(db_filePath[db_filePath.length-1][1] != id){
				db_filePath.pop();
			}
		}
		db_getFiles(id);
	});
}

function db_getFiles(folderID){
	console.log("Going to "+folderID);
	dropboxAction();
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
			db_linkFolders();
			db_linkDeletes();
			db_linkRename();
			//db_linkMove();
		}catch(e){
			if(result.indexOf("token has expired") != -1){
				$(dropbox_fileDiv).html("");
				$(dropbox_fileDiv).html("Token Has Expired<br />"+dbID);
			}else{
				alert("ERRORz: "+e.message);
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

	
	if(db_filePath.length > 1 && !foldersOnly){
		html += "<div class='breadcrumbs'>";
		for(x=0; x!=db_filePath.length; x++){
			if(x!=0){
				html += " > ";
			}
			if(x == db_filePath.length-1){
				html += "<strong>";
			}
			if(capitaliseFirstLetter(db_filePath[x][0]) == "/"){
				html += "<span class='breadcrums_item'>Root</span>";
			}else{
				html += "<span class='breadcrums_item'>"+capitaliseFirstLetter(db_filePath[x][0])+"</span>";
			}
			if(x == db_filePath.length-1){
				html += "</strong>";
			}
		}
		html += "</div>";
	}
	html += "<ul>";
	
	if(db_filePath.length > 1){
		html += "<li><a class='dbox_folder' href='"+(db_filePath[db_filePath.length-2][1])+"'><img src='images/backIcon.png' alt='back' height='30' width='30' />Back</a></li>";
	}
	
	for(i=0; i!=dbox_folders.length; i++){
		html += "<li>";
			if(!foldersOnly){
				//html += "<hr />";
			}
			html += "<a class='dbox_folder' href='"+dbox_folders[i].path+"'>";
				html += "<img width='30' border='0' height='30' alt='folder' src='images/fileIcons/48x48/"+dbox_folders[i].icon+"48.gif'>";
				html += "<span class='fileName'>"+(dbox_folders[i].path).substr(dbox_folders[i].path.lastIndexOf("/")+1)+"</span>";
			html += "</a>";
			
			if(!foldersOnly){
				html += '<div id="FileOptions">';
				
				html += '<a href="'+dbox_folders[i].path+'" class="db_renameBt"><img class="fadein" src="images/Rename.png" width="25" border="0" height="25" alt="Edit"></a> ';
				
				html += '<a href="'+dbox_folders[i].path+'" class="db_deleteBt"><img class="fadein" src="images/Delete.png" width="20" border="0" height="30" alt="Delete"></a> ';
				html += '</div>';
			}
		html += "</li>";
	}
	
	if(!foldersOnly){
		for(i=0; i!=dbox_files.length; i++){
			html += "<li>";
			//html += "<hr />";
			html += "<span class='dbox_file'>";
			html += "<img width='30' border='0' height='30' alt='file' src='images/fileIcons/16x16/"+dbox_files[i].icon+".gif'>";
			html += "<span class='fileName'>"+(dbox_files[i].path).substr(dbox_files[i].path.lastIndexOf("/")+1)+"</span>";
			html += "</span>";
			if(!foldersOnly){
				html += '<div id="FileOptions">';
				
				/*if(dbox_files[i].alternateLink){
					html += '<a href="'+dbox_files[i].alternateLink+'" target="blank"><img class="fadein" src="images/additionalOptions.png" width="25" border="0" height="25" alt="Open"></a> ';
				}*/
				
				html += '<a href="dropbox/requests/downloadFile.php?path='+dbox_files[i].path+'" target="blank"><img class="Download" src="images/Download.png" width="25" border="0" height="25" alt="Download"></a> ';
				
				html += '<a href="'+dbox_files[i].path+'" class="db_deleteBt"><img class="fadein" src="images/Delete.png" width="20" border="0" height="30" alt="Delete"></a> ';
				
				html += '<a href="'+dbox_files[i].path+'" class="db_renameBt"><img class="fadein" src="images/Rename.png" width="25" border="0" height="25" alt="Edit"></a> ';
				
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
	dropboxAction();
	startLoading("Starting Upload...");
	//displayMsg("Starting Upload...");
	
	//Find the current folder directory
	var folderID = db_filePath[db_filePath.length-1][1];

	
	//If these hidden forms don't exist add them
	if(!$("#data_folderID").length){
		$("#"+formID).append('<input type="hidden" name="folderID" id="data_folderID" />');
	}
	if(!$("#gdriveAuth").length){
		$("#"+formID).append('<input type="hidden" name="access_token" id="gdriveAuth"/>');
	}
	
	//We need to set the value to actual form items because the ajax can only send the form data from the page
	$("#data_folderID").val(folderID);
	$("#gdriveAuth").val(dbID);
	
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
    	$("#newFilePopup").dialog( "close" );
    	//Refresh the files list
    	db_getFiles(db_filePath[db_filePath.length-1][1]);
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