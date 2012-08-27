<?php
session_start();
error_reporting(E_ERROR);
?>

<html>
<body>

Wines that have been returned in search results so far are..

<?php
	$sessionarr=$_SESSION['sessionarr'];
	$i=0;
	foreach ($sessionarr as $value)
    	{
		echo $value."<br/>";
		$i=$i+1;
	}

	if($i==0)
	{
		echo "<br/>There are no wines in session yet";
	}
?>

<br/><br/>
<a href='queryform.php'>Back to query form</a>
<br/><br/>
</body>
</html>
