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
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
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
	 * SQL link identifier
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
	 * db_connect open a new connection to the SQL server.
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
	 * send_query performs a query on the database.
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
	 * db_fetch_array fetch a result row as an associative, a numeric array, or both.
	 *
	 * Returns an array of strings that corresponds to the fetched row
	 * or NULL if there are no more rows in resultset.
	 *
	 * @param  MYSQL_NUM|MYSQL_ASSOC|MYSQL_BOTH $type optional type of the result array
	 * @param  resource $result optional result set identifier
	 * @return array
	 * @access public
	 */
	function db_fetch_array() {
		$numargs = func_num_args();
		if ($numargs >= 1) {
			$type = func_get_arg(0);
		} else {
			$type = MYSQL_BOTH;
		}
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
	 * db_fetch_object returns the current row of a result set as an object.
	 *
	 * The function returns an object with string properties that corresponds to the fetched row
	 * or NULL if there are no more rows in resultset.
	 *
	 * @param  resource $result optional result set identifier
	 * @return object
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
	 * db_num_rows gets the number of rows in a result.
	 *
	 * @param  resource $result optional result set identifier
	 * @return integer  Returns number of rows in the result set.
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
	 * db_affected_rows gets the number of affected rows in a previous SQL operation.
	 *
	 * Return values:
	 * An integer greater than zero indicates the number of rows affected or retrieved.
	 * Zero indicates that no records were updated for an UPDATE statement, no rows
	 * matched the WHERE clause in the query or that no query has yet been executed.
	 * -1 indicates that the query returned an error.
	 *
	 * @param  resource $connection optional a SQL link identifier
	 * @return integer
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
	 * db_num_fields returns the number of columns for the most recent query.
	 *
	 * @return integer  Returns the number of fields in a result set.
	 * @access public
	 */
	function db_num_fields() {
		$result = end($this->result_ar);
		return mysql_num_fields($result);
	}

	/**
	 * db_data_seek adjusts the result pointer to an arbitrary row in the result.
	 *
	 * @param  integer $row_number the row number - must be between zero and the total number of rows minus one
	 * @param  resource $result optional result set identifier
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
	 * free_result frees the memory associated with a result.
	 *
	 * @param  resource $result optional result set identifier
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
	 * close_db closes a previously opened database connection.
	 *
	 * @param  resource $connection optional a SQL link identifier
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
	 * db_insert_id returns the auto generated id used in the last query.
	 *
	 * The function returns the value of the AUTO_INCREMENT field that was updated by the previous query.
	 * Returns zero if there was no previous query on the connection or if the query did not update an AUTO_INCREMENT value.
	 *
	 * Note: If the number is greater than maximal int value, db_insert_id() will return a string.
	 *
	 * @param  resource $connection optional a SQL link identifier
	 * @return integer|string The value of the AUTO_INCREMENT field that was updated by the previous query.
	                          Returns zero if there was no previous query on the connection
	                          or if the query did not update an AUTO_INCREMENT value.
	                          If the number is greater than maximal int value, mysqli_insert_id() will return a string.
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
	 * escape_string escapes special characters in a string for use in an SQL statement, taking into account the current charset of the connection.
	 *
	 * @param  string   $unescaped_string the string to be escaped
	 * @param  resource $connection optional a SQL link identifier
	 * @return string   Returns an escaped string.
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
