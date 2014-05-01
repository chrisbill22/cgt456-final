var files = "";
var tempFilePath;
var filePath = Array(Array("root","root"));
var accessToken;
var accessObj;
var gdrive_folders;
var gdrive_files;
var moveID;
var renderDiv;
var gDriveSubdir = "";


$(document).ready(function(){
	$("#getFiles").click(function(){
		getFiles('', renderDiv);
	});
	$("#uploadFileBtn").click(function(){
		uploadFile("uploadFileForm", renderDiv);
	});
});


/*function displayMsg(string){
	$("#message").html('');
	$("#message").html(string);
	console.log(string);
}*/



function linkMove(){
	$(".moveBt").click(function(event){
		event.preventDefault();
		moveID = $(this).attr("href");
		openMoveFile("");
	});
}
function openMoveFile(folderID){
	var oldFolderID = filePath[filePath.length-1][1];
	tempFilePath = filePath;
	filePath = Array(Array("root", "root"));
	$( "#movePopup" ).dialog({
	    modal: true,
	    title:"Move Item",
	    width:360,
	    buttons: {
	        "Move": function() {
	        	/*
	        	console.log("MoveID = "+moveID);
	        	console.log("NewFolder = "+filePath[filePath.length-1][1]);
	        	console.log("OldFoler = "+oldFolderID);
	        	*/
	        	moveFile(moveID, filePath[filePath.length-1][1], oldFolderID, "results");
	        	$( this ).dialog( "close" );
	        }
	    },
	    close: function(){
	    	filePath = tempFilePath;
	    	tempFilePath = Array();
	    }
    });
	if(folderID){
		getFiles(folderID, "movePopup", true);
	}else{
		getFiles("root", "movePopup", true);
	}
}
function moveFile(fileID, newParent, oldParent, displayDivID){
	console.log("FileID = "+fileID);
	console.log("NewFolder = "+newParent);
	console.log("OldFoler = "+oldParent);
	$.ajax({
        url: gDriveSubdir+'requests/moveFile.php',  //Server script to process data
        type: 'POST',
        data: {access_token:accessToken, fileID:fileID, oldParent:oldParent, newParent:newParent},
    }).done(function(msg) {
    	console.log(msg);
		//getFiles(filePath[filePath.length-1][1], displayDivID);
    }).error(function(XHR, string, error){
		alert("ERROR: "+string);
		console.log(XHR);
		console.log(string);
		console.log(error);
	});
}

function linkRename(displayDivID){
	$(".renameBt").click(function(event){
		event.preventDefault();
		var ID = $(this).attr("href");
		$( "#renamePopup" ).dialog({
		    resizable: false,
		    modal: true,
		    title:"New Name",
		    buttons: {
		        Ok: function() {
		        	//alert(ID+", "+$("#renamePopup input").val()+", "+displayDivID);
		        	renameFile(ID, $("#renamePopup input").val(), displayDivID);
		        	$( this ).dialog( "close" );
		        }
		    }
	    });
	});
}
function renameFile(fileID, newName, displayDivID){
	startLoading("Renaming File");
	$.ajax({
        url: gDriveSubdir+'requests/renameFile.php',  //Server script to process data
        type: 'POST',
        data: {access_token:accessToken, fileID:fileID, newFileName:newName},
    }).done(function(msg) {
		getFiles(filePath[filePath.length-1][1], displayDivID);
    }).error(function(XHR, string, error){
		alert("ERROR: "+string);
		console.log(XHR);
		console.log(string);
		console.log(error);
	});
}

function linkDeletes(displayDivID){
	$("a.deleteBt").click(function(event){
		event.preventDefault();
		deleteFile($(this).attr("href"), displayDivID);
	});
}
function deleteFile(fileID, displayDivID){
	var confirmResult = confirm("Are you sure you would like to delete this file?"); 
	if(confirmResult){
		startLoading("Deleting File");
		$.ajax({
	        url: gDriveSubdir+'requests/deleteFile.php',  //Server script to process data
	        type: 'POST',
	        data: {access_token:accessToken, fileID:fileID},
	    }).done(function(msg) {
	    	if(msg.indexOf("True") == -1){
	    		alert("ERROR: "+msg);
	    		stopLoading();
	    	}else{
	    		getFiles(filePath[filePath.length-1][1], displayDivID);
	    	}
	    }).error(function(XHR, string, error){
			alert("ERROR: "+string);
			console.log(XHR);
			console.log(string);
			console.log(error);
		});
	}
}

