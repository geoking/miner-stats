<?php

// Core functions file

function core_output_footerscripts() {
	echo '
		<script src="//code.jquery.com/jquery-2.2.3.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	';
}

function core_getkey() {
	include('config.php');
	return $confkey;
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

function core_calc_remaining($fin, $withMinutes) {
	$days = (gmdate('j', floor($fin * 3600)))-1;
	$hours = gmdate('G', floor($fin * 3600));
	if ($withMinutes) { $minutes = gmdate('i', floor($fin * 3600)); }
	// $seconds = gmdate('s', floor($fin * 3600));
	
	$output = '';
	if ( $days != '0' ) { if ( $days != '1' ) { $p = ' days'; } else { $p = ' day'; } $output = $output.$days.$p; }
	if ( $hours != '0' ) { if ( $hours != '1' ) { $p = ' hrs'; } else { $p = ' hr'; } $output = $output.' '.$hours.$p; }
	if ($withMinutes) { if ( $minutes != '0' ) { if ( $minutes != '1' ) { $p = ' mins'; } else { $p = ' min'; } $output = $output.' '.$minutes.$p; } }
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
	$result = file_get_contents($conf['cache_file']);
	$obj = json_decode($result, true);
} else {
	$cache = fopen($conf['cache_file'], 'w');
	fwrite($cache, $result);
	$cache = '0';
}


// gets crypto exchange rate for ETH using cryptonator.com/api
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'https://api.cryptonator.com/api/ticker/eth-'.strtolower($conf['fiat']));
$result = curl_exec($ch);
curl_close($ch);
$efi = json_decode($result, true);
$ethtofiat = $efi['ticker']['price'];
$ethchange = $efi['ticker']['change'];


// gets crypto exchange rate for BTC using cryptonator.com/api
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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
curl_setopt($ch, CURLOPT_URL, 'https://api.cryptonator.com/api/ticker/zrx-'.strtolower($conf['fiat']));
$result = curl_exec($ch);
curl_close($ch);
$zrxfi = json_decode($result, true);
$zrxtofiat = $zrxfi['ticker']['price'];
$zrxchange = $zrxfi['ticker']['change'];

$result = file_get_contents('currentStats.tmp');
$currentStats = json_decode($result, true);
$result = file_get_contents('currentStatsOld.tmp');
$currentStatsOld = json_decode($result, true);
$result = file_get_contents('currentStatsOldOld.tmp');
$currentStatsOldOld = json_decode($result, true);

$stat['hashrate'] = number_format( round( $obj['data']['currentHashrate']/1000000, 2),2 );
$stat['avghashrate'] = number_format( round( $obj['data']['averageHashrate']/1000000, 2),2 );
$stat['reportedhashrate'] = number_format( round( $obj['data']['reportedHashrate']/1000000, 2),2 );
$stat['payout'] = 0.05;
$stat['emin'] = $obj['data']['coinsPerMin'];
$stat['ehour'] = $stat['emin']*60;
$stat['eday'] = $stat['ehour']*24;
$stat['eweek'] = $stat['eday']*7;
$stat['emonth'] = ( $stat['eweek']*52 )/12;
$stat['eyear'] = $stat['eweek']*52;

if ( $stat['ehour'] != '0' ) { 

	$stat['bmin'] = $obj['data']['btcPerMin'];
	$stat['bhour'] = $stat['bmin']*60;
	$stat['bday'] = $stat['bhour']*24;
	$stat['bweek'] = $stat['bday']*7;
	$stat['bmonth'] = ( $stat['bweek']*52 )/12;
	$stat['byear'] = $stat['bweek']*52;

	$stat['umin'] = ($obj['data']['usdPerMin']);
	$stat['uhour'] = $stat['umin']*60;
	$stat['uday'] = $stat['uhour']*24;
	$stat['uweek'] = $stat['uday']*7;
	$stat['umonth'] = ( $stat['uweek']*52 )/12;
	$stat['uyear'] = $stat['uweek']*52;

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

	$tomorrow = strtotime('tomorrow');
	$now = strtotime('now');

	$stat['todayEstimated'] = ($stat['todayUnpaid'] / (86400 - ($tomorrow - $now))) * 86400;
	$stat['eneeded'] = ($stat['payout'])-($obj['data']['unpaid']/1000000000000000000) ;
	$stat['hoursuntil'] = $stat['eneeded'] / $stat['ehour'];

	$stat['paytime'] = date("D d M, H:i:s", time() + ($stat['hoursuntil'] * 3600) );

	if ($conf['show_power'] == 1) {
		// calculates the power costs of mining
		$stat['power-consumed'] = ($conf['watts']/1000)*8766; //8766 hours in 1 year
		$stat['power-annual'] = $stat['power-consumed']*$conf['kwh_rate'];
		$stat['power-month'] = $stat['power-annual']/12;
		$stat['power-week'] = $stat['power-annual']/52;
		$stat['power-day'] = $stat['power-annual']/365;
		$stat['power-hour'] = $stat['power-day']/24;
		$stat['power-min'] = $stat['power-hour']/60;

		// Profit values - What we mine vs what it costs us for electricity
		//ETH
		// $stat['epmin'] = ($stat['emin'] * $ethtofiat) - $kwh['cost_min'];
		$stat['ehourp'] = ($stat['ehour']*$ethtofiat) - $stat['power-hour'];
		// $stat['epday'] = ($stat['eday'] * $ethtofiat) - $kwh['cost_day'];
		// $stat['epweek'] = ($stat['eweek'] * $ethtofiat) - $kwh['cost_week'];
		// $stat['epmonth'] = ($stat['emonth'] * $ethtofiat) - $kwh['cost_month'];
		// //BTC
		// $stat['bpmin'] = ($stat['bmin'] * $btctofiat) - $kwh['cost_min'];
		// $stat['bphour'] = ($stat['bhour'] * $btctofiat) - $kwh['cost_hour'];
		// $stat['bpday'] = ($stat['bday'] * $btctofiat) - $kwh['cost_day'];
		// $stat['bpweek'] = ($stat['bweek'] * $btctofiat) - $kwh['cost_week'];
		// $stat['bpmonth'] = ($stat['bmonth'] * $btctofiat) - $kwh['cost_month'];

	}

} else { $stat['waiting'] = 1; }

?>