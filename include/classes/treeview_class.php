<?php

/**
 * treeview_class.php
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
 * @package   TreeView
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

/**
 * The TreeView class is responsible for builing the html symptom tree for symptoms presentation and selection
 *
 * @category  Homeopathy
 * @package   TreeView
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 */
class TreeView {

	/**
	 * Symptoms table
	 * @var string
	 * @access public
	 */
	public $symptoms_tbl;
	
	
	/**
	 * Symptom-remedy-relations table
	 * @var string
	 * @access public
	 */
	public $sym_rem_tbl;
	
	
	/**
	 * Main rubric id for which we're building the treeview. If we build for all main rubrics it has the value -1.
	 * @var integer
	 * @access public
	 */
	public $rubric_id;
	
	
	/**
	 * Symptoms table to build the tree from
	 * @var string
	 * @access public
	 */
	public $tree_symptoms_tbl = "";
	
	
	/**
	 * Remedy ID to use for the reversed repertorization in other cases 0
	 * @var integer
	 * @access public
	 */
	public $rem_id = 0;
	
	/**
	 * Class constructor
	 *
	 * @param integer $rubric_id         Main rubric id for which we're building the treeview. If we build for all main rubrics it has the value -1.
	 * @param string  $tree_symptoms_tbl Symptoms table to build the tree from.
	 * @return void
	 * @access public
	 */
	function __construct($rubric_id, $tree_symptoms_tbl = "") {
		global $db;
		$this->symptoms_tbl = $db->get_custom_table("symptoms");
		$this->sym_rem_tbl = $db->get_custom_table("sym_rem");
		$this->rubric_id = $rubric_id;
		$this->tree_symptoms_tbl = $tree_symptoms_tbl;
	}
	
	/**
	 * build_symptomtree returns the html formatted symptom tree
	 *
	 * @param boolean $static_tree if false we're building a tree that will be dynamically expanded, if true we build a static tree
	 * @return string
	 * @access public
	 */
	function build_symptomtree($static_tree = false) {
		global $db, $lang;
		if ($this->rubric_id == -1) {
			$query = "SELECT DISTINCT {$this->tree_symptoms_tbl}.rubric_id, main_rubrics.rubric_$lang FROM {$this->tree_symptoms_tbl}, main_rubrics WHERE main_rubrics.rubric_id = {$this->tree_symptoms_tbl}.rubric_id ORDER BY main_rubrics.rubric_$lang";
			$result = $db->send_query($query);
			$symptomtree = "";
			$i = 0;
			while (list($this->rubric_id, $rubric_name) = $db->db_fetch_row($result)) {
				$child = "";
				$expand = "expand('tree1_$i',{$this->rubric_id},0,1,0)";
				if ($static_tree) {
					$symptoms_ar = $this->get_treeview();
					$rep_select = true;
					$child = $this->generate_child("tree1_$i", $symptoms_ar, $rep_select);
					$expand = "expand_static('tree1_$i',1,0)";
				}
				$symptomtree .= "      <div id='tree1$i' style='padding-left:20px;'>\n";
				$symptomtree .= "        <span id='symbol_tree1$i'><a href=\"javascript:$expand;\" class='nodecls_main'><img src='skins/original/img/main_folder_arrow.png' width='14' height='14'> <img src='skins/original/img/main_folder.png' width='14' height='14'> </a></span>\n";
				$symptomtree .= "        <span class='nodecls_main'>$rubric_name</span>\n      </div>\n";
				$symptomtree .= "      <div id='tree1_$i' style='padding-left:20px;display:none'>\n";
				$symptomtree .= $child;
				$symptomtree .= "      </div>\n";
				$i++;
			}
			$db->free_result($result);
		} else {
			$collapse = "collapse('tree1_0',{$this->rubric_id},0,1,0)";
			$child = "";
			if ($static_tree) {
				$collapse = "collapse_static('tree1_0',1,0)";
				$symptoms_ar = $this->get_treeview();
				$child = $this->generate_child("tree1_0", $symptoms_ar, 1);
			}
			$query = "SELECT rubric_$lang FROM main_rubrics WHERE rubric_id = {$this->rubric_id}";
			$db->send_query($query);
			list ($rubric_name) = $db->db_fetch_row();
			$db->free_result();
			$symptomtree = "      <div id='tree10' style='padding-left:20px;'>\n";
			$symptomtree .= "        <span id='symbol_tree10'><a href=\"javascript:;\" class='nodecls_main'><img src='skins/original/img/main_folder_open_arrow.png' width='14' height='14'> <img src='skins/original/img/main_folder_open.png' width='14' height='14'> </a></span>\n";
			$symptomtree .= "        <span class='nodecls_main'>$rubric_name</span>\n      </div>\n";
			$symptomtree .= "      <div id='tree1_0' style='padding-left:20px; display:block'>\n";
			$symptomtree .= $child;
			$symptomtree .= "      </div>\n";
		}
		return $symptomtree;
	}
	
