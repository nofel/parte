<?php
require_once('dbconfig.php');
?>

<?php
/*
	Nofel Shanta
	Student ID: s3254869
	Web Database Application - Assignment 1
	Part C - Create Part B using Smarty Template
	Date 24/08/2012
*/

	  error_reporting(E_ALL);
	  define("USER_HOME_DIR", "/home/stud/s3254869");
	  require(USER_HOME_DIR . "/php/Smarty-2.6.26/Smarty.class.php");
	  $smarty = new Smarty();
	  $smarty->template_dir = USER_HOME_DIR . "/php/Smarty-Work-Dir/templates";
	  $smarty->compile_dir = USER_HOME_DIR . "/php/Smarty-Work-Dir/templates_c";
	  $smarty->cache_dir = USER_HOME_DIR . "/php/Smarty-Work-Dir/cache";
	  $smarty->config_dir = USER_HOME_DIR . "/php/Smarty-Work-Dir/configs";
?>

<?php
	  /* Populate the region dropdown */
	  $region = array();
	  $default = '';

	  // populate the list of regions from the database
	  $pdoobject = new PDO($dsn, DB_USER, DB_PW);
	  $pdoobject->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  $query = 'SELECT region_id, region_name FROM region';
	  $result = $pdoobject->query($query);
	  $i=0;
	  while ($row = $result->fetch(PDO::FETCH_OBJ))
	  {
		if($i==0)
		{ $default = $row->region_id; }
		$i = $i + 1;
		$region[$row->region_id] = $row->region_name;
	  }
	  $smarty->assign('regionOptions', $region);
	  $smarty->assign('regionSelect', $default);
?>


<?php
	  /* Populate the start year dropdown */
	  $yeararr = array();
	  $default = '';
	  // populate the list of regions from the database
	  $pdoobject = new PDO($dsn, DB_USER, DB_PW);
	  $pdoobject->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  $query = 'select distinct year from wine order by year asc';
	  $result = $pdoobject->query($query);
	  $i=0;
	  while ($row = $result->fetch(PDO::FETCH_OBJ))
	  {
		if($i==0)
		{ $default = $row->year; }
		$i = $i + 1;
		$yeararr[$row->year] = $row->year;
	  }
	  $smarty->assign('startyearOptions', $yeararr);
	  $smarty->assign('startyearSelect', $default);
?>

<?php
	  /* Populate the start year dropdown */
	  $yeararr = array();
	  $default = '';
	  // populate the list of regions from the database
	  $pdoobject = new PDO($dsn, DB_USER, DB_PW);
	  $pdoobject->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  $query = 'select distinct year from wine order by year asc';
	  $result = $pdoobject->query($query);
	  $i=0;
	  while ($row = $result->fetch(PDO::FETCH_OBJ))
	  {
		if($i==0)
		{ $default = $row->year; }
		$i = $i + 1;
		$yeararr[$row->year] = $row->year;
	  }
	  $smarty->assign('endyearOptions', $yeararr);
	  $smarty->assign('endyearSelect', $default);
?>

<?php
          $smarty->display('winery_template.tpl');
?>
