<?php

/**
 * process.php
 *
 * If process.php is directly called by a logged in user he will be logged out.
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
 * @package   Process
 * @author    Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 * @see       login.php
 */

chdir("../../..");
include("include/classes/login/session.php");

/**
 * The Process class is meant to simplify the task of processing
 * user submitted forms, redirecting the user to the correct
 * pages if errors are found, or if form is successful, either
 * way. Also handles the logout procedure.
 *
 * @category  Login
 * @package   Process
 * @author    Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 */
class  Process {

	/**
	*
	* @return void
	* @access public
	*/
	function __construct(){
		global $session;
		/* User submitted login form */
		if(isset($_POST['sublogin'])){
			$this->procLogin();
		}
		/* User submitted registration form */
		elseif(isset($_POST['subjoin'])){
			$this->procRegister();
		}
		/* User submitted forgot password form */
		elseif(isset($_POST['subforgot'])){
			$this->procForgotPass();
		}
		/* User submitted edit account form */
		elseif(isset($_POST['subedit'])){
			$this->procEditAccount();
		}
		/**
		 * The only other reason user should be directed here
		 * is if he wants to logout, which means user is
		 * logged in currently.
		 */
		elseif($session->logged_in){
			$this->procLogout();
		}
		/**
		 * Should not get here, which means user is viewing this page
		 * by mistake and therefore is redirected.
		 */
		else{
			$host  = $_SERVER['HTTP_HOST'];
			$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			$extra = "login.php";
			header("Content-Type: text/html;charset=utf-8"); 
			header("Location: ../../../$extra");
			die();
		}
	}

	/**
	 * procLogin processes the user submitted login form, if errors
	 * are found, the user is redirected to correct the information,
	 * if not, the user is effectively logged in to the system.
	 *
	 * @return void
	 * @access private
	 */
	private function procLogin(){
		global $session, $form, $db;
		/* Login attempt */
		$retval = $session->login($_POST['user'], $_POST['pass'], isset($_POST['remember']));

		/* Login successful */
		if ($retval) {
			if ($session->isAdmin()) {
				$db->update_symptom_tables();
			}
			$db->update_custom_symptom_table();
			if(isset($_POST['url'])) {
				$url   = $_POST['url'];
				if ($url == "userinfo.php") {
					$user = $_POST['user'];
					$url .= "?user=$user";
				} elseif (strpos($url, '%') !== false) {
					list($prefix, $url) = explode('%', $url, 2);
					if ($prefix === 'admin') {
						$url = "admin_tools/$url";
					}
				}
				header("Content-Type: text/html;charset=utf-8"); 
				header("Location: ../../../$url");
				die();
			} else {
				$headers = apache_request_headers();
				if (!empty($headers['Referer']) && strpos($headers['Referer'],'login.php') === false) {
					header("Content-Type: text/html;charset=utf-8");
					header("Location: " . $headers['Referer']); /* Redirect browser back to referer */
					exit;
				} elseif (!empty($session->referrer) && strpos($session->referrer,'login.php') === false){
					header("Content-Type: text/html;charset=utf-8"); 
					header("Location: ".$session->referrer);
					exit;
				} else {
					header("Content-Type: text/html;charset=utf-8"); 
					header("Location: ../../../index.php");
				}
			}
		}
		/* Login failed */
		else {
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Content-Type: text/html;charset=utf-8"); 
			header("Location: ".$session->referrer);
			die();
		}
	}

	/**
	 *  procLogout - Simply attempts to log the user out of the system
	 *  given that there is no logout form to process.
	 *
	 *  @return void
	 *  @access private
	 */
	private function procLogout(){
		global $session;
		$retval = $session->logout();
		header("Location: ../../../login.php");
	}

