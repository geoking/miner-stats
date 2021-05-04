<?php
require_once('tickers.php');
require_once('stats.php');
// Core functions file

function core_output_footerscripts() {
	include('config.php');
	echo '
		<script src="//code.jquery.com/jquery-2.2.3.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="'.$conf["js_folder_path"].'showHide.js"></script>
	';
}

function core_getkey() {
	include('config.php');
	return $confkey;
}

function core_getcryptourl($crypto, $fiat) {
	include('config.php');
	$url = 'https://uk.finance.yahoo.com/quote/'.$crypto.'-'.$fiat;
	return $url;
}

function core_getpoolurl($pool) {
	include('config.php');
	if (strpos(strtolower($pool), 'hive') !== false) {
		$url = 'https://hiveon.net/eth?miner='.$conf['wallet'];
	}
	else if (strpos(strtolower($pool), 'spark') !== false) {
		$url = 'https://www.sparkpool.com/en/miner/'.$conf['wallet'];
	}
	else if (strpos(strtolower($pool), 'ethermine') !== false) {
		$url = 'https://ethermine.org/miners/'.$conf['wallet'];
	}

	return $url;
}

function core_enc($fin) {
	$key = core_getkey();
    $enc = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $key ), $fin, MCRYPT_MODE_CBC, md5( md5( $key ) ) ) );
    return $enc;
}

function core_dec($fin) {
	$key = core_getkey();
    $dec = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $key ), base64_decode( $fin ), MCRYPT_MODE_CBC, md5( md5( $key ) ) ), "\0");
    return $dec;
}

function core_calc_remaining($fin) {
	$days = (gmdate('j', floor($fin * 3600)))-1;
	$hours = gmdate('G', floor($fin * 3600));
	if ($days == 0) { $minutes = gmdate('i', floor($fin * 3600)); }
	// $seconds = gmdate('s', floor($fin * 3600));
	
	$output = '';
	if ( $days != '0' ) { if ( $days != '1' ) { $p = ' days'; } else { $p = ' day'; } $output = $output.$days.$p; }
	if ( $hours != '0' ) { if ( $hours != '1' ) { $p = ' hrs'; } else { $p = ' hr'; } $output = $output.' '.$hours.$p; }
	if ($days == 0) { if ( $minutes != '0' ) { if ( $minutes != '1' ) { $p = ' mins'; } else { $p = ' min'; } $output = $output.' '.$minutes.$p; } }
	// if ( $seconds != '0' ) { $output = $output.', '.$seconds.' secs'; }

	return $output;
}

// handles base FIAT logic
if     ( strtoupper($conf['fiat']) == 'USD' ) { $fiat = array( 'code' => 'USD', 'sym' => '$' ); }
elseif ( strtoupper($conf['fiat']) == 'GBP' ) { $fiat = array( 'code' => 'GBP', 'sym' => '&pound;' ); }
elseif ( strtoupper($conf['fiat']) == 'EUR' ) { $fiat = array( 'code' => 'EUR', 'sym' => '&euro;' ); }

//minerstat stats
$stat['uptime'] = $msobj['info']['uptime'];
$stat['mseday'] = $msobj['revenue']['coin'];
$stat['temp0'] = $msobj['hardware'][0]['temp'];
$stat['fanspeed0'] = $msobj['hardware'][0]['fan'];
$stat['temp1'] = $msobj['hardware'][1]['temp'];
$stat['fanspeed1'] = $msobj['hardware'][1]['fan'];
$stat['hashrate'] = $msobj['mining']['hashrate']['hashrate'];
$stat['hashrate_unit'] = $msobj['mining']['hashrate']['hashrate_unit'];
$stat['pool'] = $msobj['mining']['crypto'];
$stat['power'] = $msobj['hardware'][0]['power'] + $msobj['hardware'][1]['power'];
$stat['accepted'] = $msobj['mining']['shares']['accepted_share'];
$stat['rejected'] = $msobj['mining']['shares']['rejected_share'];
$stat['usdrate'] = $msobj['revenue']['cprice'] / $ethtofiat;

