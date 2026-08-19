<?php
require_once __DIR__ . '/../services/Security.php';
Security::bootstrap();

ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

date_default_timezone_set('Asia/Dhaka');

if (!isset($_SESSION["login"]) && isset($_COOKIE["login"])) {
    // Legacy cookie restoration retained for compatibility; authorization must still be enforced by the application.
    if ($_COOKIE["login"]) {
        $_SESSION["login"] = $_COOKIE["login"];
        $_SESSION['userid'] = $_COOKIE['userid'] ?? null;
        $_SESSION['username'] = $_COOKIE['username'] ?? null;
        $_SESSION['usertype'] = $_COOKIE['usertype'] ?? null;
        $_SESSION['userfullname'] = $_COOKIE['userfullname'] ?? null;
        $_SESSION['userimage'] = $_COOKIE['userimage'] ?? null;
    }
}

Security::requireCsrf();

$page = isset($_GET['page']) ? $_GET['page'] : null;
$login = isset($_SESSION['login']) ? $_SESSION['login'] : null;
$userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;
$userName = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$fullName = isset($_SESSION['userfullname']) ? $_SESSION['userfullname'] : null;
$userImage = isset($_SESSION['userimage']) ? $_SESSION['userimage'] : null;
$ty = isset($_SESSION['usertype']) ? $_SESSION['usertype'] : null;

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
    setcookie("login", '', time() - 3600, '/', '', !empty($_SERVER['HTTPS']), true);
    setcookie("userid", '', time() - 3600, '/', '', !empty($_SERVER['HTTPS']), true);
    setcookie("username", '', time() - 3600, '/', '', !empty($_SERVER['HTTPS']), true);
    setcookie("usertype", '', time() - 3600, '/', '', !empty($_SERVER['HTTPS']), true);
    setcookie("userfullname", '', time() - 3600, '/', '', !empty($_SERVER['HTTPS']), true);
    setcookie("userimage", '', time() - 3600, '/', '', !empty($_SERVER['HTTPS']), true);
    $obj->notificationStore("Logout Success", 'success');
    header('Location: ?page=logout');
    exit();
}
if (!$login) {
    if (!$login && $page === 'logout') {
        $obj->notificationStore("Logout Success", 'success');
        echo '<script type="text/javascript">setTimeout(function(){window.location.href = "?page=login";}, 3000);</script>';
        exit();
    }
} else {
    if ($page == 'login') header('Location: ?page=dashboard');
}