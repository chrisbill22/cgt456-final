<?php
session_start();

/*
 * -------------------------------------
 * CREATE THE URL TO AUTH WITH DROPBOX
 * -------------------------------------
 * 
 */

require_once "sdk/lib/Dropbox/autoload.php";
use \Dropbox as dbx;

$appInfoFile = "requests/authorization.json";

$dbxClient = getClient();

if ($dbxClient === false) {
	$authorizeUrl = getWebAuth()->start();
    header("Location: $authorizeUrl");
	exit;
}

function getAppConfig()
{
    global $appInfoFile;

    try {
        $appInfo = dbx\AppInfo::loadFromJsonFile($appInfoFile);
    }
    catch (dbx\AppInfoLoadException $ex) {
        throw new Exception("Unable to load \"$appInfoFile\": " . $ex->getMessage());
    }

    $clientIdentifier = "examples-web-file-browser";
    $userLocale = null;

    return array($appInfo, $clientIdentifier, $userLocale);
}

function getClient()
{
    if(!isset($_SESSION['access-token'])) {
        return false;
    }

    list($appInfo, $clientIdentifier, $userLocale) = getAppConfig();
    $accessToken = $_SESSION['access-token'];
    return new dbx\Client($accessToken, $clientIdentifier, $userLocale, $appInfo->getHost());
}
function getWebAuth()
{
    list($appInfo, $clientIdentifier, $userLocale) = getAppConfig();
    $redirectUri = getUrl("dropbox-auth-finish");
    $csrfTokenStore = new dbx\ArrayEntryStore($_SESSION, 'dropbox-auth-csrf-token');
    return new dbx\WebAuth($appInfo, $clientIdentifier, $redirectUri, $csrfTokenStore, $userLocale);
}
function getUrl($relative_path)
{
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        $scheme = "https";
    } else {
        $scheme = "http";
    }
    $host = $_SERVER['HTTP_HOST'];
    $path = getPath($relative_path);
    return $scheme."://".$host.$path;
}
function getPath($relative_path)
{
    if (PHP_SAPI === 'cli-server') {
        return "/".$relative_path;
    } else {
        return $_SERVER["SCRIPT_NAME"]."/".$relative_path;
    }
}


/*
 * -------------------------------------
 * HANDLE THE REQUEST BACK FROM DROPBOX
 * -------------------------------------
 * We have to use the requestPath this once
 * because that's how DropBox likes to send
 * its data
 */

$requestPath = init();
if ($requestPath === "/dropbox-auth-finish") {
    try {
        list($accessToken, $userId, $urlState) = getWebAuth()->finish($_GET);
        // We didn't pass in $urlState to finish, and we're assuming the session can't be
        // tampered with, so this should be null.
        assert($urlState === null);
    }
    catch (dbx\WebAuthException_BadRequest $ex) {
        respondWithError(400, "Bad Request");
        // Write full details to server error log.
        // IMPORTANT: Never show the $ex->getMessage() string to the user -- it could contain
        // sensitive information.
        error_log("/dropbox-auth-finish: bad request: " . $ex->getMessage());
        exit;
    }
    catch (dbx\WebAuthException_BadState $ex) {
        // Auth session expired.  Restart the auth process.
        header("Location: ".getPath("dropbox-auth-start"));
        exit;
    }
    catch (dbx\WebAuthException_Csrf $ex) {
        respondWithError(403, "Unauthorized", "CSRF mismatch");
        // Write full details to server error log.
        // IMPORTANT: Never show the $ex->getMessage() string to the user -- it contains
        // sensitive information that could be used to bypass the CSRF check.
        error_log("/dropbox-auth-finish: CSRF mismatch: " . $ex->getMessage());
        exit;
    }
    catch (dbx\WebAuthException_NotApproved $ex) {
        echo renderHtmlPage("Not Authorized?", "Why not?");
        exit;
    }
    catch (dbx\WebAuthException_Provider $ex) {
        error_log("/dropbox-auth-finish: unknown error: " . $ex->getMessage());
        respondWithError(500, "Internal Server Error");
        exit;
    }
    catch (dbx\Exception $ex) {
        error_log("/dropbox-auth-finish: error communicating with Dropbox API: " . $ex->getMessage());
        respondWithError(500, "Internal Server Error");
        exit;
    }

    // NOTE: A real web app would store the access token in a database.
    $_SESSION['access-token'] = $accessToken;

    echo renderHtmlPage("Authorized!",
        "Auth complete, <a href='".htmlspecialchars(getPath(""))."'>click here</a> to browse.");
}
function init()
{
        // For when we're running under CGI or mod_php.
        if (isset($_SERVER['PATH_INFO'])) {
            return $_SERVER['PATH_INFO'];
        } else {
            return "/";
        }
}
?>