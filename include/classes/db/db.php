<?php

/**
 * db.php
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
 * @package   DB
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

require_once ("include/classes/db/config_db.php");
if (DB_TYPE == 'mysqli') {
    require_once ("include/classes/db/mysqli.php");
} elseif (DB_TYPE == 'mysql') {
    require_once ("include/classes/db/mysql.php");
} else {
    die ("\n<br><strong>" . _("Please configure in the file 'include/classes/db/config_db.php' the database! If your server supports it, 'mysqli' otherwise 'mysql'.") . "</strong><br>\n");
}

/**
 * Database wrapper class
 *
 * The DB class allow easy and clean access to common database commands.
 * At the moment there're drivers for PHP mysql and mysqli.
 *
 * @category  Database
 * @package   DB
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 */
class DB extends DbPlugin {

	/**
	 * db_fetch_row works analog to mysql_fetch_row
	 *
	 * @param resource $result optional SQL result
	 * @return array SQL result as numeric array
	 * @access public
	 */
	function db_fetch_row() {
		$numargs = func_num_args();
		if ($numargs >= 1) {
			$result = func_get_arg(0);
		} else {
			$result = end($this->result_ar);
		}
		return $this->db_fetch_array("NUM", $result);
	}

	/**
	 * db_fetch_assoc works analog to mysql_fetch_assoc
	 *
	 * @param resource $result optional SQL result
	 * @return array SQL result as associative array
	 * @access public
	 */
	function db_fetch_assoc() {
		$numargs = func_num_args();
		if ($numargs >= 1) {
			$result = func_get_arg(0);
		} else {
			$result = end($this->result_ar);
		}
		return $this->db_fetch_array("ASSOC", $result);
	}

	/**
	 * reset_result resets the SQL result, so that the result pointer is on the first element.
	 *
	 * @param resource $result optional SQL result
	 * @return boolean Returns true on success or false on failure.
	 * @access public
	 */
	function reset_result() {
		$numargs = func_num_args();
		if ($numargs >= 1) {
			$result = func_get_arg(0);
		} else {
			$result = end($this->result_ar);
		}
		return $this->db_data_seek(0, $result);
	}

	/**
	 * send_query_limit is used for pagination of the SQL result
	 *
	 * @param string   $query SQL query
	 * @param integer  $records number of records to fetch
	 * @param integer  $start_from the record number from which to start
	 * @param resource $connection optional SQL link
	 * @return resource SQL result
	 * @access public
	 */
	function send_query_limit() {
		$numargs = func_num_args();
		if ($numargs >= 4) {
			$query = func_get_arg(0);
			$records = func_get_arg(1);
			$start_from = func_get_arg(2);
			$connection = func_get_arg(3);
		} else {
			$query = func_get_arg(0);
			$records = func_get_arg(1);
			$start_from = func_get_arg(2);
			$connection = $this->connection;
		}
		$limit = '';
		if ($start_from >= 0 || $records >= 0)
		{
			$start_from = ($start_from >= 0) ? $start_from . "," : '';
			$records = ($records >= 0) ? $records : '18446744073709551615';
			$limit = ' LIMIT ' . $start_from . ' ' . $records;
		}
		$query .= $limit;
		$this->send_query($query, $connection);
		return end($this->result_ar);
	}

	/**
	 * get_primary_key returns the primary key of a SQL table or false if not available
	 *
	 * @param string $table SQL table
	 * @return string|false Returns the primary key or false if no primary key is defined
	 * @access public
	 */
	function get_primary_key($table) {
		$primary_key = false;
		if (substr($table, 0, 9) == "archive__") {
			$table = substr($table, 9);
		}
		$query = "DESCRIBE $table";
		$this->send_query($query);
		while ($row = $this->db_fetch_assoc()) {
			if ($row["Key"] == "PRI") {
				$primary_key = $row["Field"];
				break;
			}
		}
		$this->free_result();
		return $primary_key;
	}

	/**
	 * table_exists checks if a table exists in the database
	 *
	 * @param string $table SQL table
	 * @return boolean Returns true if table exists or false otherwise.
	 * @access public
	 */
	function table_exists($table) {
		$query = "SHOW TABLES";
		$this->send_query($query);
		$table_exists = false;
		while ($row = $this->db_fetch_row()) {
			if ($table == $row[0]) {
				$table_exists = true;
				break;
			}
		}
		return $table_exists;
	}
}
// end of class DB

?>
