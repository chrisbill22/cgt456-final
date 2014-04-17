<?php
/**********************************************************************************************************************************
//  Name:        Ronald J. Glotzbach 
//  Date:        November 1, 2009
//  Description: PHP RSS Management System - View RSS, Add RSS, Validate RSS, Edit RSS, Delete RSS - via a web interface
//
//  HOW TO TEACH THIS LAB
//     First,  this code should not be described from top to bottom... you have to jump around.
//     Second, the comments per line are provided so you can accurately describe the line of code to the students as you type it.
//     Third,  there's a lot of JavaScript, but there's only 1 <script> </script> tag, even though there's JS in functions below.
//
//     1)  HTML and CSS first: xml declaration to close html - leave out the JavaScript section for now
//     2)  Test - check design
//     3)  Add PHP at top of page - leave out section for manage=doadd for now
//     4)  Test - page should load w/o errors
//     5)  Add JavaScript section before </body> - temporarily comment out function calls
//     6)  Test - change URL between: manageRss.php ; manageRss.php?manage=add ; manageRss.php?manage=edit&guid=2 ; click Cancel
//     7)  Add function viewRSS - uncomment viewRSS call in JS section
//     8)  Test - manageRss.php --> click add link --> click Cancel --> click edit link --> click Cancel
//     9)  Add function Validate  (no need to test, nothing has changed)
//     10) Add function cleanup   (no need to test, nothing has changed)
//     11) Add function showAdd - uncomment showAdd call in JS section  (no need to test, nothing has changed)
//     12) Add section for manage=doadd at the top of page
//     13) Add function doAdd
//     14) Test - add page --> continue --> type title --> continue --> type desc --> continue --> type link --> continue --> type name --> continue
//     15) Add function showEdit - uncomment showEdit call in JS section
//     16) Test - click edit --> form should autopopulate --> click cancel
//     17) Add function doEdit
//     18) Add section for manage=doedit at the top of page
//     19) Test - click edit --> make a change --> submit --> change should be reflected
//     20) Test - click edit --> try submitting without title, without desc, without link, without name
//     21) Add function showDelete - uncomment showDelete call in JS section
//     22) Test - click delete --> item should display --> click cancel
//     23) Add function doDelete
//     24) Add section for manage=doDelete at the top of page
//     25) Test - click delete --> item should display --> click continue --> item should be gone
**********************************************************************************************************************************/
session_start();                                                     //start the session for use below

if( (!empty($_POST["submit"])) && ($_POST["submit"] == "Cancel") )
{
	cleanup();
	header("Location: manageRss.php");
	exit;
}

include("includes/constants.php");

$doc = new DOMDocument();
$doc->load($XMLfile);
$xpath = new DOMXPath($doc);

$root = $doc->documentElement;



//section for manage=doadd
if( (!empty($_GET["manage"])) && ($_GET["manage"] == "doAdd" ) )
{
	$_SESSION["title"]  = stripslashes($_POST["Title"]);
	$_SESSION["desc"]   = stripslashes($_POST["Description"]);
	$_SESSION["link"]   = stripslashes($_POST["Link"]);
	$_SESSION["author"] = stripslashes($_POST["Author"]);
	
	$_SESSION["message"] = "";
	
	Validate($_SESSION["title"], $_SESSION["desc"], $_SESSION["link"], $_SESSION["author"]);
	
	if( !$_SESSION["message"] == "")
	{
		$_SESSION["failedValidate"] = true;
		header("Location: manageRss.php?manage=add");
		exit;
	}
	
	doAdd($XMLfile);
	
	header("Location: manageRss.php");
	exit;
} //end doAdd functionality
// end section for manage=doadd



//section for manage=doedit
if( (!empty($_GET["manage"])) && ($_GET["manage"] == "doEdit" ) )
{
	$guid = $_POST["guid"];
	
	$_SESSION["title"]  = stripslashes($_POST["Title"]);
	$_SESSION["desc"]   = stripslashes($_POST["Description"]);
	$_SESSION["link"]   = stripslashes($_POST["Link"]);
	$_SESSION["author"] = stripslashes($_POST["Author"]);
	
	$_SESSION["message"] = "";
	
	Validate($_SESSION["title"], $_SESSION["desc"], $_SESSION["link"], $_SESSION["author"]);
	
	if( !$_SESSION["message"] == "" )
	{
		$_SESSION["failedValidate"] = true;
		header("Location: manageRss.php?manage=edit&guid=".$guid);
		exit;
	}
	
	doEdit($guid, $XMLfile, $doc, $xpath);
	
	header("Location: manageRss.php");
	exit;
} //end doEdit functionality
// end section for manage=doedit



