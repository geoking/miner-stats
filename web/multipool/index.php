<?php require_once('inc/loader.php'); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
  	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
	<link href=<?= $conf['css_folder_path'].'custom.css'?> rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<title>(<?=$fiat['sym'].number_format(($stat['todayEstimated']*$ethtofiat),2)?>) GK &Xi; STATS</title>
	<meta http-equiv="Refresh" content="120">
  </head>

  <body>

	<div class="container">

		<div class="col-md-12">
			<h1>GK &Xi; STATS</h1>
			<h5>Last updated: <?=$stat['now']?> (<a href="/stats" class="fa fa-refresh"></a>)</h5>
		</div>

		<div class="col-md-3">
			<h2>CURRENT POOL</h2>
			<h3><a href=<?=core_getpoolurl($stat['pool'])?> target="_blank"><?=$stat['pool']?></a></h3>
		</div>
		<div class="col-md-3">
			<h2>CURRENT RATE</h2>
			<?php if ($stat['hashrate'] == 0) { ?>
				<h3>NOT MINING! :(</h3>
			<?php } else { ?>
				<h3>&Xi;<?=number_format($stat['mseday'],5)?> (<?=$fiat['sym'].number_format(($stat['mseday']*$ethtofiat),2)?>)</h3>
			<?php } ?>
		</div>
		<div class="col-md-3">
			<h2>EST TODAY</h2>
			<h3>&Xi;<?=number_format($stat['todayEstimated'],5)?> (<?=$fiat['sym'].number_format(($ethtofiat * $stat['todayEstimated']),2)?>)</h3>
		</div>
		<div class="col-md-3">
			<h2>TOTAL UNPAID</h2>
			<h3>&Xi;<?=number_format($stat['totalUnpaid'],5)?> (<?=$fiat['sym'].number_format(($ethtofiat * $stat['totalUnpaid']),2)?>)</h3>
		</div>

		<?php if ( $stat['waiting'] == '1' ) {
			echo '<div class="col-md-12"><p align="center"><em>There is insufficient data to produce any useful metrics.<br>Please check your wallet settings in config.php.<br>The pool you are querying may also be limiting API requests - please try later.</em></p></div>';
			die;
		} ?>

		<div class="col-md-4">
			<ul class="list-group">
				<li class="list-group-item"><h4><a href='https://uk.finance.yahoo.com/cryptocurrencies' target="_blank">Watchlist (1hr +/-%)</a><span class="pull-right"><a href="javascript:void(0)" role="button" onClick="fiatUsdSwitch()" id="fiatUsdButton">$</a></span></h4></li>
				<div id="fiatDiv">
					<?php if ( $btcchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BTC', $conf['fiat'])?> target="_blank">฿TC 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($btctofiat - $btcchange) / $btctofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($btctofiat,2) ?></span> </li> <?php }
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BTC', $conf['fiat'])?> target="_blank">฿TC 	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($btctofiat - $btcchange) / $btctofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($btctofiat,2) ?></span> </li> <?php } ?>
					<?php if ( $ethchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ETH', $conf['fiat'])?> target="_blank">&Xi;TH 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($ethtofiat - $ethchange) / $ethtofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($ethtofiat,2) ?></span> </li> <?php }
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ETH', $conf['fiat'])?> target="_blank">&Xi;TH 	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($ethtofiat - $ethchange) / $ethtofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($ethtofiat,2) ?></span> </li> <?php } ?>
					<?php if ( $batchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BAT', $conf['fiat'])?> target="_blank">BAT 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($battofiat - $batchange) / $battofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($battofiat,2) ?></span> </li> <?php } 
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BAT', $conf['fiat'])?> target="_blank">BAT	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($battofiat - $batchange) / $battofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($battofiat,2) ?></span> </li> <?php } ?>
					<?php if ( $dogechange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('DOGE', $conf['fiat'])?> target="_blank">DOGE 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($dogetofiat - $dogechange) / $dogetofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($dogetofiat,3) ?></span> </li> <?php } 
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('DOGE', $conf['fiat'])?> target="_blank">DOGE	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($dogetofiat - $dogechange) / $dogetofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($dogetofiat,3) ?></span> </li> <?php } ?>
					<?php if ( $zrxchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ZRX', $conf['fiat'])?> target="_blank">ZRX 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($zrxtofiat - $zrxchange) / $zrxtofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($zrxtofiat,2) ?></span> </li> <?php } 
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ZRX', $conf['fiat'])?> target="_blank">ZRX	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($zrxtofiat - $zrxchange) / $zrxtofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($zrxtofiat,2) ?></span> </li> <?php } ?>
				</div>
				<div id="usdDiv" style="display: none;">
					<?php if ( $btcchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BTC', 'USD')?> target="_blank">฿TC 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($btctofiat - $btcchange) / $btctofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($btctofiat * $stat['usdrate'],2) ?></span> </li> <?php }
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BTC', 'USD')?> target="_blank">฿TC 	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($btctofiat - $btcchange) / $btctofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($btctofiat * $stat['usdrate'],2) ?></span> </li> <?php } ?>
					<?php if ( $ethchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ETH', 'USD')?> target="_blank">&Xi;TH 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($ethtofiat - $ethchange) / $ethtofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($ethtofiat * $stat['usdrate'],2) ?></span> </li> <?php }
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ETH', 'USD')?> target="_blank">&Xi;TH 	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($ethtofiat - $ethchange) / $ethtofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($ethtofiat * $stat['usdrate'],2) ?></span> </li> <?php } ?>
					<?php if ( $batchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BAT', 'USD')?> target="_blank">BAT 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($battofiat - $batchange) / $battofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($battofiat * $stat['usdrate'],2) ?></span> </li> <?php } 
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BAT', 'USD')?> target="_blank">BAT	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($battofiat - $batchange) / $battofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($battofiat * $stat['usdrate'],2) ?></span> </li> <?php } ?>
					<?php if ( $dogechange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('DOGE', 'USD')?> target="_blank">DOGE 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($dogetofiat - $dogechange) / $dogetofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($dogetofiat * $stat['usdrate'],3) ?></span> </li> <?php } 
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('DOGE', 'USD')?> target="_blank">DOGE	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($dogetofiat - $dogechange) / $dogetofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($dogetofiat * $stat['usdrate'],3) ?></span> </li> <?php } ?>
					<?php if ( $zrxchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ZRX', 'USD')?> target="_blank">ZRX 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($zrxtofiat - $zrxchange) / $zrxtofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($zrxtofiat * $stat['usdrate'],2) ?></span> </li> <?php } 
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ZRX', 'USD')?> target="_blank">ZRX	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($zrxtofiat - $zrxchange) / $zrxtofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($zrxtofiat * $stat['usdrate'],2) ?></span> </li> <?php } ?>
				</div>
			</ul>
		</div>

		<div class="col-md-4">
			<?php if ($stat['hashrate'] == 0) { ?>
				<h1 style="font-size: 48px;">NOT CURRENTLY MINING</h1>
			<?php }
			else { ?>
				<ul class="list-group">
					<li class="list-group-item list-group-item-<?=$conf['colour']?>"><h4><a href=<?="https://my.minerstat.com/worker/".$conf['ms_worker_name']?> target="_blank">Minerstat</a></h4></li>
					<li class="list-group-item">Hashrate	<span class="pull-right"><?=number_format($stat['hashrate'],2)?> <?=$stat['hashrate_unit']?>/s @ <?=$stat['power']?>W</span></li>
					<li class="list-group-item">Efficiency	<span class="pull-right"><?=$stat['accepted']?>/<?=$stat['rejected']?> (<?=number_format(100 - (($stat['rejected'] / $stat['accepted']) * 100), 2)?>%)</span></li>
					<li class="list-group-item">Temperature	<span class="pull-right"><?=$stat['temp0']?>°C / <?=$stat['temp1']?>°C</span></li>
					<li class="list-group-item">Fan Speed	<span class="pull-right"><?=$stat['fanspeed0']?>% / <?=$stat['fanspeed1']?>%</span></li>
					<li class="list-group-item">Uptime	<span class="pull-right"><?=$stat['uptime']?></span></li>
				</ul>
			<?php } ?>
		</div>

		<div class="col-md-4">
			<ul class="list-group">
				<li class="list-group-item list-group-item-<?=$conf['colour']?>"><h4>Earnings</h4></li>
				<li class="list-group-item">&Xi; Today (So Far) 	<span class="pull-right">&Xi;<?=number_format($stat['startOfDayUnpaid'],5)?> (<?=$fiat['sym'].number_format(($ethtofiat * $stat['startOfDayUnpaid']),2)?>)</span></li>
				<li class="list-group-item">&Xi; Yesterday		<span class="pull-right">&Xi;<?=number_format($stat['yesterdayUnpaid'],5)?> (<?=$fiat['sym'].number_format(($ethtofiat * $stat['yesterdayUnpaid']),2)?>)</span></li>
				<li class="list-group-item">&Xi; 2 Days Ago		<span class="pull-right">&Xi;<?=number_format($stat['twoDaysAgoUnpaid'],5)?> (<?=$fiat['sym'].number_format(($ethtofiat * $stat['twoDaysAgoUnpaid']),2)?>)</span></li>
			</ul>
		</div>
	</div>

	<div class="container">
		<div class="col-md-12 footer">
			<a href="https://gking.uk" target="_blank" class="pull-right"><i class="fa fa-home"></i> gking.uk</a>
		</div>
	</div>

	<?=core_output_footerscripts()?>

  </body>

</html>
