<?php

/**
 * form.php
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
 * @package   Form
 * @author    Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 * @see       login.php
 */

/**
 * The Form class is meant to simplify the task of keeping
 * track of errors in user submitted forms and the form
 * field values that were entered correctly.
 *
 * @category  Login
 * @package   Form
 * @author    Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 */

class  Form {

	/**
	 * Holds submitted form field values
	 * @var array
	 * @access protected
	 */
	protected $values = array();

	/**
	 * Holds submitted form error messages
	 * @var array
	 * @access protected
	 */
	protected $errors = array();

	/**
	 * The number of errors in submitted form
	 * @var integer 
	 * @access public
	 */
	public $num_errors;

	/**
	 * Class constructor
	 *
	 * Get form value and error arrays, used when there
	 * is an error with a user-submitted form.
	 *
	 * @return void
	 * @access public
	 */
	function __construct(){
		if(isset($_SESSION['value_array']) && isset($_SESSION['error_array'])){
			$this->values = $_SESSION['value_array'];
			$this->errors = $_SESSION['error_array'];
			$this->num_errors = count($this->errors);

			unset($_SESSION['value_array']);
			unset($_SESSION['error_array']);
		}
		else{
			$this->num_errors = 0;
		}
	}

	/**
	 * setError - Records new form error given the form
	 * field name and the error message attached to it.
	 *
	 * @param string $field form field
	 * @param string $errmsg error message
	 * @return void
	 * @access public
	 */
	function setError($field, $errmsg){
		$this->errors[$field] = $errmsg;
		$this->num_errors = count($this->errors);
	}

	/**
	 * value - Returns the value attached to the given
	 * field, if none exists, the empty string is returned.
	 *
	 * @param string $field form field
	 * @return string field value
	 * @access public
	 */
	function value($field){
		if(array_key_exists($field,$this->values)){
			return htmlspecialchars(stripslashes($this->values[$field]));
		}else{
			return "";
		}
	}

	/**
	 * error - Returns the error message attached to the
	 * given field, if none exists, the empty string is returned.
	 *
	 * @param string $field form field
	 * @return string error message
	 * @access public
	 */
	function error($field){
		if(array_key_exists($field,$this->errors)){
			return "<span class='error_message'>".$this->errors[$field]."</span>";
		}else{
			return "";
		}
	}

	/**
	 * getErrorArray - Returns the array of error messages
	 *
	 * @return array error messages
	 * @access public
	 */
	function getErrorArray(){
		return $this->errors;
	}
}
?>
