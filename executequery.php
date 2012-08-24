<?php
error_reporting(E_ALL);
	
// include the database configuration
require_once('dbconfig.php');

define("USER_HOME_DIR", "/home/stud/s3254869");
require(USER_HOME_DIR . "/php/Smarty-2.6.26/Smarty.class.php");
$smarty = new Smarty();
$smarty->template_dir = USER_HOME_DIR . "/php/Smarty-Work-Dir/templates";
$smarty->compile_dir = USER_HOME_DIR . "/php/Smarty-Work-Dir/templates_c";
$smarty->cache_dir = USER_HOME_DIR . "/php/Smarty-Work-Dir/cache";
$smarty->config_dir = USER_HOME_DIR . "/php/Smarty-Work-Dir/configs";

// if the connection can not be established, then show error
if (!($connection = @ mysql_connect(DB_HOST . ":" . DB_PORT, DB_USER, DB_PW)))
{
	display_error();
}

// if database can not be selected, then show error
if (!mysql_select_db('winestore', $connection))
{
	display_error();
}

// get all required inputs from the form
$region = clean_sql($_GET, "region", 4, $connection);
$startyear = clean_sql($_GET, "startyear", 4, $connection);
$mincost = clean_sql($_GET, "mincost", 50, $connection);
$maxcost = clean_sql($_GET, "maxcost", 50, $connection);
$wine = clean_sql($_GET, "wine", 50, $connection);
$winery = clean_sql($_GET, "winery", 100, $connection);
$endyear = clean_sql($_GET, "endyear", 4, $connection);
$stocknum = clean_sql($_GET, "stocknum", 5, $connection);

$errordisplay ='';

// perform some basic validations to ensure that at least some records will be returned to the user

// validation 1 - start year must be after the end year
if($startyear>$endyear)
{
	$errordisplay.="Start year must be same as or before the end year";
	$errordisplay.="<br/>";
}

// make sure the stock number is a valid number if it has been supplied
if($stocknum!='')
{
	if(!is_numeric($stocknum))
	{
		$errordisplay.="The value entered for minimum stock is not valid";
		$errordisplay.="<br/>";
	}
}

if($mincost!='')
{
	if(!is_numeric($mincost))
	{
		$errordisplay.="The value entered for the minimum cost is not valid";
		$errordisplay.="<br/>";
	}
}

if($maxcost!='')
{
	if(!is_numeric($maxcost))
	{
		$errordisplay.="The value for maximum cost entered is not valid";
		$errordisplay.="<br/>";
	}
}

?>

<!DOCTYPE HTML PUBLIC
		"-//W3C//DTD HTML 4.01 Transitional//EN"
		 "http://www.w3.org/TR/html401/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Wine Search (s3254869)</title>
</head>
<body style="background-color:#FFFFCC">
<h2 align="center">Search Results</h2>

<?php
if($errordisplay!='')
{
	$str1= "<font color='red'><strong>".$errordisplay."</strong></font>";
	$str1.="<br/><br/>";
	$str1.="There are errors in what you have submitted. Please enter search criteria again.";
	$str1.="<br/>";
	$str1.="<a href='javascript:history.back()'>Go back to fix search criteria.</a><br/>";
	$smarty->assign('errorresult', $str1);
}

?>

<?php

// create a PDO connection
$pdo_variable = new PDO($dsn, DB_USER, DB_PW);
$pdo_variable->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// create a query that will return the appropriate search results
$sql = "SELECT w.wine_name, gv.variety, w.year, wi.winery_name, r.region_name, inv.cost
		 FROM wine w, winery wi, region r, inventory inv, grape_variety gv, wine_variety wv
		 WHERE w.winery_id = wi.winery_id
		 AND wi.region_id = r.region_id
		 AND w.wine_id = wv.wine_id
		 AND w.wine_id = inv.wine_id
		 AND gv.variety_id = wv.variety_id";

// add the search criteria into the qery
$sql = $sql . " AND w.wine_name like ?";
$sql = $sql . " AND wi.winery_name like ?";
if($region!='1')
{ $sql = $sql . " AND r.region_id = ?"; }
$sql = $sql . " AND w.year >= ? and w.year<=?";

// create a values array for the remaining of the
if($region!='1')
{ $values = 
array("%".$wine."%","%".$winery."%",$region,$startyear,$endyear); }
else
{ $values = array("%".$wine."%","%".$winery."%",$startyear,$endyear); }


// optional values of stock number, minimum cost and maximum cost are to be inserted now
if($stocknum!='')
{
	$sql = $sql . " AND inv.on_hand>=?";
	array_push($values,$stocknum);
}

if($mincost!='')
{
	$sql = $sql . " AND inv.cost>=?";
	array_push($values,$mincost);
}

if($maxcost!='')
{
	$sql = $sql . " AND inv.cost<=?";
	array_push($values,$maxcost);
}

// execute the query and fetch the records that are needed
$stmt = $pdo_variable->prepare($sql);
$stmt->execute($values);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$numrecords = count($rows);

if($numrecords==0)
{
	$str="<br/><font color='red' size='5'>No records match your search criteria</font>";
	$str.="<br/><br/><a href='javascript:history.back()'>Go back to try again</a><br/>";
}
else
{ 
	$str="<br/>".$numrecords." records match your search criteria<br/><br/>"; 
}

	// execute the statement
	$stmt = $pdo_variable->prepare($sql);
	$stmt->execute($values);
	$i=0;
	$arr=array();
	$sessionarr=$_SESSION['sessionarr'];
	if(!isset($sessionarr))
	{		
		$sessionarr=array();
	}
	while ($row = $stmt->fetch(PDO::FETCH_OBJ))
	{
                array_push($sessionarr,$row->wine_name);
		$arr[$i]['wine_name']=$row->wine_name;
		$arr[$i]['variety']=$row->variety;
		$arr[$i]['year']=$row->year;
		$arr[$i]['winery_name']=$row->winery_name;
		$arr[$i]['region_name']=$row->region_name;
		$arr[$i]['cost']=$row->cost;
		$i=$i+1;
	}

	echo "</table>";

	$smarty->assign('searchresult', $str);
	$smarty->assign('records', $arr);
        $smarty->display('result_template.tpl');
?>
