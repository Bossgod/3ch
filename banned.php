<?php
session_start();

include("config.php");

$con = mysql_connect(SQLHOST, SQLUSER, SQLPASS);

if (!$con) {
    echo S_SQLCONF; //unable to connect to DB (wrong user/pass?)
    exit;
}

$db_id = mysql_select_db(SQLDB, $con);
if (!$db_id) {
    echo S_SQLDBSF;
}

$host = $_SERVER['REMOTE_ADDR'];
$footer = '<div class="footer"><h2><center>[<a href="' . PHP_SELF . '"/>Return</a>]</center></h2></div></div></body></html>';


//Check if user is banned
$query = mysql_query("SELECT * FROM " . SQLBANLOG . " WHERE ip='$host' ");
while ($row = mysql_fetch_array($query)) {
    global $ip, $pubreason, $banlength, $placedOn;
	$ip = $row["ip"];
	$pubreason = $row["pubreason"];
	$banlength = $row["banlength"];
	$placedOn = $row["placedOn"];
	    
}


/* FINAL TODO: Bans remove themselves after time is served
function amend_ban 
{
	hahahahhhaha
}
*/

if ($ip == $host) {
	switch ($banlength) {
		case '100':
			$status = 'have been warned';
			$type = 1;
			mysql_query("DELETE FROM " . SQLBANLOG . " WHERE ip='$host'");
			break;
		case '1':
			$status = 'have been banned';
			$blength = '<b>3 hours</b>';
			$type = 2;
			break;
		case '2':
			$status = 'have been banned';
			$blength = '<b>3 days</b>';
			$type = 2;
			break;
		case '3':
			$status = 'have been banned';
			$blength = '<b>1 week</b>';
			$type = 2;
			break;
		case '4':
			$status = 'have been banned';
			$blength = '<b>1 month</b>';
			$type = 2;
			break;
		case '-1':
			$status = 'have been permanently banned';
			$blength = '<b>forever</b>';
			$type = 2;
			break;
	}
} else {
	$type = 0;
	$status = 'are not banned';
}

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>You ' . $status . '</title>
<link href="css/banned.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="container">
<div class="header"></a><h1>You <b>' . $status . '.</b></h1></div>';

	
if ($type == 0) {
	//not banned, display the footer, hope the user goes away and doesn't try to talk to me
	echo '<p>You are not banned from posting on board: <b>/' . BOARD_DIR . '/ - ' . TITLE . '</b>' . $footer;
} else if ($type == 1) {	
	echo '<p>You <b>' . $status . '</b> on the board: <b>/' . BOARD_DIR . '/ - ' . TITLE . '</b> for the following reason: </p><br /><p><b>' . $pubreason . '</b></p><br /><hr />
				<p>Please be aware that further rule violations may result in a harsher ban sentence after viewing this warning.</p><br />
				<h3>This warn was issued for the IP address ' . $ip . '</h3>' . $footer;
} else {
	echo '<p>You <b>' . $status . '</b> from posting on board: <b>/' . BOARD_DIR . '/ - ' . TITLE . '</b> for the following reason: </p><br /><p><b>' . $pubreason . '</b></p><br /><hr />
				<p>This ban will last ' . $blength . '. It was placed on <b>' . $placedOn . '</b><br/><h3>This ban was issued for the IP address ' . $ip . '</h3>' . $footer;// and will remain in effect until ' . $expiresOn . '</p>
				
}

?>