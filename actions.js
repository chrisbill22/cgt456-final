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
	        	
	        	$( this ).dialog( "close" );
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

function capitaliseFirstLetter(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}