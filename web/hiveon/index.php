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

		<div id="overallStatsDiv">
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
					<li class="list-group-item list-group-item-<?=$conf['colour']?>"><h4><a href=<?="https://hiveon.net/eth?miner=".$conf['wallet']?> target="_blank">Earnings</a></h4></li>
					<li class="list-group-item">&Xi; Today (So Far) 	<span class="pull-right">&Xi;<?=number_format($stat['todayUnpaid'],4)?> (<?=$fiat['sym'].number_format(($ethtogbp * $stat['todayUnpaid']),2)?>)</span></li>
					<li class="list-group-item">&Xi; Yesterday		<span class="pull-right">&Xi;<?=number_format($stat['yesterdayUnpaid'],4)?> (<?=$fiat['sym'].number_format(($ethtogbp * $stat['yesterdayUnpaid']),2)?>)</span></li>
					<li class="list-group-item">&Xi; 2 Days Ago		<span class="pull-right">&Xi;<?=number_format($stat['twoDaysAgoUnpaid'],4)?> (<?=$fiat['sym'].number_format(($ethtogbp * $stat['twoDaysAgoUnpaid']),2)?>)</span></li>
					<li class="list-group-item">&Xi; Payout Figure		<span class="pull-right">&Xi;<?=$conf['payout_threshold']?> (<?=$fiat['sym'].number_format(($ethtogbp * $conf['payout_threshold']),2)?>)</span></li>
					<li class="list-group-item">&Xi; Remaining 	<span class="pull-right">&Xi;<?=number_format($stat['eneeded'],4)?> (<?=$fiat['sym'].number_format(($ethtogbp * $stat['eneeded']),2)?>)</span></li>
				</ul>
			</div>
		</div>

		<div id="averageStatsDiv" style="display: none;">
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

		<div id="currentStatsDiv" style="display: none;">
			<div class="col-md-4">
				<ul class="list-group">
					<li class="list-group-item list-group-item-<?=$conf['colour']?>"><h4>&Xi;TH (current)</h4></li>
					<li class="list-group-item">Hour 	<span class="pull-right">&Xi;<?=number_format($stat['ecurhour'],5)?></span></li>
					<li class="list-group-item">Day 	<span class="pull-right">&Xi;<?=number_format($stat['ecurday'],5)?></span></li>
					<li class="list-group-item">Week 	<span class="pull-right">&Xi;<?=number_format($stat['ecurweek'],5)?></span></li>
					<li class="list-group-item">Month 	<span class="pull-right">&Xi;<?=number_format($stat['ecurmonth'],5)?></span></li>
					<li class="list-group-item">Year 	<span class="pull-right">&Xi;<?=number_format($stat['ecuryear'],5)?></span></li>
				</ul>
			</div>

			<div class="col-md-4">
				<ul class="list-group">
					<li class="list-group-item list-group-item-<?=$conf['colour']?>"><h4><?=$fiat['code']?> (current)</h4></li>
					<li class="list-group-item">Hour 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['ecurhour']*$ethtogbp),2)?></span></li>
					<li class="list-group-item">Day 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['ecurday']*$ethtogbp),2)?></span></li>
					<li class="list-group-item">Week 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['ecurweek']*$ethtogbp),2)?></span></li>
					<li class="list-group-item">Month 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['ecurmonth']*$ethtogbp),2)?></span></li>
					<li class="list-group-item">Year 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['ecuryear']*$ethtogbp),2)?></span></li>
				</ul>
			</div>
		</div>

		<div class="col-md-4">
			<ul class="list-group">
				<li class="list-group-item"><h4><a href='https://uk.finance.yahoo.com/cryptocurrencies' target="_blank">Watchlist (1hr +/-%)</a><span class="pull-right"><a href="javascript:void(0)" role="button" onClick="fiatUsdSwitch()" id="fiatUsdButton">£<span style="font-size: 10px;">/$</span></a></span></h4></li>
				<div id="fiatDiv">
					<?php foreach($cryptoWatchlistArray as $crypto) { 
					if ( $crypto[1]['gbpchange'] >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl($crypto[0], 'GBP')?> target="_blank"><?=$crypto[0]?> 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?= $crypto[1]['gbpchange'] ?>%)</span><span class="pull-right">£<?= number_format($crypto[1]['togbp'], 2) ?></span> </li> <?php }
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl($crypto[0], 'GBP')?> target="_blank"><?=$crypto[0]?> 	</a><span class="pull-right" style="color: red">&nbsp;(<?= $crypto[1]['gbpchange'] ?>%)</span><span class="pull-right">£<?= number_format($crypto[1]['togbp'], 2) ?></span> </li> <?php } ?>
					<?php } ?>
				</div>
				<div id="usdDiv" style="display: none;">
					<?php foreach($cryptoWatchlistArray as $crypto) { 
					if ( $crypto[1]['usdchange'] >= '0' ) { ?><li class="list-group-item"><a href=<?=core_getcryptourl($crypto[0], 'USD')?> target="_blank"><?=$crypto[0]?> 	</a><span class="pull-right" style="color: lightgreen">&nbsp;(+<?= $crypto[1]['usdchange'] ?>%)</span><span class="pull-right">$<?= number_format($crypto[1]['tousd'], 2) ?></span> </li> <?php }
					else { ?><li class="list-group-item"><a href=<?=core_getcryptourl($crypto[0], 'USD')?> target="_blank"><?=$crypto[0]?> 	</a><span class="pull-right" style="color: red">&nbsp;(<?= $crypto[1]['usdchange'] ?>%)</span><span class="pull-right">$<?= number_format($crypto[1]['tousd'], 2) ?></span> </li> <?php } ?>
					<?php } ?>
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
