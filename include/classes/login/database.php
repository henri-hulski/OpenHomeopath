<?php

/**
 * database.php
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
 * @package   UserDB
 * @author    Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 * @see       login.php
 */

require_once("include/classes/login/constants.php");
require_once("include/classes/db/openhomeo_db.php");

/**
 * The Database class is meant to simplify the task of accessing
 * information from the website's database.
 *
 * @category  Login
 * @package   UserDB
 * @author    Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 */
class UserDB extends OpenHomeoDB {

	/**
	 * Number of active users viewing site
	 * @var integer 
	 * @access public
	 */
	public $num_active_users;


	/**
	 * Number of active guests viewing site
	 * @var integer
	 * @access public
	 */
	public $num_active_guests;


	/**
	 * Number of signed-up users
	 * @var integer 
	 * @access public
	 */
	public $num_members;
	/* Note: call getNumMembers() to access $num_members! */

	/**
	 * Only query database to find out number of members
	 * when getNumMembers() is called for the first time,
	 * until then, default value set.
	 *
	 *  @return void
	 *  @access public
	 */
	function get_num_visitors() {
		$this->num_members = -1;

		if (TRACK_VISITORS) {
			/* Calculate number of users at site */
			$this->calcNumActiveUsers();

			/* Calculate number of guests at site */
			$this->calcNumActiveGuests();
		}
	}

	/**
	 * confirmUserPass - Checks whether or not the given
	 * username is in the database, if so it checks if the
	 * given password is the same password in the database
	 * for that user. If the user doesn't exist or if the
	 * passwords don't match up, it returns an error code
	 * (1 or 2). On success it returns 0.
	 *
	 * @param string $username username
	 * @param string $password password
	 * @return integer 0 | 1 | 2
	 * @access public
	 */
	function confirmUserPass($username, $password) {
		/* Add slashes if necessary (for query) */
		if (!get_magic_quotes_gpc()) {
			$username = addslashes($username);
		}

		/* Verify that user is in database */
		$query = "SELECT password FROM " . TBL_USERS . " WHERE username = '$username'";

		$this->send_query($query);
		if ($this->db_num_rows() < 1) {
			return 1; // Indicates username failure
		}
		
		/* Retrieve password from result, strip slashes */
		$dbarray = $this->db_fetch_row();
		$this->free_result();

		$dbarray[0] = stripslashes($dbarray[0]);
		$password = stripslashes($password);
		
		/* Validate that password is correct */
		if ($password == $dbarray[0]) {
			return 0; // Success! Username and password confirmed
		} else {
			return 2; // Indicates password failure
		}
	}

	/**
	 * confirmUserID - Checks whether or not the given
	 * username is in the database, if so it checks if the
	 * given userid is the same userid in the database
	 * for that user. If the user doesn't exist or if the
	 * userids don't match up, it returns an error code
	 * (1 or 2). On success it returns 0.
	 *
	 * @param string $username username
	 * @param string $userid  user-ID
	 * @return integer 0 | 1 | 2
	 * @access public
	 */
	function confirmUserID($username, $userid) {
		/* Add slashes if necessary (for query) */
		if (!get_magic_quotes_gpc()) {
			$username = addslashes($username);
		}
		
		/* Verify that user is in database */
		$query = "SELECT userid FROM " . TBL_USERS . " WHERE username = '$username'";

		$this->send_query($query);
		if ($this->db_num_rows() < 1) {
			return 1; //Indicates username failure
		}
		
		/* Retrieve userid from result, strip slashes */
		$dbarray = $this->db_fetch_row();
		$this->free_result();

		$dbarray[0] = stripslashes($dbarray[0]);
		$userid = stripslashes($userid);
		
		/* Validate that userid is correct */
		if ($userid == $dbarray[0]) {
			return 0; //Success! Username and userid confirmed
		}
		else {
			return 2; //Indicates userid invalid
		}
	}
	
