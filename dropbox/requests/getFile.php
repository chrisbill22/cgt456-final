<?php
session_start();
$subDir = "../";
include("../requests/authorize.php");

$body;
/*
if ($dbxClient === false) {
	header("Location: ".getPath("dropbox-auth-start"));
	exit;
}
*/

$path = "/";
if (isset($_GET['path'])) $path = $_GET['path'];
if (isset($_POST['path'])) $path = $_POST['path'];

$entry = $dbxClient->getMetadataWithChildren($path);
echo json_encode($entry);
function renderFile($entry)
{
    $metadataStr = htmlspecialchars(print_r($entry, true));
    $downloadPath = getPath("download?path=".htmlspecialchars($entry['path']));
    $body = <<<HTML
        <pre>$metadataStr</pre>
        <a href="$downloadPath">Download this file</a>
		<button action="delete();">Delete</button>
HTML;
	
    return renderHtmlPage("File: ".$entry['path'], $body);
}

function renderFolder($entry)
{
    // TODO: Add a token to counter CSRF attacks.
    $upload_path = htmlspecialchars(getPath('upload'));
    $path = htmlspecialchars($entry['path']);
    $form = '';

    $listing = '';
    foreach($entry['contents'] as $child) {
        $cp = $child['path'];
        $cn = basename($cp);
        if ($child['is_dir']) $cn .= '/';

        $cp = htmlspecialchars($cp);
        $link = getPath("?path=".htmlspecialchars($cp));
        $listing .= "<div>
					 <a style='text-decoration: none' href='$link'>$cn</a>
		             <a href='requests/downloadFile.php?path=".htmlspecialchars($cp)."'><button>Download</button></a>
					 <a href='requests/deleteFile.php?path=".htmlspecialchars($cp)."'><button>Delete</button></a>
					 
					 <div id='rename'>
					 	<form action='requests/renameFile.php?path=".htmlspecialchars($cp)."' method='post' enctype='multipart/form-data'>
					 	<input type='text' name='newName' id='newName' />
						<input type='submit' value='Rename File' />
						</form>
					 </div>	
					 </div>";
    }

    return renderHtmlPage("Folder: $entry[path]", $form.$listing);
}

function renderHtmlPage($title, $body)
{
    return <<<HTML
    <html>
        <head>
            <title>$title</title>
        </head>
        <body>
            <h1>$title</h1>
            $body
        </body>
    </html>
HTML;
}
//print_r(json_encode($entry));

if(empty($_POST[""])) {
	
} else {
	
}






?>