var files = "";
var filePath = Array("root");
var accessToken;
var accessObj;
var gdrive_folders;
var gdrive_files;

$(document).ready(function(){
	
});


/*function displayMsg(string){
	$("#message").html('');
	$("#message").html(string);
	console.log(string);
}*/





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
	        url: 'requests/renameFile.php',  //Server script to process data
	        type: 'POST',
	        data: {access_token:accessToken, fileID:fileID, newFileName:newName},
	    }).done(function(msg) {
    		getFiles(filePath[filePath.length-1], displayDivID);
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
	        url: 'requests/deleteFile.php',  //Server script to process data
	        type: 'POST',
	        data: {access_token:accessToken, fileID:fileID},
	    }).done(function(msg) {
	    	if(msg.indexOf("True") == -1){
	    		alert("ERROR: "+msg);
	    		stopLoading();
	    	}else{
	    		getFiles(filePath[filePath.length-1], displayDivID);
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
	var folderID = filePath[filePath.length-1];
	
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
    	getFiles(filePath[filePath.length-1], displayDivID);
	}
	var formData = new FormData($('#'+formID)[0]);
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
    	successHandler(msg);
    });
}

function getFiles(folderID, displayDivID){
	startLoading("Getting Folder");
	//displayMsg("Getting folder "+folderID);
	if(accessToken == ""){
		$("#"+displayDivID).html("No Token. <a href='requests/login.php'>Please Log In</a>");
		stopLoading();
		return false;
	}
	updateLoadingProgress(50, "Asking Google For Files");
	$.ajax({
		url:"requests/getFiles.php",
		type:"POST",
		data:{access_token:accessToken, folderID:folderID}
	}).done(function(result){
		updateLoadingProgress(70, "Rendering Results");
		try{
			files = eval(result);
			console.log(files);
			//displayMsg("Files Retrieved");
			displayFiles(displayDivID);
			stopLoading();
		}catch(e){
			if(result.indexOf("token has expired") != -1){
				$("#"+displayDivID).html("");
				$("#"+displayDivID).html("Token Has Expired<br />"+accessToken);
			}else{
				alert("ERROR: "+result);
			}
		}
	}).error(function(XHR, string, error){
		alert("ERROR: "+string);
		console.log(XHR);
		console.log(string);
		console.log(error);
	});
}
function linkFolders(displayDivID){
	$(".gdrive_folder").click(function(){
		var id = $(this).attr("id");
		if(filePath.indexOf(id) == -1){
			filePath.push(id);
		}else{
			while(filePath[filePath.length-1] != id){
				filePath.pop();
			}
		}
		getFiles(id, displayDivID);
	});
}
function displayFiles(displayDivID){
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
		
		if(gdrive_files[i].alternateLink){
			html += "<a href='"+gdrive_files[i].alternateLink+"' target='blank'>Open</a> ";
		}
		
		if(gdrive_files[i].webContentLink){
			html += "<a href='"+gdrive_files[i].webContentLink+"' target='blank'>Download</a> ";
		}
		
		html += "<a href='"+gdrive_files[i].id+"' class='deleteBt'>Delete</a> ";
		
		html += "<a href='"+gdrive_files[i].id+"' class='renameBt'>Rename</a> ";
		
		
		
		html += "<img src='"+gdrive_files[i].iconLink+"'>";
		html += gdrive_files[i].title+"<br />";
	}
	
	$("#"+displayDivID).html(html);
	linkFolders(displayDivID);
	linkDeletes(displayDivID);
	linkRename(displayDivID);
}