	/**
	 * usernameTaken - Returns true if the username has
	 * been taken by another user, false otherwise.
	 *
	 * @param string $username username
	 * @return boolean
	 * @access public
	 */
	function usernameTaken($username) {
		if (!get_magic_quotes_gpc()) {
			$username = addslashes($username);
		}
		$query = "SELECT username FROM " . TBL_USERS . " WHERE username = '$username'";
		$this->send_query($query);
		$username_exists = false;
		if ($this->db_num_rows() > 0) {
			$username_exists = true;
		}
		$this->free_result();

		return ($username_exists);
	}

	/**
	 * usernameBanned - Returns true if the username has
	 * been banned by the administrator.
	 *
	 * @param string $username username
	 * @return boolean
	 * @access public
	 */
	function usernameBanned($username) {
		if (!get_magic_quotes_gpc()) {
			$username = addslashes($username);
		}
		$query = "SELECT username FROM " . TBL_BANNED_USERS . " WHERE username = '$username'";
		$this->send_query($query);
		$username_banned = false;
		if ($this->db_num_rows() > 0) {
			$username_banned = true;
		}
		$this->free_result();
		return ($username_banned);
		}

	/**
	 * addNewUser - Inserts the given (username, password, email)
	 * info into the database. Appropriate user level is set.
	 * Returns true on success, false otherwise.
	 *
	 * @param string $username username
	 * @param string $password password
	 * @param string $email e-mail
	 * @return boolean true on success
	 * @access public
	 */
	function addNewUser($username, $password, $email) {
		/* If admin sign up, give admin user level */
		if (strcasecmp($username, ADMIN_NAME) == 0 || strcasecmp($username, ADMIN_NAME_2) == 0) {
			$ulevel = ADMIN_LEVEL;
		} else {
			$ulevel = USER_LEVEL;
		}
		$query = "INSERT INTO " . TBL_USERS . " (username, password, userid, userlevel, email, email_registered, registration) VALUES ('$username', '$password', '0', $ulevel, '$email', '$email', NOW())";
		$result = $this->send_query($query);
		return $result;
	}

	/**
	 * updateUserField - Updates a field, specified by the field
	 * parameter, in the user's row of the database.
	 *
	 * @param string $username username
	 * @param string $field form field
	 * @param string $value field value
	 * @return boolean true on success
	 * @access public
	 */
	function updateUserField($username, $field, $value) {
		$query = "UPDATE " . TBL_USERS . " SET " . $field . " = '$value' WHERE username = '$username'";
		$result = $this->send_query($query);
		return $result;
	}

	/**
	 * getUserInfo - Returns the result array from a database
	 * query asking for all information stored regarding
	 * the given username. If query fails, NULL is returned.
	 *
	 * @param string $username username
	 * @param string $column column(s) of user table to query - comma seperated
	 * @return boolean true on success
	 * @access public
	 */
	function getUserInfo($username, $column) {
		$query = "SELECT $column FROM " . TBL_USERS . " WHERE username = '$username'";
		$this->send_query($query);
		/* Error occurred, return given name by default */
		if ($this->db_num_rows() < 1) {
			$this->free_result();
			return NULL;
		}
		/* Return result array */
		$dbarray = $this->db_fetch_row();
		$this->free_result();
		return $dbarray;
	}
	
	/**
	 * getNumMembers - Returns the number of signed-up users
	 * of the website, banned members not included. The first
	 * time the function is called on page load, the database
	 * is queried, on subsequent calls, the stored result
	 * is returned. This is to improve efficiency, effectively
	 * not querying the database when no call is made.
	 *
	 *  @return integer
	 *  @access public
	 */
	function getNumMembers() {
		if ($this->num_members < 0) {
			$query = "SELECT COUNT(*) FROM " . TBL_USERS;
			$this->send_query($query);
			list($this->num_members) = $this->db_fetch_row();
			$this->free_result();
		}
		return $this->num_members;
	}