//section for manage=doDelete
if( (!empty($_GET["manage"])) && ($_GET["manage"] == "doDelete" ) )
{
	$guid = $_POST["guid"];
	
	doDelete($guid, $XMLfile, $doc, $root, $xpath);
	
	header("Location: manageRss.php");
	exit;
}
//end section for manage=doDelete



/**********************************************************************************************************************************
***********************************************************************************************************************************
begin HTML -- all HTML is below this line 
***********************************************************************************************************************************
**********************************************************************************************************************************/

echo("<?xml version=\"1.0\" encoding=\"UTF-8\"?>");                  //write out the XML page declaration
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Lab 11 - Manage RSS</title>
	<meta http-equiv="Content-Type" content="text/html; UTF-8" />
	<link rel="stylesheet" href="includes/rssStyles.css" type="text/css" />
</head>

<body>
<h1>Lab 11 - Manage RSS</h1>

<div id="viewRSS"></div>

<div id="theForm">
	<form id="form0" action="" method="post">
		<fieldset id="formFieldset">
	        <legend id="formLegend"></legend>
            <ul>
            	<li><label for="Title" title="Title">Title:</label><div id="titleDiv"><input type="text" name="Title" id="Title" size="80" value="" /></div></li>
            	<li><label for="Description" title="Description">Description:</label><div id="descDiv"><textarea name="Description" id="Description" cols="53" rows="5" ></textarea></div></li>
            	<li><label for="Link" title="Link">Link:</label><div id="linkDiv"><input type="text" name="Link" id="Link" size="80" value="" /></div></li>
            	<li><label for="Author" title="Author">Author:</label><div id="authorDiv"><input type="text" name="Author" id="Author" size="80" value="" /></div></li>
                <li><div id="pubDateDiv"><label for="notUsed" title="PubDate">Pub Date:</label><div id="pubDate"><input type="text" name="notUsed" id="notUsed" value="" /></div></div></li>
            </ul>
		</fieldset>
        <fieldset id="submit">
            <input type="submit" name="submit" id="SubmitBtn" value="Continue" /><input type="submit" name="submit" id="CancelBtn" value="Cancel"  />
            <input type="hidden" name="guid" id="guid" value="" />
        </fieldset>
	</form>
</div>

<div id="errorMsg"></div>





<?php
/**********************************************************************************************************************************
***********************************************************************************************************************************
begin PHP management -- HTML page is above this line -- only JavaScript and closing body / html tags below
***********************************************************************************************************************************
**********************************************************************************************************************************/





/**********************************************************************************************************************************
Determine what to display... view, add, edit...

if page is loaded without a querystring
**********************************************************************************************************************************/
?>
<script type="text/javascript"><!--                                                   //open the script tag... closed down below
	document.getElementById("titleDiv").style.border  = "0px";
	document.getElementById("descDiv").style.border   = "0px";
	document.getElementById("linkDiv").style.border   = "0px";
	document.getElementById("authorDiv").style.border = "0px";
	document.getElementById("pubDate").style.border   = "0px";
	
