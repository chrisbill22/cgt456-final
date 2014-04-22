var files = "";
var filePath = Array("root");
var accessToken;
var accessObj;

function displayMsg(string){
	$("#message").html('');
	$("#message").html(string);
	console.log(string);
}

function uploadFile(formID, displayDivID){
	displayMsg("Starting Upload...");
	
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
		displayMsg("ERROR: "+errorMsg);
	}
	function progressHandlingFunction(e){
	    if(e.lengthComputable){
	    	if(Math.round((e.loaded / e.total)*100) == 100){
	    		displayMsg("Sending To Google Drive...");
	    	}else{
	    		displayMsg("Progress = "+Math.round((e.loaded / e.total)*100)+"%");
	    	}
	    }
	}
	function successHandler(msg){
		displayMsg("Upload of file "+msg+" complete.");
		
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
	displayMsg("Getting folder "+folderID);
	if(accessToken == ""){
		$("#"+displayDivID).html("No Token. <a href='requests/login.php'>Please Log In</a>");
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
			displayMsg("Files Retrieved");
			displayFiles(displayDivID);
		}catch(e){
			if(result.indexOf("token has expired") != -1){
				$("#"+displayDivID).html("");
				$("#"+displayDivID).html("Token Has Expired<br />"+accessToken);
			}else{
				displayMsg("ERROR: "+result);
			}
		}
	}).error(function(XHR, string, error){
		displayMsg("ERROR");
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
	
	$("#"+displayDivID).html(html);
	linkFolders(displayDivID);
}