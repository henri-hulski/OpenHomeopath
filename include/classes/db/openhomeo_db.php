<?php

/**
 * openhomeo_db.php
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
 * @package   OpenHomeoDB
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

require_once ("include/classes/db/config_openhomeopath.php");
require_once("include/classes/db/db.php");

/**
 * Short description for class
 *
 * Long description (if any) ...
 *
 * @category  Database
 * @package   OpenHomeoDB
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 */
class OpenHomeoDB extends DB {

	/* archiving of edited or deleted table rows */
	
    /**
     * Short description for function
     *
     * Long description (if any) ...
     *
     * @param unknown $table        Parameter description (if any) ...
     * @param unknown $where        Parameter description (if any) ...
     * @param unknown $archive_type Parameter description (if any) ...
     * @return void   
     * @access public
     */
	function archive_table_row($table, $where, $archive_type) {
		$query = "INSERT INTO archive__$table SELECT *, '$archive_type' FROM $table WHERE $where";
		$this->send_query($query);
}
	
	/* restoring of archived table rows */
		
    /**
     * Short description for function
     *
     * Long description (if any) ...
     *
     * @param unknown $table     Parameter description (if any) ...
     * @param unknown $where     Parameter description (if any) ...
     * @param unknown $timestamp Parameter description (if any) ...
     * @return void   
     * @access public
     */
	function restore_table_row($table, $where, $timestamp) {
		global $session;
		$archive_type = "restore_$timestamp";
		$this->archive_table_row($table, $where, $archive_type);
		$query = "DESCRIBE $table";
		$this->send_query($query);
		while ($row = $this->db_fetch_assoc()) {
			$fields_ar[] = $row["Field"];
		}
		$this->free_result();
		$select = "";
		foreach ($fields_ar as $field) {
			$select .= "`$field`, ";
		}
		$select = substr($select, 0, -2); // delete the last ', '
		$query = "REPLACE INTO `$table` SELECT $select FROM `archive__$table` WHERE $where AND `timestamp`='$timestamp'";
		$this->send_query($query);
		$username = $session->username;
		$query = "UPDATE `$table` SET `timestamp`= NOW(), `username`= '$username' WHERE $where";
		$this->send_query($query);
	}
	
    /**
     * Short description for function
     *
     * Long description (if any) ...
     *
     * @param string $table Parameter description (if any) ...
     * @return string Return description (if any) ...
     * @access public
     */
	function get_custom_table($table) {
		global $session;
		if ($session->logged_in) {  // user logged in
			if ($table === "materia") {
				$src = "src_materia";
			} else {
				$src = "src_rep";
			}
			$username = $session->username;
			$query = "SELECT $src FROM users WHERE username='$username'";
			$this->send_query($query);
			list($src_row) = $this->db_fetch_row();
			$this->free_result();
			if ($src_row !== 'all') {
				if ($src === "src_rep" && $sym_lang = $this->get_lang_only_symptom_table()) {
					if ($table === "symptoms") {
						$custom_table = $sym_lang['table'];
					} else {
						$custom_table = $table;
					}
				} elseif ($src_row === 'custom') {
					if ($table === "materia") {
						$src_table = "custom_materia";
					} else {
						$src_table = "custom_rep";
					}
					$custom_src_ar = $this->get_custom_src($username, $src_table);
					$src_nr_ar = $this->get_source_nr($custom_src_ar);
					$custom_src = implode("_", $src_nr_ar);
					$custom_table = $table . "__" . $custom_src;
					if ($table === "symptoms") {
						$custom_table = "sym__" . $custom_src;
						$symptom_lang = $this->get_custom_symptom_lang($custom_src_ar);
						if ($symptom_lang !== false) {
							$custom_table .= "_$symptom_lang";
						}
					}
					if ($this->table_exists($custom_table) === false) {
						if ($table === "symptoms") {
							$this->create_custom_symptom_table();
						} else {
							$this->create_custom_table($table);
						}
					}
				}
			} elseif ($table === "symptoms") {
				$custom_table = $this->get_sym_base_table();
			} else {
				$custom_table = $table;
			}
		} elseif ($table === "symptoms" || $table === "sym_rem") {
			$lang = $session->lang;
			if ($this->is_sym_lang($lang) === false) {
				$lang = "en";
			}
			if ($table === "symptoms") {
				if ($lang == 'en') {
					$custom_table = DEFAULT_SYMPTOM_TABLE_EN;
				} elseif ($lang == 'de') {
					$custom_table = DEFAULT_SYMPTOM_TABLE_DE;
				}
			} elseif ($table === "sym_rem") {
				if ($lang == 'en') {
					$custom_table = DEFAULT_SYMPTOM_REMEDY_TABLE_EN;
				} elseif ($lang == 'de') {
					$custom_table = DEFAULT_SYMPTOM_REMEDY_TABLE_DE;
				}
			}
		} else {
			$custom_table = $table;
		}
		return $custom_table;
	}

