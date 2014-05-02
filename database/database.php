<?php

$host = "localhost";
$user = "chrbil90_456";
$password = "results";
$db = "chrbil90_456";

$sql_connection = mysql_connect($host, $user, $password) or die('Error connecting to database');
$sql_db = mysql_select_db($db, $sql_connection) or die('Error selecting databse');

?>