
<?php
	session_start();
	/* 
	 * Verification: Verify whether the user is accessing the page for the first time.
	 * Values back to JS:
	 * 1 - can play the game
	 * 2 - not WeChat
	 * 3 - has played for 3 times
	 * 4 - internal error (cannot connect to db)
	 */
	$canPlay = "canPlay";
	$notWeChat = "notWeChat";
	$playedThreeTimes = "playedThreeTimes";
	$cannotConnectDb = "cannotConnectDb";

	$wgateid = mysql_escape_string($_GET["wgateid"]);
	$verify = mysql_escape_string($_GET["verify"]);
	//echo "wgateid=" . $wgateid . "<br />";
	//echo "verify=" . $verify . "<br />";

	/* Verify whether the client is WeChat */
	$wgVerifyUrl = "http://www.weixingate.com/verify.php?wgateid=" . $wgateid . "&verify=" . $verify;
	$isWeChat = file_get_contents($wgVerifyUrl);
	// echo "wechat? " . $isWeChat . "<br />";
	if (strcmp($isWeChat, "true")) {
		echo $notWeChat;
		exit(1);
	}

	$dbConfigFileName = "db_config.php";
	require $dbConfigFileName;
	if (!$dbConnection) {
		echo $cannotConnectDb;
		mysql_close($dbConnection);
		exit(1);
	}
	
	$dbName = "activity_prize";
	/* Query times of playing */
	$queryPlayTimes = "SELECT COUNT(1) AS playtimes FROM personinfo WHERE wgateid='" . $wgateid . "'";
	$resultPlayTimesName = "playtimes";
	mysql_select_db($dbName);
	$result = mysql_query($queryPlayTimes, $dbConnection);
	$playTimes = mysql_result($result, 0);
	mysql_close($dbConnection);
	
	/* Judge whether the player can still play the game for one more time */
	if ($playTimes >= 3) {
		// echo "Cannot play for more. You have played for " . $playTimes . " times already.";
		echo $playedThreeTimes;
		exit(1);
	}

	/* Generate prize */
	$rand = mt_rand(1, 100); // no need to mt_srand() after php 4.2
	$prize = 0;
	if ($rand >= 1 && $rand <= 80) { // $50 class-5 prize 80%
		$prize = "5";
	} else if ($rand > 80 && $rand <= 90) { // $100 class-4 prize 10%
		$prize = "4";
	} else if ($rand > 91 && $rand <= 96) { // $150 class-3 prize 6%
		$prize = "3";
	} else if ($rand > 96 && $rand <= 99) { // $200 class-2 prize 3%
		$prize = "2";
	} else if ($rand == 100) { // $250 class-1 prize 1%
		$prize = "1";
	}

	// echo "Play now: is there an 1? ";
	// echo $canPlay;
	/* Use SESSION to pass to other php pages*/
	//$_SESSION["canplay"] = $canPlay;

	$_SESSION["wgateid"] = $wgateid;
	$_SESSION["activityid"] = "1"; // the first activity in db
	$_SESSION["prize"] = $prize;

	echo $prize;
	//echo "Click <a href='prize_gen.php'>here</a> to prize_gen.php<br />";

	// mysql_close($dbConnection);
	/* Use GET to pass the params to another php page
	$getSendToPrizeGen = "prize_gen.php?canplay=" . $canPlay . "&wgateid=" . $wgateid;
	$isSendOk = file_get_contents($getSendToPrizeGen);
	if (strcmp($isSendOk, "true") {
		echo "Cannot link between verify_user.php and prize_gen.php" . "<br />";
		
	}
	*/

	// echo "You have played " . $playTimes . ", now you can go on playing.";

	/* Test code for inserting data before info.php is completed */
	//$queryTestInsert = "INSERT INTO personinfo (personname, mobile, email, qqnumber, wgateid, create_at) VALUES ('test', '1234567890', 'example@test.com', '123456789', '" . $wgateid . "', now())";
	//mysql_query($queryTestInsert);

	/**/
?>