    /**
     * Short description for function
     *
     * Long description (if any) ...
     *
     * @param unknown $username  Parameter description (if any) ...
     * @param unknown $src_table Parameter description (if any) ...
     * @return array   Return description (if any) ...
     * @access public
     */
	function get_custom_src($username, $src_table) {
		$custom_src_ar = array();
		$query = "SELECT src_id FROM $src_table WHERE username='$username'";
		$this->send_query($query);
		while (list($src_id) = $this->db_fetch_row()) {
			$custom_src_ar[] = $src_id;
		}
		$this->free_result();
		return $custom_src_ar;
	}

	/** function get_sym_base_table returns the sym-basetable to be used for symptoms. If there exists no symptom-translations the whole sym-table will be used. If there are translations the language-based sym-table according to the usersettings will be used. If given $custom_symptom_lang the given language will be used. **/
	
	function get_sym_base_table($custom_symptom_lang = false) {
		global $session;
		$sym_base_table = "symptoms";
		if ($custom_symptom_lang === false) {
			$custom_symptom_lang = $this->get_custom_symptom_lang();
		}
		if ($custom_symptom_lang !== false) {
			$sym_base_table = "sym__$custom_symptom_lang";
			if ($this->table_exists($sym_base_table) === false) {
				$this->update_lang_symptom_tables(1);
			}
		}
		return $sym_base_table;
	}

	/** function get_custom_symptom_lang returns the language to be used for symptoms. If there exists no symptom-translations the function returns false. If there are translations the language according to the usersettings will be used. If given an array of src_id's as $sources the sym-language will only be returned, when for one of the given sources exists an translation. Else the function returns false. **/
	
	function get_custom_symptom_lang($sources = 'all') {
		global $session;
		$custom_symptom_lang = false;
		if ($this->exist_symptom_translation($sources) === true) {
			$custom_symptom_lang = $session->lang;
			if ($session->logged_in || isset($_REQUEST['patient'])) {  // user logged in oder gespeicherte Rep wird aufgerufen
				$username = $session->username;
				list($user_sym_lang) = $this->getUserInfo($username, 'sym_lang_id');
				if (!empty($user_sym_lang) && $this->is_sym_lang($user_sym_lang)) {
					$custom_symptom_lang = $user_sym_lang;
				}
			}
			if ($this->is_sym_lang($custom_symptom_lang) === false) {
				$custom_symptom_lang = "en";
			}
		}
		return $custom_symptom_lang;
	}

	
    /**
     * Short description for function
     *
     * Long description (if any) ...
     *
     * @return mixed  Return description (if any) ...
     * @access public
     */
	function get_lang_only_symptom_table() {
		global $session;
		$sym_lang = false;
		if ($this->exist_symptom_translation() === true) {
			$username = $session->username;
			$query = "SELECT src_rep FROM users WHERE username='$username'";
			$this->send_query($query);
			list($src_rep) = $this->db_fetch_row();
			$this->free_result();
			if (strpos($src_rep, "lang_") === 0) {  // starts with "lang_" -> Position 0
				$sym_lang['id'] = substr($src_rep, 5);
				$sym_lang['table'] = "sym__" . $sym_lang['id'] . "_only";
				$lang = $session->lang;
				$query = "SELECT lang_$lang FROM languages WHERE lang_id = '" . $sym_lang['id'] . "'";
				$this->send_query($query);
				list($sym_lang['name']) = $this->db_fetch_row();
				$this->free_result();
			}
		}
		return $sym_lang;
	}
	
