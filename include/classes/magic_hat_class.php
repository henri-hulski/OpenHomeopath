<?php

/**
 * magic_hat.php
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
 * @category  Donations
 * @package   MagicHat
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

/**
 * The MagicHat class handels the donations and restrictions
 *
 * @category  Donations
 * @package   MagicHat
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 */
class MagicHat {
	
	/**
	 * Holds information about received donations
	 * @var array
	 * @access private
	 */
	private $donations_ar = array();
	
	
	/**
	 * True if currently logged in user donated for OpenHomeopath or is Admin/Editor, false otherwise.
	 * @var boolean
	 * @access public
	 */
	public $is_donator;
	
	
	/**
	 * True, if OpenHomeopath is set to restricted mode, false otherwise.
	 * @var unknown
	 * @access public
	 */
	public $restricted_mode;

	/**
	 * Class constructor
	 *
	 * @return void
	 * @access public
	 */
	public function __construct() {
		$this->get_received_donations();
		$this->set_is_donator();
		$this->set_restricted_mode();
	}

	/**
	 * get_received_donations retieves the received donations from the database and stores them in an array ($this->donations_ar).
	 *
	 * The created array holds the following information:
	 *   - the received donations from the current month (['sum_curmonth']) and last month (['sum_lastmonth']) in EUR and USD together
	 *   - the current and last monthname depending on locale (['curmonth'], ['lastmonth'])
	 *   - the monthly donation goal (['goal_monthly']).
	 *
	 * @return void
	 * @access private
	 */
	private function get_received_donations() {
		global $db;
		$query = "SELECT SUM(amount) FROM magic_hat WHERE (currency = 'EUR' OR currency = 'USD') AND YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE())";
		$db->send_query($query);
		list($this->donations_ar['sum_curmonth']) = $db->db_fetch_row();
		$db->free_result();
		$query = "SELECT SUM(amount) FROM magic_hat WHERE (currency = 'EUR' OR currency = 'USD') AND YEAR(date) = YEAR(CURDATE() - INTERVAL 1 MONTH) AND MONTH(date) = MONTH(CURDATE() - INTERVAL 1 MONTH)";
		$db->send_query($query);
		list($this->donations_ar['sum_lastmonth']) = $db->db_fetch_row();
		$db->free_result();
		foreach ($this->donations_ar as $key => $value) {
			if (empty($value)) {
				$this->donations_ar[$key] = 0;
			}
		}
		$this->donations_ar['curmonth'] = strftime("%B");
		$this->donations_ar['lastmonth'] = strftime("%B", strtotime("-1 month"));
		$this->donations_ar['goal_monthly'] = DONATION_GOAL_MONTHLY;
	}

	/**
	 * goal_reached returns true if the donation goal is reached for $period, otherwise false.
	 *
	 * If $period is 'all' until the 10th of the current month goal_reached returns true,
	 * when the goal is reached either for the current or the last month.
	 *
	 * If $period is 'all' between the 11th and 20th of the current month goal_reached returns true,
	 * when the goal is reached either for the current month or the goal is reached for the last month
	 * and half of the goal is reached for the current month.
	 *
	 * @param string  $period 'all'|'curmonth'|'lastmonth': the month for which the goal should be proved. For 'all' see function description.
	 * @return boolean True, if the goal is reached, false otherwise.
	 * @access private
	 */
	private function goal_reached($period = 'all') {
		$goal_reached = false;
		switch ($period) {
			case 'all':
				$day_of_month = date('j');
				if ($this->donations_ar['sum_curmonth'] >= $this->donations_ar['goal_monthly'] || ($this->donations_ar['sum_lastmonth'] >= $this->donations_ar['goal_monthly'] && ($day_of_month <= 10 || ($day_of_month <= 20 && $this->donations_ar['sum_curmonth'] >= $this->donations_ar['goal_monthly'] / 2)))) {
					$goal_reached = true;
				}
				break;
			case 'curmonth':
				if ($this->donations_ar['sum_curmonth'] >= $this->donations_ar['goal_monthly']) {
					$goal_reached = true;
				}
				break;
			case 'lastmonth':
				if ($this->donations_ar['lastmonth'] >= $this->donations_ar['goal_monthly']) {
					$goal_reached = true;
				}
				break;
		}
		return $goal_reached;
	}
	
	/**
	 * print_received_donations returns a html list with the received donations of the current and the last month
	 *
	 * @return string
	 * @access public
	 */
	public function print_received_donations() {
		$class_curmonth = $this->goal_reached('curmonth') ? 'goal_reached' : 'goal_not_reached';
		$class_lastmonth = $this->goal_reached('lastmonth') ? 'goal_reached' : 'goal_not_reached';
		$received_donations = "                <li>\n";
		$received_donations .= "                  <strong>" . $this->donations_ar['curmonth'] . ": <span class='$class_curmonth'>" . money_format('%!.0n', floor($this->donations_ar['sum_curmonth'])) . " €/$</span></strong><br>\n";
		$received_donations .= "                  " . $this->donations_ar['lastmonth'] . ": <span class='$class_lastmonth'>" . money_format('%!.0n', floor($this->donations_ar['sum_lastmonth'])) . " €/$</span><br>\n";
		$received_donations .= "                  <strong>" . _("Goal") . ":</strong><br><strong>" . money_format('%!.0n', floor($this->donations_ar['goal_monthly'])) . " €/$</strong> " . _("per month") . "\n";
		$received_donations .= "                </li>\n";
		return $received_donations;
	}


	/**
	 * set_is_donator sets the $this->is_donator variable.
	 *
	 * set_is_donator sets $this->is_donator to true if currently logged in user donated for OpenHomeopath or is Admin/Editor,
	 * otherwise to false.
	 *
	 * @return void
	 * @access private
	 */
	private function set_is_donator(){
		global $db, $session;
		if (!isset($session->logged_in) || !$session->logged_in) {
			$this->is_donator = false;
		} elseif ($session->userlevel >= EDITOR_LEVEL || $session->username  == ADMIN_NAME) {
			$this->is_donator = true;
		} else {
			$query = "SELECT username FROM magic_hat WHERE username = '" . $session->username . "' OR email = '" . $session->email . "' LIMIT 1";
			$db->send_query($query);
			$num = $db->db_num_rows();
			$db->free_result();
			if ($num > 0) {
				$this->is_donator = true;
			} else {
				$query = "SELECT email_registered FROM users WHERE username = '" . $session->username . "'";
				$db->send_query($query);
				list($email_registered) = $db->db_fetch_row();
				$db->free_result();
				$query = "SELECT username FROM magic_hat WHERE email = '$email_registered' LIMIT 1";
				$db->send_query($query);
				$num = $db->db_num_rows();
				$db->free_result();
				if ($num > 0) {
					$this->is_donator = true;
				} else {
					$this->is_donator = false;
				}
			}
		}
	}
	
	/**
	 * set_restricted_mode sets the $this->restricted_mode variable.
	 *
	 * set_restricted_mode sets $this->restricted_mode to false if goal_reached('all') returns true
	 * or currently logged in user is a donator, otherwise to true.
	 *
	 * @return void
	 * @access private
	 */
	private function set_restricted_mode(){
		$this->restricted_mode = !($this->is_donator || $this->goal_reached());
	}
}
?>
