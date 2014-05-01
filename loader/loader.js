var loadingProgress = 0;

function startLoading(message){
	loadingProgress = 4;
	renderLoader();
	//renderLoaderMessage();
	renderDisabler();
	updateLoadingProgress(loadingProgress, message);
	$("#loader").fadeIn();
	$("#loaderMessage").fadeIn();
	$("#disabler").fadeIn();
}

function stopLoading(){
	if(loadingProgress != 100){
		console.log("loading at "+loadingProgress);
		updateLoadingProgress(100);
		setTimeout(stopLoading, 200);
	}else{
		$("#loader").fadeOut(500, destroyLoader);
		//$("#loaderMessage").fadeOut(500, destroyLoaderMessage);
		$("#disabler").fadeOut(500, destroyDisabler);
	}
}

//function updateLoadingProgress(number, message){ updateLoadingProgress(number, message, true); }
function updateLoadingProgress(number, message, animate){
	if(animate == null){
		animate = true;
	}
	$("#loaderMessage").html(message);
	
	if(number > 1){
		number /= 100;
	}
	loadingProgress = number*100;
	add_width = (number*$('#loader').width())+'px';
	if(animate){
		$("#loader_progress").animate({width:add_width});
	}else{
		$("#loader_progress").css('width', add_width);
	}
}

function renderLoader(){
	if(!$("#loader").length){
		$("body").append("<div id='loader'><div id='loader_image'><img src='images/LoadingIcon.gif' alt='Loading animation' /><span id='loaderMessage'></span></div><div id='loader_progress'></div></div>");
	}
}
function destroyLoader(){
	if($("#loader").length){
		$("#loader").remove();
	}
}
function renderDisabler(){
	if(!$("#disabler").length){
		$("body").append("<div id='disabler' style='display:none;'></div>");
	}
}
function destroyDisabler(){
	if($("#disabler").length){
		$("#disabler").remove();
	}
}
/*
function renderLoaderMessage(){
	if(!$("#loaderMessage").length){
		$("body").append("<div id='loaderMessage'></div>");
	}
}
function destroyLoaderMessage(){
	if($("#loaderMessage").length){
		$("#loaderMessage").remove();
	}
}
*/
