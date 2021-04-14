<?php require_once('inc/loader.php'); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
  	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
	<link href=<?= $conf['css_folder_path']?> rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<title>(<?=$fiat['sym'].number_format(($stat['todayEstimated']*$ethtofiat),2)?>) GK &Xi; STATS</title>
	<meta http-equiv="Refresh" content="300">
  </head>

  <body>

	<div class="container">

		<div class="col-md-12">
			<h1>GK &Xi; STATS</h1>
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
			<h2>UNPAID</h2>
			<h3>&Xi;<?=number_format($stat['unpaid'],5)?> (<?=$fiat['sym'].number_format(($ethtofiat * $stat['unpaid']),2)?>)</h3>
		</div>
		<div class="col-md-3">
			<h2>EST PAYOUT</h2>
			<h3><?=core_calc_remaining($stat['hoursuntil'], false)?></h3>
		</div>

		<?php if ( $stat['waiting'] == '1' ) {
			echo '<div class="col-md-12"><p align="center"><em>There is insufficient data to produce any useful metrics.<br>Please check your wallet settings in config.php.<br>The pool you are querying may also be limiting API requests - please try later.</em></p></div>';
			die;
		} ?>

		<div id="showDiv">
			<div class="col-md-4">
				<?php if ($stat['hashrate'] == 0) { ?>
					<h1 style="font-size: 48px;">NOT CURRENTLY MINING</h1>
				<?php }
				else { ?>
					<ul class="list-group">
						<li class="list-group-item list-group-item-<?=$conf['colour']?>"><span id="heading"><a href=<?="https://my.minerstat.com/worker/".$conf['ms_worker_name']?> target="_blank">Minerstat</a></span></li>
						<li class="list-group-item">Hashrate	<span class="pull-right"><?=number_format($stat['hashrate'],2)?> MH/s @ <?=$stat['power']?>W</span></li>
						<li class="list-group-item">Efficiency	<span class="pull-right"><?=$stat['accepted']?>/<?=$stat['rejected']?> (<?=number_format(100 - (($stat['rejected'] / $stat['accepted']) * 100), 2)?>%)</span></li>
						<li class="list-group-item">Temperature	<span class="pull-right"><?=$stat['temp']?>°C</span></li>
						<li class="list-group-item">Fan Speed	<span class="pull-right"><?=$stat['fanspeed']?>%</span></li>
						<li class="list-group-item">Uptime	<span class="pull-right"><?=$stat['uptime']?></span></li>
					</ul>
				<?php } ?>
			</div>

			<div class="col-md-4">
				<ul class="list-group">
					<li class="list-group-item list-group-item-<?=$conf['colour']?>"><span id="heading"><a href=<?="https://ethermine.org/miners/".$conf['wallet']."/dashboard"?> target="_blank">Earnings</a></span></li>
					<li class="list-group-item">&Xi; Today (So Far) 	<span class="pull-right">&Xi;<?=number_format($stat['todayUnpaid'],5)?> (<?=$fiat['sym'].number_format(($ethtofiat * $stat['todayUnpaid']),2)?>)</span></li>
					<li class="list-group-item">&Xi; Yesterday		<span class="pull-right">&Xi;<?=number_format($stat['yesterdayUnpaid'],5)?> (<?=$fiat['sym'].number_format(($ethtofiat * $stat['yesterdayUnpaid']),2)?>)</span></li>
					<li class="list-group-item">&Xi; 2 Days Ago		<span class="pull-right">&Xi;<?=number_format($stat['twoDaysAgoUnpaid'],5)?> (<?=$fiat['sym'].number_format(($ethtofiat * $stat['twoDaysAgoUnpaid']),2)?>)</span></li>
					<li class="list-group-item">&Xi; Payout Figure		<span class="pull-right">&Xi;<?=$stat['payout']?> (<?=$fiat['sym'].number_format(($ethtofiat / 20),2)?>)</span></li>
					<li class="list-group-item">&Xi; Remaining 	<span class="pull-right">&Xi;<?=number_format($stat['eneeded'],5)?> (<?=$fiat['sym'].number_format(($ethtofiat * $stat['eneeded']),2)?>)</span></li>
				</ul>
			</div>

			<div class="col-md-4">
				<ul class="list-group">
					<li class="list-group-item"><span id="heading"><a href='https://uk.finance.yahoo.com/cryptocurrencies' target="_blank">Watchlist (1hr +/-%)</a></span><span class="pull-right"><a href="#" onClick="fiatUsdSwitch()" id="fiatUsdButton">$</a></span></li>
					<div id="fiatDiv">
						<?php if ( $btcchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BTC')?> target="_blank">฿TC 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($btctofiat - $btcchange) / $btctofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($btctofiat,2) ?></span> </li> <?php }
						else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BTC')?> target="_blank">฿TC 	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($btctofiat - $btcchange) / $btctofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($btctofiat,2) ?></span> </li> <?php } ?>
						<?php if ( $ethchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ETH')?> target="_blank">&Xi;TH 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($ethtofiat - $ethchange) / $ethtofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($ethtofiat,2) ?></span> </li> <?php }
						else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ETH')?> target="_blank">&Xi;TH 	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($ethtofiat - $ethchange) / $ethtofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($ethtofiat,2) ?></span> </li> <?php } ?>
						<?php if ( $batchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BAT')?> target="_blank">BAT 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($battofiat - $batchange) / $battofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($battofiat,2) ?></span> </li> <?php } 
						else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BAT')?> target="_blank">BAT	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($battofiat - $batchange) / $battofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($battofiat,2) ?></span> </li> <?php } ?>
						<?php if ( $dogechange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('DOGE')?> target="_blank">DOGE 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($dogetofiat - $dogechange) / $dogetofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($dogetofiat,3) ?></span> </li> <?php } 
						else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('DOGE')?> target="_blank">DOGE	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($dogetofiat - $dogechange) / $dogetofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($dogetofiat,3) ?></span> </li> <?php } ?>
						<?php if ( $zrxchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ZRX')?> target="_blank">ZRX 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($zrxtofiat - $zrxchange) / $zrxtofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($zrxtofiat,2) ?></span> </li> <?php } 
						else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ZRX')?> target="_blank">ZRX	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($zrxtofiat - $zrxchange) / $zrxtofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($zrxtofiat,2) ?></span> </li> <?php } ?>
					</div>
					<div id="usdDiv" style="display: none;">
						<?php if ( $btcchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BTC')?> target="_blank">฿TC 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($btctofiat - $btcchange) / $btctofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($btctofiat * $stat['usdrate'],2) ?></span> </li> <?php }
						else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BTC')?> target="_blank">฿TC 	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($btctofiat - $btcchange) / $btctofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($btctofiat * $stat['usdrate'],2) ?></span> </li> <?php } ?>
						<?php if ( $ethchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ETH')?> target="_blank">&Xi;TH 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($ethtofiat - $ethchange) / $ethtofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($ethtofiat * $stat['usdrate'],2) ?></span> </li> <?php }
						else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ETH')?> target="_blank">&Xi;TH 	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($ethtofiat - $ethchange) / $ethtofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($ethtofiat * $stat['usdrate'],2) ?></span> </li> <?php } ?>
						<?php if ( $batchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BAT')?> target="_blank">BAT 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($battofiat - $batchange) / $battofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($battofiat * $stat['usdrate'],2) ?></span> </li> <?php } 
						else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BAT')?> target="_blank">BAT	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($battofiat - $batchange) / $battofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($battofiat * $stat['usdrate'],2) ?></span> </li> <?php } ?>
						<?php if ( $dogechange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('DOGE')?> target="_blank">DOGE 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($dogetofiat - $dogechange) / $dogetofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($dogetofiat * $stat['usdrate'],3) ?></span> </li> <?php } 
						else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('DOGE')?> target="_blank">DOGE	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($dogetofiat - $dogechange) / $dogetofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($dogetofiat * $stat['usdrate'],3) ?></span> </li> <?php } ?>
						<?php if ( $zrxchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ZRX')?> target="_blank">ZRX 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($zrxtofiat - $zrxchange) / $zrxtofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($zrxtofiat * $stat['usdrate'],2) ?></span> </li> <?php } 
						else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ZRX')?> target="_blank">ZRX	</a><span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($zrxtofiat - $zrxchange) / $zrxtofiat) * 100),2)?>%)</span><span class="pull-right">$<?=number_format($zrxtofiat * $stat['usdrate'],2) ?></span> </li> <?php } ?>
					</div>
				</ul>
			</div>
		</div>
		<div id="hideDiv" style="display: none;">
			<div class="col-md-4">
				<ul class="list-group">
					<li class="list-group-item list-group-item-<?=$conf['colour']?>"><span id="heading">&Xi;TH (est avg)</span></li>
					<li class="list-group-item">Hour 	<span class="pull-right">&Xi;<?=number_format($stat['ehour'],5)?></span></li>
					<li class="list-group-item">Day 	<span class="pull-right">&Xi;<?=number_format($stat['eday'],5)?></span></li>
					<li class="list-group-item">Week 	<span class="pull-right">&Xi;<?=number_format($stat['eweek'],5)?></span></li>
					<li class="list-group-item">Month 	<span class="pull-right">&Xi;<?=number_format($stat['emonth'],5)?></span></li>
					<li class="list-group-item">Year 	<span class="pull-right">&Xi;<?=number_format($stat['eyear'],5)?></span></li>
				</ul>
			</div>

			<div class="col-md-4">
				<ul class="list-group">
					<li class="list-group-item list-group-item-<?=$conf['colour']?>"><span id="heading"><?=$fiat['code']?> (est avg)</span></li>
					<li class="list-group-item">Hour 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['ehour']*$ethtofiat),2)?></span></li>
					<li class="list-group-item">Day 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['eday']*$ethtofiat),2)?></span></li>
					<li class="list-group-item">Week 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['eweek']*$ethtofiat),2)?></span></li>
					<li class="list-group-item">Month 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['emonth']*$ethtofiat),2)?></span></li>
					<li class="list-group-item">Year 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['eyear']*$ethtofiat),2)?></span></li>
				</ul>
			</div>

			<div class="col-md-4">
				<ul class="list-group">
					<li class="list-group-item list-group-item-<?=$conf['colour']?>"><span id="heading">฿TC (est avg)</span></li>
					<li class="list-group-item">Hour 	<span class="pull-right">฿<?=number_format($stat['bhour'],5)?></span></li>
					<li class="list-group-item">Day 	<span class="pull-right">฿<?=number_format($stat['bday'],5)?></span></li>
					<li class="list-group-item">Week 	<span class="pull-right">฿<?=number_format($stat['bweek'],5)?></span></li>
					<li class="list-group-item">Month 	<span class="pull-right">฿<?=number_format($stat['bmonth'],5)?></span></li>
					<li class="list-group-item">Year 	<span class="pull-right">฿<?=number_format($stat['byear'],5)?></span></li>
				</ul>
			</div>
		</div>

		<div class="col-md-12">
			<div class="progress">
			  	<div class="progress-bar progress-bar-striped progress-bar-<?=$conf['colour']?> active" role="progressbar" aria-valuenow="<?=$stat['unpaid']?>" aria-valuemin="0" aria-valuemax="100" style="width:<?=number_format(($stat['unpaid']/$stat['payout'])*100)?>%">
			  		<p><?=number_format(($stat['unpaid']/$stat['payout'])*100)?>%</p>
				</div>
			</div><br>
		</div>

		<div id="showHideButtonDiv" class="col-md-12">
			<button id="showHideButton" style="display: none;" onclick="showHide()">Show average rates</button>
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
