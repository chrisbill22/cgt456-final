<?php
$requestPath = init();

if ($dbxClient === false) {
	header("Location: ".getPath("dropbox-auth-start"));
	exit;
}

$path = "/";
if (isset($_GET['path'])) $path = $_GET['path'];

$entry = $dbxClient->getMetadataWithChildren($path);
if ($entry['is_dir']) {
	echo renderFolder($entry);
}
else {
	echo renderFile($entry);
}

function renderFile($entry)
{
    $metadataStr = htmlspecialchars(print_r($entry, true));
    $downloadPath = getPath("download?path=".htmlspecialchars($entry['path']));
    $body = <<<HTML
        <pre>$metadataStr</pre>
        <a href="$downloadPath">Download this file</a>
HTML;

    return renderHtmlPage("File: ".$entry['path'], $body);
}

function renderFolder($entry)
{
    // TODO: Add a token to counter CSRF attacks.
    $upload_path = htmlspecialchars(getPath('upload'));
    $path = htmlspecialchars($entry['path']);
    $form = <<<HTML
        <form action='$upload_path' method='post' enctype='multipart/form-data'>
        <label for='file'>Upload file:</label> <input name='file' type='file'/>
        <input type='submit' value='Upload'/>
        <input name='folder' type='hidden' value='$path'/>
        </form>
HTML;

    $listing = '';
    foreach($entry['contents'] as $child) {
        $cp = $child['path'];
        $cn = basename($cp);
        if ($child['is_dir']) $cn .= '/';

        $cp = htmlspecialchars($cp);
        $link = getPath("?path=".htmlspecialchars($cp));
        $listing .= "<div><a style='text-decoration: none' href='$link'>$cn</a></div>";
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

?>