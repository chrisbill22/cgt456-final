var filePath = Array(Array("root","root"));
var dBoxSubdir = "";






function db_getFiles(folderID){
	if(folderID == "root" || folderID == ""){
		folderID = "/";
	}
	currentView = "dbox";
	startLoading("Getting Files");
	//displayMsg("Getting folder "+folderID);
	if(dbID == ""){
		$(google_fileDiv).html("No Token. <a href='requests/login.php'>Please Log In</a>");
		stopLoading();
		return false;
	}
	updateLoadingProgress(50, "Asking Dropbox For Files");
	$.ajax({
		url:dBoxSubdir+"requests/getFile.php",
		type:"POST",
		data:{access_token:dbID, folderID:folderID}
	}).done(function(result){
		updateLoadingProgress(70, "Rendering Results");
		try{
			files = eval(result);
			console.log(files);
			//gd_displayFiles(google_fileDiv, false);
			//gd_displayFiles(google_folderDiv, true);
			stopLoading();
			//gd_linkFolders();
			//gd_linkDeletes();
			//gd_linkRename();
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