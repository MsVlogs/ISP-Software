<?php
if (!isset($_SESSION["session"])) {
    session_start();
    $_SESSION["session"] = true;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Asia/Dhaka');
if (!isset($_SESSION["login"]) && isset($_COOKIE["login"])) {
    // Restore session from cookie
    if ($_COOKIE["login"]) {
        $_SESSION["login"] = $_COOKIE["login"];
        $_SESSION['userid'] = $_COOKIE['userid'];
        $_SESSION['username'] = $_COOKIE['username'];
        $_SESSION['usertype'] = $_COOKIE['usertype'];
        $_SESSION['userfullname'] = $_COOKIE['userfullname'];
        $_SESSION['userimage'] = $_COOKIE['userimage'];
    }
}
$page = isset($_GET['page']) ? $_GET['page'] : null;
$login = isset($_SESSION['login']) ? $_SESSION['login'] : NULL;
$userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : NULL;
$userName = isset($_SESSION['username']) ? $_SESSION['username'] : NULL;
$fullName = isset($_SESSION['userfullname']) ? $_SESSION['userfullname'] : NULL;
$userImage = isset($_SESSION['userimage']) ? $_SESSION['userimage'] : NULL;
$ty = isset($_SESSION['usertype']) ? $_SESSION['usertype'] : NULL;

require(realpath(__DIR__ . '/../services/Model.php'));

try {
    $obj = new Model();
} catch (Throwable $e) {
    error_log("Application database initialization failed: " . $e->getMessage());
    http_response_code(503);
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Service unavailable</title></head><body><h1>Service temporarily unavailable</h1><p>We could not connect to the application database. Please try again later.</p></body></html>';
    exit();
}

if (($page == 'logout') && $login) {
    session_unset();
    session_destroy();
    // Clear cookies
    setcookie("login", false, time() - 3600);
    setcookie("userid", "", time() - 3600);
    setcookie("username", "", time() - 3600);
    setcookie("usertype", "", time() - 3600);
    setcookie("userfullname", "", time() - 3600);
    setcookie("userimage", "", time() - 3600);
    $obj->notificationStore("Logout Success", 'success');
    header('Location: ?page=logout');
    exit();
}
if (!$login) {
    if (!$login && $page === 'logout') {
        $obj->notificationStore("Logout Success", 'success');
        echo '<script type="text/javascript">
                    setTimeout(function(){
                        window.location.href = "?page=login";
                    }, 3000);
                </script>';
        exit();
    }
} else {

    if ($page == 'login') header('Location: ?page=dashboard');