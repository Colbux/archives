<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$servers = [
    ['name' => 'PocketInfinite', 'ip' => '129.204.146.146', 'port' => 19133],
    ['name' => 'InPie', 'ip' => 'inpie.ru', 'port' => 19132],
    ['name' => 'WorldPE', 'ip' => 'worldpe.ddns.net', 'port' => 25616],
    ['name' => 'RPGCraft', 'ip' => 'rpgcraft.ddns.net', 'port' => 25617],
    ['name' => 'EpicMinigames', 'ip' => 'epicminigames.ddns.net', 'port' => 25619],
    ['name' => '口袋记忆无限', 'ip' => '129.204.146.146', 'port' => 19132],
    ['name' => 'Simple Survival', 'ip' => '68.191.66.178', 'port' => 19133],
    ['name' => 'OldAlpha', 'ip' => 'justalpha.ddns.net', 'port' => 2057],
    ['name' => 'PocketLands', 'ip' => 'pocketlands.ddns.net', 'port' => 19132],
    ['name' => 'MCPI Server', 'ip' => 'mcpi.izor.in', 'port' => 19132],
    ['name' => 'PocketAnarchy', 'ip' => '188.245.62.84', 'port' => 25569],
    ['name' => 'CyxarikPE', 'ip' => 'cyxarik.farted.net', 'port' => 19132]
];

$response = [];

function getServerStatus($ip, $port) {
    $timeout = 1;
    $fp = @fsockopen("udp://$ip", $port, $errno, $errstr, $timeout);
    if (!$fp) {
        return ['online' => false, 'players' => 0];
    }

    stream_set_timeout($fp, $timeout);
    $request = "\x01\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00";
    fwrite($fp, $request);
    $response = fread($fp, 2048);
    fclose($fp);

    if (strlen($response) > 0) {
        $data = unpack('c', $response[5]);
        $playerCount = $data[1];
        return ['online' => true, 'players' => $playerCount];
    }

    return ['online' => false, 'players' => 0];
}

foreach ($servers as $server) {
    $ip = $server['ip'];
    $port = $server['port'];
    $status = getServerStatus($ip, $port);

    $response[] = [
        'name' => $server['name'],
        'ip' => $ip,
        'port' => $port,
        'online' => $status['online'],
        'players' => $status['players']
    ];
}

echo json_encode($response);
?>