<?php
	if( empty($_GET["manage"]) )
	{
		?>
		document.getElementById("viewRSS").style.display = "block";
		document.getElementById("theForm").style.display = "none";
		<?php
		
		$table = viewRSS($doc);
		
		?>
		document.getElementById("viewRSS").innerHTML = "<?php echo $table; ?>";
		<?php
	}
	else if( $_GET["manage"] == "add" )
	{
		?>
		document.getElementById("form0").action = "manageRss.php?manage=doAdd";
		document.getElementById("viewRSS").style.display = "none";
		document.getElementById("theForm").style.display = "block";
		document.getElementById("pubDateDiv").style.display = "none";
		document.getElementById("formLegend").innerHTML = "Add a New Feed Item";
		document.getElementById("Title").focus();
		document.getElementById("Author").value = "<?php echo $author; ?>";
		<?php
		
		showAdd();
	}
	else if( (!empty($_GET["guid"])) && ($_GET["manage"] == "edit") )
	{
		$guid = $_GET["guid"];
		
		?>
		document.getElementById("form0").action = "manageRss.php?manage=doAdd";
		document.getElementById("guid").value = "<?php echo $guid; ?>";
		document.getElementById("viewRSS").style.display = "none";
		document.getElementById("pubDateDiv").style.display = "none";
		document.getElementById("theForm").style.display = "block";
		document.getElementById("formLegend").innerHTML = "Edit RSS Feed Item";
		document.getElementById("Title").focus();
		<?php
		
		showEdit($guid, $xpath);
	}
	else if( (!empty($_GET["guid"])) && ($_GET["manage"] == "delete") )
	{
		$guid = $_GET["guid"];
		
		?>
		document.getElementById("form0").action = "manageRss.php?manage=doDelete";
		document.getElementById("guid".value    = "<?php echo $guid; ?>";
		document.getElementById("viewRSS").style.display    = "none";
		document.getElementById("Title").style.display      = "none";
		document.getElementById("Description").style.diplay = "none";
		document.getElementById("Link").style.display       = "none";
		document.getElementById("Author").style.diplay      = "none";
		document.getElementById("notUsed").style.display    = "none";
		document.getElementById("pubDateDiv").style.diplay  = "block";
		document.getElementById("titleDiv").style.border    = "1px solid #ccc";
		document.getElementById("descDiv").style.border     = "1px solid #ccc";
		document.getElementById("linkDiv").style.border     = "1px solid #ccc";
		document.getElementById("authorDiv").style.border   = "1px solid #ccc";
		document.getElementById("pubDate").style.border     = "1px solid #ccc";
		document.getElementById("CancelBtn").focus();
		document.getElementById("formLegend").innerHTML = "You are about to permanently delete the following RSS itme:";
		document.getElementById("errorMsg").innerHTML = "Are you sure you want to delete this item?";
		<?php
		
		showDelete($guid, $xpath);
	}
	else
	{
		?>
		document.getElementById("viewRSS").style.display = "block";
		document.getElementById("theForm").style.display = "none";
		<?php
	}

	
	if( !empty($_SESSION["message"]) )  											  //set the error message -- this is used on every page
	{
		?>
		document.getElementById("errorMsg").innerHTML = "<?php echo($_SESSION["message"]); ?>";     //set the innerHTML of the errorMsg div
		<?php
	}
	
	$_SESSION["message"] = "";                                                        //clear error message -- it has served it's purpose
	
?>
--></script>

</body>
</html>
<?php





/**********************************************************************************************************************************
***********************************************************************************************************************************
Only functions below this line  --  all HTML is above this line
Code below has to be called from something above
***********************************************************************************************************************************
**********************************************************************************************************************************/





/************************************************************************
FUNCTION:    viewRSS($doc)
ARGUMENTS:   $doc - This is the XML document, after it has been loaded
			 into a local variable (above)
			 Returns $table - a variable containing an HTML table.
DESCRIPTION: This function is called when there is no querystring. It 
             loops through the <item> elements in the XML file and 
			 creates a table to display them all - each having an edit
			 and delete link next to it.
************************************************************************/
function viewRSS($doc)
{
	$_SESSION["guid"] = 0;
	
	$itemArray = $doc->getElementsByTagName("item");
	
	$table = "<p><a href=\\\"manageRss.php?manage=add\\\">add a new item</a></p><table>";
	
	foreach($itemArray as $item)
	{
		$iGuid = 0;
		$iGuid = $item->getElementsByTagName("guid");
		$iGuidValue = (int)$iGuid->item(0)->nodeValue;
		
		if((int)$iGuidValue > (int)$_SESSION["guid"])
			$_SESSION["guid"] = $iGuidValue;
		
		//double escape sequences blow...  \\  produces   \
		// and \"   produces    "
		//thus, \\\"   produces   \"
		//which is used in JS to escape the   "
		$table .= "<tr><td valign=\\\"top\\\"><a href=\\\"manageRss.php?manage=edit&guid=$iGuidValue\\\">edit</a></td><td valign=\\\"top\\\"><a href=\\\"manageRss.php?manage=delete&guid=$iGuidValue\\\">delete</a></td>";
		$table .= "<td><strong>".$item->getElementsByTagName("title")->item(0)->nodeValue.":</strong> ".$item->getElementsByTagName("description")->item(0)->nodeValue." [<a href=\\\"".$item->getElementsByTagName("link")->item(0)->nodeValue."\\\">link</a>]</td></tr>";
	}//end foreach
	
	$table .= "</table>";
	
	return $table;                                                                         //return the <table>...</table> 
}





/************************************************************************
FUNCTION:    Validate($title, $desc, $link, $author)
ARGUMENTS:   $title - the title element of the RSS item being validated
			 $desc  - The description element of the RSS item to be validated
			 $link  - The link element of the RSS item being validated
			 $author - The author element of the RSS item being validated. 
DESCRIPTION: This function is called when a new RSS item is being added or
             edited. The values passed in are session values that posted
			 from one of the forms. $title, $desc, and $author are just 
			 checked to make sure they aren't empty. $link has a regular
			 expression to make sure that is conforms to the syntax of a valid
			 link, however, it does not actually test to see if the link
			 is real. If any errors are found, the session message variable
			 is populated. Any function that uses Validate will check the
			 session message after calling validate to see if message is
			 empty. If its not empty it redirects to the appropriate page
			 where the message will be displayed.
************************************************************************/
function Validate($title, $desc, $link, $author)
{
	if($title == "")                                                                  //check for empty title
	{
		$_SESSION["message"] = "Please Enter a Title.";
	}
	else if($desc == "")                                                              //check for empty description
	{
		$_SESSION["message"] = "Please Enter a Description.";
	}
	else if(!preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $link))
	{                                                                                 //check to see if link matches the correct syntax for a link
		$_SESSION["message"] = "Please Enter a Valid Link.";
	}
	else if($author == "")                                                            //check for empty author
	{
		$_SESSION["message"] = "Please Enter an Author.";
	}
	else                                                                              //validated successfully
	{
		$_SESSION["failedValidate"] = false;
	}
} // end function Validate





