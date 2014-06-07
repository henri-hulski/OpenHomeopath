<?php

/**
 * symrem_class.php
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
 * @category  Homeopathy
 * @package   SymRem
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

/**
 * The SymRem Class is responsible for retrieving and presenting the remedies for a given symptom for the symptominfo (symptominfo.php)
 *
 * @category  Homeopathy
 * @package   SymRem
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 */
class SymRem {

	/**
	 * Remedies array
	 * @var array
	 * @access private
	 */
	private $rems_ar = array();
	
	
	/**
	 * Number of found remedies
	 * @var integer
	 * @access public
	 */
	public $rem_count;
	
	
	/**
	 * Symptom ID
	 * @var integer
	 * @access public
	 */
	public $sym_id;
	
	
	/**
	 * From which grade on remedies should be retrieved (1|2|3)
	 * @var integer
	 * @access private
	 */
	private $grade;
	
	
	/**
	 * How the remedies should be sorted (grade|remname|shortname)
	 * @var string
	 * @access private
	 */
	private $sort;
	
	
	/**
	 * Symptom-remedy-relations table
	 * @var string
	 * @access private
	 */
	private $sym_rem_tbl;
	
	
	/**
	 * Class constructor
	 *
	 * @return SymRem
	 * @access public
	 */
	function __construct() {
		global $db;
		$this->sym_id = (empty($_REQUEST['sym'])) ? 0 : $_REQUEST['sym'];
		$this->sort = (isset($_REQUEST['sort'])) ? $_REQUEST['sort'] : 'grade';
		$this->grade = (isset($_REQUEST['grade'])) ? $_REQUEST['grade'] : 1;
		$this->sym_rem_tbl = $db->get_custom_table("sym_rem");
		$this->set_sym_rems();
	}
	
	/**
	 * set_sym_rems retrieves the details of remedies related to the given symptom ($this->sym_id) and stores them in an array ($this->rems_ar)
	 *
	 * @return void
	 * @access private
	 */
	private function set_sym_rems() {
		global $db;
		$query = "SELECT remedies.rem_id, remedies.rem_short, remedies.rem_name, {$this->sym_rem_tbl}.grade, {$this->sym_rem_tbl}.src_id FROM remedies, {$this->sym_rem_tbl} WHERE {$this->sym_rem_tbl}.sym_id = {$this->sym_id} AND {$this->sym_rem_tbl}.rem_id = remedies.rem_id ";
		if ($this->grade > 1) {
			$query .= "AND  {$this->sym_rem_tbl}.grade >= {$this->grade} ";
		}
		switch ($this->sort) {
			case 'grade':
				$query .= "ORDER BY {$this->sym_rem_tbl}.grade DESC, remedies.rem_name ASC";
				break;
			case 'remname':
				$query .= "ORDER BY remedies.rem_name";
				break;
			case 'shortname':
				$query .= "ORDER BY remedies.rem_short";
				break;
		}
		$db->send_query($query);
		$this->rem_count = $db->db_num_rows();
		if ($this->rem_count > 0)   {
			while (list($rem_id, $rem_short, $rem_name, $grade, $src_id) = $db->db_fetch_row()) {
				if (empty($this->rems_ar[$rem_id])) {
					$this->rems_ar[$rem_id] = array('shortname' => $rem_short, 'remname' => $rem_name, 'max_grade' => $grade, 'source' => array($src_id => $grade));
				} elseif (empty($this->rems_ar[$rem_id]['source'][$src_id])) {
					$this->rems_ar[$rem_id]['source'][$src_id] = $grade;
					if ($grade > $this->rems_ar[$rem_id]['max_grade']) {
						$this->rems_ar[$rem_id]['max_grade'] = $grade;
					}
				}
			}
		}
		$db->free_result();
	}
	
	/**
	 * get_grade_select returns a radio selection form for the max. desired grade (1|2|3)
	 *
	 * @param string $name The name of the radio selection form element.
	 * @return string
	 * @access public
	 */
	function get_grade_select($name = 'remgrade') {
		$grade_ar= array(1=>_("all"), 2=>"&ge;2", 3=>"&ge;3");
		$grade_radio = "";
		foreach ($grade_ar as $key => $value) {
			$grade_radio .= "<span class='nobr'><input type='radio' class='button' name='$name' id='$name$key' value='$key'";
			if($this->grade == $key){
				$grade_radio .= " checked='checked'";
			}
			$grade_radio .= " onchange= \"getSymRems('remgrade')\"> <label for='$name$key'><span class='grade_$key'>$value</span></label>&nbsp;</span>";
		}
		return $grade_radio;
	}
	
	/**
	 * get_sort_select returns a html selection form for the desired sorting
	 *
	 * @return string
	 * @access public
	 */
	function get_sort_select() {
		$sort_ar= array('grade'=>_("Grade"), 'remname'=>_("Remedy name"), 'shortname'=>_(" Abbreviation"));
		$sort_select = "<select class='drop-down' name='sort' id='sort' size='1' onchange= \"getSymRems('remgrade')\">\n";
		foreach ($sort_ar as $key => $value) {
			$sort_select .= "  <option value='$key'";
			
			if($this->sort == $key){
				$sort_select .= " selected='selected'";
			}
			$sort_select .= ">$value</option>\n";
		}
		$sort_select .= "</select>\n";
		return $sort_select;
	}
	
	/**
	 * get_rems_list returns a sorted html list of remedies related to the given symptom ($this->sym_id)
	 *
	 * @return string
	 * @access public
	 */
	function get_rems_list() {
		$rems_list = "";
		foreach ($this->rems_ar as $rem_id => $rem_ar) {
			$sources_ar = array();
			foreach ($rem_ar['source'] as $source => $grade) {
				if ($grade != $rem_ar['max_grade']) {
					$source .= "($grade" . _("-gr.") . ")";
				}
				$sources_ar[] = $source;
			}
			$title = $rem_ar['max_grade'] . _("-gr.") . ": " . implode("/", $sources_ar);
			$text = $rem_ar['remname'] . " (" . $rem_ar['shortname'] . ")";
			$tag = "div";
			$class = "";
			if ($this->sort == 'shortname') {
				$title = $rem_ar['remname'] . " (" . $rem_ar['max_grade'] . _("-gr.") . "): " . implode("/", $sources_ar);
				$text = $rem_ar['shortname'];
				end($this->rems_ar);
				if ($rem_id !== key($this->rems_ar)) {
					$text .= ",<span style='white-space:normal;'> </span>";
				}
				$tag = "span";
				$class = " class='nobr'";
			}
			if (isset($_REQUEST['tab'])) {
				$materia_url = "javascript:tabOpen(\"materia.php?rem=\", $rem_id, \"GET\", 2)";
			} else {
				$materia_url = "materia.php?rem=$rem_id";
			}
			$rems_list .= "<$tag$class><a href='$materia_url' title='" . _("Materia Medica") . "'>&nbsp;<img src='skins/original/img/materia.png' width='12' height='12'>&nbsp;</a><a href=\"javascript:popup_url('details.php?sym={$this->sym_id}&rem=$rem_id&sym_rem_tbl={$this->sym_rem_tbl}',540,380)\" title='$title' class='grade_" . $rem_ar['max_grade'] . "'>$text</a></$tag>";
		}
		return $rems_list;
	}
}