    /**
     * Short description for function
     *
     * Long description (if any) ...
     *
     * @param unknown $lang_id Parameter description (if any) ...
     * @return mixed   Return description (if any) ...
     * @access public
     */
	function is_sym_lang($lang_id) {
		$query = "SELECT sym_lang FROM languages WHERE lang_id = '$lang_id'";
		$this->send_query($query);
		list ($is_sym_lang) = $this->db_fetch_row();
		$this->free_result();
		if ($is_sym_lang === 0) {
			$is_sym_lang = false;
		} else {
			$is_sym_lang = true;
		}
		return $is_sym_lang;
	}
	
    /**
     * Short description for function
     *
     * Long description (if any) ...
     *
     * @param string  $table Parameter description (if any) ...
     * @return boolean Return description (if any) ...
     * @access public
     */
	function is_custom_table($table) {
		global $session;
		$is_custom_table = false;
		if ($session->logged_in) {  // user logged in
			if ($table == "materia") {
				$src = "src_materia";
			} else {
				$src = "src_rep";
			}
			$username = $session->username;
			$query = "SELECT $src FROM users WHERE username='$username'";
			$this->send_query($query);
			list($src_row) = $this->db_fetch_row();
			$this->free_result();
			if ($table === "symptoms" && $sym_lang = $this->get_lang_only_symptom_table()) {
				$is_custom_table = $sym_lang['name'];
			} elseif ($src_row == 'custom') {
				$is_custom_table = true;
			}
		}
		return $is_custom_table;
	}

	/** function exist_symptom_translation checks if there exists a translation for symptoms from the symptoms table. If src_id's-array given as $sources only with symptoms related to the sources **/
	
	function exist_symptom_translation($sources = 'all') {
		$exist_symptom_translation = false;
		if ($sources == 'all') {
			$query = "SELECT sym_id FROM symptoms s WHERE EXISTS (SELECT 1 FROM sym_translations st WHERE st.sym_id = s.sym_id) LIMIT 1";
		} else {
			$source_query = implode("' || sym_rem.src_id = '", $sources);
			$where = "(sym_rem.src_id = '" . $source_query . "')";
			$query = "SELECT s.sym_id FROM symptoms s, sym_rem WHERE s.sym_id = sym_rem.sym_id AND $where AND EXISTS (SELECT 1 FROM sym_translations st WHERE st.sym_id = s.sym_id) LIMIT 1";
		}
		$this->send_query($query);
		$num = $this->db_num_rows();
		$this->free_result();
		if ($num > 0) {
			$exist_symptom_translation = true;
		}
		return $exist_symptom_translation;
	}

	/** function is_translated checks if the given symptom has a translation **/
	
	function is_translated($sym_id) {
		$is_translated = false;
		$query = "SELECT sym_id FROM sym_translations WHERE sym_id = $sym_id LIMIT 1";
		$this->send_query($query);
		$num = $this->db_num_rows();
		$this->free_result();
		if ($num > 0) {
			$is_translated = true;
		}
		return $is_translated;
	}

    /**
     * Short description for function
     *
     * Long description (if any) ...
     *
     * @param string $table Parameter description (if any) ...
     * @return void  
     * @access public
     */
	function create_custom_table($table) {
		global $session;
		if ($table == "materia") {
			$src = "src_materia";
		} else {
			$src = "src_rep";
		}
		$username = $session->username;
		$query = "SELECT $src FROM users WHERE username='$username'";
		$this->send_query($query);
		list($src_row) = $this->db_fetch_row();
		$this->free_result();
		if ($src_row == 'custom') {
			if ($table == "materia") {
				$src_table = "custom_materia";
			} else {
				$src_table = "custom_rep";
			}
			$src_id_ar = $this->get_custom_src($username, $src_table);
			$src_no_ar = $this->get_source_nr($src_id_ar);
			$custom_src = implode("_", $src_no_ar);
			$source_query = implode("' || src_id = '", $src_id_ar);
			$where = "(src_id = '" . $source_query . "')";
			$query = "CREATE OR REPLACE VIEW " . $table . "__" . $custom_src . " AS SELECT * FROM $table WHERE $where";
			$this->send_query($query);
		}
	}

