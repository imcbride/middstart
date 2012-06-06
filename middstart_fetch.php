<?php

/*

Place this script in a non-web accessible directory and run nightly against the CyberSource TransactionDetailReport. In order for this to work, you need to set up a new table in your WordPress database. You can name this table anything you want, but make sure that the same name is set as the value of DONORS_TABLE in functions.php. Here is the table schema.

CREATE TABLE IF NOT EXISTS `middstart_donations` (
  `id` mediumint(9) NOT NULL auto_increment,
  `date` datetime NOT NULL,
  `form` int(11) NOT NULL,
  `first` varchar(255) NOT NULL,
  `last` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

*/

$acct = 'ACCOUNT_NAME';       // A CyberSource account name
$user = 'USER_NAME';          // A CyberSource user
$pass = 'PASSWORD';           // Password for the CyberSource user
$host = 'HOST';               // The hostname of your WordPress database
$database = 'DATABASE';       // The database name of your WordPress database
$db_user = 'DATABASE_USER';   // A user with INSERT access on your WordPress database
$db_pass = 'DATABASE_PASS';   // The password for the WordPress database account
$table = 'TABLE';             // The name of the table where donation information is kept

// A regular expression pattern used to extract the id of the donation form to separate out donations for specific projects.
$ref_pattern = '/MDR-([0-9]+)-/';

ini_set('display_errors', 'On');
date_default_timezone_set('America/New_York');

$year = date('Y', strtotime('yesterday'));
$month = date('m', strtotime('yesterday'));
$day = date('d', strtotime('yesterday'));

$path = "https://ebc.cybersource.com/ebc/DownloadReport/". $year . "/". $month ."/". $day ."/". $acct ."/TransactionDetailReport.xml";

$ch = curl_init($path);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $user .':'. $pass);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$ret = curl_exec($ch);

curl_close($ch);

$xml = new SimpleXMLElement($ret);

$link = mysql_connect($host, $db_user, $db_pass);
mysql_select_db($database, $link);

foreach($xml->Requests->Request as $r) {
  if ($r['Source'] != "SCMP API") continue;

  $amount = $r->PaymentData->Amount;

  $error = FALSE;
  foreach($r->ApplicationReplies as $reply) {
    if ($reply->ApplicationReply['Name'] == 'ics_auth' && $reply->ApplicationReply->RCode == 0) {
      $error = TRUE;
    }
  }

  $matches = array();
  preg_match($ref_pattern, $r['MerchantReferenceNumber'], $matches);

  if (!$error && $amount && isset($matches[1])) {
    $query = sprintf("INSERT INTO %s (date, form, first, last, amount) VALUES ('%s', %d, '%s', '%s', %d)",
      $table,
      mysql_real_escape_string(date("Y-m-d H:i:s", strtotime($r['RequestDate']))),
      mysql_real_escape_string($matches[1]),
      mysql_real_escape_string((string)$r->BillTo->FirstName),
      mysql_real_escape_string((string)$r->BillTo->LastName),
      mysql_real_escape_string((string)$amount));
      
      mysql_query($query);
  }
}

mysql_close($link);
?>
