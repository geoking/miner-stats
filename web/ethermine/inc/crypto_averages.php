<?php
$watchlistArray = implode(',', $conf['watchlist']);
// gets crypto exchange rate for ETH using cryptocompare.com/api
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_URL, 'https://min-api.cryptocompare.com/data/pricemultifull?fsyms='.$watchlistArray.'&tsyms=GBP,USD');
$result = curl_exec($ch);
curl_close($ch);
$cryptodata = json_decode($result, true);
$cryptoWatchlistArray = array();
foreach($conf['watchlist'] as $crypto) {
    $figureArray = array();
    $figureArray['togbp'] = $cryptodata['RAW'][$crypto]['GBP']['PRICE'];
    $figureArray['gbpchange'] = $cryptodata['DISPLAY'][$crypto]['GBP']['CHANGEPCTHOUR'];
    $figureArray['tousd'] = $cryptodata['RAW'][$crypto]['USD']['PRICE'];
    $figureArray['usdchange'] = $cryptodata['DISPLAY'][$crypto]['USD']['CHANGEPCTHOUR'];
    $cryptoWatchlistArray[$crypto] = array($crypto, $figureArray);
}
$ethtogbp = $cryptoWatchlistArray['ETH'][1]['togbp'];
?>