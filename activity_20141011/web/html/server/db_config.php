
<?php
	/* Database configuration to get a connection */
	$dbUrl = "localhost:3306";
	$dbUser = "xiaoy";
	$dbPasswd = "h341qFh83290Gy8t";
	// $slash = "/";
	// $dbName = "zjwdb_233159";

	// $dbAddress = $dbUrl . $slash . $dbName;

	$dbConnection = mysql_connect($dbUrl, $dbUser, $dbPasswd);
?>