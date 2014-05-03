var files = "";
var tempFilePath;
var gd_finalPath = Array(Array("root","root"));
var gdID;
//var accessObj;
var gdrive_folders;
var gdrive_files;
//var moveID;
var gDriveSubdir = "";
var google_folderDiv = "";
var google_fileDiv = "";
/*

$(document).ready(function(){
	$("#getFiles").click(function(){
		gd_getFiles('');
	});
	$("#uploadFileBtn").click(function(){
		gd_uploadFile("uploadFileForm", renderDiv);
	});
});
*/

/*function displayMsg(string){
	$("#message").html('');
	$("#messagfe").html(string);
	console.log(string);
}*/

function gd_createFolder(){
	currentView = "gdrive";
	startLoading("Creating Folder");
	$.ajax({
        url: gDriveSubdir+'requests/createFolder.php',  //Server script to process data
        type: 'POST',
        data: {access_token:gdID, title:$("#newFolderPopup input").val(), parentID:gd_finalPath[gd_finalPath.length-1][1]},
    }).done(function(msg) {
    	console.log(msg);
		gd_getFiles(gd_finalPath[gd_finalPath.length-1][1]);
    }).error(function(XHR, string, error){
		alert("ERROR: "+string);
		console.log(XHR);
		console.log(string);
		console.log(error);
	});
}


/*
function linkMove(){
	$(".moveBt").click(function(event){
		event.preventDefault();
		moveID = $(this).attr("href");
		openMoveFile("");
	});
}
function openMoveFile(folderID){
	var oldFolderID = gd_finalPath[gd_finalPath.length-1][1];
	tempFilePath = gd_finalPath;
	gd_finalPath = Array(Array("root", "root"));
	$( "#movePopup" ).dialog({
	    modal: true,
	    title:"Move Item",
	    width:360,
	    buttons: {
	        "Move": function() {
	        	
	        	//console.log("MoveID = "+moveID);
	        	//console.log("NewFolder = "+gd_finalPath[gd_finalPath.length-1][1]);
	        	//console.log("OldFoler = "+oldFolderID);
	       
	        	moveFile(moveID, gd_finalPath[gd_finalPath.length-1][1], oldFolderID, "results");
	        	$( this ).dialog( "close" );
	        }
	    },
	    close: function(){
	    	gd_finalPath = tempFilePath;
	    	tempFilePath = Array();
	    }
    });
	if(folderID){
		gd_getFiles(folderID, "movePopup", true);
	}else{
		gd_getFiles("root", "movePopup", true);
	}
}
function moveFile(fileID, newParent, oldParent){
	console.log("FileID = "+fileID);
	console.log("NewFolder = "+newParent);
	console.log("OldFoler = "+oldParent);
	$.ajax({
        url: gDriveSubdir+'requests/moveFile.php',  //Server script to process data
        type: 'POST',
        data: {access_token:gdID, fileID:fileID, oldParent:oldParent, newParent:newParent},
    }).done(function(msg) {
    	console.log(msg);
		//gd_getFiles(gd_finalPath[gd_finalPath.length-1][1]);
    }).error(function(XHR, string, error){
		alert("ERROR: "+string);
		console.log(XHR);
		console.log(string);
		console.log(error);
	});
}
*/


function gd_linkRename(){
	$(".renameBt").click(function(event){
		currentView = "gdrive";
		event.preventDefault();
		var ID = $(this).attr("href");
		$( "#renamePopup" ).dialog({
		    resizable: false,
		    modal: true,
		    title:"New Name",
		    buttons: {
		        Ok: function() {
		        	//alert(ID+", "+$("#renamePopup input").val());
		        	gd_renameFile(ID, $("#renamePopup input").val());
		        	$( this ).dialog( "close" );
		        }
		    }
	    });
	});
}
function gd_renameFile(fileID, newName){
	currentView = "gdrive";
	startLoading("Renaming File");
	$.ajax({
        url: gDriveSubdir+'requests/renameFile.php',  //Server script to process data
        type: 'POST',
        data: {access_token:gdID, fileID:fileID, newFileName:newName},
    }).done(function(msg) {
		gd_getFiles(gd_finalPath[gd_finalPath.length-1][1]);
    }).error(function(XHR, string, error){
		alert("ERROR: "+string);
		console.log(XHR);
		console.log(string);
		console.log(error);
	});
}

function gd_linkDeletes(){
	$("a.deleteBt").click(function(event){
		currentView = "gdrive";
		event.preventDefault();
		gd_deleteFile($(this).attr("href"));
	});
}
function gd_deleteFile(fileID){
	currentView = "gdrive";
	var confirmResult = confirm("Are you sure you would like to delete this file?"); 
	if(confirmResult){
		startLoading("Deleting File");
		$.ajax({
	        url: gDriveSubdir+'requests/deleteFile.php',  //Server script to process data
	        type: 'POST',
	        data: {access_token:gdID, fileID:fileID},
	    }).done(function(msg) {
	    	if(msg.indexOf("True") == -1){
	    		alert("ERROR: "+msg);
	    		stopLoading();
	    	}else{
	    		gd_getFiles(gd_finalPath[gd_finalPath.length-1][1]);
	    	}
	    }).error(function(XHR, string, error){
			alert("ERROR: "+string);
			console.log(XHR);
			console.log(string);
			console.log(error);
		});
	}
}

