<?php

/**
 * session.php
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
 * @package   Session
 * @author    Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 * @see       login.php
 */

include("include/functions/common.php");
include_once("include/classes/login/database.php");
include_once("include/classes/login/mailer.php");
include_once("include/classes/login/form.php");

$tabbed = false;

/**
 * The Session class is meant to simplify the task of keeping
 * track of logged in users and also guests.
 *
 * @category  Login
 * @package   Session
 * @author    Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 */
class Session {

	/**
	 * Username given on sign-up
	 * @var string
	 * @access public
	 */
	public $username;


	/**
	 * email with which the user is registered
	 * @var string
	 * @access public
	 */
	public $email;


	/**
	 * Random value generated on current login
	 * @var string
	 * @access public
	 */
	public $userid;


	/**
	 * ID signed to user by auto_increment
	 * @var integer
	 * @access public
	 */
	public $id_user;


	/**
	 * The level to which the user pertains
	 * @var integer
	 * @access public
	 */
	public $userlevel;


	/**
	 * Is 1, when the user want to hide his email, otherwise 0
	 * @var integer
	 * @access public
	 */
	public $hide_email;


	/**
	 * True if user is logged in, false otherwise
	 * @var boolean
	 * @access public
	 */
	public $logged_in;


	/**
	 * The array holding all user info
	 * @var array
	 * @access public
	 */
	public $userinfo = array();


	/**
	 * The page url current being viewed
	 * @var string
	 * @access public
	 */
	public $url;


	/**
	 * Last recorded site page viewed
	 *
	 * Note: referrer should really only be considered the actual
	 * page referrer in process.php, any other time it may be
	 * inaccurate.
	 *
	 * @var string
	 * @access public
	 */
	public $referrer;

	/**
	 * skin the user uses
	 * @var string
	 * @access public
	 */
	public $skin = DEFAULT_SKIN;


	/**
	 * language the user uses
	 * @var string
	 * @access public
	 */
	public $lang = DEFAULT_LANGUAGE;

	/**
	 * Class constructor
	 *
	 * @return void
	 * @access public
	 */
	function __construct() {
		ini_set("date.timezone", "Europe/Berlin");
		$this->startSession();
		$current_dir = getcwd();
		$skin = $this->skin;
		require_once("skins/$skin/skin_config.php");
	}

	/**
	 * startSession performs all the actions necessary to
	 * initialize this session object. Tries to determine if the
	 * the user has logged in already, and sets the variables
	 * accordingly. Also takes advantage of this page load to
	 * update the active visitors tables.
	 *
	 * @return void
	 * @access public
	 */
	function startSession(){
		global $db;  //The database connection
		$current_dir = getcwd();
		session_start();   //Tell PHP to start the session

		$db = new UserDB;
		$db->db_connect();
		$db->get_num_visitors();

		/* Determine if user is logged in */
		$this->logged_in = $this->checkLogin();
		$this->setSkin();
		$this->setLanguage();


		/**
		 * Set guest value to users not logged in, and update
		 * active guests table accordingly.
		 */
		if (!$this->logged_in) {
			$this->username = $_SESSION['username'] = GUEST_NAME;
			$this->userlevel = GUEST_LEVEL;
			if (!bot_detected()) {
				$db->addActiveGuest($_SERVER['REMOTE_ADDR']);
			}
		}
		/* Update users last active timestamp */
		else {
			$db->addActiveUser($this->username);
		}

		/* Remove inactive visitors from database */
		$db->removeInactiveUsers();
		$db->removeInactiveGuests();

		/* Set referrer page */
		if (isset($_SESSION['url'])) {
			$this->referrer = $_SESSION['url'];
		} else {
			$this->referrer = "/";
		}

		/* Set current url */
		$this->url = $_SESSION['url'] = $_SERVER['PHP_SELF'];
	}