	/**
	 *  procRegister - Processes the user submitted registration form,
	 *  if errors are found, the user is redirected to correct the
	 *  information, if not, the user is effectively registered with
	 *  the system and an email is (optionally) sent to the newly
	 *  created user.
	 *
	 *  @return void
	 *  @access private
	 */
	private function procRegister(){
		global $session, $form;
		/* Convert username to all lowercase (by option) */
		if(ALL_LOWERCASE){
			$_POST['user'] = strtolower($_POST['user']);
		}
		/* Registration attempt */
		$retval = $session->register($_POST['user'], $_POST['pass'], $_POST['pass2'], $_POST['email']);

		/* Registration Successful */
		if($retval == 0){
			$_SESSION['reguname'] = $_POST['user'];
			$_SESSION['regemail'] = $_POST['email'];
			$_SESSION['regsuccess'] = true;
			header("Content-Type: text/html;charset=utf-8"); 
			header("Location: ".$session->referrer);
			die();
		}
		/* Error found with form */
		elseif($retval == 1){
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Content-Type: text/html;charset=utf-8"); 
			header("Location: ".$session->referrer);
			die();
		}
		/* Registration attempt failed */
		elseif($retval == 2){
			$_SESSION['reguname'] = $_POST['user'];
			 $_SESSION['regsuccess'] = false;
			header("Content-Type: text/html;charset=utf-8"); 
			header("Location: ".$session->referrer);
			die();
		}
	}

	/**
	 *  procForgotPass - Validates the given username then if
	 *  everything is fine, a new password is generated and
	 *  emailed to the address the user gave on sign up.
	 *
	 *  @return void
	 *  @access private
	 */
	private function procForgotPass(){
		global $db, $session, $mailer, $form;
		/* Username error checking */
		$subuser = $_POST['lostpass'];
		$field = "lostpass";  //Use field name for username
		if(!$subuser || strlen($subuser = trim($subuser)) == 0){
			$form->setError($field, " " . _("* Username not entered") . "<br>");
		}
		else{
			/* Make sure username is in database */
			$subuser = stripslashes($subuser);
			if(strlen($subuser) < 5 || strlen($subuser) > 30 || !preg_match("/^([0-9a-z])+$/i", $subuser) || (!$db->usernameTaken($subuser))){
				$form->setError($field, " " . _("* Username does not exist") . "<br>");
			}
		}

		/* Errors exist, have user correct them */
		if($form->num_errors > 0){
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
		}
		/* Generate new password and email it to user */
		else{
			/* Generate new password */
			$newpass = $session->generateRandStr(8);

			/* Get email of user */
			$usrinf = $db->getUserInfo($subuser, 'email_registered, userlevel, id_user');
			$email  = $usrinf[0];
			$userlevel  = $usrinf[1];

			/* Attempt to send the email with new password */
			if($mailer->sendNewPass($subuser,$email,$newpass)){
				/* Email sent, update database */
				$db->updateUserField($subuser, "password", md5($newpass));
				$_SESSION['forgotpass'] = true;
			}
			/* Email failure, do not change password */
			else{
				$_SESSION['forgotpass'] = false;
			}
		}

		header("Content-Type: text/html;charset=utf-8"); 
		header("Location: ".$session->referrer);
		die();
	}

	/**
	 *  procEditAccount - Attempts to edit the user's account
	 *  information, including the password, which must be verified
	 *  before a change is made.
	 *
	 *  @return void
	 *  @access private
	 */
	private function procEditAccount(){
		global $session, $form;
		$show_active = (!empty($_POST['show_active'])) ? $_POST['show_active'] : "";
		$hide_email = (!empty($_POST['hide_email'])) ? $_POST['hide_email'] : "";
		/* Account edit attempt */
		$retval = $session->editAccount($_POST['curpass'], $_POST['newpass'], $_POST['newpass2'], $_POST['email'], $_POST['real_name'], $_POST['extra'], $show_active, $hide_email, $_POST['skin'], $_POST['lang'], $_POST['sym_lang']);

		/* Account edit successful */
		if($retval){
			$_SESSION['useredit'] = true;
			header("Content-Type: text/html;charset=utf-8"); 
			header("Location: ".$session->referrer);
			die();
		}
		/* Error found with form */
		else{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Content-Type: text/html;charset=utf-8"); 
			header("Location: ".$session->referrer);
			die();
		}
	}
};

/* Initialize process */
$process = new Process;

?>
