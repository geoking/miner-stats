<?php
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
?>