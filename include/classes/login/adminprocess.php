<?
/**
 * adminprocess.php
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
 * @package   AdminProcess
 * @author    Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 * @see       login.php
 */

chdir("../../..");
include("include/classes/login/session.php");

/**
 * The AdminProcess class is meant to simplify the task of processing
 * admin submitted forms from the admin center, these deal with
 * member system adjustments.
 *
 * @category  Login
 * @package   AdminProcess
 * @author    Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 */
class  AdminProcess {

	/**
	 * Class constructor
	 *
	 * @return void
	 * @access public
	 */
	function __construct() {
		global $session;
		/* Make sure administrator is accessing page */
		if(!$session->isAdmin()) {
			$host  = $_SERVER['HTTP_HOST'];
			$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			$extra = "login.php";
			header("Content-Type: text/html;charset=utf-8");
			header("Location: ../../../$extra");
			exit;
		}
		/* Admin submitted update user level form */
		if(isset($_POST['subupdlevel'])) {
			$this->procUpdateLevel();
		}
		/* Admin submitted delete user form */
		elseif(isset($_POST['subdeluser'])) {
			$this->procDeleteUser();
		}
		/* Admin submitted delete inactive users form */
		elseif(isset($_POST['subdelinact'])) {
			$this->procDeleteInactive();
		}
		/* Admin submitted ban user form */
		elseif(isset($_POST['subbanuser'])) {
			$this->procBanUser();
		}
		/* Admin submitted delete banned user form */
		elseif(isset($_POST['subdelbanned'])) {
			$this->procDeleteBannedUser();
		}
		/* Admin submitted delete userdata form */
		elseif(isset($_POST['subdeluserdata'])) {
			$this->procDeleteUserData();
		}
		/* Should not get here, redirect to login page */
		else {
			$host  = $_SERVER['HTTP_HOST'];
			$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			$extra = "login.php";
			header("Content-Type: text/html;charset=utf-8"); 
			header("Location: ../../../$extra");
			die();
		}
	}

	/**
	 * procUpdateLevel - If the submitted username is correct,
	 * their user level is updated according to the admin's
	 * request.
	 *
	 * @return void
	 * @access private
	 */
	private function procUpdateLevel() {
		global $session, $db, $form;
		/*
		 * Username error checking
		 */

		$subuser = $this->checkUsername("upduser");
		/*
		 * Errors exist, have user correct them
		 */

		if($form->num_errors > 0) {
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Content-Type: text/html;charset=utf-8"); 
			header("Location: " . $session->referrer);
			die();
		}
		/* Update user level */
		else {
			$db->updateUserField($subuser, "userlevel", (int)$_POST['updlevel']);
			header("Content-Type: text/html;charset=utf-8"); 
			header("Location: " . $session->referrer);
			die();
		}
	}

	/**
	 * procDeleteUser - If the submitted username is correct,
	 * the user is deleted from the database.
	 *
	 * @return void
	 * @access private
	 */
	private function procDeleteUser() {
		global $session, $db, $form;
		/*
		 * Username error checking
		 */
		$subuser = $this->checkUsername("deluser");

		/*
		 * Errors exist, have user correct them
		 */
		if($form->num_errors > 0) {
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Content-Type: text/html;charset=utf-8"); 
			header("Location: " . $session->referrer);
			die();
		}
		/*
		 * Delete user from database
		 */
		else {
			$user_id = $db->getUserInfo($subuser, 'id_user');
			$user_ar["user_id"] = $user_id[0];
			$query = "DELETE FROM " . TBL_USERS . " WHERE username = '$subuser'";
			$db->send_query($query);
			/*
			 * Delete all repertories of the user, who will be deleted.
			 */
			$query = "SELECT rep_id FROM repertorizations WHERE username = '$subuser'";
			$result = $db->send_query($query);
			while(list($rep_id) = $db->db_fetch_row($result)) {
				$query = "DELETE FROM rep_sym WHERE rep_id='$rep_id'";
				$db->send_query($query);
			}
			$db->free_result($result);
			$query = "DELETE FROM repertorizations WHERE username = '$subuser'";
			$db->send_query($query);
			/*
			 *  If the user has changed the database,
			 *  the username will be banned, so that
			 *  no other user can register with that username
			 *  and have access to his data.
			 */
			$tables_ar = array('materia', 'symptoms', 'sym_rem', 'sources', 'remedies', 'main_rubrics', 'languages');
			$user_changed_database = false;
			foreach ($tables_ar as $table) {
				$query = "SELECT username FROM $table WHERE username = '$subuser' LIMIT 1";
				$db->send_query($query);
				$count = $db->db_num_rows();
				$db->free_result ();
				if ($count > 0) {
					$user_changed_database = true;
					break;
				}
			}
			if ($user_changed_database) {
				$query = "INSERT INTO " . TBL_BANNED_USERS . " VALUES ('$subuser', $session->time)";
				$db->send_query($query);
			}
			header("Content-Type: text/html;charset=utf-8"); 
			header("Location: " . $session->referrer);
			die();
		}
	}