    /**
     * Short description for function
     *
     * Long description (if any) ...
     *
     * @param integer $update Parameter description (if any) ...
     * @return void   
     * @access public
     */
	function create_custom_symptom_table($update = 0) {
		global $session;
		$username = $session->username;
		$query = "SELECT src_rep FROM users WHERE username='$username'";
		$this->send_query($query);
		list($src_rep) = $this->db_fetch_row();
		$this->free_result();
		if ($src_rep == 'custom') {
			$src_id_ar = $this->get_custom_src($username, 'custom_rep');
			$symptom_lang = $this->get_custom_symptom_lang($src_id_ar);
			$sym_table = $this->get_sym_base_table($symptom_lang);
			$src_no_ar = $this->get_source_nr($src_id_ar);
			$custom_src = implode("_", $src_no_ar);
			if ($symptom_lang !== false) {
				$custom_src .= "_$symptom_lang";    // for sources with different translations adds the language at the end of the symptoms tablename (e.g. _en)
			}
			$source_query = implode("' || sym_rem.src_id = '", $src_id_ar);
			$where = "(sym_rem.src_id = '" . $source_query . "')";
			if ($this->table_exists("sym__$custom_src") === false || $update == 1) {
				set_time_limit(0);
				ignore_user_abort(true);
				$query = "DROP TABLE IF EXISTS sym__$custom_src";
				$this->send_query($query);
				$query = "CREATE TABLE sym__$custom_src LIKE symptoms";
				$this->send_query($query);
				$query = "INSERT INTO sym__$custom_src SELECT DISTINCT $sym_table.* FROM $sym_table, sym_rem WHERE $sym_table.sym_id = sym_rem.sym_id AND $where ORDER BY $sym_table.sym_id";
				$this->send_query($query);
				$this->add_missing_parents("sym__$custom_src");
				$this->update_symptom_tree("sym__$custom_src");
				$query = "SELECT COUNT(*) FROM $sym_table";
				$this->send_query($query);
				list ($sym_num) = $this->db_fetch_row();
				$this->free_result();
				$query = "INSERT INTO sym_stats (sym_table, sym_base_table, sym_count, username) VALUES ('sym__$custom_src', '$sym_table', $sym_num, '$username') ON DUPLICATE KEY UPDATE sym_base_table = '$sym_table', sym_count = $sym_num, username = '$username'";
				$this->send_query($query);
			} else {
				$this->update_custom_symptom_table();
			}
		}
	}

    /**
     * Short description for function
     *
     * Long description (if any) ...
     *
     * @return void  
     * @access public
     */
	function update_custom_symptom_table() {
		global $session;
		if ($session->logged_in && $this->is_custom_table('symptoms') === true) {
			$symptoms_tbl = $this->get_custom_table('symptoms');
			$query = "SELECT sym_count, sym_base_table FROM sym_stats WHERE sym_table = '$symptoms_tbl'";
			$this->send_query($query);
			list ($sym_num, $sym_base_table) = $this->db_fetch_row();
			$this->free_result();
			$query = "SELECT COUNT(*) FROM $sym_base_table";
			$this->send_query($query);
			list ($sym_num_actual) = $this->db_fetch_row();
			$this->free_result();
			if ($sym_num_actual != $sym_num) {
				$this->create_custom_symptom_table(1);
			}
		}
	}
	