function uploadFile(formID, displayDivID){
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
    	
    	//Refresh the files list
    	getFiles(filePath[filePath.length-1][1], displayDivID);
	}
	var formData = new FormData($('#'+formID)[0]);
    $.ajax({
        url: gDriveSubdir+'requests/uploadFile.php',  //Server script to process data
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

function getFiles(folderID, displayDivID, foldersOnly){
	startLoading("Getting Folder");
	//displayMsg("Getting folder "+folderID);
	if(accessToken == ""){
		$("#"+displayDivID).html("No Token. <a href='requests/login.php'>Please Log In</a>");
		stopLoading();
		return false;
	}
	updateLoadingProgress(50, "Asking Google For Files");
	$.ajax({
		url:gDriveSubdir+"requests/getFiles.php",
		type:"POST",
		data:{access_token:accessToken, folderID:folderID}
	}).done(function(result){
		updateLoadingProgress(70, "Rendering Results");
		try{
			files = eval(result);
			console.log(files);
			//displayMsg("Files Retrieved");
			if(displayDivID){
				displayFiles(displayDivID, foldersOnly);
			}
			stopLoading();
		}catch(e){
			if(result.indexOf("token has expired") != -1){
				$("#"+displayDivID).html("");
				$("#"+displayDivID).html("Token Has Expired<br />"+accessToken);
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
function linkFolders(displayDivID, foldersOnly){
	$("#"+displayDivID+" .gdrive_folder").click(function(event){
		event.preventDefault();
		var id = $(this).attr("href");
		var title = $(this).html();
		
		var contains = false;
		for(var i = 0; i != filePath.length; i++) {
		   if(filePath[i][1] == id) {
		     contains = true;
		   }
		}
		
		if(!contains){
			filePath.push(Array(title, id));
		}else{
			while(filePath[filePath.length-1][1] != id){
				filePath.pop();
			}
		}
		getFiles(id, displayDivID, foldersOnly);
	});
}
function displayFiles(displayDivID, foldersOnly){
	$("#"+displayDivID).html("");
	var html = "<h1>Files</h1>";
	gdrive_folders = Array();
	gdrive_files = Array();
	
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
		for(x=0; x!=filePath.length; x++){
			if(x!=0){
				html += " > ";
			}
			if(x == filePath.length-1){
				html += "<strong>";
			}
			html += capitaliseFirstLetter(filePath[x][0]);
			if(x == filePath.length-1){
				html += "</strong>";
			}
		}
	}
	html += "";
	
	if(filePath.length > 1){
		html += "<br /><br /><a class='gdrive_folder' href='"+(filePath[filePath.length-2][1])+"'>Back</a><br />";
	}
	
	for(i=0; i!=gdrive_folders.length; i++){
		html += "<img src='"+gdrive_folders[i].iconLink+"'>";
		html += "<a class='gdrive_folder' href='"+gdrive_folders[i].id+"'>"+gdrive_folders[i].title+"</a><br />";
	}
	
	if(!foldersOnly){
		for(i=0; i!=gdrive_files.length; i++){
			
			if(gdrive_files[i].alternateLink){
				html += "<a href='"+gdrive_files[i].alternateLink+"' target='blank'>Open</a> ";
			}
			
			if(gdrive_files[i].webContentLink){
				html += "<a href='"+gdrive_files[i].webContentLink+"' target='blank'>Download</a> ";
			}
			
			html += "<a href='"+gdrive_files[i].id+"' class='deleteBt'>Delete</a> ";
			
			html += "<a href='"+gdrive_files[i].id+"' class='renameBt'>Rename</a> ";
			
			//html += "<a href='"+gdrive_files[i].id+"' class='moveBt'>Move</a> ";
			
			html += "<img src='"+gdrive_files[i].iconLink+"'>";
			html += gdrive_files[i].title+"<br />";
		}
	}
	$("#"+displayDivID).html(html);
	linkFolders(displayDivID, foldersOnly);
	linkDeletes(displayDivID);
	linkRename(displayDivID);
	linkMove(displayDivID);
}


function capitaliseFirstLetter(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}