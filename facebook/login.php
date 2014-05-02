<?php session_start();
echo("<?xml version=\"1.0\" encoding=\"UTF-8\"?>");
?>
<!DOCTYPE html PUBLIC "-//w3c//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Facebook Login</title>
    <!--insert css stuff here-->
</head>

<body>
<?php

//facebook login
$app_id = "1416567535280863";
$app_secret = "760b7e6b1bb4ac6f5c3cd9c0788c49cf";
$my_url = "http://web.ics.purdue.edu/sheckman/index.html"; //replace this

if(isset($_REQUEST["code"]))
{
	$code = $_REQUEST["code"];
	
	$token_url = "https://graph.facebook.com/oauth/access_token?client_id="
		. $app_id . "&redirect_uri=" . urlencode($my_url) . "&client_secret="
		. $app_secret . "&code=" . $code;
	
	$access_token = file_get_contents($token_url);
	
	$graph_url = "https://graph.facebook.com/me?" . $access_token;
	
	$user = json_decode(file_get_contents($graph_url));
	
	echo("Hello " . $user->username);
	
	$_SESSION["username"] = $user->username;
}

//check authentication - not sure if necessary
if(!empty($_SESSION["username"]))
{
	if($_SESSION["username"] == "insert_list_of_usernames_here")
	{
		$_SESSION["authenticated"] = "true";
		$_SESSION["errorMessage"] = "";
	}
	else
	{
		$_SESSION["authenticated"] = "false";
		$_SESSION["errorMessage"] = "You are not authorized to use this application.";
	}
	{
		$_SESSION["username"] = "";
		$_SESSION["authenticated"] = "false";
	}
}

if($_SESSION["authenticated"] == "true")
{
	//twitter stuff
	if(isset($_POST["twitter_msg"]) && isset($_POST["bitlyURL"]))
	{
		$status = $_POST["twitter_msg"];
		$url = $_POST["bitlyURL"];
		
		if((strlen($status)<1) || strlen($url)<6)
		{
			$error=1;
		}
		else
		{
			//bitly stuff
			$login = "bitlyLogin";
			$appkey = "bitlyKey";
			$format = "txt";
			
			$bitlyURL = "this is not an actual url";
		
			$c = curl_init();
			$timeout = 5;
			curl_setopt($c,CURLOPT_URL,$bitlyURL);
			curl_setopt($c,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($c,CURLOPT_CONNECTTIMEOUT,$timeout);
			$data = curl_exec($c);
			curl_close($c);
			//end of bitly stuff
			
			//sends to twitter
			require_once "includes/twitteroauth.php";
			
			//twitter account keys
			define("CONSUMER_KEY", "xxx");
			define("CONSUMER_SECRET", "xxx");
			define("OAUTH_TOKEN", "xxx");
			define("OAUTH_SECRET", "xxx");
			
			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);
			$content = $connection->get("account/verify_credentials");
			
			$connection->post("statuses/update", array("status" => $status." ".$data));
			//end twitter stuff
		}
	}
	
	if(isset($_POST["twitter_msg"]) && !isset($error))
	{
		?>
        <div class="msg"><?php echo $status." ".$data ?></div>
        <?php
	}
	else if(isset($error))
	{
		?>
        <div class="msg">Error: please enter a message</div>
        <?php
	}
	?>
    <span style="text-align:right"><a href="logout.php">logout</a></span>
    <h1 style="font-size:14pt; text-indent:80px;">Facebook api example</h1>
    <form id="form3" action"login.php" method="post">
    	<fieldset id="info">
        	<legend>What's happening?</legend>
            <ul>
            	<li><label title="status" for="twitter_msg">Status</label><input name="twitter_msg" type="text" id="twitter_msg" size="40" maxlength="118"/></li>
                <li><label title="url" for="bitlyURL">URL </label> <input name="bitlyURL" type="text" id="bitlyURL" size="40" maxlength="256"/></li>
            </ul>
        </fieldset>
        <fieldset id="submit">
        	<input type="submit" name="button" id="button" value="post" />
        </fieldset>
    </form>
    
    <?php
	
}
else
{
	//facebook login
	if(!isset($_REQUEST["code"]))
	{
		$dialog_url = "http://www.facebook.con/dialog/oauth?client_id="
			. $app_id . "&redirect_uri=" . urlencode($my_url);
			
		echo("<script top.location.href='" . $dialog_url . "'</script>");
		
	}
}

?>

</body>
</html>