    /**
     * Short description for function
     *
     * Long description (if any) ...
     *
     * @param string  $sym_table Parameter description (if any) ...
     * @return unknown Return description (if any) ...
     * @access public
     */
	function add_missing_parents($sym_table = "symptoms") {
		global $session;
		$num_parents = 0;
		$num_changed = 0;
		set_time_limit(0);
		ignore_user_abort(true);
		$time_start = time();
		do {
			$num_rows = 0;
			$query = "SELECT s1.sym_id, s1.rubric_id, s1.symptom, s1.lang_id, s1.translation, s2.symptom FROM $sym_table AS s1 LEFT JOIN $sym_table AS s2 ON s1.pid = s2.sym_id AND s1.rubric_id = s2.rubric_id AND s1.lang_id = s2.lang_id WHERE s1.symptom LIKE '%>%' ORDER BY sym_id";
			$result = $this->send_query($query);
			while (list($sym_id, $rubric_id, $symptom, $lang_id, $translation, $db_parent) = $this->db_fetch_row($result)) {
				$symptom_ar = explode(" > ", $symptom);
				$sym_child = array_pop($symptom_ar);
				$parent = implode(" > ", $symptom_ar);
				if (empty($db_parent) || strcasecmp($parent, $db_parent) != 0) {
					$parent_escaped = $this->escape_string($parent);
					$query = "SELECT sym_id FROM $sym_table WHERE rubric_id = $rubric_id AND symptom = '$parent_escaped' LIMIT 1";
					$this->send_query($query);
					$cnt = $this->db_num_rows();
					$this->free_result();
					if ($cnt == 0) {
						$query = "SELECT sym_id FROM $sym_table WHERE rubric_id='$rubric_id' AND symptom LIKE '$parent_escaped > %' LIMIT 2";
						$this->send_query($query);
						$num = $this->db_num_rows();
						$this->free_result();
						if ($num > 1) {
							$current_user = $session->username;
							$query = "INSERT INTO $sym_table (symptom, rubric_id, lang_id, translation, username) VALUES ('$parent_escaped', $rubric_id, '$lang_id', $translation, '$current_user')";
							$this->send_query($query);
							$num_parents ++;
						} else {
							$symptom = $parent . ", " . $sym_child;
							$symptom_escaped = $this->escape_string($symptom);
							$query = "UPDATE $sym_table SET symptom = '$symptom_escaped' WHERE sym_id = $sym_id";
							$this->send_query($query);
							$num_changed ++;
						}
						$num_rows ++;
					}
				}
			}
			$this->free_result($result);
		} while ($num_rows > 0);
		$time_end = time();
		$time = $time_end - $time_start;
		$log = sprintf("<p>" . _("<strong>%d parent-rubrics</strong> were added and <strong>%d symptoms</strong> without parents-rubric were renamed. Time: <strong>%d seconds</strong>.") . "</p>", $num_parents, $num_changed, $time);
		return $log;
	}
	
    /**
     * Short description for function
     *
     * Long description (if any) ...
     *
     * @param string $sym_table Parameter description (if any) ...
     * @return string Return description (if any) ...
     * @access public
     */
	function update_symptom_tree($sym_table = "symptoms") {
		$num_rows = 0;
		$num_changed = 0;
		$num_gesamt = 0;
		$num_prev_loop = 0;
		set_time_limit(0);
		ignore_user_abort(true);
		$time_start = time();
		$query = "SELECT COUNT(*) FROM $sym_table WHERE symptom NOT LIKE '%>%'";
		$this->send_query($query);
		list($num_gesamt) = $this->db_fetch_row();
		$this->free_result();
		if ($num_gesamt > 0) {
			$query = "UPDATE $sym_table SET pid = 0 WHERE symptom NOT LIKE '%>%' AND pid != 0";
			$this->send_query($query);
			$num_changed += $this->db_affected_rows();
		}
		$symptom_schema = "%";
		do {
			$query = "SELECT rubric_id FROM $sym_table WHERE symptom LIKE '$symptom_schema>%' LIMIT 1";
			$this->send_query($query);
			$num_schema = $this->db_num_rows();
			$this->free_result();
			if ($num_schema == 0) {
				$symptom_schema_max = $symptom_schema;
			}
			$symptom_schema .= ">%";
		} while ($num_schema > 0);
		do {
			$log = "";
			if (isset($num_loop)) {
				$num_prev_loop = $num_loop;
			}
			$num_loop = 0;
			$symptom_schema = $symptom_schema_max;
			while ($symptom_schema !== "%") {
				$query = "SELECT s1.sym_id, s1.rubric_id, s1.symptom, s2.symptom FROM $sym_table AS s1 LEFT JOIN $sym_table AS s2 ON s1.pid = s2.sym_id AND s1.rubric_id = s2.rubric_id WHERE s1.symptom LIKE '$symptom_schema' AND s1.symptom NOT LIKE '$symptom_schema>%' ORDER BY sym_id";
				$result = $this->send_query($query);
				$num_rows = $this->db_num_rows($result);
				if ($num_rows > 0) {
					while (list($sym_id, $rubric_id, $symptom, $db_parent) = $this->db_fetch_row($result)) {
						$symptom_ar = explode(" > ", $symptom);
						array_pop($symptom_ar);
						$parent = implode(" > ", $symptom_ar);
						if (empty($db_parent) || strcasecmp($parent, $db_parent) != 0) {
							$parent_escaped = $this->escape_string($parent);
							$query = "SELECT sym_id FROM $sym_table WHERE rubric_id = $rubric_id AND symptom = '$parent_escaped' LIMIT 1";
							$this->send_query($query);
							$num_parents = $this->db_num_rows();
							if ($num_parents > 0) {
								list($pid) = $this->db_fetch_row();
							}
							$this->free_result();
							if ($num_parents == 0) {
								while ($num_parents == 0 && count($symptom_ar) > 1) {
									array_pop($symptom_ar);
									$parent_escaped = $this->escape_string(implode(" > ", $symptom_ar));
									$query = "SELECT sym_id FROM $sym_table WHERE rubric_id = $rubric_id AND symptom = '$parent_escaped' LIMIT 1";
									$this->send_query($query);
									$num_parents = $this->db_num_rows();
									if ($num_parents > 0) {
										list($pid) = $this->db_fetch_row();
									}
									$this->free_result();
								}
								if ($num_parents == 0) {
									$pid = 0;
								}
							}
							$query = "UPDATE $sym_table SET pid='$pid' WHERE sym_id='$sym_id'";
							$this->send_query($query);
							$num_changed ++;
							$num_loop ++;
						}
					}
					$log .= sprintf("<p>" . ngettext("<strong>%d symptom</strong> with the schema <strong>%s</strong> was parsed.", "<strong>%d symptoms</strong> with the schema <strong>%s</strong> were parsed.", $num_rows) . "</p>", $num_rows, $symptom_schema);
				}
				$this->free_result($result);
				$num_gesamt += $num_rows;
				$symptom_schema = substr($symptom_schema, 2);
			}
		} while ($num_loop > 0 && $num_loop != $num_prev_loop);
		$this->send_query("OPTIMIZE TABLE $sym_table");
		$time_end = time();
		$time = $time_end - $time_start;
		$log .= sprintf("<p>" . _("Totally <strong>%d symptoms</strong> were parsed in <strong>%d seconds</strong>.") . _("<strong>%d parent rubrics</strong> were updated.") . "</p>", $num_gesamt, $time, $num_changed);
		return $log;
	}
	
