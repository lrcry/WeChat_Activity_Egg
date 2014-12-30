
<?php
	/* To save the information of players into database */
	session_start();

	/* Get the data first from the form via POST */
	// name, phone, mailaddr, qq, wgid. prize info
	$personname = mysql_escape_string($_POST["name"]);
	$mobile = mysql_escape_string($_POST["phone"]);
	$email = mysql_escape_string($_POST["mailAddr"]);
	$qqnumber = mysql_escape_string($_POST["qq"]);
	$options = mysql_escape_string($_POST["interest"]);
	$wgateid = $_SESSION["wgateid"]; //$_POST["wgid"];

	$rand = mt_rand(100, 999);

	$vouchercode = time() . $rand;
	$activityid = $_SESSION["activityid"]; //$_POST["voucherSerial"];
	$prize = $_SESSION["prize"]; //$_POST["prizetype"]; // 1=$50, 2=$100, 3=$150, 4=$200, 5=$250

	/* Check the validity of data */
	// TODO

	$dbConfigFileName = "db_config.php";
	require $dbConfigFileName;
	if (!$dbConnection) {
		echo "connError";
		mysql_close($dbConnection);
		// die ("Could not connect to database: " . mysql_error());
		exit(1);
	}

	/* Save the data: person information, prize information */
	$dbName = "activity_prize";
	mysql_select_db($dbName);
	$insertPersonInfo = "INSERT INTO personinfo (personname, mobile, email, qqnumber, wgateid, create_at, vouchercode, voucherserial, prizedetails, options, prizestartdate, prizeenddate) " 
		. "VALUES ('"
		. $personname . "', '" . $mobile . "', '" . $email . "', '" . $qqnumber . "', '" . $wgateid . "', now(), '" . $vouchercode . "', " . $activityid . ", " . $prize . ", '" . $options . "', date(now()), ";

	$queryPrizeExpiredTime = "SELECT vouchervalidtime, voucherexpiredate FROM activity WHERE activityid=" . $activityid;
	$queryResult = mysql_query($queryPrizeExpiredTime, $dbConnection);
	$prizeValidDays = mysql_result($queryResult, 0, 0);
	$prizeExpiredTime = mysql_result($queryResult, 0, 1);
	//echo "prizeValidDays=" . $prizeValidDays . ", prizeExpiredTime=" . $prizeExpiredTime . "<br />";
	if ($prizeValidDays == -1) { // voucherexpiredate is needed
		$insertPersonInfo = $insertPersonInfo . "date('" . $prizeExpiredTime . "'))";
	} else if ($prizeValidDays == 0) { // valid for ever
		$insertPersonInfo = $insertPersonInfo . "'')"; 
	} else { // days > 0
		$insertPersonInfo = $insertPersonInfo . "date(now()) + " . $prizeValidDays . ")";
	}

	//echo $queryPrizeExpiredTime . "<br />";
	//echo $insertPersonInfo . "<br />";

	$isInsertSucceessful = mysql_query($insertPersonInfo, $dbConnection);
	
	// echo $isInsertSucceessful;
	echo $vouchercode;
	mysql_close($dbConnection);

	session_destroy();
	/* */
?>