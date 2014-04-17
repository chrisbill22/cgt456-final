<?php
session_start();
echo("<?xml version=\"1.0\" encoding=\"UTF-8\"?>");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Facebook, Twitter, Bit.ly API Example - Login</title>
    <style type="text/css">
		ul{ list-style:none; margin-top:5px;}
		ul li{ display:block; float:left; width:100%; height:1%;}
		ul li label{ float:left; padding:7px; color:#6666ff}
		ul li input, ul li textarea{ float:right; margin-right:10px; border:1px solid #ccc; padding:3px; font-family: Georgia, "Times New Roman", Times, serif; width:60%;}
		li input:focus, li textarea:focus{ border:1px solid #666; }
		fieldset{ padding:10px; border:1px solid #ccc; width:400px; overflow:auto; margin:10px;}
		legend{ color:#000099; margin:0 10px 0 0; padding:0 5px; font-size:11pt; font-weight:bold; }
		label span{ color:#ff0000; }
		fieldset#info{ position:absolute; top:60px; left:20px; width:460px; }
		fieldset#submit{ position:absolute; top:200px; left:20px; width:460px; text-align:center; }
		fieldset input#SubmitBtn{ background:#E5E5E5; color:#000099; border:1px solid #ccc; padding:5px; width:150px;}
		div#errorMsg {color:#ff0000; font-weight:bold; font-size:12pt; position:absolute; top:150px; left:25px;}
		div#newLogin {color:#0000ff; font-size:12pt; position:absolute; top:350px; left:25px;}
	</style>
</head>

<body>
<?php
// facebook login
$app_id = "619016628159629";
$app_secret = "f20d1f3180ec066a4fbdddac78720977";
$my_url = "http://cgtweb2.tech.purdue.edu/356/heckman/Lab11/send2Twitter.php";

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

//end facebook login
//more below

if(!empty($_SESSION["username"]))
{
	//this is where you would have a database with a table of all the usernames you want to allow
	//the code takes the username that facebook provided above (user->username) and verifies it against the table
	if($_SESSION["username"] == "sean.heckmandavis")
	{
		$_SESSION["authenticated"] = "true";
		$_SESSION["errorMessage"] = "";
	}
	else
	{
		$_SESSION["authenticated"] = "false";
		$_SESSION["errorMessage"] = "You are not authorized to use this application.";
	}
}
else
{
	$_SESSION["username"] = "";
	$_SESSION["authenticated"] = "false";
}

//end check authentication

if($_SESSION["authenticated"] == "true")
{
	//checks to see if both a twitter status and a URL was entered
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
			//send to bit.ly
			$login = "o_2sl5gba1ka";
			$appkey = "R_5afcea6536e9bc1a3ff27ff3852854a3";
			$format = "txt";
			
			//returns the shortened url
			$bitlyURL = "http://api.bit.ly/v3/shorten?login=".$login."&apiKey=".$appkey."&uri=".urlencode($url)."&format=".$format;
			
			//if you want the expanded url
			// $bitlyURL = "http://api.bit.ly/v3/expand?login=".$login."&apiKey=".$appkey."&shortUrl=".urlencode($url)."&format=".$format;
			
			//gets a result from bit.ly
			$c = curl_init();
			$timeout = 5;
			curl_setopt($c,CURLOPT_URL,$bitlyURL);
			curl_setopt($c,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($c,CURLOPT_CONNECTTIMEOUT,$timeout);
			$data = curl_exec($c);
			curl_close($c);
			
			//end send to bit.ly
			//////////////////////////
			//send to twitter
			
			require_once "includes/twitteroauth.php";
			
			//the following 4 keys come from my twitter acct
			define("CONSUMER_KEY", "ZdxowLj9pJWSjZutzkF4w");
			define("CONSUMER_SECRET", "iAjHLZj6WF7c74bDapOj0VWDsR3FtMtAMR5U9mPAI");
			define("OAUTH_TOKEN", "1170297973-4Xaxq10VSc1g5B6PeEPc91E7X9KlDICwXQWgFnI");
			define("OAUTH_SECRET", "RDXG4NcBXgfWFmxrIHORGHNytZL7FdKiUsvGqSrB0rHLn");
			
			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);
			$content = $connection->get("account/verify_credentials");
			
			$connection->post("statuses/update", array("status" => $status." ".$data));
			//end send to twitter
		}
	}
	
	if(isset($_POST["twitter_msg"]) && !isset($error))
	{
		?>
        <div class="msg"<?php echo $status." ".$data ?></div>
        <?php
	}
	else if(isset($error))
	{
		?>
        <div class="msg">Error: You didn't insert a message, nerd!</div>
        <?php
	}
	?>
    
    <!-- this starts the logout/post button stuff -->
    <span style="text-align:right"><a href="logout.php">logout</a></span>
    <h1 style="font-size:14pt; text-indent:80px;">Facebook API + Bit.ly API + Twitter API Example</h1>
    <form id="form3" action="send2Twitter.php" method="post">
    	<fieldset id="info">
        	<legend>What&apos;s happening?</legend>
            <ul>
            	<!-- STATUS MAXLENGTH SET TO 188 because a bit.ly link will typically be shorter than 22 characters -->
                <li> <label title="Status" for="twitter_msg">Status </label> <input name="twitter_msg" type="text" id="twitter_msg" size="40" maxlength="188"/></li>
                <li> <label title="URL" for="bitlyURL">URL </label> <input name="bitlyURL" type="text" id="bitlyURL" size="40" maxlength="256"/></li>
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
	$_SESSION["authenticated"] = "false";
	$_SESSION["username"] = "";
	
	?>
    
    <h1 style="font-size:14pt; text-indent:80px;">Facebook API + Bit.ly API + Twitter API Example - Login</h1>
    
    <div id="errorMsg"><?php if(!empty($_SESSION["errorMessage"])){echo($_SESSION["errorMessage"]);} ?> <a href="logout.php">logout</a></div>
    
    <?php
	
	//facebook login
	
	if(!isset($_REQUEST["code"]))
	{
		$dialog_url = "http://www.facebook.com/dialog/oauth?client_id="
			. $app_id . "&redirect_uri=" .urlencode($my_url);
			
		echo("<script> top.location.href='" . $dialog_url . "'</script>");
	}
	//end facebook login
} //end show login box
?>

</body>
</html>