	/**
	 * checkLogin - Checks if the user has already previously
	 * logged in, and a session with the user has already been
	 * established. Also checks to see if user has been remembered.
	 * If so, the database is queried to make sure of the user's
	 * authenticity. Returns true if the user has logged in.
	 *
	 * @return void
	 * @access public
	 */
	function checkLogin(){
		global $db;  //The database connection
		/* Check if user has been remembered */
		if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])) {
			$this->username = $_SESSION['username'] = $_COOKIE['cookname'];
			$this->userid   = $_SESSION['userid']   = $_COOKIE['cookid'];
		}

		/* Username and userid have been set and not guest */
		if (isset($_SESSION['username']) && isset($_SESSION['userid']) &&
			$_SESSION['username'] != GUEST_NAME) {
			/* Confirm that username and userid are valid */
			if ($db->confirmUserID($_SESSION['username'], $_SESSION['userid']) != 0) {
				/* Variables are incorrect, user not logged in */
				unset($_SESSION['username']);
				unset($_SESSION['userid']);
				return false;
			}

			/* User is logged in, set class variables */
			$this->userinfo  = $db->getUserInfo($_SESSION['username'], 'username, userid, userlevel, hide_email, id_user, email');
			$this->username  = $this->userinfo[0];
			$this->userid    = $this->userinfo[1];
			$this->userlevel = $this->userinfo[2];
			$this->hide_email = $this->userinfo[3];
			$this->id_user = $this->userinfo[4];
			$this->email = $this->userinfo[5];
			return true;
		} else {   /* User not logged in */
			return false;
		}
	}

	/**
	 * setSkin - Set the current skin.
	 *
	 * @return void
	 * @access public
	 */
	function setSkin() {
		global $db;  //The database connection
		if (!empty($_GET['skin'])) {
			$this->skin = $_GET['skin'];
			$_SESSION['skin'] = $_GET['skin'];
		} elseif (!empty($_SESSION['skin'])) {
			$this->skin = $_SESSION['skin'];
		} elseif ($this->logged_in) {
			$skin  = $db->getUserInfo($this->username, 'skin_name');
			if (!empty($skin[0])) {
				$this->skin = $skin[0];
			}
		}
	}

	/**
	 * setLanguage - Set the current user-language.
	 *
	 * @return void
	 * @access public
	 */
	function setLanguage() {
		global $db;  //The database connection
		if (!empty($_GET['lang'])) {
			$this->lang = $_GET['lang'];
			$_SESSION['lang'] = $_GET['lang'];
		} elseif (!empty($_SESSION['lang'])) {
			$this->lang = $_SESSION['lang'];
		} elseif ($this->logged_in) {
			$lang  = $db->getUserInfo($this->username, 'lang_id');
			if (!empty($lang[0])) {
				$this->lang = $lang[0];
			}
		} else {
			$allowed_langs = array ('de', 'en');
			$this->lang = get_browser_language($allowed_langs, DEFAULT_LANGUAGE, null, false);
		}
	}

	/**
	 * login - The user has submitted his username and password
	 * through the login form, this function checks the authenticity
	 * of that information in the database and creates the session.
	 * Effectively logging in the user if all goes well.
	 *
	 * @param string $subuser username
	 * @param string $subpass password
	 * @param boolean $subremember is true if the user want to stay logged in
	 * @return boolean true if login completed successfully
	 * @access public
	 */
	function login($subuser, $subpass, $subremember) {
		global $db, $form;  //The database and form object

		/* Username error checking */
		$field = "user";  // Use field name for username
		if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
			$form->setError($field, _("* Username not entered"));
		} else {
			/* Check if username is not alphanumeric */
			if(!preg_match("/^([0-9a-z_])*$/i", $subuser)){
				$form->setError($field, _("* Username not alphanumeric (incl. '_')"));
			}
		}

		/* Password error checking */
		$field = "pass";  // Use field name for password
		if (!$subpass) {
			$form->setError($field, _("* Password not entered"));
		}

		/* Return if form errors exist */
		if ($form->num_errors > 0) {
			return false;
		}

		/* Checks that username is in database and password is correct */
		$subuser = stripslashes($subuser);
		$result = $db->confirmUserPass($subuser, md5($subpass));

		/* Check error codes */
		if ($result == 1) {
			$field = "user";
			$form->setError($field, _("* Username not found"));
		} elseif ($result == 2) {
			$field = "pass";
			$form->setError($field, _("* Invalid password"));
		}

		/* Return if form errors exist */
		if ($form->num_errors > 0) {
			return false;
		}

		/* Username and password correct, register session variables */
		$this->userinfo  = $db->getUserInfo($subuser, 'username, userlevel');
		$this->username  = $_SESSION['username'] = $this->userinfo[0];
		$this->userid    = $_SESSION['userid']   = $this->generateRandID();
		$this->userlevel = $this->userinfo[1];
		$this->logged_in = true;

		/* Insert userid into database and update active users table */
		$db->updateUserField($this->username, "userid", $this->userid);
		$db->addActiveUser($this->username);
		$db->removeActiveGuest($_SERVER['REMOTE_ADDR']);

		/**
		 * This is the cool part: the user has requested that we remember that
		 * he's logged in, so we set two cookies. One to hold his username,
		 * and one to hold his random value userid. It expires by the time
		 * specified in constants.php. Now, next time he comes to our site, we will
		 * log him in automatically, but only if he didn't log out before he left.
		 */

		if ($subremember) {
			setcookie("cookname", $this->username, time()+COOKIE_EXPIRE, COOKIE_PATH);
			setcookie("cookid",   $this->userid,   time()+COOKIE_EXPIRE, COOKIE_PATH);
		}
		if (isset($_SESSION['skin'])) {
			$user_skin = $db->getUserInfo($subuser, 'skin_name');
			if (!empty($user_skin[0])) {
				unset($_SESSION['skin']);
			}
		}
		if (isset($_SESSION['lang'])) {
			$user_lang = $db->getUserInfo($subuser, 'lang_id');
			if (!empty($user_lang[0])) {
				unset($_SESSION['lang']);
			}
		}

		/* Login completed successfully */
		return true;
	}

	/**
	 * logout - Gets called when the user wants to be logged out of the
	 * website. It deletes any cookies that were stored on the users
	 * computer as a result of him wanting to be remembered, and also
	 * unsets session variables and demotes his user level to guest.
	 *
	 * @return void
	 * @access public
	 */
	function logout() {
		//The database connection
		global $db;
		/**
		 * Delete cookies - the time must be in the past,
		 * so just negate what you added when creating the
		 * cookie.
		 */
		if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])) {
			setcookie("cookname", "", time()-COOKIE_EXPIRE, COOKIE_PATH);
			setcookie("cookid",   "", time()-COOKIE_EXPIRE, COOKIE_PATH);
		}

		/* Unset PHP session variables */
		unset($_SESSION['username']);
		unset($_SESSION['userid']);

		/* Reflect fact that user has logged out */
		$this->logged_in = false;

		/**
		 * Remove from active users table and add to
		 * active guests tables.
		 */
		$db->removeActiveUser($this->username);
		$db->addActiveGuest($_SERVER['REMOTE_ADDR']);

		/* Set user level to guest */
		$this->username  = GUEST_NAME;
		$this->userlevel = GUEST_LEVEL;
	}

	/**
	 * register - Gets called when the user has just submitted the
	 * registration form. Determines if there were any errors with
	 * the entry fields, if so, it records the errors and returns
	 * 1. If no errors were found, it registers the new user and
	 * returns 0. Returns 2 if registration failed.
	 *
	 * @param string $subuser username
	 * @param string $subpass password
	 * @param string $subpass2 control password
	 * @param string $subemail email
	 * @return integer 0 | 1 | 2
	 * @access public
	 */
	function register($subuser, $subpass, $subpass2, $subemail) {
		global $db, $form, $mailer;  //The database, form and mailer object

		/* Username error checking */
		$field = "user";  // Use field name for username
		if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
			$form->setError($field, _("* Username not entered"));
		} else {
			/* Spruce up username, check length */
			$subuser = stripslashes($subuser);
			if (strlen($subuser) < 5) {
				$form->setError($field, _("* Username below 5 characters"));
			} elseif (strlen($subuser) > 30) {
				$form->setError($field, _("* Username above 30 characters"));
			} elseif (!preg_match("/^([0-9a-z_])+$/i", $subuser)) {   /* Check if username is not alphanumeric */
				$form->setError($field, _("* Username not alphanumeric (incl. '_')"));
			} elseif (strcasecmp($subuser, GUEST_NAME) == 0) {   /* Check if username is reserved */
				$form->setError($field, "* Benutzername ist ein reserviertes Wort");
			} elseif ($db->usernameTaken($subuser)) {   /* Check if username is already in use */
				$form->setError($field, _("* Username already in use"));
			} elseif ($db->usernameBanned($subuser)) {   /* Check if username is banned */
				$form->setError($field, _("* Username banned"));
			}
		}
		if (!REGISTER_VERIFY_EMAIL) {
			/* Password error checking */
			$field = "pass";  // Use field name for password
			if (!$subpass) {
				$form->setError($field, _("* Password not entered"));
			} else {
				/* Spruce up password and check length*/
				$subpass = stripslashes($subpass);
				if (strlen($subpass) < 4) {
					$form->setError($field, _("* Password too short"));
				}
				/* Check if password is not alphanumeric */
				elseif (!preg_match("/^([0-9a-z])+$/i", ($subpass = trim($subpass)))) {
					$form->setError($field, _("* Password not alphanumeric"));
				}
				/**
				 * Note: I trimmed the password only after I checked the length
				 * because if you fill the password field up with spaces
				 * it looks like a lot more characters than 4, so it looks
				 * kind of stupid to report "password too short".
				 */
			}
			
			/* Password2 error checking */
			$field = "pass2";  // Use field name for second password
			if (!$subpass) {
				$form->setError($field, _("* Password not re-entered"));
			} else {
				if ($subpass != $subpass2) {
					$form->setError($field, _("* Passwords not the same"));
				}
			}
		}
		
		/* Email error checking */
		$field = "email";  // Use field name for email
		if (!$subemail || strlen($subemail = trim($subemail)) == 0) {
			$form->setError($field, _("* E-mail not entered"));
		} else {
			/* Check if valid email address */
			$atIndex = strrpos($subemail, "@");
			if ((is_bool($atIndex) && !$atIndex) || !filter_var($subemail, FILTER_VALIDATE_EMAIL) || !(checkdnsrr(substr($subemail, $atIndex+1),"MX") || checkdnsrr(substr($subemail, $atIndex+1),"A"))) {
				$form->setError($field, _("* E-mail invalid"));
			}
			$subemail = stripslashes($subemail);
		}

		/* Errors exist, have user correct them */
		if ($form->num_errors > 0) {
			return 1;  // Errors with form
		} else {   /* No errors, add the new account to the database */
			if (REGISTER_VERIFY_EMAIL) {
				$subpass = $this->generateRandStr(8);
			}
			if ($db->addNewUser($subuser, md5($subpass), $subemail)) {
				if (REGISTER_VERIFY_EMAIL) {
					$mailer->sendWelcomePass($subuser,$subemail,$subpass);
				} elseif (EMAIL_WELCOME) {
					$mailer->sendWelcome($subuser,$subemail,$subpass);
				}
				return 0;  //New user added succesfully
			} else {
				return 2;  //Registration attempt failed
			}
		}
	}
	
	/**
	 * editAccount - Attempts to edit the user's account information
	 * including the password, which it first makes sure is correct
	 * if entered, if so and the new password is in the right
	 * format, the change is made. All other fields are changed
	 * automatically.
	 *
	 * @param string $subcurpass current password
	 * @param string $subnewpass new password
	 * @param string $subnewpass2 verification of the new password
	 * @param string $subemail email
	 * @param string $real_name user real name
	 * @param string $extra extra public information about the user
	 * @param integer $show_active 0 | 1 - 1 if the user wants to see the active users
	 * @param integer $hide_email 0 | 1 - 1 if the user wants to hide his email from other users
	 * @param string $skin original | kraque - skin the user uses
	 + @param string $lang de | en - users interface language
	 + @param string $sym_lang de | en - language the user prefers for symptoms
	 * @return boolean true on success
	 * @access public
	 */
	function editAccount($subcurpass, $subnewpass, $subnewpass2, $subemail, $real_name, $extra, $show_active, $hide_email, $skin, $lang, $sym_lang) {
		global $db, $form;  //The database and form object
		/* New password entered */
		if($subnewpass){
			/* Current Password error checking */
			$field = "curpass";  // Use field name for current password
			if(!$subcurpass){
				$form->setError($field, _("* Current Password not entered"));
			}
			else{
				/* Check if password too short or is not alphanumeric */
				$subcurpass = stripslashes($subcurpass);
				if(strlen($subcurpass) < 4 ||
					!preg_match("/^([0-9a-z])+$/i", ($subcurpass = trim($subcurpass)))){
					$form->setError($field, _("* Current Password incorrect"));
				}
				/* Password entered is incorrect */
				if($db->confirmUserPass($this->username,md5($subcurpass)) != 0){
					$form->setError($field, _("* Current Password incorrect"));
				}
			}

			/* New Password error checking */
			$field = "newpass";  // Use field name for new password
			/* Spruce up password and check length*/
			$subnewpass = stripslashes($subnewpass);
			if(strlen($subnewpass) < 4){
				$form->setError($field, _("* New Password too short"));
			}
			/* Check if password is not alphanumeric */
			elseif(!preg_match("/^([0-9a-z])+$/i", ($subnewpass = trim($subnewpass)))){
				$form->setError($field, _("* New Password not alphanumeric"));
			}

			/* Password2 error checking */
			$field = "newpass2";  // Use field name for second password
			if(!$subnewpass2){
				$form->setError($field, _("* Password not re-entered"));
			}
			else{
				if($subnewpass != $subnewpass2) {
					$form->setError($field, _("* Passwords not the same"));
				}
			}
		}
		/* Change password attempted */

		/* Email error checking */
		$field = "email";  // Use field name for email
		if($subemail && strlen($subemail = trim($subemail)) > 0){
			/* Check if valid email address */
			$atIndex = strrpos($subemail, "@");
			if ((is_bool($atIndex) && !$atIndex) || !filter_var($subemail, FILTER_VALIDATE_EMAIL) || !(checkdnsrr(substr($subemail, $atIndex+1),"MX") || checkdnsrr(substr($subemail, $atIndex+1),"A"))) {
				$form->setError($field, _("* E-mail invalid"));
			}
			$subemail = stripslashes($subemail);
		}
		
		/* Errors exist, have user correct them */
		if($form->num_errors > 0){
			return false;  // Errors with form
		}
		
		/* Update password since there were no errors */
		if($subcurpass && $subnewpass){
			$db->updateUserField($this->username,"password",md5($subnewpass));
		}
		
		/* Change Email */
		if(isset($subemail)){
			$db->updateUserField($this->username,"email",$subemail);
		}
		
		/* Change realname */
		if(isset($real_name)){
			$real_name = trim($real_name);
			$real_name = stripslashes($real_name);
			$db->updateUserField($this->username,"user_real_name",$real_name);
		}
		
		/* Change skin */
		if(!empty($skin)){
			$skin = trim($skin);
			$skin = stripslashes($skin);
			$db->updateUserField($this->username,"skin_name",$skin);
			if (isset($_SESSION['skin'])) {
				unset($_SESSION['skin']);
			}
		}
		
		/* Change language */
		if(!empty($lang)){
			$lang = trim($lang);
			$lang = stripslashes($lang);
			$db->updateUserField($this->username,"lang_id",$lang);
			if (isset($_SESSION['lang'])) {
				unset($_SESSION['lang']);
			}
		}
		
		/* Change symptom-language */
		if(!empty($sym_lang)){
			$sym_lang = trim($sym_lang);
			$sym_lang = stripslashes($sym_lang);
			if ($sym_lang == "wo") {
				$sym_lang = "";
			}
			$db->updateUserField($this->username,"sym_lang_id",$sym_lang);
			$db->create_custom_symptom_table();
		}
		
		/* Change extra */
		if(isset($extra)){
			$extra = str_replace("\n\r", "<br>", $extra);
			$extra = str_replace("\n", "<br>", $extra);
			$extra = trim($extra);
			$db->updateUserField($this->username,"user_extra",$extra);
		}
		
		/* show active users */
		if($show_active && !$this->showActive()) {
			$db->updateUserField($this->username,"userlevel",SHOW_LEVEL);
		} elseif (!$show_active && $this->userlevel == SHOW_LEVEL) {
			$db->updateUserField($this->username,"userlevel",USER_LEVEL);
		}
		
		/* hide email */
		if($hide_email && $this->hide_email == "0") {
			$db->updateUserField($this->username,"hide_email",1);
		} elseif (!$hide_email && $this->hide_email == "1") {
			$db->updateUserField($this->username,"hide_email",0);
		}
		
		/* Success! */
		return true;
	}
	
	/**
	 * isAdmin - Returns true if currently logged in user is
	 * an administrator, false otherwise.
	 *
	 * @return boolean
	 * @access public
	 */
	function isAdmin(){
		return ($this->userlevel == ADMIN_LEVEL || $this->username  == ADMIN_NAME);
	}
	
	/**
	 * isEditor - Returns true if currently logged in user is
	 * an editor, false otherwise.
	 *
	 * @return boolean
	 * @access public
	 */
	function isEditor(){
		return ($this->userlevel >= EDITOR_LEVEL || $this->username  == ADMIN_NAME);
	}
	
	/**
	 * showActiveUsers - Returns true if currently logged in user is
	 * an administrator or if he wants to see active users
	 *
	 * @return boolean
	 * @access public
	 */
	 function showActive(){
		return ($this->isAdmin() || $this->isEditor() || $this->userlevel == SHOW_LEVEL);
	}

	/**
	 * generateRandID - Generates a string made up of randomized
	 * letters (lower and upper case) and digits and returns
	 * the md5 hash of it to be used as a userid.
	 *
	 * @return string
	 * @access public
	 */
	function generateRandID(){
		return md5($this->generateRandStr(16));
	}
	
	/**
	 * generateRandStr - Generates a string made up of randomized
	 * letters (lower and upper case) and digits, the length
	 * is a specified parameter.
	 *
	 * @return string
	 * @access public
	 */
	function generateRandStr($length){
		$randstr = "";

		for($i=0; $i<$length; $i++){
			$randnum = mt_rand(0,61);
			if($randnum < 10){
				$randstr .= chr($randnum+48);
			}elseif($randnum < 36){
				$randstr .= chr($randnum+55);
			}else{
				$randstr .= chr($randnum+61);
			}
		}
		return $randstr;
	}
};


/**
 * Initialize session object - This must be initialized before
 * the form object because the form uses session variables,
 * which cannot be accessed unless the session has started.
 */
$session = new Session;
include_once("locale/localization.php");

/* Initialize form object */
$form = new Form;

/* tabbed has to be set false at startup */
$tabbed = false;

?>