function gd_uploadFile(formID){
	currentView = "gdrive";
	startLoading("Starting Upload...");
	//displayMsg("Starting Upload...");
	
	//Find the current folder directory
	var folderID = gd_finalPath[gd_finalPath.length-1][1];
	
	//If these hidden forms don't exist add them
	if(!$("#data_folderID").length){
		$("#"+formID).append('<input type="hidden" name="folderID" id="data_folderID" />');
	}
	if(!$("#gdriveAuth").length){
		$("#"+formID).append('<input type="hidden" name="access_token" id="gdriveAuth"/>');
	}
	
	//We need to set the value to actual form items because the ajax can only send the form data from the page
	$("#data_folderID").val(folderID);
	$("#gdriveAuth").val(gdID);
	
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
    	gd_getFiles(gd_finalPath[gd_finalPath.length-1][1]);
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

function gd_getFiles(folderID){
	currentView = "gdrive";
	startLoading("Getting Folder");
	//displayMsg("Getting folder "+folderID);
	if(gdID == ""){
		$(google_fileDiv).html("No Token. <a href='requests/login.php'>Please Log In</a>");
		stopLoading();
		return false;
	}
	updateLoadingProgress(50, "Asking Google For Files");
	$.ajax({
		url:gDriveSubdir+"requests/getFiles.php",
		type:"POST",
		data:{access_token:gdID, folderID:folderID}
	}).done(function(result){
		updateLoadingProgress(70, "Rendering Results");
		try{
			files = eval(result);
			console.log(files);
			gd_displayFiles(google_fileDiv, false);
			gd_displayFiles(google_folderDiv, true);
			stopLoading();
			gd_linkFolders();
			gd_linkDeletes();
			gd_linkRename();
			//gd_linkMove();
		}catch(e){
			if(result.indexOf("token has expired") != -1){
				$(google_fileDiv).html("");
				$(google_fileDiv).html("Token Has Expired<br />"+gdID);
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
function gd_linkFolders(){
	$(".gdrive_folder").click(function(event){
		currentView = "gdrive";
		event.preventDefault();
		var id = $(this).attr("href");
		var title = $(this).children(".fileName").html();
		
		var contains = false;
		for(var i = 0; i != gd_finalPath.length; i++) {
		   if(gd_finalPath[i][1] == id) {
		     contains = true;
		   }
		}
		
		if(!contains){
			gd_finalPath.push(Array(title, id));
		}else{
			while(gd_finalPath[gd_finalPath.length-1][1] != id){
				gd_finalPath.pop();
			}
		}
		gd_getFiles(id);
	});
}
function gd_displayFiles(displayDivID, foldersOnly){
	currentView = "gdrive";
	console.log(displayDivID);
	$(displayDivID).html("");
	var html = "";
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

	
	if(gd_finalPath.length > 1){
		html += "<div class='breadcrumbs'>";
		for(x=0; x!=gd_finalPath.length; x++){
			if(x!=0){
				html += " > ";
			}
			if(x == gd_finalPath.length-1){
				html += "<strong>";
			}
			html += "<span class='breadcrums_item'>"+capitaliseFirstLetter(gd_finalPath[x][0])+"</span>";
			if(x == gd_finalPath.length-1){
				html += "</strong>";
			}
		}
		html += "</div>";
	}
	html += "<ul>";
	
	if(gd_finalPath.length > 1){
		html += "<li><a class='gdrive_folder' href='"+(gd_finalPath[gd_finalPath.length-2][1])+"'>Back</a></li>";
	}
	
	for(i=0; i!=gdrive_folders.length; i++){
		html += "<li>";
			if(!foldersOnly){
				html += "<hr />";
			}
			html += "<a class='gdrive_folder' href='"+gdrive_folders[i].id+"'>";
				html += "<img width='30' border='0' height='30' alt='folder' src='"+gdrive_folders[i].iconLink+"'>";
				html += "<span class='fileName'>"+gdrive_folders[i].title+"</span>";
			html += "</a>";
			
			if(!foldersOnly){
				html += '<div id="FileOptions">';
				
				html += '<a href="'+gdrive_folders[i].id+'" class="renameBt"><img class="fadein" src="images/additionalOptions.png" width="25" border="0" height="25" alt="Edit"></a> ';
				
				html += '<a href="'+gdrive_folders[i].id+'" class="deleteBt"><img class="fadein" src="images/Delete.png" width="20" border="0" height="30" alt="Delete"></a> ';
				html += '</div>';
			}
		html += "</li>";
	}
	
	if(!foldersOnly){
		for(i=0; i!=gdrive_files.length; i++){
			html += "<li>";
			html += "<hr />";
			html += "<img width='30' border='0' height='30' alt='file' src='"+gdrive_files[i].iconLink+"'>";
			html += gdrive_files[i].title+"<br />";
			
			if(!foldersOnly){
				html += '<div id="FileOptions">';
				
				if(gdrive_files[i].alternateLink){
					html += '<a href="'+gdrive_files[i].alternateLink+'" target="blank"><img class="fadein" src="images/additionalOptions.png" width="25" border="0" height="25" alt="Open"></a> ';
				}
				
				if(gdrive_files[i].webContentLink){
					html += '<a href="'+gdrive_files[i].webContentLink+'" target="blank"><img class="Download" src="images/additionalOptions.png" width="25" border="0" height="25" alt="Download"></a> ';
				}
				
				html += '<a href="'+gdrive_files[i].id+'" class="deleteBt"><img class="fadein" src="images/Delete.png" width="20" border="0" height="30" alt="Delete"></a> ';
				
				html += '<a href="'+gdrive_files[i].id+'" class="renameBt"><img class="fadein" src="images/additionalOptions.png" width="25" border="0" height="25" alt="Edit"></a> ';
				
				//html += "<a href='"+gdrive_files[i].id+"' class='moveBt'>Move</a> ";
				html += "</div>";
			}
			html += "</li>";
		}
	}
	html += "</ul>";
	
	$(displayDivID).html(html);
}

