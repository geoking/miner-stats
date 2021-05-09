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
	<title>(<?=$fiat['sym'].number_format(($stat['todayEstimated']*$ethtogbp),2)?>) GK &Xi; STATS</title>
	<meta http-equiv="Refresh" content="120">
  </head>

  <body>

	<div class="container">

		<div class="col-md-12">
			<h1>GK &Xi; STATS</h1>
			<h5>Last updated: <?=$stat['now']?> (<a href="/stats" class="fa fa-refresh"></a>)</h5>
		</div>

		<div class="col-md-3">
			<h2>CURRENT RATE</h2>
			<?php if ($stat['hashrate'] == 0) { ?>
				<h3>NOT MINING! :(</h3>
			<?php } else { ?>
				<h3>&Xi;<?=number_format($stat['mseday'],5)?> (<?=$fiat['sym'].number_format(($stat['mseday']*$ethtogbp),2)?>)</h3>
			<?php } ?>
		</div>
		<div class="col-md-3">
			<h2>EST TODAY</h2>
			<h3>&Xi;<?=number_format($stat['todayEstimated'],5)?> (<?=$fiat['sym'].number_format(($ethtogbp * $stat['todayEstimated']),2)?>)</h3>
		</div>
		<div class="col-md-3">
			<h2>UNPAID</h2>
			<h3>&Xi;<?=number_format($stat['unpaid'],5)?> (<?=$fiat['sym'].number_format(($ethtogbp * $stat['unpaid']),2)?>)</h3>
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
					<li class="list-group-item list-group-item-<?=$conf['colour']?>"><h4><a href=<?="https://ethermine.org/miners/".$conf['wallet']."/dashboard"?> target="_blank">Earnings</a></h4></li>
					<li class="list-group-item">&Xi; Today (So Far) 	<span class="pull-right">&Xi;<?=number_format($stat['todayUnpaid'],5)?> (<?=$fiat['sym'].number_format(($ethtogbp * $stat['todayUnpaid']),2)?>)</span></li>
					<li class="list-group-item">&Xi; Yesterday		<span class="pull-right">&Xi;<?=number_format($stat['yesterdayUnpaid'],5)?> (<?=$fiat['sym'].number_format(($ethtogbp * $stat['yesterdayUnpaid']),2)?>)</span></li>
					<li class="list-group-item">&Xi; 2 Days Ago		<span class="pull-right">&Xi;<?=number_format($stat['twoDaysAgoUnpaid'],5)?> (<?=$fiat['sym'].number_format(($ethtogbp * $stat['twoDaysAgoUnpaid']),2)?>)</span></li>
					<li class="list-group-item">&Xi; Payout Figure		<span class="pull-right">&Xi;<?=$conf['payout_threshold']?> (<?=$fiat['sym'].number_format(($ethtogbp / 20),2)?>)</span></li>
					<li class="list-group-item">&Xi; Remaining 	<span class="pull-right">&Xi;<?=number_format($stat['eneeded'],5)?> (<?=$fiat['sym'].number_format(($ethtogbp * $stat['eneeded']),2)?>)</span></li>
				</ul>
			</div>
		</div>

		<div id="hideDiv" style="display: none;">
			<div class="col-md-4">
				<ul class="list-group">
					<li class="list-group-item list-group-item-<?=$conf['colour']?>"><h4>&Xi;TH (est avg)</h4></li>
					<li class="list-group-item">Hour 	<span class="pull-right">&Xi;<?=number_format($stat['ehour'],5)?></span></li>
					<li class="list-group-item">Day 	<span class="pull-right">&Xi;<?=number_format($stat['eday'],5)?></span></li>
					<li class="list-group-item">Week 	<span class="pull-right">&Xi;<?=number_format($stat['eweek'],5)?></span></li>
					<li class="list-group-item">Month 	<span class="pull-right">&Xi;<?=number_format($stat['emonth'],5)?></span></li>
					<li class="list-group-item">Year 	<span class="pull-right">&Xi;<?=number_format($stat['eyear'],5)?></span></li>
				</ul>
			</div>

			<div class="col-md-4">
				<ul class="list-group">
					<li class="list-group-item list-group-item-<?=$conf['colour']?>"><h4><?=$fiat['code']?> (est avg)</h4></li>
					<li class="list-group-item">Hour 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['ehour']*$ethtogbp),2)?></span></li>
					<li class="list-group-item">Day 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['eday']*$ethtogbp),2)?></span></li>
					<li class="list-group-item">Week 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['eweek']*$ethtogbp),2)?></span></li>
					<li class="list-group-item">Month 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['emonth']*$ethtogbp),2)?></span></li>
					<li class="list-group-item">Year 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['eyear']*$ethtogbp),2)?></span></li>
				</ul>
			</div>
		</div>

		<div class="col-md-4">
			<ul class="list-group">
				<li class="list-group-item"><h4><a href='https://uk.finance.yahoo.com/cryptocurrencies' target="_blank">Watchlist (1hr +/-%)</a><span class="pull-right"><a href="javascript:void(0)" role="button" onClick="fiatUsdSwitch()" id="fiatUsdButton">£<span style="font-size: 10px;">/$</span></a></span></h4></li>
				<div id="fiatDiv">
				<?php if ( $btcgbpchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BTC', 'GBP')?> target="_blank">฿TC 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?= $btcgbpchange ?>%)</span><span class="pull-right">£<?= number_format($btctogbp, 2) ?></span> </li> <?php }
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BTC', 'GBP')?> target="_blank">฿TC 	</a><span class="pull-right" style="color: red">&nbsp;(<?= $btcgbpchange ?>%)</span><span class="pull-right">£<?= number_format($btctogbp, 2) ?></span> </li> <?php } ?>
					<?php if ( $ethgbpchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ETH', 'GBP')?> target="_blank">&Xi;TH 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?= $ethgbpchange ?>%)</span><span class="pull-right">£<?= number_format($ethtogbp, 2) ?></span> </li> <?php }
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ETH', 'GBP')?> target="_blank">&Xi;TH 	</a><span class="pull-right" style="color: red">&nbsp;(<?= $ethgbpchange ?>%)</span><span class="pull-right">£<?= number_format($ethtogbp, 2) ?></span> </li> <?php } ?>
					<?php if ( $batgbpchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BAT', 'GBP')?> target="_blank">BAT 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?= $batgbpchange ?>%)</span><span class="pull-right">£<?= number_format($battogbp, 3) ?></span> </li> <?php } 
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BAT', 'GBP')?> target="_blank">BAT	</a><span class="pull-right" style="color: red">&nbsp;(<?= $batgbpchange ?>%)</span><span class="pull-right">£<?= number_format($battogbp, 3) ?></span> </li> <?php } ?>
					<?php if ( $dogegbpchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('DOGE', 'GBP')?> target="_blank">DOGE 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?= $dogegbpchange ?>%)</span><span class="pull-right">£<?= number_format($dogetogbp, 3) ?></span> </li> <?php } 
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('DOGE', 'GBP')?> target="_blank">DOGE	</a><span class="pull-right" style="color: red">&nbsp;(<?= $dogegbpchange ?>%)</span><span class="pull-right">£<?= number_format($dogetogbp, 3) ?></span> </li> <?php } ?>
					<?php if ( $zrxgbpchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ZRX', 'GBP')?> target="_blank">ZRX 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?= $zrxgbpchange ?>%)</span><span class="pull-right">£<?= number_format($zrxtogbp, 3) ?></span> </li> <?php } 
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ZRX', 'GBP')?> target="_blank">ZRX	</a><span class="pull-right" style="color: red">&nbsp;(<?= $zrxgbpchange ?>%)</span><span class="pull-right">£<?= number_format($zrxtogbp, 3) ?></span> </li> <?php } ?>
				</div>
				<div id="usdDiv" style="display: none;">
					<?php if ( $btcusdchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BTC', 'USD')?> target="_blank">฿TC 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?= $btcusdchange ?>%)</span><span class="pull-right">$<?= number_format($btctousd, 2) ?></span> </li> <?php }
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BTC', 'USD')?> target="_blank">฿TC 	</a><span class="pull-right" style="color: red">&nbsp;(<?= $btcusdchange ?>%)</span><span class="pull-right">$<?= number_format($btctousd, 2) ?></span> </li> <?php } ?>
					<?php if ( $ethusdchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ETH', 'USD')?> target="_blank">&Xi;TH 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?= $ethusdchange ?>%)</span><span class="pull-right">$<?= number_format($ethtousd, 2) ?></span> </li> <?php }
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ETH', 'USD')?> target="_blank">&Xi;TH 	</a><span class="pull-right" style="color: red">&nbsp;(<?= $ethusdchange ?>%)</span><span class="pull-right">$<?= number_format($ethtousd, 2) ?></span> </li> <?php } ?>
					<?php if ( $batusdchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BAT', 'USD')?> target="_blank">BAT 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?= $batusdchange ?>%)</span><span class="pull-right">$<?= number_format($battousd, 3) ?></span> </li> <?php } 
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('BAT', 'USD')?> target="_blank">BAT	</a><span class="pull-right" style="color: red">&nbsp;(<?= $batusdchange ?>%)</span><span class="pull-right">$<?= number_format($battousd, 3) ?></span> </li> <?php } ?>
					<?php if ( $dogeusdchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('DOGE', 'USD')?> target="_blank">DOGE 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?= $dogeusdchange ?>%)</span><span class="pull-right">$<?= number_format($dogetousd, 3) ?></span> </li> <?php } 
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('DOGE', 'USD')?> target="_blank">DOGE	</a><span class="pull-right" style="color: red">&nbsp;(<?= $dogeusdchange ?>%)</span><span class="pull-right">$<?= number_format($dogetousd, 3) ?></span> </li> <?php } ?>
					<?php if ( $zrxusdchange >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ZRX', 'USD')?> target="_blank">ZRX 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?= $zrxusdchange ?>%)</span><span class="pull-right">$<?= number_format($zrxtousd, 3) ?></span> </li> <?php } 
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl('ZRX', 'USD')?> target="_blank">ZRX	</a><span class="pull-right" style="color: red">&nbsp;(<?= $zrxusdchange ?>%)</span><span class="pull-right">$<?= number_format($zrxtousd, 3) ?></span> </li> <?php } ?>
				</div>
			</ul>
		</div>
		

		<div class="col-md-12">
			<div class="progress">
			  	<div class="progress-bar progress-bar-striped progress-bar-<?=$conf['colour']?> active" role="progressbar" aria-valuenow="<?=$stat['unpaid']?>" aria-valuemin="0" aria-valuemax="100" style="width:<?=number_format(($stat['unpaid']/$conf['payout_threshold'])*100)?>%">
			  		<p><?=number_format(($stat['unpaid']/$conf['payout_threshold'])*100)?>%</p>
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
