<?php

// "en" or "nl" (add your own language in assets/language.php)
$lang = "en";

// the name of the active voting round
$folder = "active";

// a non-numeric character used to separate votes
$separator = ";";

// default graph view on results page (either "bar" or "pie")
$defaultgraph = "bar";

// the interval at which clients check for new results
$refreshinterval= 1500;

// show link to results after voting
$resultslink = true;

// One vote per user
$onevote = true;

// immediatly start loading live results
$live = true;

// show pauze live refresh button on results page
$livetoggle = false;

// automaticly check on vote page if next question is active
$reloadactive = true;

// show a list with your own votes at the end of voting
$hideownvotes = false;

// seo makes the use of ".php" in url's optional (you need to load "mod_rewrite" in apache)
$seo = true;

// enable ability to remove previous rounds
$removerounds = true;


require('options.php');
require('assets/language.php');

?>