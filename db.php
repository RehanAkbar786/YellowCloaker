<?php
require_once __DIR__ . "/db/Exceptions/IOException.php";
require_once __DIR__ . "/db/Exceptions/JsonException.php";
require_once __DIR__ . "/db/Classes/IoHelper.php";
require_once __DIR__ . "/db/SleekDB.php";
require_once __DIR__ . "/db/Store.php";
require_once __DIR__ . "/db/QueryBuilder.php";
require_once __DIR__ . "/db/Query.php";
require_once __DIR__ . "/db/Cache.php";
require_once __DIR__ . "/cookies.php";

use SleekDB\Store;

function add_white_click($data, $reason) {
    $dataDir = __DIR__ . "/logs";
    $wclicksStore = new Store("whiteclicks", $dataDir);

    $calledIp = $data['ip'];
    $country = $data['country'];
    $dt = new DateTime();
    $time = $dt->getTimestamp();
    $os = $data['os'];
    $isp = str_replace(',', ' ', $data['isp']);
    $user_agent = str_replace(',', ' ', $data['ua']);

    parse_str(isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '', $queryarr);

    $click = [
        "time" => $time,
        "ip" => $calledIp,
        "country" => $country,
        "os" => $os,
        "isp" => $isp,
        "ua" => $user_agent,
        "reason" => $reason,
        "subs" => $queryarr
    ];
    $wclicksStore->insert($click);
}

function add_black_click($subid, $data, $preland, $land) {
    $dataDir = __DIR__ . "/logs";
    $bclicksStore = new Store("blackclicks", $dataDir);

    $calledIp = $data['ip'];
    $country = $data['country'];
    $dt = new DateTime();
    $time = $dt->getTimestamp();
    $os = $data['os'];
    $isp = str_replace(',', ' ', $data['isp']);
    $user_agent = str_replace(',', ' ', $data['ua']);
    $prelanding = empty($preland) ? 'unknown' : $preland;
    $landing = empty($land) ? 'unknown' : $land;

    parse_str(isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '', $queryarr);

    $click = [
        "subid" => $subid,
        "time" => $time,
        "ip" => $calledIp,
        "country" => $country,
        "os" => $os,
        "isp" => $isp,
        "ua" => $user_agent,
        "subs" => $queryarr,
        "preland" => $prelanding,
        "land" => $landing
    ];
    $bclicksStore->insert($click);
}

// Other functions remain unchanged (no major errors detected)
?>
