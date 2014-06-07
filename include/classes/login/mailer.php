<?php

/**
 * mailer.php
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
 * @category  Login
 * @package   Mailer
 * @author    Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 * @see       login.php
 */

/**
 * The Mailer class is meant to simplify the task of sending
 * emails to users. Note: this email system will not work
 * if your server is not setup to send mail.
 *
 * @category  Login
 * @package   Mailer
 * @author    Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 */
class Mailer {
	/**
	 * sendWelcome - Sends a welcome message to the newly
	 * registered user, also supplying the username and
	 * password.
	 *
	 * @param string $user username
	 * @param string $email email
	 * @param string $pass password
	 * @return boolean true if the mail has been successful sent
	 * @access public
	 */
	function sendWelcome($user, $email, $pass){
		$subject = "" . _("Welcome to OpenHomeopath!") . "";
		$body = $user.",\n\n"
				 ."" . _("Welcome!") . " " . _("You was just registered on OpenHomeopath:") . "\n\n"
				 ."" . _("Username:") . " ".$user."\n"
				 ."" . _("Password:") . " ".$pass."\n\n"
				 ."" . _("Wenn you forget your password we will send a new one to this e-mail.") . "\n"
				 ."" . _("In section 'settings' you can change the e-mail.") . "\n\n"
				 ."- " . _("The OpenHomeopath-Team") . " ;-)";

		return $this->send_mail($email, $body, $subject);
	}

	/**
	 * sendWelcomePass - Sends a welcome message to the newly
	 * registered user, sending a generated password
	 * and supplying the username.
	 *
	 * @param string $user username
	 * @param string $email email
	 * @param string $pass password
	 * @return boolean true if the mail has been successful sent
	 * @access public
	 */
	function sendWelcomePass($user, $email, $pass){
		$subject = "" . _("Welcome to OpenHomeopath!") . "";
		$body = $user.",\n\n"
				 ."" . _("Welcome!") . " " . _("You was just registered on OpenHomeopath with username") . " $user.\n\n"
				 ."" . _("Your password:") . " ".$pass."\n\n"
				 ."" . _("You can use this password along with your username to login on OpenHomeopath.") . "\n\n"
				 ."" . _("In section 'settings' you can change the password.") . "\n\n"
				 ."" . _("Wenn you forget your password we will send a new one to this e-mail.") . "\n"
				 ."" . _("In section 'settings' you can change the e-mail.") . "\n\n"
				 ."- " . _("The OpenHomeopath-Team") . " ;-)";

		return $this->send_mail($email, $body, $subject);
	}

	/**
	 * sendNewPass - Sends the newly generated password
	 * to the user's email address that was specified at
	 * sign-up.
	 *
	 * @param string $user username
	 * @param string $email email
	 * @param string $pass password
	 * @return boolean true if the mail has been successful sent
	 * @access public
	 */
	function sendNewPass($user, $email, $pass){
		$subject = "" . _("OpenHomeopath - Your new password") . "";
		$body = $user.",\n\n"
				 ."" . _("At your request, we've created a new password for you.") . "\n"
				 ."" . _("You can use this password along with your username to login on OpenHomeopath.") . "\n\n"
				 ."" . _("Username:") . " ".$user."\n"
				 ."" . _("New Password:") . " ".$pass."\n\n"
				 ."" . _("In section 'settings' you can change the password.") . "\n\n"
				 ."- " . _("The OpenHomeopath-Team") . " ;-)";

		return $this->send_mail($email, $body, $subject);
	}

	/**
	 * mail_header_escape - encode the header with 'quoted-printable',
	 * so that also non US-ASCII letters can be transmitted.
	 *
	 * @param string $header email header
	 * @return string the encoded header
	 * @access public
	 */
	function mail_header_escape ($header) {
		if (preg_match('/[^a-z0-9 _-]/i', $header)) {
			$header = preg_replace('/([^a-z0-9 ])/ie', 'sprintf("=%02x", ord(stripslashes("$1")))', $header);
			$header = str_replace(' ', '_', $header);
			return "=?utf-8?Q?$header?=";
		}
		return $header;
	}

	/**
	 * send_mail - Send the email with mail().
	 *
	 * @param string $to receiver
	 * @param string $body email body
	 * @param string $subject email subject
	 * @return boolean true if the mail has been successful sent
	 * @access public
	 */
	function send_mail($to, $body, $subject) {
		$header = "From: \"".$this->mail_header_escape(EMAIL_FROM_NAME)."\" <".EMAIL_FROM_ADDR.">\r\n";
		$header .= "Reply-To: ".EMAIL_FROM_ADDR."\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: text/plain; charset=\"".MAIL_ENCODING."\"\r\n";
		$header .= "Content-Transfer-Encoding: 7bit\r\n";
		if (mail($to, $this->mail_header_escape($subject), $body, $header)) {
			return true;
		} else {
			return false;
		}
	}
};

/* Initialize mailer object */
$mailer = new Mailer;