	/**
	 * calcNumActiveUsers - Finds out how many active users
	 * are viewing site and sets class variable accordingly.
	 *
	 *  @return integer
	 *  @access private
	 */
	private function calcNumActiveUsers() {
	/* Calculate number of users at site */
		$query = "SELECT COUNT(*) FROM " . TBL_ACTIVE_USERS;
		$this->send_query($query);
		list($this->num_active_users) =  $this->db_fetch_row();
		$this->free_result();
	}
	
	/**
	 * calcNumActiveGuests - Finds out how many active guests
	 * are viewing site and sets class variable accordingly.
	 *
	 *  @return integer
	 *  @access private
	 */
	private function calcNumActiveGuests() {
	/* Calculate number of guests at site */
		$query = "SELECT COUNT(*) FROM " . TBL_ACTIVE_GUESTS;
		$this->send_query($query);
		list($this->num_active_guests) =  $this->db_fetch_row();
		$this->free_result();
	}
	
	/**
	 * addActiveUser - Updates username's last activity
	 * in the database, and also adds him to the table of
	 * active users, or updates timestamp if already there.
	 *
	 * @param string $username username
	 * @return void
	 * @access public
	 */
	function addActiveUser($username) {
		$query = "UPDATE " . TBL_USERS . " SET `last_activity` = NOW() WHERE username = '$username'";
		$this->send_query($query);
		if (!TRACK_VISITORS) {
			return;
		}
		$query = "REPLACE INTO " . TBL_ACTIVE_USERS . " (username) VALUES ('$username')";
		$this->send_query($query);
		$this->calcNumActiveUsers();
	}
	
	/**
	 * addActiveGuest - Adds guest to active guests table
	 *
	 * @param string $ip IP-address
	 * @return void
	 * @access public
	 */
	function addActiveGuest($ip) {
		if (!TRACK_VISITORS) {
			return;
		}
		$query = "REPLACE INTO " . TBL_ACTIVE_GUESTS . " (ip) VALUES ('$ip')";
		$this->send_query($query);
		$this->calcNumActiveGuests();
	}
	
	/**
	 * removeActiveUser - removes user with given username
	 * from active_users table
	 *
	 * @param string $username username
	 * @return void
	 * @access public
	 */
	function removeActiveUser($username) {
		if (!TRACK_VISITORS) {
			return;
		}
		$query = "DELETE FROM " . TBL_ACTIVE_USERS . " WHERE username = '$username'";
		$this->send_query($query);
		$this->calcNumActiveUsers();
	}
	
	/**
	 * removeActiveGuest - removes guest with given IP
	 * from active_guests table
	 *
	 * @param string $ip IP-address
	 * @return void
	 * @access public
	 */
	function removeActiveGuest($ip) {
		if (!TRACK_VISITORS) {
			return;
		}
		$query = "DELETE FROM " . TBL_ACTIVE_GUESTS . " WHERE ip = '$ip'";
		$this->send_query($query);
		$this->calcNumActiveGuests();
	}
	
	/**
	 * removeInctiveUsers - removes users after inactivity of USER_TIMEOUT
	 * from active_users table
	 *
	 * @return void
	 * @access public
	 */
	function removeInactiveUsers() {
		if (!TRACK_VISITORS) {
			return;
		}
		$query = "DELETE FROM " . TBL_ACTIVE_USERS . " WHERE TIMESTAMPDIFF(MINUTE, `timestamp`, NOW()) > " . USER_TIMEOUT;
		$this->send_query($query);
		$this->calcNumActiveUsers();
	}
	
	/**
	 * removeInactiveGuest - removes guests after inactivity of GUEST_TIMEOUT
	 * from active_guests table
	 *
	 * @return void
	 * @access public
	 */
	function removeInactiveGuests() {
		if (!TRACK_VISITORS) {
			return;
		}
		$query = "DELETE FROM " . TBL_ACTIVE_GUESTS . " WHERE TIMESTAMPDIFF(MINUTE, `timestamp`, NOW()) > " . GUEST_TIMEOUT;
		$this->send_query($query);
		$this->calcNumActiveGuests();
	}

}
// end of class UserDB
