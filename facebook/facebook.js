$(document).ready(function() {
	$("#mainLogin").dialog({
		open : function(event, ui) {
			$(".ui-dialog-titlebar-close", $(this).parent()).hide();
		},
		resizable : false,
		modal : true,
		title : "Please Login",
		closeOnEscape : false,
		//autoOpen:false
	});
});

var facebookID;
var googleDriveID;
var dropboxID;
var username;


// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {
	console.log('statusChangeCallback');
	console.log(response);
	// The response object is returned with a status field that lets the
	// app know the current login status of the person.
	// Full docs on the response object can be found in the documentation
	// for FB.getLoginStatus().
	if (response.status === 'connected') {
		// Logged into your app and Facebook.
		console.log("Logged in");
		testAPI();
		$("#mainLogin").dialog( "close" );
	/*} else if (response.status === 'not_authorized') {
		// The person is logged into Facebook, but not your app.
		console.log("Not Logged in1");
		$("#mainLogin_loading").fadeOut('fast', function(){
			$("#mainLogin_login").fadeIn();
		});*/
		
	} else {
		// The person is not logged into Facebook, so we're not sure if
		console.log("Not Logged in2");
		$("#mainLogin_loading").fadeOut('fast', function(){
			$("#mainLogin_login").fadeIn();
		});	
	}
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
function checkLoginState() {
	FB.getLoginStatus(function(response) {
		statusChangeCallback(response);
	});
}

window.fbAsyncInit = function() {
	FB.init({
		appId : '228477767346644',
		cookie : true, // enable cookies to allow the server to access
		// the session
		xfbml : true, // parse social plugins on this page
		version : 'v2.0' // use version 2.0
	});

	// Now that we've initialized the JavaScript SDK, we call
	// FB.getLoginStatus().  This function gets the state of the
	// person visiting this page and can return one of three states to
	// the callback you provide.  They can be:
	//
	// 1. Logged into your app ('connected')
	// 2. Logged into Facebook, but not your app ('not_authorized')
	// 3. Not logged into Facebook and can't tell if they are logged into
	//    your app or not.
	//
	// These three cases are handled in the callback function.

	// Login status check - checks to see if a user is already logged in or not.
	FB.getLoginStatus(function(response) {
		statusChangeCallback(response);
	});

};

// Load the SDK asynchronously
( function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id))
			return;
		js = d.createElement(s);
		js.id = id;
		js.src = "//connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
function testAPI() {
	console.log('Welcome!  Fetching your information.... ');
	FB.api('/me', function(response) {
		//console.log("RESPONSE");
		//console.log(response);
		//console.log(JSON.stringify(response));
		var fbData = eval(response);
		username = fbData.first_name;
		
		$(".name").each(function(){
			$(this).html(username);
		});
		
		//console.log(fbData.id);
		
		$.ajax({
	        url: 'database/login.php',  //Server script to process data
	        type: 'POST',
	        data: {fbID:fbData.id},
	    }).done(function(msg) {
	    	try{
	    		var dbData = eval('('+msg+')');
	    		facebookID = dbData.fbID;
	    		dropboxID = dbData.dbID;
	    		googleDriveID = dbData.gdID;
	    	}catch(e){
				alert("Eval Error: "+msg);	    		
	    	}
	    }).error(function(XHR, string, error){
			alert("ERROR2: "+error);
			console.log(XHR);
			console.log(string);
			console.log(error);
		});
		
	});
}