	/**
	 * procDeleteInactive - All inactive users are deleted from
	 * the database, not including administrators. Inactivity
	 * is defined by the number of days specified that have
	 * gone by that the user has not logged in.
	 *
	 * @return void
	 * @access private
	 */
	private function procDeleteInactive() {
		global $session, $db;
		$inact_time = $session->time - $_POST['inactdays']*24*60*60;
		$query = "SELECT username FROM " . TBL_USERS . " WHERE TIMESTAMPDIFF(DAY, `timestamp`, NOW()) > " . $_POST['inactdays'] . " AND userlevel != " . ADMIN_LEVEL;
		$db->send_query($query);
		while($username = $db->db_fetch_row()) {
			$deluser_ar[] = $username[0];
		}
		$db->free_result();
		/*
		 * Delete all repertories of the users, who will be deleted.
		 */
		if (!empty($deluser_ar)) {
			foreach ($deluser_ar as $subuser) {
				$query = "SELECT rep_id FROM repertorizations WHERE username = '$subuser'";
				$db->send_query($query);
				while(list($rep_id) = $db->db_fetch_row()) {
					$query = "DELETE FROM rep_sym WHERE rep_id='$rep_id'";
					$db->send_query($query);
				}
				$db->free_result();
				$query = "DELETE FROM repertorizations WHERE username = '$subuser'";
				$db->send_query($query);
				/*
				 *  If the user has changed the database,
				 *  the username will be banned.
				 */
				$query = "SELECT * FROM materia, symptoms, sym_rem, sources, remedies, main_rubrics, languages WHERE materia.username = '$subuser' OR symptoms.username = '$subuser' OR sym_rem.username = '$subuser' OR sources.username = '$subuser' OR remedies.username = '$subuser' OR main_rubrics.username = '$subuser' OR languages.username = '$subuser' LIMIT 1";
				$db->send_query($query);
				$count = $db->db_num_rows();
				$db->free_result();
				if ($count > 0) {
					$query = "INSERT INTO " . TBL_BANNED_USERS . " VALUES ('$subuser', $session->time)";
					$db->send_query($query);
				}
			}
		}
		$query = "DELETE FROM " . TBL_USERS . " WHERE timestamp < $inact_time AND userlevel != " . ADMIN_LEVEL;
		$db->send_query($query);
		header("Content-Type: text/html;charset=utf-8"); 
		header("Location: " . $session->referrer);
		die();
	}

	/**
	 * procBanUser - If the submitted username is correct,
	 * the user is banned from the member system, which entails
	 * removing the username from the users table and adding
	 * it to the banned users table.
	 *
	 * @return void
	 * @access private
	 */
	private function procBanUser() {
		global $session, $db, $form;
		/*
		 * Username error checking
		 */
		$subuser = $this->checkUsername("banuser");
		/*
		 * Errors exist, have user correct them
		 */
		if($form->num_errors > 0) {
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Content-Type: text/html;charset=utf-8"); 
			header("Location: " . $session->referrer);
			die();
		}
		/*
		 * Ban user from member system.
		 */
		else {
			$user_id = $db->getUserInfo($subuser, 'id_user');
			$user_ar["user_id"] = $user_id[0];
			$query = "DELETE FROM " . TBL_USERS . " WHERE username = '$subuser'";
			$db->send_query($query);
			/*
			 * Delete all repertories of the user, who will be deleted.
			 */
			$query = "SELECT rep_id FROM repertorizations WHERE username = '$subuser'";
			$db->send_query($query);
			while(list($rep_id) = $db->db_fetch_row()) {
				$query = "DELETE FROM rep_sym WHERE rep_id='$rep_id'";
				$db->send_query($query);
			}
			$db->free_result();
			$query = "DELETE FROM repertorizations WHERE username = '$subuser'";
			$db->send_query($query);
			$query = "INSERT INTO " . TBL_BANNED_USERS . " VALUES ('$subuser', $session->time)";
			$db->send_query($query);
			header("Content-Type: text/html;charset=utf-8"); 
			header("Location: " . $session->referrer);
			die();
		}
	}

	/**
	 * procDeleteBannedUser - If the submitted username is correct,
	 * the user is deleted from the banned users table, which
	 * enables someone to register with that username again.
	 *
	 * @return void
	 * @access private
	 */
	private function procDeleteBannedUser() {
		global $session, $db, $form;
		/*
		 * Username error checking
		 */
		$subuser = $this->checkUsername("delbanuser", true);

		/*
		 * Errors exist, have user correct them
		 */
		if($form->num_errors > 0) {
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: " . $session->referrer);
		}
		/*
		 * Delete user from database
		 */
		else {
			$query = "DELETE FROM " . TBL_BANNED_USERS . " WHERE username = '$subuser'";
			$db->send_query($query);
			header("Content-Type: text/html;charset=utf-8"); 
			header("Location: " . $session->referrer);
			die();
		}
	}