/************************************************************************
FUNCTION:    cleanup()
DESCRIPTION: This function clears all the session variables that are used
             for adding or editing elements as well as for error checking.
			 Its called at the end of the doEdit and doAdd functions.
************************************************************************/
function cleanup()
{
	$_SESSION["message"] = "";
	$_SESSION["title"]   = "";
	$_SESSION["desc"]    = "";
	$_SESSION["link"]    = "";
	$_SESSION["author"]  = "";
	$_SESSION["failedValidate"] = false;
} // end function cleanup





/************************************************************************
FUNCTION:    showAdd()
ARGUMENTS:   none.
DESCRIPTION: This function autopopulates the form when a person has clicked
             add, but there is something wrong with the form (fails to 
		     validate). The purpose is to keep what was previously entered 
			 into the form so that the user does not have to retype it.
************************************************************************/
function showAdd()
{
	?>
    document.getElementById("form0").action = "manageRss.php?manage=doAdd";
    <?php
	
	if( !empty($_SESSION["title"]) )
	{
		?>
        document.getElementById("Title").value = "<?php echo $_SESSION["title"]; ?>";
        <?php
	}
	if( !empty($_SESSION["desc"]) )
	{
		?>
        document.getElementById("Description").value = "<?php echo $_SESSION["desc"]; ?>";
        <?php
	}
	if( !empty($_SESSION["link"]) )
	{
		?>
        document.getElementById("Link").value = "<?php echo $_SESSION["link"]; ?>";
        <?php
	}
	if( !empty($_SESSION["author"]) )
	{
		?>
        document.getElementById("Author").value = "<?php echo $_SESSION["author"]; ?>";
        <?php
	}

} //end function showAdd





