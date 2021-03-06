<?php

// -------------------------
// --- REQUIRED SETTINGS ---
// -------------------------

// --- Encryption key for URLS ---
// Used to mask links and other private data configured within miner stats
// Set this to a random string of at least 20 characters
	$conf['key'] = '86XzjlVmdY4TY8NHLD5';


// --- Wallet/Account Address ---
// Used to pull stats from ethermine, as well as the etherium blockchain
// This is the same as "<Your_Ethereum_Address>" when configuring your miner
// on ethermine.org
	$conf['wallet'] = '0x1078A33e916c33D5DDe843BEC9C1168d92C1dE56';


// --- Timezone ---
// Set this to your timezone, so that "next eth" and other date/time related 
// statistics are calculated so they are relevant to you.
// To find your timezone see - http://php.net/manual/en/timezones.php
	date_default_timezone_set('Europe/London'); 


// --- Cache File ---
// In the event that the Ethermine API returns no data, a local cache file
// is created and used.  No data is returned from the API query when there
// are too many requests being made.  Specify what you'd like your cache 
// file to be called.  This file will be readable from your webserver, so
// use a random name to maintain the obscurity of your mining wallet 
// address.
	$conf['cache_file'] = 'changeme.tmp';


	
// -------------------------
// --- OPTIONAL SETTINGS ---
// -------------------------

// Set the base FIAT currency (ISO 4217) you are interested in.  Currently 
// I have only created support for USD, EUR and GBP.  If you want others 
// adding let me know.
	$conf['fiat'] = 'gbp';

// Set the colour scheme of all elements using the bootstrap utility
// standards (info = blue, success = green, warning = yellow, danger = red).
	$conf['colour'] = 'success';


// --- Power Consumption ---
// The following information is used to provide a more accurate view of your
// mining profitability.  To diplay this information on the dashboard, be
// sure to set show_power to 1 in the display settings below.

	// What is the average power consumption of your mining rig in watts?
	// If you have more than one rig, and assuming they are on the same
	// supply, paying the same kWh rate, simply combine the power values.
	$conf['watts'] = '600';

	// How much do you pay per kWh?  This will usually be in the lowest
	// common denominator for your base currency.
	$conf['kwh_rate'] = '0';


// --- Display Settings ---
// These settings control what is displayed on your dashboard
// Yes = 1, No = 0

	// Display the mining progress bar
	$conf['show_bar'] = '1';

	// Include and display your power costs as part of your profitability
	$conf['show_power'] = '1';

	// Display stats related to the mining performance of time period
	$conf['show_min'] = '1';		// per minute performace
	$conf['show_hour'] = '1';		// per hour performace
	$conf['show_day'] = '1';		// per day performance
	$conf['show_week'] = '1';		// per week performance
	$conf['show_month'] = '1';		// per month performance


// --- Show Hashrate ---
// Some miners (qtminer, ethminer etc) don't correctly "report" a hashrate to
// to ethermine.org.  Check your stats via ethermine.org, if there is no 
// figure shown for "Reported Hashrate", then set this to 0. Setting this to
// 0 will replace the "Reported Hashrate" section on the dashboard with the
// value of ETH mined so far on the job cycle.
	$conf['show_reportedhash'] = '1';



// ----------------------
// --- DEBUG SETTINGS ---
// ----------------------

// You can safely leave these settings as they are, unless you know what 
// you are doing and need to change them for some reason.
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
// error_reporting(E_ALL);
// ini_set('allow_url_fopen', 1);


?>