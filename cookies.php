<?php
// Start output buffering to prevent "headers already sent" errors
ob_start();

function ywbsetcookie($name, $value, $path = '/') {
    $expires = time() + 60 * 60 * 24 * 5; // 5 days
    header("Set-Cookie: {$name}={$value}; Expires={$expires}; Path={$path}; SameSite=None; Secure", false);
}

function get_cookie($name) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start(['read_and_close' => true]);
    }
    $c = (isset($_COOKIE[$name]) ? $_COOKIE[$name] : (isset($_SESSION[$name]) ? $_SESSION[$name] : ''));
    return $c;
}

function get_subid() {
    $subid = get_cookie('subid');
    return $subid;
}

function set_subid() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        ini_set("session.cookie_secure", 1);
        session_start();
    }
    $cursubid = isset($_COOKIE['subid']) ? $_COOKIE['subid'] : uniqid();
    ywbsetcookie('subid', $cursubid, '/');
    $_SESSION['subid'] = $cursubid;
    session_write_close();
    return $cursubid;
}

function set_facebook_cookies() {
    global $fbpixel_subname;
    if (isset($_GET[$fbpixel_subname]) && $_GET[$fbpixel_subname] != '')
        ywbsetcookie($fbpixel_subname, $_GET[$fbpixel_subname], '/');
    if (isset($_GET['fbclid']) && $_GET['fbclid'] != '')
        ywbsetcookie('fbclid', $_GET['fbclid'], '/');
}

// Check if conversion cookies exist and whether to reset them
function has_conversion_cookies($name, $phone) {
    $date = new DateTime();
    $ts = $date->getTimestamp();
    $is_duplicate = false;
    $cname = isset($_COOKIE['name']) ? $_COOKIE['name'] : '';
    $cphone = isset($_COOKIE['phone']) ? $_COOKIE['phone'] : '';
    $ctime = isset($_COOKIE['ctime']) ? $_COOKIE['ctime'] : '';

    if (!empty($ctime) && !empty($name) && !empty($phone)) {
        if ($cname === $name && $cphone === $phone) {
            $secondsDiff = $ts - $ctime;
            if ($secondsDiff < 24 * 60 * 60) {
                $is_duplicate = true;
                ywbsetcookie('ctime', $ts);
            }
        }
    }
    return $is_duplicate;
}

// End output buffering
ob_end_flush();
?>