/************************************************************************
FUNCTION:    doAdd($XMLfile)
ARGUMENTS:   $XMLfile - This is the path to the XML file where the RSS items
			 are being stored. Its set in the constants.php file thats included
			 at the top of the page. 
DESCRIPTION: This function is called when the user submits the add new item
			 form. The next GUID is found by incrementing a session variable containing the highest
			 GUID. Two DOM objects are created so that the contents of the XML file
			 can be copied and rebuilt into a new file. After copying the basic
			 tags at the top of the XML file, the new <item> element is built
			 and added. Then a loop goes thru all the other <item> elements from
			 the previous file so the new file is complete with all the other
			 elements and has the newest (highest guid) at the top.
************************************************************************/
function doAdd($XMLfile)
{
	// get the session guid variable and increment it for the new RSS Item
	$guid = $_SESSION["guid"];
	$guid++;

	// a current date variable
	$pubDate = date("F j, Y H:ia");

	// create new DOM object and load the XML file
	$doc = new DOMDocument();
	$doc->load($XMLfile);

	//create a second DOM object that will be used to copy nodes into
	//setting the preserve white space to false combined later with formatOutput = true
	// will create the document tabbed correctly
	$newDoc = new DOMDocument('1.0');
	$newDoc->preserveWhiteSpace = false;

	//create the RSS Node
	$rss = $newDoc->createElement("rss");
	//create attribute of version and assign it 2.0 as a value
	$rss->setAttribute("version", "2.0");
	//append the RSS node
	$newDoc->appendChild($rss);

	//make the new RSS node the root Node
	$root = $newDoc->documentElement;

	//create the channel element
	$channelElement = $newDoc->createElement("channel");
	//append the channel element to the new XML file
	$root->appendChild($channelElement);

	//get the new channel node of the new XML File
	$channelNode = $root->getElementsByTagName("channel")->item(0);

	//get the main title text from the previous XML File and import it into the new XML File
	// first argument of importNode is the node to import (i'm using getelements by tag name to get the node)
	//second argument is true if you want to get the childnodes, but in this case there are no childNodes
	$mainTitleNode = $newDoc->importNode($doc->getElementsByTagName("title")->item(0), true);
	//append the title to the new XML file
	$channelNode->appendChild($mainTitleNode);

	//get the main link text from the previous XML File. and import it into the new XML File
	$mainTitleNode = $newDoc->importNode($doc->getElementsByTagName("link")->item(0), true);
	//append the title to the new XML file
	$channelNode->appendChild($mainLinkNode);

	//get the main description text from the previous XML File. and import it into the new XML File
	$mainTitleNode = $newDoc->importNode($doc->getElementsByTagName("description")->item(0), true);
	//append the title to the new XML file
	$channelNode->appendChild($mainDescNode);

	//get the language text from the previous XML File. and import it into the new XML File
	$mainTitleNode = $newDoc->importNode($doc->getElementsByTagName("language")->item(0), true);
	//append the title to the new XML file
	$channelNode->appendChild($languageNode);

	//get the copyright text from the previous XML File. and import it into the new XML File
	$mainTitleNode = $newDoc->importNode($doc->getElementsByTagName("copyright")->item(0), true);
	//append the title to the new XML file
	$channelNode->appendChild($copyrightNode);

	//get the docs text from the previous XML File. and import it into the new XML File
	$mainTitleNode = $newDoc->importNode($doc->getElementsByTagName("docs")->item(0), true);
	//append the title to the new XML file
	$channelNode->appendChild($docsNode);

	//create a new lastBuildDate element for the new build date
	$lastBuildDateNode = $newDoc->createElement("lastBuildDate");
	//append the text node containing a date object set as [day of week, Month day of month, Year]
	$lastBuildDateNode->appendChild($newDoc->createTextNode(date("l, F j, Y H:ia")));
	//apppend the whole builddate node to the new XML file
	$channelNode->appendChild($lastBuildDateNode);

	//get the image node and childnodes from the previous XML File. and import it into the new XML File
	$imageNode = $newDoc->importNode($doc->getElementsByTagName("image")->item(0), true);
	//append the title to the new XML file
	$channelNode->appendChild($imageNode);

	/***********************************************************
	//     	START BUILDING THE NEW ITEM FROM POSTED DATA
	***********************************************************/
	$item = $newDoc->createElement("item");

	//create the title element
	$titleNode = $newDoc->createElement("title");
	$titleNode->appendChild($newDoc->createTextNode($_SESSION["title"]));
	$item->appendChild($titleNode);
	
	//create the description element
	$descNode = $newDoc->createElement("description");
	$descNode->appendChild($newDoc->createTextNode($_SESSION["desc"]));
	$item->appendChild($descNode);

	//create the link element
	$linkNode = $newDoc->createElement("link");
	$linkNode->appendChild($newDoc->createTextNode($_SESSION["link"]));
	$item->appendChild($linkNode);

	//create the GUID element
	$guidNode = $newDoc->createElement("guid");
	$guidNode->appendChild($newDoc->createTextNode($guid));
	$item->appendChild($guidNode);

	//create the author element
	$authorNode = $newDoc->createElement("author");
	$authorNode->appendChild($newDoc->createTextNode($_SESSION["author"]));
	$item->appendChild($authorNode);

	//create the pudDate element
	$pudDateNode = $newDoc->createElement("pubDate");
	$pudDateNode->appendChild($newDoc->createTextNode($pubDate));
	$item->appendChild($pudDateNode);

	/***********************************************************
	//     	        END BUILDING THE NEW ITEM 
	***********************************************************/
	
	//append the entire item node that was just built to the new xml document
	$channelNode->appendChild($item);

	//now the other items from the old XML file need to be added to the new XML File
	$itemsArray = $doc->getElementsByTagName("item");

	// loop thru each of the items, and add it to the new XML file
	foreach($itemsArray as $item)
	{
		$tempNode = $newDoc->importNode($item, true);
		$channelNode->appendChild($tempNode);
	}

	//format the output so that its tabbed correctly
	$newDoc->formatOutput = true;
	// save the document
	$newDoc->save($XMLfile);

	//cleanup
	cleanup();

} // end function doAdd()





