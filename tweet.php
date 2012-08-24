<?php
session_start();
error_reporting(E_ALL);
require_once('twitteroauth.php');

$consumerKey    = 'pcZM5FxBiS1ZjAiswdGA'; //'<insert your consumer key';
$consumerSecret = '3VWcFQY5vehzBtkA6O2CKxi5Q8urX44FECuGUxa9wM';//'<insert your consumer secret>';
$oAuthToken     = '759200563-v8N47NYs3l9OUd9lcIaLJDfvmu1SSDHpG19D5A5l'; //'<insert your access token>';
$oAuthSecret    = 'owRCQNZaKwm3h8yXidHNog3hKZfm1BlMIpefsM49JA'; //'<insert your token secret>';

// twitteroauth.php points to OAuth.php
// all files are in the same dir
// create a new instance
$tweet = new TwitterOAuth($consumerKey, $consumerSecret, $oAuthToken, $oAuthSecret);

$sessionarr=$_SESSION['sessionarr'];
$statusMessage='';
	$i=0;
	foreach ($sessionarr as $value)
    	{
		$statusMessage.=$value.", ";
		$i=$i+1;
	}

	if($i==0)
	{
		$statusMessage="<br/>There are no wines in session yet";
	}

$statusMessage=substr($statusMessage,0,100);

$tweet->post('statuses/update', array('status' => $statusMessage));

header('Location: queryform.php');
?>
