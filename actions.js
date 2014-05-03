var currentView = ""; //Values: gdrive or dbox

function openNewFolderPopup(){
	$("#newFolderPopup").dialog({
	    modal: true,
	    title:"New Folder",
	    width:360,
	    buttons: {
	        "Create": function() {
	        	if(currentView == "gdrive"){
	        		gd_createFolder();
	        	}
	        	
	        	$(this).dialog( "close" );
	        	$(this).children("input").val("");
	        }
	    }
    });
}

function openFileUpload(){
	$("#newFilePopup").dialog({
	    modal: true,
	    title:"Upload File",
	    width:360
    });
}

function dropboxAction(){
	currentView = "gdrive";
	if($("#startup").is(":visible")){
		$("#startup").fadeOut('normal', function(){
			$("#dropboxFiles").fadeIn();
		});
	}else if(!$("#dropboxFiles").is(":visible")){
		$("#googleFiles").fadeOut('normal', function(){
			$("#dropboxFiles").fadeIn();
		});
	}
	
}
function gDriveAction(){
	currentView = "dbox";
	if($("#startup").is(":visible")){
		$("#startup").fadeOut('normal', function(){
			$("#googleFiles").fadeIn();
		});
	}else if(!$("#googleFiles").is(":visible")){
		$("#dropboxFiles").fadeOut('normal', function(){
			$("#googleFiles").fadeIn();
		});
	}
}

function capitaliseFirstLetter(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function baseName(str)
{
   var base = new String(str).substring(str.lastIndexOf('/') + 1); 
    if(base.lastIndexOf(".") != -1)       
       base = base.substring(0, base.lastIndexOf("."));
   return base;
}