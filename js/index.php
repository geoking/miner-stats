<?php require_once('inc/loader.php'); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
  	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
	<link href="/stats/css/custom.css" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<title>(<?=$fiat['sym'].number_format(($stat['todayEstimated']*$ethtofiat),2)?>) GKING ETHERMINE STATS</title>
	<meta http-equiv="Refresh" content="300">
  </head>

  <body>

	<div class="container">

		<div class="col-md-12">
			<h1>GKING ETHERMINE STATS</h1>
		</div>

		<?php if ( $stat['waiting'] == '1' ) {
			echo '<div class="col-md-12"><p align="center"><em>There is insufficient data to produce any useful metrics.<br>Please check your wallet settings in config.php.<br>The pool you are querying may also be limiting API requests - please try later.</em></p></div>';
			die;
		} ?>

		<div class="col-md-4">
			<ul class="list-group">
				<li class="list-group-item list-group-item-<?=$conf['colour']?>"><h4>Payments</h4></li>
				<li class="list-group-item">&Xi; Today 	<span class="pull-right">&Xi;<?=number_format($stat['todayUnpaid'],5)?> (<?=$fiat['sym'].number_format(($ethtofiat * $stat['todayUnpaid']),2)?>)</span></li>
				<li class="list-group-item">&Xi; Today (est) 	<span class="pull-right">&Xi;<?=number_format($stat['todayEstimated'],5)?> (<?=$fiat['sym'].number_format(($ethtofiat * $stat['todayEstimated']),2)?>)</span></li>
				<li class="list-group-item">&Xi; Yesterday		<span class="pull-right">&Xi;<?=number_format($stat['yesterdayUnpaid'],5)?> (<?=$fiat['sym'].number_format(($ethtofiat * $stat['yesterdayUnpaid']),2)?>)</span></li>
				<li class="list-group-item">&Xi; 2 Days Ago		<span class="pull-right">&Xi;<?=number_format($stat['twoDaysAgoUnpaid'],5)?> (<?=$fiat['sym'].number_format(($ethtofiat * $stat['twoDaysAgoUnpaid']),2)?>)</span></li>
				<li class="list-group-item">&Xi; Payout Figure		<span class="pull-right">&Xi;<?=$stat['payout']?> (<?=$fiat['sym'].number_format(($ethtofiat / 20),2)?>)</span></li>
				<li class="list-group-item">&Xi; Remaining 	<span class="pull-right">&Xi;<?=number_format($stat['eneeded'],5)?> (<?=$fiat['sym'].number_format(($ethtofiat * $stat['eneeded']),2)?>)</span></li>
			</ul>
		</div>

		<div class="col-md-4">
			<ul class="list-group">
				<li class="list-group-item list-group-item-<?=$conf['colour']?>"><h4>Statistics</h4></li>
				<li class="list-group-item">&Xi; Unpaid 	<span class="pull-right">&Xi;<?=number_format($stat['unpaid'],5)?> (<?=$fiat['sym'].number_format(($ethtofiat * $stat['unpaid']),2)?>)</span></li>
				<li class="list-group-item">
					<?php if ( $conf['show_reportedhash'] == '0' ) { ?>Mined<span class="pull-right">&Xi;<?=$stat['unpaid']?></span> <?php } ?>
					<?php if ( $conf['show_reportedhash'] == '1' ) { ?>Hashrate (now)<span class="pull-right"><?=$stat['reportedhashrate']?> MH/s</span> <?php } ?>
				</li>				
				<li class="list-group-item">Hashrate (/hr)	<span class="pull-right"><?=$stat['hashrate']?> MH/s</span></li>
				<li class="list-group-item">Hashrate (/24hr)		<span class="pull-right"><?=$stat['avghashrate']?> MH/s</span></li>
				<li class="list-group-item">Next Payout	<span class="pull-right"><?=$stat['paytime']?></span></li>
				<li class="list-group-item">Time Left	<span class="pull-right"><?=core_calc_remaining($stat['hoursuntil'])?></span></li>
			</ul>
		</div>

		<div class="col-md-4">
			<ul class="list-group">
				<li class="list-group-item list-group-item-<?=$conf['colour']?>"><h4>Watchlist (1hr +/-%)</h4></li>
				<?php if ( $ethchange >= '0' ) { ?><li class="list-group-item">&Xi;TH 	<span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($ethtofiat - $ethchange) / $ethtofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($ethtofiat,2) ?></span> </li> <?php } ?>
				<?php if ( $ethchange < '0' ) { ?><li class="list-group-item">&Xi;TH 	<span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($ethtofiat - $ethchange) / $ethtofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($ethtofiat,2) ?></span> </li> <?php } ?>
				<?php if ( $btcchange >= '0' ) { ?><li class="list-group-item">???TC 	<span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($btctofiat - $btcchange) / $btctofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($btctofiat,2) ?></span> </li> <?php } ?>
				<?php if ( $btcchange <'0' ) { ?><li class="list-group-item">???TC 	<span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($btctofiat - $btcchange) / $btctofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($btctofiat,2) ?></span> </li> <?php } ?>
				<?php if ( $batchange >= '0' ) { ?><li class="list-group-item">BAT 	<span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($battofiat - $batchange) / $battofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($battofiat,2) ?></span> </li> <?php } ?>
				<?php if ( $batchange <'0' ) { ?><li class="list-group-item">BAT	<span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($battofiat - $batchange) / $battofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($battofiat,2) ?></span> </li> <?php } ?>
				<?php if ( $dogechange >= '0' ) { ?><li class="list-group-item">DOGE 	<span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($dogetofiat - $dogechange) / $dogetofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($dogetofiat,3) ?></span> </li> <?php } ?>
				<?php if ( $dogechange <'0' ) { ?><li class="list-group-item">DOGE	<span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($dogetofiat - $dogechange) / $dogetofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($dogetofiat,3) ?></span> </li> <?php } ?>
				<?php if ( $zrxchange >= '0' ) { ?><li class="list-group-item">ZRX 	<span class="pull-right" style="color: lightgreen">&nbsp;(+<?=number_format(((1 - ($zrxtofiat - $zrxchange) / $zrxtofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($zrxtofiat,2) ?></span> </li> <?php } ?>
				<?php if ( $zrxchange <'0' ) { ?><li class="list-group-item">ZRX	<span class="pull-right" style="color: red">&nbsp;(<?=number_format(((1 - ($zrxtofiat - $zrxchange) / $zrxtofiat) * 100),2)?>%)</span><span class="pull-right"><?=$fiat['sym'].number_format($zrxtofiat,2) ?></span> </li> <?php } ?>
			</ul>
		</div>

		<?php if ( $conf['show_bar'] == '1' ) { ?>
		<div class="col-md-12">
			<div class="progress">
			  	<div class="progress-bar progress-bar-striped progress-bar-<?=$conf['colour']?> active" role="progressbar" aria-valuenow="<?=$stat['unpaid']?>" aria-valuemin="0" aria-valuemax="100" style="width:<?=number_format(($stat['unpaid']/$stat['payout'])*100)?>%">
			  		<p><?=number_format(($stat['unpaid']/$stat['payout'])*100)?>%</p>
				</div>
			</div><br>
		</div>
		<?php } ?>	

		<div class="col-md-4">
			<ul class="list-group">
				<li class="list-group-item list-group-item-<?=$conf['colour']?>"><h4>&Xi;TH (est avg)</h4></li>
				<?php if ( $conf['show_hour'] == '1' ) { ?>	<li class="list-group-item">Hour 	<span class="pull-right">&Xi;<?=number_format($stat['ehour'],5)?></span></li>	<?php } ?>
				<?php if ( $conf['show_day'] == '1' ) { ?>		<li class="list-group-item">Day 	<span class="pull-right">&Xi;<?=number_format($stat['eday'],5)?></span></li>	<?php } ?>
				<?php if ( $conf['show_week'] == '1' ) { ?>	<li class="list-group-item">Week 	<span class="pull-right">&Xi;<?=number_format($stat['eweek'],5)?></span></li>	<?php } ?>
				<?php if ( $conf['show_month'] == '1' ) { ?>	<li class="list-group-item">Month 	<span class="pull-right">&Xi;<?=number_format($stat['emonth'],5)?></span></li>	<?php } ?>
				<li class="list-group-item">Year 	<span class="pull-right">&Xi;<?=number_format($stat['eyear'],5)?></span></li>
			</ul>
		</div>

		<div class="col-md-4">
			<ul class="list-group">
				<li class="list-group-item list-group-item-<?=$conf['colour']?>"><h4><?=$fiat['code']?> (est avg)</h4></li>
				<?php if ( $conf['show_hour'] == '1' ) { ?>	<li class="list-group-item">Hour 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['ehour']*$ethtofiat),2)?></span></li><?php } ?>
				<?php if ( $conf['show_day'] == '1' ) { ?>		<li class="list-group-item">Day 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['eday']*$ethtofiat),2)?></span></li><?php } ?>
				<?php if ( $conf['show_week'] == '1' ) { ?>	<li class="list-group-item">Week 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['eweek']*$ethtofiat),2)?></span></li><?php } ?>
				<?php if ( $conf['show_month'] == '1' ) { ?>	<li class="list-group-item">Month 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['emonth']*$ethtofiat),2)?></span></li><?php } ?>
				<li class="list-group-item">Year 	<span class="pull-right"><?=$fiat['sym'].number_format(($stat['eyear']*$ethtofiat),2)?></span></li>
			</ul>
		</div>

		<div class="col-md-4">
			<ul class="list-group">
				<li class="list-group-item list-group-item-<?=$conf['colour']?>"><h4>???TC (est avg)</h4></li>
				<?php if ( $conf['show_hour'] == '1' ) { ?>	<li class="list-group-item">Hour 	<span class="pull-right">???<?=number_format($stat['bhour'],5)?></span></li>	<?php } ?>
				<?php if ( $conf['show_day'] == '1' ) { ?>		<li class="list-group-item">Day 	<span class="pull-right">???<?=number_format($stat['bday'],5)?></span></li>	<?php } ?>
				<?php if ( $conf['show_week'] == '1' ) { ?>	<li class="list-group-item">Week 	<span class="pull-right">???<?=number_format($stat['bweek'],5)?></span></li>	<?php } ?>
				<?php if ( $conf['show_month'] == '1' ) { ?>	<li class="list-group-item">Month 	<span class="pull-right">???<?=number_format($stat['bmonth'],5)?></span></li>	<?php } ?>
				<li class="list-group-item">Year 	<span class="pull-right">???<?=number_format($stat['byear'],5)?></span></li>
			</ul>
		</div>
	</div>

	<!-- Please leave this footer block in place, so that others can find ethermine-stats -->
	<div class="container">
		<div class="col-md-12 footer">
			<a href="https://gking.uk" target="_blank" class="pull-right"><i class="fa fa-home"></i> gking.uk</a>
		</div>
	</div>
	<!-- Please leave this footer block in place, so that others can find ethermine-stats -->

	<?=core_output_footerscripts()?>

  </body>

</html>