	/**
	 * procDeleteUserData - If the submitted username is correct,
	 * the data inserted by the user is deleted from the database.
	 *
	 * @return void
	 * @access private
	 */
	private function procDeleteUserData() {
		global $session, $db, $form;
		/*
		 * Username error checking
		 */
		$subuser = $_POST["deluserdata"];
		$field = "deluserdata";  //Use field name for username
		if(!$subuser || strlen($subuser = trim($subuser)) == 0) {
			$form->setError($field, "* " . _("Please enter your username") . "<br>");
		}
		/*
		 * Errors exist, have user correct them
		 */
		if($form->num_errors > 0) {
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Content-Type: text/html;charset=utf-8"); 
			header("Location: " . $session->referrer);
			die();
		}
		/*
		 * Delete userdata from database
		 */
		else {
			$where = "username = '$subuser'";
			$count_materia = $this->delete_row('materia', $where);

			$count_sym_rem = $this->delete_row('sym_rem', $where);

			$where = "username = '$subuser' AND NOT EXISTS (SELECT 1 FROM sym_rem WHERE sym_rem.sym_id = symptoms.sym_id)";
			$count_symptoms = $this->delete_row('symptoms', $where);

			$where = "username = '$subuser' AND NOT EXISTS (SELECT 1 FROM symptoms WHERE symptoms.rubric_id = main_rubrics.rubric_id)";
			$count_main_rubrics = $this->delete_row('main_rubrics', $where);

			$where = "username = '$subuser' AND NOT EXISTS (SELECT 1 FROM materia WHERE materia.rem_id = remedies.rem_id) AND NOT EXISTS (SELECT 1 FROM sym_rem WHERE sym_rem.rem_id = remedies.rem_id)";
			$count_remedies = $this->delete_row('remedies', $where);

			$where = "username = '$subuser' AND NOT EXISTS (SELECT 1 FROM materia WHERE materia.src_id = sources.src_id) AND NOT EXISTS (SELECT 1 FROM sym_rem WHERE sym_rem.src_id = sources.src_id)";
			$count_sources = $this->delete_row('sources', $where);

			$where = "username = '$subuser' AND NOT EXISTS (SELECT 1 FROM sources WHERE sources.lang_id = languages.lang_id)";
			$count_languages = $this->delete_row('languages', $where);

			$count = $count_materia + $count_sym_rem + $count_symptoms + $count_main_rubrics + $count_remedies + $count_sources + $count_languages;
			header("Content-Type: text/html;charset=utf-8"); 
			header("Location: " . $session->referrer . "?count=$count");
			die();
		}
	}

	/**
	 * delete_row - Copy datasets that will be deleted to the archive tables and deletes the records.
	 * Returns the number of deleted datasets.
	 *
	 * @param string $table database table
	 * @param string $where SQL where clause
	 * @return integer
	 * @access private
	 */
	private function delete_row($table, $where) {
		global $db;
		$query = "SELECT COUNT(*) FROM $table WHERE $where";
		$db->send_query($query);
		list($count) = $db->db_fetch_row();
		$db->free_result();
		if ($count > 0) {
			$archive_type = "admin_delete";
			$db->archive_table_row($table, $where, $archive_type);
			$query = "DELETE FROM $table WHERE $where";
			$db->send_query($query);
		}
		return $count;
	}

	/**
	 * checkUsername - Helper function for the above processing,
	 * it makes sure the submitted username is valid, if not,
	 * it adds the appropritate error to the form.
	 * Returns the username.
	 *
	 * @param string  $user_post POST key which contains the username
	 * @param boolean $ban true if the user is banned
	 * @return string
	 * @access private
	 */
	private function checkUsername($user_post, $ban=false) {
		global $db, $form;
		/*
		 * Username error checking
		 */
		$subuser = $_POST[$user_post];
		$field = $username;  // Use field name for username
		if(!$subuser || strlen($subuser = trim($subuser)) == 0) {
			$form->setError($field, "* " . _("Please enter your username") . "<br>");
		} else {
			/*
			 * Make sure username is in database
			 */
			$subuser = stripslashes($subuser);
			if(strlen($subuser) < 5 || strlen($subuser) > 30 || !preg_match("/^([0-9a-z])+$/i", $subuser) || (!$ban && !$db->usernameTaken($subuser))) {
				$form->setError($field, "* " . _("User doesn't exist") . "<br>");
			}
		}
		return $subuser;
	}
}

/*
 * Initialize process
 */
$adminprocess = new AdminProcess;

?>