    /**
     * Short description for function
     *
     * Long description (if any) ...
     *
     * @param integer $update_tree Parameter description (if any) ...
     * @return string  Return description (if any) ...
     * @access public
     */
	function update_symptom_tables($update_tree = 0) {
		global $session;
		$log = "";
		if ($session->logged_in) {
			$query = "SELECT sym_count FROM sym_stats WHERE sym_table = 'symptoms'";
			$this->send_query($query);
			list ($sym_num) = $this->db_fetch_row();
			$this->free_result();
			$query = "SELECT COUNT(*) FROM symptoms";
			$this->send_query($query);
			list ($sym_num_actual) = $this->db_fetch_row();
			$this->free_result();
			if ($sym_num_actual != $sym_num) {
				$log_add_parents = $this->add_missing_parents();
				$log_update_tree = $this->update_symptom_tree();
				$log = $this->update_lang_symptom_tables(1);
				$log = $log_add_parents . "\n<br>\n" . $log_update_tree . "\n<br>\n" . $log;
				$current_user = $session->username;
				$query = "SELECT COUNT(*) FROM symptoms";
				$this->send_query($query);
				list ($sym_num_new) = $this->db_fetch_row();
				$query = "INSERT INTO sym_stats (sym_table, sym_base_table, sym_count, username) VALUES ('symptoms', 'symptoms', $sym_num_new, '$current_user') ON DUPLICATE KEY UPDATE sym_count = $sym_num_new, username = '$current_user'";
				$this->send_query($query);
			} elseif ($update_tree != 0) {
				$log = $this->update_lang_symptom_tables();
			}
		}
		return $log;
	}
	
