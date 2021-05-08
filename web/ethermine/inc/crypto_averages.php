<?php
// gets crypto exchange rate for ETH using cryptocompare.com/api
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_URL, 'https://min-api.cryptocompare.com/data/pricemultifull?fsyms=BTC,ETH,DOGE,ZRX,BAT&tsyms=GBP,USD');
$result = curl_exec($ch);
curl_close($ch);
$cryptodata = json_decode($result, true);

//gbp
$ethtogbp = $cryptodata['RAW']['ETH']['GBP']['PRICE'];
$ethgbpchange = $cryptodata['DISPLAY']['ETH']['GBP']['CHANGEPCTHOUR'];
$btctogbp = $cryptodata['RAW']['BTC']['GBP']['PRICE'];
$btcgbpchange = $cryptodata['DISPLAY']['BTC']['GBP']['CHANGEPCTHOUR'];
$zrxtogbp = $cryptodata['RAW']['ZRX']['GBP']['PRICE'];
$zrxgbpchange = $cryptodata['DISPLAY']['ZRX']['GBP']['CHANGEPCTHOUR'];
$dogetogbp = $cryptodata['RAW']['DOGE']['GBP']['PRICE'];
$dogegbpchange = $cryptodata['DISPLAY']['DOGE']['GBP']['CHANGEPCTHOUR'];
$battogbp = $cryptodata['RAW']['BAT']['GBP']['PRICE'];
$batgbpchange = $cryptodata['DISPLAY']['BAT']['GBP']['CHANGEPCTHOUR'];

//usd
$ethtousd = $cryptodata['RAW']['ETH']['USD']['PRICE'];
$ethusdchange = $cryptodata['DISPLAY']['ETH']['USD']['CHANGEPCTHOUR'];
$btctousd = $cryptodata['RAW']['BTC']['USD']['PRICE'];
$btcusdchange = $cryptodata['DISPLAY']['BTC']['USD']['CHANGEPCTHOUR'];
$zrxtousd = $cryptodata['RAW']['ZRX']['USD']['PRICE'];
$zrxusdchange = $cryptodata['DISPLAY']['ZRX']['USD']['CHANGEPCTHOUR'];
$dogetousd = $cryptodata['RAW']['DOGE']['USD']['PRICE'];
$dogeusdchange = $cryptodata['DISPLAY']['DOGE']['USD']['CHANGEPCTHOUR'];
$battousd = $cryptodata['RAW']['BAT']['USD']['PRICE'];
$batusdchange = $cryptodata['DISPLAY']['BAT']['USD']['CHANGEPCTHOUR'];
?>