<?php
session_start();
$_SESSION["authenticated"] = "false";
$_SESSION["username"]      = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Lab 11 Logout</title>
</head>

<body>
	<h1>Facebook API + Bit.ly API + Twitter API Example</h1>
    <p><fb:login-button autologoutlink="true"></fb:login-button></p>
    <p><fb:like></fb:like></p>
    
    <div id="fb-root"></div>
    <script>
		window.fbAsyncInit = function() {
			FB.init({appId: '619016628159629', status: true, cookie: true,
				xfbm1: true});
		};
		(function() {
			var e = document.createElement('script');
			e.type = 'text/javascript';
			e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
			e.async = true;
			document.getElementById('fb-root').appendChild(e);
		}());
	</script>
    
    <a href="send2twitter.php">Login again</a>
</body>
</html>