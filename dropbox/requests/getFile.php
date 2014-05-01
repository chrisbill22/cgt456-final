<?php
$body;
if ($dbxClient === false) {
	header("Location: ".getPath("dropbox-auth-start"));
	exit;
}

$path = "/";
if (isset($_GET['path'])) $path = $_GET['path'];

$entry = $dbxClient->getMetadataWithChildren($path);

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
    $form = '';

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