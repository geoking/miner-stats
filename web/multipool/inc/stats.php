<?php
// get stats from ethermine
if (in_array('ethermine', $conf['pools'])) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 4);
    curl_setopt($ch, CURLOPT_URL, 'https://api.ethermine.org/miner/'.$conf['wallet'].'/currentStats');
    $result = curl_exec($ch);
    curl_close($ch);
    $etherobj = json_decode($result, true);

    // creates a local cache file
    if ( is_null($etherobj) ) {
        $cache = '1';
        $result = file_get_contents("tmp/".$conf['em_cache_file']);
        $etherobj = json_decode($result, true);
    } else {
        $cache = fopen("tmp/".$conf['em_cache_file'], 'w');
        fwrite($cache, $result);
        $cache = '0';
    }
}

// get stats from spark
if (in_array('spark', $conf['pools'])) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 4);
    curl_setopt($ch, CURLOPT_URL, 'https://www.sparkpool.com/v1/bill/stats?miner='.$conf['wallet'].'&currency=ETH');
    $result = curl_exec($ch);
    curl_close($ch);
    $sparkobj = json_decode($result, true);

    // creates a local cache file
    if ( is_null($sparkobj) ) {
        $cache = '1';
        $result = file_get_contents("tmp/".$conf['spark_cache_file']);
        $sparkobj = json_decode($result, true);
    } else {
        $cache = fopen("tmp/".$conf['spark_cache_file'], 'w');
        fwrite($cache, $result);
        $cache = '0';
    }
}

// get stats from hiveon
if (in_array('hive', $conf['pools'])) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 4);
    curl_setopt($ch, CURLOPT_URL, 'https://hiveon.net/api/v1/stats/miner/'.$conf['wallet'].'/ETH/billing-acc');
    $result = curl_exec($ch);
    curl_close($ch);
    $hiveobj = json_decode($result, true);

    // creates a local cache file
    if ( is_null($hiveobj) ) {
        $cache = '1';
        $result = file_get_contents("tmp/".$conf['hive_cache_file']);
        $hiveobj = json_decode($result, true);
    } else {
        $cache = fopen("tmp/".$conf['hive_cache_file'], 'w');
        fwrite($cache, $result);
        $cache = '0';
    }
}

// get stats from minerstat
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 4);
curl_setopt($ch, CURLOPT_URL, 'https://api.minerstat.com/v2/stats/'.$conf['ms_access_key'].'/worker1');
$result = curl_exec($ch);
curl_close($ch);
$msobj = json_decode($result, true);
$msobj = reset($msobj);

// creates a local cache file
if ( is_null($msobj) ) {
	$cache = '1';
	$result = file_get_contents("tmp/".$conf['ms_cache_file']);
	$msobj = json_decode($result, true);
} else {
	$cache = fopen("tmp/".$conf['ms_cache_file'], 'w');
	fwrite($cache, $result);
	$cache = '0';
}

?>