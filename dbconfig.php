<?php

/*
	Nofel Shanta
	Student ID: s3254869
	Web Database Application - Assignment 1
	Part D - Develop a two component query module.
*/

define('DB_HOST', 'yallara.cs.rmit.edu.au');
define('DB_PORT', '54335');
define('DB_NAME', 'winestore');
define('DB_USER', 'root');
define('DB_PW',   '4getmenot');

$dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;

// Function to show an error to the user
function display_error()
{
  die("An error has occurred" . mysql_errno() . " : " . mysql_error());
}

// Function to clean an array
// This is the security feature - to get rid of all special character (convert to escape sequence)
function clean_sql($array, $index, $maxlength, $connection)
{
  if (isset($array["{$index}"]))
  {
     $input = substr($array["{$index}"], 0, $maxlength);
     $input = mysql_real_escape_string($input, $connection);
     return ($input);
  }
  return NULL;
}
