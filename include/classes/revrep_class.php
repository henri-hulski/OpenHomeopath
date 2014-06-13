<?php

/**
 * revrep_class.php
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
 * @package   RevRep
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

require_once("include/classes/treeview_class.php");

/**
 * The RevRep class is responsible for the reversed repertorization for a given remedy in the materia medica (materia.php)
 *
 * @category  Homeopathy
 * @package   RevRep
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 */
class RevRep extends TreeView {

	/**
	 * Number of found symptoms
	 * @var integer
	 * @access public
	 */
	public $sym_count;
	
	
	/**
	 * Ordered array of main rubrics realted to the queried remedy
	 * @var array
	 * @access private
	 */
	private $rubrics_ar = array();
	
	
	/**
	 * Grade from which on symptoms will be shown (1|2|3)
	 * @var integer
	 * @access private
	 */
	private $grade = 1;

	/**
	 * Class constructor
	 *
	 * @return \RevRep
	@access public
	 */
	function __construct() {
		global $db;
		$this->rem_id = (empty($_REQUEST['rem'])) ? 0 : $_REQUEST['rem'];
		$this->grade = (isset($_REQUEST['grade'])) ? $_REQUEST['grade'] : 1;
		$this->sym_rem_tbl = $db->get_custom_table("sym_rem");
		$this->symptoms_tbl = $db->get_custom_table("symptoms");
		$this->tree_symptoms_tbl = 'rem_symptoms';
	}
	
	
	/**
	 * get_grade_select returns a radio selection form for the max. desired grade (1|2|3)
	 *
	 * @return string
	 * @access public
	 */
	function get_grade_select() {
		$grade_ar= array(1=>_("all"), 2=>"&ge;2", 3=>"&ge;3");
		$grade_radio = "";
		foreach ($grade_ar as $key => $value) {
			$grade_radio .= "<span class='nobr'><input type='radio' class='button' name='grade' id='grade$key' value='$key'";
			if($this->grade == $key){
				$grade_radio .= " checked='checked'";
			}
			$grade_radio .= " onchange= \"getSymRems('remgrade')\"> <label for='grade$key'><span class='grade_$key'>$value</span></label>&nbsp;</span>";
		}
		return $grade_radio;
	}
		