    /**
     * Short description for function
     *
     * Long description (if any) ...
     *
     * @param integer $create_tables Parameter description (if any) ...
     * @return string  Return description (if any) ...
     * @access public
     */
	function update_lang_symptom_tables($create_tables = 0) {
		$log = "";
		set_time_limit(0);
		ignore_user_abort(true);
		$query = "SELECT lang_id FROM languages WHERE sym_lang != 0";
		$this->send_query($query);
		while (list($sym_lang) = $this->db_fetch_row()) {
			$sym_lang_ar[] = $sym_lang;
		}
		$this->free_result();
		if ($this->exist_symptom_translation() === true) {
			foreach ($sym_lang_ar as $sym_lang) {
				if ($create_tables != 0) {
					$query = "DROP TABLE IF EXISTS sym__$sym_lang";
					$this->send_query($query);
					$query = "CREATE TABLE IF NOT EXISTS sym__$sym_lang LIKE symptoms";
					$this->send_query($query);
					$query = "INSERT INTO sym__$sym_lang SELECT * FROM symptoms ORDER BY symptoms.sym_id";
					$this->send_query($query);
					$query = "UPDATE sym__$sym_lang s, sym_translations st SET s.symptom = st.symptom, s.lang_id = st.lang_id, s.translation = 1, s.username = st.username, s.`timestamp` = st.`timestamp` WHERE s.sym_id = st.sym_id AND st.lang_id = '$sym_lang'";
					$this->send_query($query);
				}
				$log_add_parents = $this->add_missing_parents("sym__$sym_lang");
				$log_update_tree = $this->update_symptom_tree("sym__$sym_lang");
				$log .= "\n<br>" . $sym_lang . ":<br>\n" . $log_add_parents . "\n<br>\n" . $log_update_tree;
				$query = "CREATE OR REPLACE VIEW sym__" . $sym_lang . "_only AS SELECT * FROM sym__$sym_lang WHERE lang_id = '$sym_lang'";
				$this->send_query($query);
			}
		} else {
			foreach ($sym_lang_ar as $sym_lang) {
				$query = "CREATE OR REPLACE VIEW sym__" . $sym_lang . "_only AS SELECT * FROM symptoms WHERE lang_id = '$sym_lang'";
				$this->send_query($query);
			}
		}
		return $log;
	}
	
	/** function get_source_id returns a src_id for a given src_no.
	If $source_nr is an array of source-numbers it returns an array of source-ids **/
	
	function get_source_id($source_nr) {
		if (is_array($source_nr)) {
			foreach ($source_nr as $src_no) {
				$query = "SELECT src_id FROM sources WHERE src_no = $src_no";
				$this->send_query($query);
				list($src_id) = $this->db_fetch_row();
				$this->free_result();
				$source_id[$src_no] = $src_id;
			}
			ksort($source_nr, SORT_NUMERIC);
		} else {
			$query = "SELECT src_id FROM sources WHERE src_no = $source_nr";
			$this->send_query($query);
			list($source_id) = $this->db_fetch_row();
			$this->free_result();
		}
		return $source_id;
	}

	/** function get_source_nr returns a src_no for a given src_id.
	If $source_id is an array of source-ids it returns an array of source-numbers **/
	
	function get_source_nr($source_id) {
		if (is_array($source_id)) {
			foreach ($source_id as $src_id) {
				$query = "SELECT src_no FROM sources WHERE src_id = '$src_id'";
				$this->send_query($query);
				list($src_no) = $this->db_fetch_row();
				$this->free_result();
				$source_nr[] = $src_no;
			}
			sort($source_nr, SORT_NUMERIC);
		} else {
			$query = "SELECT src_no FROM sources WHERE src_id = '$source_id'";
			$this->send_query($query);
			list($source_nr) = $this->db_fetch_row();
			$this->free_result();
		}
		return $source_nr;
	}
	
	/** function get_symptomname returns the symptom including the main rubric for a given sym_id. **/
	
	function get_symptomname($sym_id) {
		global $lang;
		$symptoms_tbl = $this->get_custom_table("symptoms");
		$query = "SELECT $symptoms_tbl.symptom, main_rubrics.rubric_$lang FROM $symptoms_tbl, main_rubrics WHERE $symptoms_tbl.sym_id = $sym_id AND main_rubrics.rubric_id = $symptoms_tbl.rubric_id";
		$this->send_query($query);
		list ($symptom, $main_rubric) = $this->db_fetch_row();
		$this->free_result();
		$symptomname = "$main_rubric >> $symptom";
		return $symptomname;
	}
}
// end of class OpenHomeoDB

?>