/************************************************************************
FUNCTION:    showEdit($guid, $xpath)
ARGUMENTS:   $guid - the global unique identifier that was passed in as
             a querystring to the manage page. This number represents the item
			 to be edited, and is needed for the xpath query to find the 
			 right <item>.
			 $xpath - xpath was created at the top of the document. It is used
			 to pass and xpath statement into the DOM and return a node.
DESCRIPTION: This function is called when the querystring manage = "edit".
             This function builds a form thats just like the add form except
			 that it autopopulates the form with the data about the <item> that
			 corresponds to the $guid that was passed in. Initially an xpath query
			 is used to search for the item element that has a guid matching
			 $guid. This is how the correct <item> is found.
************************************************************************/
function showEdit($guid, $xpath)
{
	$query = "//rss/channel/item/guid[. = '".$guid."']";
	
	$guidNode = $xpath->query($query);
	
	$itemNode = $guidNode->item(0)->parentNode;
	
	if($_SESSION["failedValidate"] == true)
	{
		?>
        document.getElementById("Title").value       = "<?php echo $_SESSION["title"]; ?>";
        document.getElementById("Description").value = "<?php echo $_SESSION["desc"]; ?>";
        document.getElementById("Link").value        = "<?php echo $_SESSION["link"]; ?>";
        document.getElementById("Author").value      = "<?php echo $_SESSION["author"]; ?>";
        <?php
	}
	else
	{
		?>
        document.getElementById("Title").value       = "<?php echo $itemNode->getElementsByTagName("title")->item(0)->nodeValue; ?>";
        document.getElementById("Description").value = "<?php echo $itemNode->getElementsByTagName("description")->item(0)->nodeValue; ?>";
        document.getElementById("Link").value        = "<?php echo $itemNode->getElementsByTagName("link")->item(0)->nodeValue; ?>";
        document.getElementById("Author").value      = "<?php echo $itemNode->getElementsByTagName("author")->item(0)->nodeValue; ?>";
        <?php
	}

} // end function showEdit()





/************************************************************************
FUNCTION:    doEdit($guid, $XMLfile, $doc, $xpath)
ARGUMENTS:   $guid - the global unique identifier that was passed in as
             a querystring to the manage page. This number represents the item
			 to be edited, and is needed for the xpath query to find the 
			 right <item>.
			 $XMLfile - This is the path to the XML file where the RSS items
			 are being stored. Its set in the constants.php file thats included
			 at the top of the page.
			 $doc - This is the XML document, after it has been loaded
			 into a local variable (above)
			 $xpath - xpath was created at the top of the document. It is used
			 to pass and xpath statement into the DOM and return a node.
DESCRIPTION: This function is called when the user submits the edit item
			 form. It gets called whehter they hit the Submit or Cancel button.
			 The $submit variable is checked first to see if they hit cancel, and
			 if they did, they are taken back to the main page. If not, an
			 xpath query is performed to search for the <item> element that has
			 a guid matching $guid. Once this is found each element within <item>
			 is changed to the new data that was posted from the edit item form.
************************************************************************/
function doEdit($guid, $XMLfile, $doc, $xpath)
{
	$query = "//rss/channel/item/guid[. = '".$guid."']";
	
	$guidNode = $xpath->query($query);
	
	$itemNode = $guidNode->item(0)->parentNode;
	
	$titleNode = $itemNode->getElementsByTagName("title")->item(0);
	$titleNode->nodeValue = $_SESSION["title"];
	
	$descNode = $itemNode->getElementsByTagName("description")->item(0);
	$descNode->nodeValue = $_SESSION["desc"];
	
	$linkNode = $itemNode->getElementsByTagName("link")->item(0);
	$linkNode->nodeValue = $_SESSION["link"];
	
	$authorNode = $itemNode->getElementsByTagName("author")->item(0);
	$authorNode->nodeValue = $_SESSION["author"];
	
	$pubDateNode = $itemNode->getElementsByTagName("pubDate")->item(0);
	$pubDateNode->nodeValue = date("Y-m-d H:i:s");
	
	$doc->save($XMLfile);
	
	cleanup();
	
} // end function doEdit()





