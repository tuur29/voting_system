<?php
	$pass = "pass";				// password used to login on the results page
	$lang = "en";				// "en" or "nl" (add your own language in assets/language.php)

	// Advanced options:
	$folder = 'round';			// the name of the active voting round
	$separator = ";";			// a non-numeric character used to separate votes
	$resultslink = true;		// show link to results after voting
	$livetoggle = false;		// show pauze live refresh button on results page
	$refreshinterval= 1500;		// the interval at which clients check for new results
	$seo = true;				// seo makes the use of ".php" in url's optional (you need to load "mod_rewrite" in apache)
?>