	/**
	 * get_treeview returns an array with the symptoms prepared for the treeview.
	 *
	 * @param integer $pid ID of the parents symptom, 0 if there is no parent
	 * @return array
	 * @access public
	 */
	function get_treeview($pid = 0) {
		global $db;
		$symptoms_tbl = empty($this->tree_symptoms_tbl) ? $this->symptoms_tbl : $this->tree_symptoms_tbl;
		$query = "SELECT sym_id, symptom FROM $symptoms_tbl WHERE rubric_id = {$this->rubric_id} AND pid = $pid ORDER BY symptom";
		$result = $db->send_query($query);
		$symptoms_ar = array();
		$i = 0;
		while (list ($sym_id, $symptom) = $db->db_fetch_row($result)) {
			if (!empty($pid)) {
				$query = "SELECT symptom FROM $symptoms_tbl WHERE sym_id = $pid";
				$db->send_query($query);
				list($parents_name) = $db->db_fetch_row();
				$db->free_result();
				$symptom = substr($symptom, strlen($parents_name) + 3);
			}
			$symptoms_ar[$i]['id'] = $sym_id;
			$symptoms_ar[$i]['name'] = $symptom;
			$symptoms_ar[$i]['pid'] = $pid;
			$query = "SELECT pid FROM $symptoms_tbl WHERE pid = $sym_id LIMIT 1";
			$db->send_query($query);
			$num_rows = $db->db_num_rows();
			$db->free_result();
			$symptoms_ar[$i]['folder'] = $num_rows;
			if (!empty($this->rem_id)) {
				$query = "SELECT src_id, grade FROM {$this->sym_rem_tbl} WHERE sym_id = $sym_id AND rem_id = {$this->rem_id} ORDER BY grade DESC, src_id ASC";
				$db->send_query($query);
				while (list($src_id, $grade) = $db->db_fetch_row()) {
					if (empty($symptoms_ar[$i]['max_grade']) || $grade > $symptoms_ar[$i]['max_grade']) {
						$symptoms_ar[$i]['max_grade'] = $grade;
					}
					$symptoms_ar[$i]['sources'][$src_id] = $grade;
				}
				$db->free_result();
				$symptoms_ar[$i]['max_grade'] = (empty($symptoms_ar[$i]['max_grade'])) ? 0 : $symptoms_ar[$i]['max_grade'];
				$in_use_where = "sym_id = $sym_id AND rem_id = {$this->rem_id}";
			} else {
				$in_use_where = "sym_id = $sym_id";
			}
			$symptoms_ar[$i]['in_use'] = 1;
			if ($num_rows > 0) {
				$query = "SELECT sym_id FROM {$this->sym_rem_tbl} WHERE $in_use_where LIMIT 1";
				$db->send_query($query);
				$num_rows = $db->db_num_rows();
				$db->free_result();
				$symptoms_ar[$i]['in_use'] = $num_rows;
			}
			$i++;
		}
		$db->free_result($result);
		return $symptoms_ar;
	}
	
	/**
	 * generate_child returns the recursively generated child nodes for the html symptom tree
	 *
	 * @param string $output_id   id of the parent div, in which to put the child nodes
	 * @param array  $symptoms_ar array that contains the symptoms
	 * @param boolean $rep_select if true you can select the symptoms for repertorization, false otherwise
	 * @return string
	 * @access public
	 */
	function generate_child($output_id, $symptoms_ar, $rep_select = false) {
		$str = "";
		$i = 0;
		$display = 1;
		$main_id = str_replace('_', '', $output_id);
		for($i = 0; $i < count($symptoms_ar); $i++) {
			$str .= "<div id='" . $main_id . $i . "' style='padding-left:20px;'>\n";
			if ($symptoms_ar[$i]['folder'] > 0) {
				$child_ar = $this->get_treeview($symptoms_ar[$i]['id']);
				$child = $this->generate_child($output_id . "_" . $i, $child_ar, $rep_select);
				if ($symptoms_ar[$i]['in_use'] > 0) {
					$str .= "  <span id='symbol_" . $main_id . "" . $i . "'><a href=\"javascript:expand_static('" . $output_id . "_" . $i . "',0,1);\" class='nodecls'><img src='skins/original/img/folder_arrow.png' width='12' height='12'> <img src='skins/original/img/folder_aeskulap.png' width='12' height='12'> </a></span>\n";
					if ($rep_select) {
						$str .= "  <a href='javascript:selectSymptom(" . $symptoms_ar[$i]['id'] . ");' class='nodecls'>" . $symptoms_ar[$i]['name']. "</a>\n";
					} else {
						$str .= "  " . $symptoms_ar[$i]['name']. "\n";
					}
					$str .= "  <a href='javascript:symptomData(" . $symptoms_ar[$i]['id'] . ");' class='nodecls' title='" . _("Symptom-Info") . "'><img src='skins/original/img/info.gif' width='12' height='12'></a>\n";
				} else {
					$str .= "  <span id='symbol_" . $main_id . $i . "'><a href=\"javascript:expand_static('" . $output_id . "_" . $i . "',0,0);\" class='nodecls'><img src='skins/original/img/folder_arrow.png' width='12' height='12'> <img src='skins/original/img/folder.png' width='12' height='12'> </a></span>\n";
					$str .= "  <span class='nodecls'>" . $symptoms_ar[$i]['name']. "</span>\n";
				}
				$str .= "</div>\n";
				$str .= "<div id='" . $output_id . "_" . $i . "' style='padding-left:20px;display:none'>\n";
				$str .= $child;
				$str .= "</div>\n";
			} else {
				$str .= "  <span class='nodecls'><span style='visibility:hidden'><img src='skins/original/img/folder_arrow.png' width='12' height='12'> </span><img src='skins/original/img/aeskulap.png' width='12' height='12'> </span>\n";
				if ($rep_select) {
					$str .= "  <a href='javascript:selectSymptom(" . $symptoms_ar[$i]['id'] . ");' class='nodecls'>" . $symptoms_ar[$i]['name']. "</a>\n";
				} else {
					$str .= "  " . $symptoms_ar[$i]['name']. "\n";
				}
				$str .= "  <a href='javascript:symptomData(" . $symptoms_ar[$i]['id'] . ");' class='nodecls' title='" . _("Symptom-Info") . "'><img src='./skins/original/img/info.gif' width='12' height='12'></a>\n";
				$str .= "</div>\n";
			}
		}
		return $str;
	}
}
?>