if (in_array('ethermine', $conf['pools'])) {
	$result = file_get_contents('tmp/eCurrentStats.tmp');
	$eCurrentStats = json_decode($result, true);
	$result = file_get_contents('tmp/eCurrentStatsOld.tmp');
	$eCurrentStatsOld = json_decode($result, true);
	$result = file_get_contents('tmp/eCurrentStatsOldOld.tmp');
	$eCurrentStatsOldOld = json_decode($result, true);

	$stat['eunpaid'] = number_format((($etherobj['data']['unpaid']/10)/100000000000000000),5);
	$stat['eCurrentStatsUnpaid'] = number_format((($eCurrentStats['data']['unpaid']/10)/100000000000000000),5);
	$stat['eCurrentStatsOldUnpaid'] = number_format((($eCurrentStatsOld['data']['unpaid']/10)/100000000000000000),5);
	$stat['eCurrentStatsOldOldUnpaid'] = number_format((($eCurrentStatsOldOld['data']['unpaid']/10)/100000000000000000),5);
	$stat['totalUnpaid'] = $stat['totalUnpaid'] + $stat['eunpaid'];
	$stat['startOfDayUnpaid'] = $stat['startOfDayUnpaid'] + ($stat['eunpaid'] - $stat['eCurrentStatsUnpaid']);
	$stat['yesterdayUnpaid'] = $stat['yesterdayUnpaid'] + ($stat['eCurrentStatsUnpaid'] - $stat['eCurrentStatsOldUnpaid']);
	$stat['twoDaysAgoUnpaid'] = $stat['twoDaysAgoUnpaid'] + ($stat['eCurrentStatsOldUnpaid'] - $stat['eCurrentStatsOldOldUnpaid']);
}

if (in_array("hive", $conf['pools'])) {
	$result = file_get_contents('tmp/hCurrentStats.tmp');
	$hCurrentStats = json_decode($result, true);
	$result = file_get_contents('tmp/hCurrentStatsOld.tmp');
	$hCurrentStatsOld = json_decode($result, true);
	$result = file_get_contents('tmp/hCurrentStatsOldOld.tmp');
	$hCurrentStatsOldOld = json_decode($result, true);

	$stat['hunpaid'] = $hiveobj['totalUnpaid'];
	$stat['hCurrentStatsUnpaid'] = $hCurrentStats['totalUnpaid'];
	$stat['hCurrentStatsOldUnpaid'] = $hCurrentStatsOld['totalUnpaid'];
	$stat['hCurrentStatsOldOldUnpaid'] = $hCurrentStatsOldOld['totalUnpaid'];
	$stat['totalUnpaid'] = $stat['totalUnpaid'] + $stat['hunpaid'];
	$stat['startOfDayUnpaid'] = $stat['startOfDayUnpaid'] + ($stat['hunpaid'] - $stat['hCurrentStatsUnpaid']);
	$stat['yesterdayUnpaid'] = $stat['yesterdayUnpaid'] + ($stat['hCurrentStatsUnpaid'] - $stat['hCurrentStatsOldUnpaid']);
	$stat['twoDaysAgoUnpaid'] = $stat['twoDaysAgoUnpaid'] + ($stat['hCurrentStatsOldUnpaid'] - $stat['hCurrentStatsOldOldUnpaid']);
}

if (in_array("spark", $conf['pools'])) {
	$result = file_get_contents('tmp/sCurrentStats.tmp');
	$sCurrentStats = json_decode($result, true);
	$result = file_get_contents('tmp/sCurrentStatsOld.tmp');
	$sCurrentStatsOld = json_decode($result, true);
	$result = file_get_contents('tmp/sCurrentStatsOldOld.tmp');
	$sCurrentStatsOldOld = json_decode($result, true);

	$stat['sunpaid'] = $sparkobj['data']['balance'];
	$stat['sCurrentStatsUnpaid'] = $sCurrentStats['data']['balance'];
	$stat['sCurrentStatsOldUnpaid'] = $sCurrentStatsOld['data']['balance'];
	$stat['sCurrentStatsOldOldUnpaid'] = $sCurrentStatsOldOld['data']['balance'];
	$stat['totalUnpaid'] = $stat['totalUnpaid'] + $stat['sunpaid'];
	$stat['startOfDayUnpaid'] = $stat['startOfDayUnpaid'] + ($stat['sunpaid'] - $stat['sCurrentStatsUnpaid']);
	$stat['yesterdayUnpaid'] = $stat['yesterdayUnpaid'] + ($stat['sCurrentStatsUnpaid'] - $stat['sCurrentStatsOldUnpaid']);
	$stat['twoDaysAgoUnpaid'] = $stat['twoDaysAgoUnpaid'] + ($stat['sCurrentStatsOldUnpaid'] - $stat['sCurrentStatsOldOldUnpaid']);
}

$tomorrow = strtotime('tomorrow');
$now = strtotime('now');
$stat['todayEstimated'] = (($stat['startOfDayUnpaid']) / (86400 - ($tomorrow - $now))) * 86400;
$stat['now'] = date("H:i:s", time());
?>