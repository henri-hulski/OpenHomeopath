<?php

/**
 * mysql.php
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
 * @category  Database
 * @package   DbPlugin
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 * @see       DB
 */

/**
 * This is the mysql driver for the DB class
 *
 * @category  Database
 * @package   DbPlugin
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 */
class DbPlugin {

	/**
	 * SQL link
	 * @var resource
	 * @access public
	 */
	public $connection;
	
	
	/**
	 * Array of SQL result resources
	 * @var array
	 * @access public
	 */
	public $result_ar = array();
	
	/**
	 * db_connect connects to the SQL database
	 *
	 * @return resource Returns the SQL link.
	 * @access public
	 */
	function db_connect() {
		$this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS) OR die(_("No connection to the database.") . ". " . _("Error message:") . " " . mysql_error());
		$db_selected = mysql_select_db(DB_NAME, $this->connection) OR die("File: " . __FILE__ . " on line: " . __LINE__."<br>The database " . DB_NAME . " cannot be used:" . mysql_error());
		/* change character set to utf8 */
		mysql_query("SET NAMES 'utf8'", $this->connection);
		mysql_query("SET CHARACTER SET utf8", $this->connection);
		return $this->connection;
	}

	/**
	 * send_query sends a SQL query.
	 *
	 * @param string   $query SQL query
	 * @param resource $connection optional SQL link
	 * @return resource|boolean Returns the SQL resource for SELECT queries etc. or boolean for INSERTs etc.
	 * @access public
	 */
	function send_query() {
		$query = func_get_arg(0);
		$numargs = func_num_args();
		if ($numargs >= 2) {
			$connection = func_get_arg(1);
		} else {
			$connection = $this->connection;
		}
		$result = mysql_query($query, $connection) OR die("<br>Database error on the query:<br>$query<br>Error message: " . mysql_error());
		if ($result !== TRUE && $result !== FALSE) {
			$this->result_ar[] = $result;
		}
		return $result;
	}

	/**
	 * Fetch the SQL result as an array either as numeric or as associative array or both.
	 *
	 * @param MYSQL_NUM|MYSQL_ASSOC|MYSQL_BOTH $type type of the result array
	 * @param resource $result optional SQL result
	 * @return array  SQL result array
	 * @access public
	 */
	function db_fetch_array() {
		$type = func_get_arg(0);
		$numargs = func_num_args();
		if ($numargs >= 2) {
			$result = func_get_arg(1);
		} else {
			$result = end($this->result_ar);
		}
		if ($type == "NUM") {
			$type = MYSQL_NUM;
		} elseif ($type == "ASSOC") {
			$type = MYSQL_ASSOC;
		} else {
			$type = MYSQL_BOTH;
		}
		return mysql_fetch_array($result, $type);
	}

	/**
	 * Short description for function
	 *
	 * Long description (if any) ...
	 *
	 * @return unknown Return description (if any) ...
	 * @access public
	 */
	function db_fetch_object() {
		$numargs = func_num_args();
		if ($numargs >= 1) {
			$result = func_get_arg(0);
		} else {
			$result = end($this->result_ar);
		}
		return mysql_fetch_object($result);
	}

	/**
	 * Short description for function
	 *
	 * Long description (if any) ...
	 *
	 * @return unknown Return description (if any) ...
	 * @access public
	 */
	function db_num_rows() {
		$numargs = func_num_args();
		if ($numargs >= 1) {
			$result = func_get_arg(0);
		} else {
			$result = end($this->result_ar);
		}
		return mysql_num_rows($result);
	}

	/**
	 * Short description for function
	 *
	 * Long description (if any) ...
	 *
	 * @return unknown Return description (if any) ...
	 * @access public
	 */
	function db_affected_rows() {
		$numargs = func_num_args();
		if ($numargs >= 1) {
			$connection = func_get_arg(0);
		} else {
			$connection = $this->connection;
		}
		return mysql_affected_rows($connection);
	}

	/**
	 * Short description for function
	 *
	 * Long description (if any) ...
	 *
	 * @return unknown Return description (if any) ...
	 * @access public
	 */
	function db_num_fields() {
		$numargs = func_num_args();
		if ($numargs >= 1) {
			$result = func_get_arg(0);
		} else {
			$result = end($this->result_ar);
		}
		return mysql_num_fields($result);
	}

	/**
	 * Short description for function
	 *
	 * Long description (if any) ...
	 *
	 * @return boolean Returns TRUE on success or FALSE on failure.
	 * @access public
	 */
	function db_data_seek() {
		$row_number = func_get_arg(0);
		$numargs = func_num_args();
		if ($numargs >= 2) {
			$result = func_get_arg(1);
		} else {
			$result = end($this->result_ar);
		}
		return mysql_data_seek($result, $row_number);
	}

	/**
	 * Short description for function
	 *
	 * Long description (if any) ...
	 *
	 * @return void  
	 * @access public
	 */
	function free_result() {
		$numargs = func_num_args();
		if ($numargs >= 1) {
			$result = func_get_arg(0);
		} else {
			$result = end($this->result_ar);
		}
		array_pop($this->result_ar);
		/* free result set */
		mysql_free_result($result);
	}

	/**
	 * Short description for function
	 *
	 * Long description (if any) ...
	 *
	 * @return void  
	 * @access public
	 */
	function close_db() {
		$numargs = func_num_args();
		if ($numargs >= 1) {
			$connection = func_get_arg(0);
		} else {
			$connection = $this->connection;
		}
		/* close connection */
		mysql_close($connection);
	}

	/**
	 * Short description for function
	 *
	 * Long description (if any) ...
	 *
	 * @return unknown Return description (if any) ...
	 * @access public
	 */
	function db_insert_id() {
		$numargs = func_num_args();
		if ($numargs >= 1) {
			$connection = func_get_arg(0);
		} else {
			$connection = $this->connection;
		}
		/* returns the auto generated id used in the last query */
		return mysql_insert_id();
	}

	/**
	 * Short description for function
	 *
	 * Long description (if any) ...
	 *
	 * @return unknown Return description (if any) ...
	 * @access public
	 */
	function escape_string() {
		$unescaped_string = func_get_arg(0);
		$numargs = func_num_args();
		if ($numargs >= 2) {
			$connection = func_get_arg(1);
		} else {
			$connection = $this->connection;
		}
		if(get_magic_quotes_runtime()) {
			$unescaped_string = stripslashes($unescaped_string);
		}
		return mysql_real_escape_string($unescaped_string, $connection);
	}

// end of class DbPlugin
}

?>
