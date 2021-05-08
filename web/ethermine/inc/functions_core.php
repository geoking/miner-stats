<?php

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


function core_get_transactions($fin) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, 'https://etherchain.org/api/account/'.$fin.'/tx/0');
	$result = curl_exec($ch);
	curl_close($ch);
	$data = (array) $result;

	$data = explode('[{', $result);
	$data = (string) $data[1];
	$data = explode('},{', $data);

	$graphtime = array();
	$grapheth = array();
	$merged = array();
	
	foreach ($data as &$val) {
		$obj = explode(',', $val);
		$otime = str_replace('time:', '', str_replace('"', '', $obj[8]));
		$osender = str_replace('sender:', '', str_replace('"', '', $obj[1]));
		$oeth = str_replace('amount:', '', str_replace('"', '', $obj[6]));
		$oeth = number_format(($oeth/1000000000000000000),5);

		if ( $osender != $fin ) {
			$merged[] = substr($otime, 0, strpos($otime, "T")).','.$oeth;
		}
	}
	sort($merged);
	
	foreach ($merged as &$val) {
		$obj = explode(',', $val);
		$graphtime[] = $obj[0];
		$grapheth[] = $obj[1];
	}
}


// handles base FIAT logic
if     ( strtoupper($conf['fiat']) == 'USD' ) { $fiat = array( 'code' => 'USD', 'sym' => '$' ); }
elseif ( strtoupper($conf['fiat']) == 'GBP' ) { $fiat = array( 'code' => 'GBP', 'sym' => '&pound;' ); }
elseif ( strtoupper($conf['fiat']) == 'EUR' ) { $fiat = array( 'code' => 'EUR', 'sym' => '&euro;' ); }


// get stats from ethermine
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 4);
curl_setopt($ch, CURLOPT_URL, 'https://api.ethermine.org/miner/'.$conf['wallet'].'/currentStats');
$result = curl_exec($ch);
curl_close($ch);
$obj = json_decode($result, true);


// creates a local cache file, in the event that the ethermine api limit is reached
if ( is_null($obj) ) {
	$cache = '1';
	$result = file_get_contents($conf['em_cache_file']);
	$obj = json_decode($result, true);
} else {
	$cache = fopen($conf['em_cache_file'], 'w');
	fwrite($cache, $result);
	$cache = '0';
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

// creates a local cache file, in the event that the minerstat api limit is reached
if ( is_null($msobj) ) {
	$cache = '1';
	$result = file_get_contents($conf['ms_cache_file']);
	$msobj = json_decode($result, true);
} else {
	$cache = fopen($conf['ms_cache_file'], 'w');
	fwrite($cache, $result);
	$cache = '0';
}

//reset to move array up one
$msobj = reset($msobj);

// gets crypto exchange rate for ETH using coinbase.com/api
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://api.coinbase.com/v2/prices/eth-'.strtolower($conf['fiat']).'/spot');
$result = curl_exec($ch);
curl_close($ch);
$efi = json_decode($result, true);
$ethtofiat = $efi['data']['amount'];

// gets crypto exchange rate for ETH using cryptonator.com/api
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_URL, 'https://api.cryptonator.com/api/ticker/eth-'.strtolower($conf['fiat']));
$result = curl_exec($ch);
curl_close($ch);
$efi = json_decode($result, true);
$ethchange = $efi['ticker']['change'];

//if cryptonator api is working
if ($ethtofiat != 0) {

	// gets crypto exchange rate for BTC using cryptonator.com/api
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_URL, 'https://api.cryptonator.com/api/ticker/btc-'.strtolower($conf['fiat']));
	$result = curl_exec($ch);
	curl_close($ch);
	$bfi = json_decode($result, true);
	$btctofiat = $bfi['ticker']['price'];
	$btcchange = $bfi['ticker']['change'];

	// gets crypto exchange rate for BAT using cryptonator.com/api
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_URL, 'https://api.cryptonator.com/api/ticker/bat-'.strtolower($conf['fiat']));
	$result = curl_exec($ch);
	curl_close($ch);
	$batfi = json_decode($result, true);
	$battofiat = $batfi['ticker']['price'];
	$batchange = $batfi['ticker']['change'];

	// gets crypto exchange rate for DOGE using cryptonator.com/api
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_URL, 'https://api.cryptonator.com/api/ticker/doge-'.strtolower($conf['fiat']));
	$result = curl_exec($ch);
	curl_close($ch);
	$dogefi = json_decode($result, true);
	$dogetofiat = $dogefi['ticker']['price'];
	$dogechange = $dogefi['ticker']['change'];

	// gets crypto exchange rate for ZRX using cryptonator.com/api
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_URL, 'https://api.cryptonator.com/api/ticker/zrx-'.strtolower($conf['fiat']));
	$result = curl_exec($ch);
	curl_close($ch);
	$zrxfi = json_decode($result, true);
	$zrxtofiat = $zrxfi['ticker']['price'];
	$zrxchange = $zrxfi['ticker']['change'];
}