/************************************************************************
FUNCTION:    showDelete($guid, $xpath)
ARGUMENTS:   $guid - the global unique identifier that was passed in as
             a querystring to the manage page. This number represents the item
			 to be deleted, and is needed for the xpath query to find the 
			 right <item>.
			 $xpath - xpath was created at the top of the document. It is used
			 to pass and xpath statement into the DOM and return a node.
DESCRIPTION: This function is called when the querystring manage = "delete".
             This function uses an xpath query to find the <item> element that
			 has a guid matching $guid. It builds a small table to show you
			 what the information will be deleted, and asks you if you are sure
			 that you want to delete it.
************************************************************************/
function showDelete($guid, $xpath)
{
	$query = "//rss/channel/item/guid[. = '".$guid."']";
	
	$guidNode = $xpath->query($query);
	
	$itemNode = $guidNode->item(0)->parentNode;
	
	?>
    document.getElementById("titleDiv").innerHTML = "<?php echo $itemNode->getElementsByTagName("title")->item(0)->nodeValue; ?>";
    document.getElementById("descDiv").innerHTML = "<?php echo $itemNode->getElementsByTagName("description")->item(0)->nodeValue; ?>";
    document.getElementById("linkDiv").innerHTML = "<?php echo $itemNode->getElementsByTagName("link")->item(0)->nodeValue; ?>";
    document.getElementById("authorDiv").innerHTML = "<?php echo $itemNode->getElementsByTagName("author")->item(0)->nodeValue; ?>";
    document.getElementById("pubDate").innerHTML = "<?php echo $itemNode->getElementsByTagName("pubDate")->item(0)->nodeValue; ?>";
    <?php

} // end function showDelete()





/************************************************************************
FUNCTION:    doDelete($guid, $XMLfile, $doc, $root, $xpath)
ARGUMENTS:   $guid - the global unique identifier that was passed in as
             a querystring to the manage page. This number represents the item
			 to be deleted, and is needed for the xpath query to find the 
			 right <item>.
			 $XMLfile - This is the path to the XML file where the RSS items
			 are being stored. Its set in the constants.php file thats included
			 at the top of the page.
			 $doc - This is the XML document, after it has been loaded
			 into a local variable (above)
			 $root - this is the root element, root node, containing all
			 subelements for the file.
			 $xpath - xpath was created at the top of the document. It is used
			 to pass and xpath statement into the DOM and return a node.
DESCRIPTION: This function is called when the user submits the delete item
			 form. It gets called whether they hit the Submit or Cancel button.
			 The $submit variable is checked first to see if they hit cancel, and
			 if they did, they are taken back to the main page, and nothing is
			 deleted. If not, an xpath query is performed to search for the 
			 <item> element that has a guid matching $guid. Once this is found 
			 that entire <item> element is removed and the XML file is saved.
************************************************************************/
function doDelete($guid, $XMLfile, $doc, $root, $xpath)
{
	$channelNode = $root->getElementsByTagName("channel");
	
	$query = "//rss/channel/item/guid[. = '".$guid."']";
	
	$guidNode = $xpath->query($query);
	
	$itemNode = $guidNode->item(0)->parentNode;
	
	$channelNode->item(0)->removeChild($itemNode);
	
	$doc->save($XMLfile);
}


?>
