<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 4);
curl_setopt($ch, CURLOPT_URL, 'https://api.ethermine.org/miner/MINER_URL/currentStats');
$result = curl_exec($ch);
curl_close($ch);
$obj = json_decode($result, true);

$currentStatsDest = 'domains/gking.uk/public_html/stats/currentStats.tmp';
$currentStatsOldDest = 'domains/gking.uk/public_html/stats/currentStatsOld.tmp';
$currentStatsOldOldDest = 'domains/gking.uk/public_html/stats/currentStatsOldOld.tmp';

copy($currentStatsOldDest, $currentStatsOldOldDest);
copy($currentStatsDest, $currentStatsOldDest);
$cache = fopen($currentStatsDest, 'w');
fwrite($cache, $result);
$cache = '0';
$currentStatsOld = '0';
$currentStats = '0';
$currentStatsOldOld = '0';
?>