$result = file_get_contents('tmp/currentStats.tmp');
$currentStats = json_decode($result, true);
$result = file_get_contents('tmp/currentStatsOld.tmp');
$currentStatsOld = json_decode($result, true);
$result = file_get_contents('tmp/currentStatsOldOld.tmp');
$currentStatsOldOld = json_decode($result, true);
$stat['unpaid'] = number_format((($obj['data']['unpaid']/10)/100000000000000000),5);
$stat['currentStatsUnpaid'] = number_format((($currentStats['data']['unpaid']/10)/100000000000000000),5);
$stat['currentStatsOldUnpaid'] = number_format((($currentStatsOld['data']['unpaid']/10)/100000000000000000),5);
$stat['currentStatsOldOldUnpaid'] = number_format((($currentStatsOldOld['data']['unpaid']/10)/100000000000000000),5);

if (($stat['unpaid'] - $stat['currentStatsUnpaid']) > 0) {
	$stat['todayUnpaid'] = $stat['unpaid'] - $stat['currentStatsUnpaid'];
}
else {
	$stat['todayUnpaid'] = $stat['unpaid'];
}

if (($stat['currentStatsUnpaid'] - $stat['currentStatsOldUnpaid']) > 0) {
	$stat['yesterdayUnpaid'] = $stat['currentStatsUnpaid'] - $stat['currentStatsOldUnpaid'];
}
else {
	$stat['yesterdayUnpaid'] = $stat['currentStatsUnpaid'];
}

if (($stat['currentStatsOldUnpaid'] - $stat['currentStatsOldOldUnpaid']) > 0) {
	$stat['twoDaysAgoUnpaid'] = $stat['currentStatsOldUnpaid'] - $stat['currentStatsOldOldUnpaid'];
}
else {
	$stat['twoDaysAgoUnpaid'] = $stat['currentStatsOldUnpaid'];
}

//minerstat stats
$stat['uptime'] = $msobj['info']['uptime'];
$stat['mseday'] = $msobj['revenue']['coin'];
$stat['temp0'] = $msobj['hardware'][0]['temp'];
$stat['fanspeed0'] = $msobj['hardware'][0]['fan'];
$stat['temp1'] = $msobj['hardware'][1]['temp'];
$stat['fanspeed1'] = $msobj['hardware'][1]['fan'];
$stat['hashrate'] = $msobj['mining']['hashrate']['hashrate'];
$stat['hashrate_unit'] = $msobj['mining']['hashrate']['hashrate_unit'];
$stat['power'] = $msobj['hardware'][0]['power'] + $msobj['hardware'][1]['power'];
$stat['accepted'] = $msobj['mining']['shares']['accepted_share'];
$stat['rejected'] = $msobj['mining']['shares']['rejected_share'];

$stat['usdrate'] = $msobj['revenue']['cprice'] / $ethtofiat;

//ether stats
$tomorrow = strtotime('tomorrow');
$now = strtotime('now');
$stat['todayEstimated'] = ($stat['todayUnpaid'] / (86400 - ($tomorrow - $now))) * 86400;
$stat['eday'] = $stat['todayEstimated'];
$stat['emin'] = $stat['ehour']/60;
$stat['ehour'] = $stat['eday']/24;
$stat['eweek'] = $stat['eday']*7;
$stat['emonth'] = ( $stat['eweek']*52 )/12;
$stat['eyear'] = $stat['eweek']*52;
$stat['eneeded'] = ($conf['payout_threshold'])-($obj['data']['unpaid']/1000000000000000000);
$stat['hoursuntil'] = $stat['eneeded'] / $stat['ehour'];
$stat['paytime'] = date("D d M, H:i:s", time() + ($stat['hoursuntil'] * 3600) );
$stat['now'] = date("H:i:s", time());

?>