	/**
	 * prepare_rem_symptoms retrieves the related remedy-symptom-relations and store them in a temporary table for building the symptom tree.
	 *
	 * @param array  $rem_rubrics_ar contains either the requested main rubric or if none an ordered array of main rubrics related with the queried remedy
	 * @return void
	 * @access public
	 */
	function prepare_rem_symptoms($rem_rubrics_ar) {
		global $db;
		$query = "SELECT DISTINCT {$this->symptoms_tbl}.sym_id, {$this->symptoms_tbl}.symptom, {$this->symptoms_tbl}.pid, {$this->symptoms_tbl}.rubric_id FROM {$this->sym_rem_tbl}, {$this->symptoms_tbl} WHERE {$this->sym_rem_tbl}.rem_id = {$this->rem_id} ";
		if ($this->grade > 1) {
			$query .= "AND  {$this->sym_rem_tbl}.grade >= {$this->grade} ";
		}
		$query .= "AND {$this->sym_rem_tbl}.sym_id = {$this->symptoms_tbl}.sym_id ";
		if (count($rem_rubrics_ar) == 1) {
			list($rubric_id) = array_keys($rem_rubrics_ar);
			$query .= "AND {$this->symptoms_tbl}.rubric_id = $rubric_id";
		}
		$result = $db->send_query($query);
		$this->sym_count = $db->db_num_rows($result);
		$new_rubrics_ar = array();
		if ($this->sym_count > 0) {
			$query = "DROP TEMPORARY TABLE IF EXISTS {$this->tree_symptoms_tbl}";
			$db->send_query($query);
			$query = "CREATE TEMPORARY TABLE {$this->tree_symptoms_tbl} (
				sym_id mediumint(8) unsigned NOT NULL,
				symptom varchar(510) NOT NULL,
				pid mediumint(8) unsigned NOT NULL,
				rubric_id tinyint(3) unsigned NOT NULL,
				PRIMARY KEY(sym_id),
				KEY pid (pid)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			$db->send_query($query);
			$query = "CREATE TEMPORARY TABLE rem_sym__1 (
				sym_id mediumint(8) unsigned NOT NULL,
				PRIMARY KEY(sym_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			$db->send_query($query);
			$query = "CREATE TEMPORARY TABLE rem_sym__2 (
				sym_id mediumint(8) unsigned NOT NULL,
				pid mediumint(8) unsigned NOT NULL,
				PRIMARY KEY(sym_id),
				KEY pid (pid)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			$db->send_query($query);
			while (list($sym_id, $symptom, $pid, $rubric_id) = $db->db_fetch_row($result)) {
				$query = "INSERT INTO {$this->tree_symptoms_tbl} SET sym_id = $sym_id, symptom = '" . $db->escape_string($symptom) . "', pid  = $pid, rubric_id = $rubric_id";
				$db->send_query($query);
				$query = "INSERT INTO rem_sym__1 SET sym_id = $sym_id";
				$db->send_query($query);
				$query = "INSERT INTO rem_sym__2 SET sym_id = $sym_id, pid  = $pid";
				$db->send_query($query);
				if (!isset($new_rubrics_ar[$rubric_id])) {
					$new_rubrics_ar[$rubric_id] = true;
				}
			}
			do {
				$query = "SELECT DISTINCT pid FROM {$this->tree_symptoms_tbl} WHERE pid != 0 AND NOT EXISTS (SELECT 1 FROM rem_sym__1 WHERE rem_sym__1.sym_id = {$this->tree_symptoms_tbl}.pid) AND EXISTS (SELECT 1 FROM rem_sym__2 WHERE rem_sym__2.pid = {$this->tree_symptoms_tbl}.pid AND rem_sym__2.sym_id != {$this->tree_symptoms_tbl}.sym_id)";
				$sub_result = $db->send_query($query);
				$num_rows = $db->db_num_rows($sub_result);
				if ($num_rows > 0) {
					while (list ($missing_pid) = $db->db_fetch_row($sub_result)) {
						$query = "SELECT {$this->symptoms_tbl}.sym_id, {$this->symptoms_tbl}.symptom, {$this->symptoms_tbl}.pid, {$this->symptoms_tbl}.rubric_id FROM {$this->symptoms_tbl} WHERE {$this->symptoms_tbl}.sym_id = $missing_pid";
						$sub_result2 = $db->send_query($query);
						$symptom = $db->db_fetch_row($sub_result2);
						$db->free_result($sub_result2);
						$query = "INSERT INTO {$this->tree_symptoms_tbl} SET sym_id = $symptom[0], symptom = '" . $db->escape_string($symptom[1]) . "', pid  = $symptom[2], rubric_id  = $symptom[3]";
						$db->send_query($query);
						$query = "INSERT INTO rem_sym__1 SET sym_id = $symptom[0]";
						$db->send_query($query);
						$query = "INSERT INTO rem_sym__2 SET sym_id = $symptom[0], pid  = $symptom[2]";
						$db->send_query($query);
					}
				}
				$db->free_result($sub_result);
			} while ($num_rows > 0);
			$query = "UPDATE {$this->tree_symptoms_tbl} SET pid = 0 WHERE NOT EXISTS (SELECT 1 FROM rem_sym__1 WHERE rem_sym__1.sym_id = {$this->tree_symptoms_tbl}.pid)";
			$db->send_query($query);
			$query = "DROP TEMPORARY TABLE IF EXISTS rem_sym__1";
			$db->send_query($query);
			$query = "DROP TEMPORARY TABLE IF EXISTS rem_sym__2";
			$db->send_query($query);
		}
		$db->free_result($result);
		$ordered_rubrics_ar = array();
		foreach ($rem_rubrics_ar as $rubric_id => $rubric_name) {
			if (!empty($new_rubrics_ar[$rubric_id])) {
				$ordered_rubrics_ar[$rubric_id] = $rubric_name;
			}
		}
		$this->rubrics_ar = $ordered_rubrics_ar;
	}
	
	
	/**
	 * build_symptomtree returns the html formatted symptom tree
	 *
	 * @return string
	 * @access public
	 */
	function build_symptomtree() {
		global $db;
		$expand = "collapse";
		$open = "_open";
		$display_child = "block";
		if (count($this->rubrics_ar) != 1) {
			$expand = "expand";
			$open = "";
			$display_child = "none";
		}
		$symptomtree = "";
		$i = 0;
		foreach ($this->rubrics_ar as $this->rubric_id => $rubric_name) {
			$symptoms_ar = $this->get_treeview();
			$child = $this->generate_child("tree2_$i", $symptoms_ar);
			$symptomtree .= "      <div id='tree2$i' style='padding-left:20px;'>\n";
			$symptomtree .= "        <span id='symbol_tree2$i'><a href=\"javascript:" . $expand . "_static('tree2_$i',1,0);\" class='nodecls_main'><img src='skins/original/img/main_folder" . $open . "_arrow.png' alt='Expand main rubric' width='14' height='14'> <img src='skins/original/img/main_folder" . $open . ".png' alt='Main rubric' width='14' height='14'> </a></span>\n";
			$symptomtree .= "        <span class='nodecls_main'>$rubric_name</span>\n      </div>\n";
			$symptomtree .= "      <div id='tree2_$i' style='padding-left:20px; display:$display_child'>\n";
			$symptomtree .= $child;
			$symptomtree .= "      </div>\n";
			$i++;
		}
		return $symptomtree;
	}
	
	
	/**
	 * generate_child returns the recursively generated child nodes for the html symptom tree
	 *
	 * @param string $output_id   id of the parent div, in which to put the child nodes
	 * @param array  $symptoms_ar array that contains the symptoms
	 * @return string
	 * @access public
	 */
	function generate_child($output_id, $symptoms_ar) {
		$str = "";
		$i = 0;
		$display = 1;
		$main_id = str_replace('_', '', $output_id);
		for($i = 0; $i < count($symptoms_ar); $i++) {
			$class = "grade_" . $symptoms_ar[$i]['max_grade'];
			if (!empty($symptoms_ar[$i]['sources'])) {
				$sources_ar = array();
				foreach ($symptoms_ar[$i]['sources'] as $src_id => $grade) {
					$source = "$src_id";
					if ($grade != $symptoms_ar[$i]['max_grade']) {
						$source .= "($grade" . _("-gr.") . ")";
					}
					$sources_ar[] = $source;
				}
				$sources = implode("/", $sources_ar);
			}
			$str .= "<div id='" . $main_id . $i . "' style='padding-left:20px;'>\n";
			if ($symptoms_ar[$i]['folder'] > 0) {
				$child_ar = $this->get_treeview($symptoms_ar[$i]['id']);
				$child = $this->generate_child($output_id . "_" . $i, $child_ar);
				if ($symptoms_ar[$i]['in_use'] > 0) {
					$str .= "  <span id='symbol_" . $main_id . "" . $i . "'><a href=\"javascript:expand_static('" . $output_id . "_" . $i . "',0,1);\"><img src='skins/original/img/folder_arrow.png'  alt='Expand rubric' width='12' height='12'> <img src='skins/original/img/folder_aeskulap.png' alt='Symptom folder' width='12' height='12'> </a></span>\n";
					$str .= "  <a href=\"javascript:popup_url('details.php?sym=" . $symptoms_ar[$i]['id'] . "&amp;rem={$this->rem_id}&amp;sym_rem_tbl={$this->sym_rem_tbl}',540,380)\" title='" . $symptoms_ar[$i]['max_grade'] . _("-gr.") . ": $sources' class='$class'>" . $symptoms_ar[$i]['name'] . "</a>\n";
					$str .= "  <a href='javascript:symptomData(" . $symptoms_ar[$i]['id'] . ");' title='" . _("Symptom-Info") . "'><img src='skins/original/img/info.gif' width='12' height='12' alt='Info'></a>\n";
				} else {
					$str .= "  <span id='symbol_" . $main_id . $i . "'><a href=\"javascript:expand_static('" . $output_id . "_" . $i . "',0,0);\"><img src='skins/original/img/folder_arrow.png'  alt='Expand rubric' width='12' height='12'> <img src='skins/original/img/folder.png' alt='Folder' width='12' height='12'> </a></span>\n";
					$str .= "  <span class='$class'>" . $symptoms_ar[$i]['name'] . "</span>\n";
				}
				$str .= "</div>\n";
				$str .= "<div id='" . $output_id . "_" . $i . "' style='padding-left:20px;display:none'>\n";
				$str .= $child;
				$str .= "</div>\n";
			} else {
				$str .= "  <span class='nodecls'><span style='visibility:hidden'><img src='skins/original/img/folder_arrow.png'  alt='Expand rubric' width='12' height='12'> </span><img src='skins/original/img/aeskulap.png' alt='Symptom' width='12' height='12'> </span>\n";
				$str .= "  <a href=\"javascript:popup_url('details.php?sym=" . $symptoms_ar[$i]['id'] . "&amp;rem={$this->rem_id}&amp;sym_rem_tbl={$this->sym_rem_tbl}',540,380)\" title='" . $symptoms_ar[$i]['max_grade'] . _("-gr.") . ": $sources' class='$class'>" . $symptoms_ar[$i]['name'] . "</a>\n";
				$str .= "  <a href='javascript:symptomData(" . $symptoms_ar[$i]['id'] . ");' title='" . _("Symptom-Info") . "'><img src='skins/original/img/info.gif' width='12' height='12' alt='Info'></a>\n";
				$str .= "</div>\n";
			}
		}
		return $str;
	}
}
