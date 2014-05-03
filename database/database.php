<?php

$host = "localhost";
$user = "chrbil90_456";
$password = "results";
$db = "chrbil90_456";
/*
$host = "mydb.itap.purdue.edu";
$user = "bill0";
$password = "80970013";
$db = "bill0";
*/

$host = "localhost";
$user = "root";
$password = "";
$db = "cgt456";

$sql_connection = mysql_connect($host, $user, $password); //or die('Error connecting to database');
$sql_db = mysql_select_db($db, $sql_connection); //or die('Error selecting databse');

?>