<?php

/**
 * ipn_listener.php
 *
 * This handler listens to PayPals IPN and store the donation data in the magic_hat table.
 *
 * PHP version 5
 *
 * LICENSE: This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Donated
 * @package   Donations
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

// CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
// Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
// Set this to 0 once you go live or don't require logging.
define("DEBUG", 0);

// Set to 0 once you're ready to go live
define("USE_SANDBOX", 0);


define("LOG_FILE", "ipn.log");

// Read POST data
// reading posted data directly from $_POST causes serialization
// issues with array data in POST. Reading raw POST data from input stream instead.
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
	$keyval = explode ('=', $keyval);
	if (count($keyval) == 2)
		$myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
	$get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
	if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
		$value = urlencode(stripslashes($value));
	} else {
		$value = urlencode($value);
	}
	$req .= "&$key=$value";
}

// Post IPN data back to PayPal to validate the IPN data is genuine
// Without this step anyone can fake IPN data

if(USE_SANDBOX == true) {
	$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} else {
	$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
}

$ch = curl_init($paypal_url);
if ($ch == FALSE) {
	return FALSE;
}

curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

if(DEBUG == true) {
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
}

// CONFIG: Optional proxy configuration
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);

// Set TCP timeout to 30 seconds
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
// of the certificate as shown below. Ensure the file is readable by the webserver.
// This is mandatory for some environments.

$cert = __DIR__ . "/cacert.pem";
curl_setopt($ch, CURLOPT_CAINFO, $cert);

$res = curl_exec($ch);
if (curl_errno($ch) != 0) {  // cURL error
	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message:" . PHP_EOL . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
	}
	curl_close($ch);
	exit;

} else {
		// Log the entire HTTP response if debug is switched on.
		if(DEBUG == true) {
			error_log(date('[Y-m-d H:i e] ') . "HTTP request of validation request:" . PHP_EOL . curl_getinfo($ch, CURLINFO_HEADER_OUT) ."for IPN payload:" . PHP_EOL . $req . PHP_EOL, 3, LOG_FILE);
			error_log(PHP_EOL . date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);

			// Split response headers and payload
			list($headers, $res) = explode("\r\n\r\n", $res, 2);
		}
		curl_close($ch);
}

// Inspect IPN validation result and act accordingly

if (strcmp ($res, "VERIFIED") == 0) {
	// assign posted variables to local variables
	$payment_status = $_POST['payment_status'];
	$receiver_email = $_POST['receiver_email'];
	$txn_type = $_POST['txn_type'];
	$txn_id = $_POST['txn_id'];
	$payment_amount = $_POST['mc_gross'];
	$payment_currency = $_POST['mc_currency'];
	$payer_email = $_POST['payer_email'];
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	if ($payment_status === 'Completed' && $receiver_email === 'henri.hulski@gazeta.pl' && ($txn_type === 'web_accept' || $txn_type === 'recurring_payment' || $txn_type === 'subscr_payment')) {
		chdir("..");
		include_once ("include/classes/login/session.php");
		$query = "SELECT txn_id FROM magic_hat WHERE txn_id = '$txn_id' LIMIT 1";
		$db->send_query($query);
		$num = $db->db_num_rows();
		$db->free_result();
		if ($num == 0) {
			if ($txn_type === 'web_accept') {
				$type = 'paypal';
			} else {
				$type = 'paypal_abo';
			}
			$query = "SELECT username FROM users WHERE email = '$payer_email' || email_registered = '$payer_email' ORDER BY last_activity DESC LIMIT 1";
			$db->send_query($query);
			list ($username) = $db->db_fetch_row();
			$db->free_result();
			$query = "INSERT INTO magic_hat(username, email, first_name, last_name, type, currency, amount, date, txn_id, txn_type) VALUES ('$username', '$payer_email', '$first_name', '$last_name', '$type', '$payment_currency', $payment_amount, CURDATE(), '$txn_id', '$txn_type')";
			$db->send_query($query);
		}
		chdir("handler");
		// Log the $query variable if debug is switched on.
		if(DEBUG == true) {
			error_log(PHP_EOL . 'MySQL-Query:' . PHP_EOL . print_r($query, true) . PHP_EOL, 3, LOG_FILE);
		}
	}
	if(DEBUG == true) {
		error_log(PHP_EOL . date('[Y-m-d H:i e] '). "Verified IPN:" . PHP_EOL . $req . PHP_EOL, 3, LOG_FILE);
	}
} elseif (strcmp ($res, "INVALID") == 0) {
	// log for manual investigation
	// Add business logic here which deals with invalid IPN messages
	if(DEBUG == true) {
		error_log(PHP_EOL . date('[Y-m-d H:i e] '). "Invalid IPN:" . PHP_EOL . $req . PHP_EOL, 3, LOG_FILE);
	}
}
