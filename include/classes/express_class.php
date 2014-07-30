<?php

/**
 * express_class.php
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
 * @package   Express
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

/**
 * The Express class parses the express script and extract the containing data to the database.
 *
 * The Express tool provides a powerful scripting language for inserting rubrics from repertories for example in book form into the OpenHomeopath database.
 *
 * @category  Homeopathy
 * @package   Express
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 */
class Express {

	/**
	 * The output in the scripting textarea containing the previous sent records prepared for correction
	 * @var string
	 * @access public
	 */
	public $script = "";

	/**
	 * The ID of the source from which we're inserting the rubrics.
	 * @var string
	 * @access public
	 */
	public $src_id = "";

	/**
	 * The ID of the source language
	 * @var string
	 * @access public
	 */
	public $src_lang = "";

	/**
	 * The ID of the main rubric from which we're inserting rubrics.
	 *
	 * If -1 the main rubric must be defined within the script as part of the symptom (like "main rubric >> symptom").
	 *
	 * @var integer
	 * @access public
	 */
	public $rubric_id = -1;

	/**
	 * The name of the main rubric in the language of the source.
	 * @var string
	 * @access public
	 */
	public $rubric_name = "";

	/**
	 * Array with given references which were not found in the sources table
	 * @var array
	 * @access public
	 */
	public $ref_not_found_ar = array();

	/**
	 * Array of remedy-backups from given remedy abbreviations which were not found in the remedies or alias table.
	 * @var array
	 * @access public
	 */
	public $rem_error_ar = array();

	/**
	 * Array of different counters (details see the class constructor)
	 * @var array
	 * @access public
	 */
	public $count_ar = array();

	/**
	 * Class constructor
	 *
	 * @param string $sym_rem the script submitted by the user usually through the textarea form
	 * @return Express
	 * @access public
	 */
	function __construct($sym_rem) {
		global $db;

		$this->count_ar['sym']['in'] = 0;                // Counter for inserted symptoms
		$this->count_ar['sym']['ex'] = 0;                // Counter for existing symptoms
		$this->count_ar['sym']['sim'] = 0;               // Counter for similar symptoms
		$this->count_ar['sym']['sim_in'] = 0;            // Counter for similar symptoms, which got inserted
		$this->count_ar['sym']['nonclassic_in'] = 0;     // Counter for inserted nonclassic symptoms
		$this->count_ar['symrem']['in'] = 0;             // Counter for inserted symptom-remedy-relations
		$this->count_ar['symrem']['ex'] = 0;             // Counter for existing symptom-remedy-relations
		$this->count_ar['rem']['noex'] = 0;              // Counter for not existing remedy abbreviations
		$this->count_ar['rem']['alias'] = 0;             // Counter for remedy abbreviations which where found by an alias
		$this->count_ar['grade_ch'] = 0;                 // Counter for changed grade
		$this->count_ar['status_ch'] = 0;                // Counter for changed status
		$this->count_ar['kuenzli_ch'] = 0;               // Counter for changed Künzli-dots
		$this->count_ar['rec']['all'] = 0;               // Counter for processed records
		$this->count_ar['rec']['nocolon'] = 0;           // Counter for records without colon
		$this->count_ar['rec']['alias']['all'] = 0;      // Counter for processed alias records
		$this->count_ar['rec']['alias']['noequal'] = 0;  // Counter for alias records without '='
		$this->count_ar['rec']['src'] = 0;               // Counter for processed source/reference records
		$this->count_ar['alias']['in'] = 0;              // Counter for inserted aliases
		$this->count_ar['alias']['ex'] = 0;              // Counter for existing aliases
		$this->count_ar['alias']['noex'] = 0;            // Counter for not existing remedy abbreviations within aliases
		$this->count_ar['main_noex'] = 0;                // Counter for not existing main rubrics
		$this->count_ar['parent_noex'] = 0;              // Counter for unidentifiable parent rubrics
		$this->count_ar['src']['in'] = 0;                // Counter for inserted sources/references
		$this->count_ar['src']['ex'] = 0;                // Counter for existing sources/references
		$this->count_ar['src']['err'] = 0;               // Counter for errors within sources/references
		$this->count_ar['ref_noex'] = 0;                 // Counter for not existing reference-sources
		$this->count_ar['no_src'] = 0;                   // Counter for not specified source
		$this->count_ar['no_main'] = 0;                  // Counter for not specified main rubric

		if (!empty($_POST['src_id'])) {
			$this->src_id = $_POST['src_id'];
			$query = "SELECT lang_id FROM sources WHERE src_id = '$this->src_id'";
			$db->send_query($query);
			list($this->src_lang) = $db->db_fetch_row();
			$db->free_result();
			if (!empty($_POST['rubric_id'])) {
				$this->rubric_id = $_POST['rubric_id'];
				$query = "SELECT rubric_" . $this->src_lang . " FROM main_rubrics WHERE rubric_id = " . $this->rubric_id;
				$db->send_query($query);
				list($this->rubric_name) = $db->db_fetch_row();
				$db->free_result();
			}
		}
		$this->create_temporary_express_tables();
		$this->parse_express_script($sym_rem);
	}

	/**
	 * create_temporary_express_tables creates the temporary tables, which hold the information from the express-script.
	 *
	 * 4 tables gonna be created:
	 * expess_symptoms for the entered symptoms,
	 * express_sym_rem for the rubrics with the symptom-remedy-relations,
	 * express_alias for entered aliases and
	 * express_source for provided sources or references.
	 *
	 * @return void
	 * @access private
	 */
	private function create_temporary_express_tables() {
		global $db;
		$query = "DROP TEMPORARY TABLE IF EXISTS express_symptoms, express_sym_rem, express_alias, express_source";
		$db->send_query($query);
		$query = "CREATE TEMPORARY TABLE express_symptoms (
			sympt_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			symptom text NOT NULL,
			rubric_id tinyint(3) unsigned NOT NULL,
			page smallint(5) unsigned NOT NULL,
			kuenzli tinyint(1) NOT NULL,
			extra text NOT NULL,
			backup text NOT NULL,
			PRIMARY KEY(sympt_id)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		$db->send_query($query);
		$query = "CREATE TEMPORARY TABLE express_sym_rem (
			sympt_id mediumint(8) unsigned NOT NULL,
			remedy varchar(255) NOT NULL,
			wert tinyint(1) unsigned NOT NULL,
			status tinyint(3) unsigned NOT NULL,
			kuenzli tinyint(1) NOT NULL,
			ref varchar(12) NOT NULL,
			nonclassic tinyint(1) NOT NULL,
			backup text NOT NULL,
			KEY sympt_id (sympt_id)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		$db->send_query($query);
		$query = "CREATE TEMPORARY TABLE express_alias (
			remedy varchar(255) NOT NULL,
			aliase varchar(255) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		$db->send_query($query);
		$query = "CREATE TEMPORARY TABLE express_source (
			src_id varchar(12) NOT NULL,
			author tinytext NOT NULL,
			title varchar(200) NOT NULL,
			year varchar(9) NOT NULL,
			lang varchar(6) NOT NULL,
			grade_max tinyint(1) unsigned NOT NULL,
			src_type varchar(30) NOT NULL,
			primary_src tinyint(1) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		$db->send_query($query);
	}


	/**
	 * parse_express_script parses the submitted script and insert the data in the temporary tables.
	 * 
	 * @param string   $sym_rem  the script submitted by the user usually through the textarea form
	 * @return void   
	 * @access private
	 */
	private function parse_express_script ($sym_rem) {

		$prev_symptom = "";
		$sym_rem = str_replace("\r", "\n", $sym_rem);
		$sym_rem = preg_replace('/\n[\n\s]*\n/u', "\n", $sym_rem);
		$sym_rem = str_replace("%", "&percent;", $sym_rem);
		$sym_rem = str_replace("\n", "%", $sym_rem);
		if ($sym_rem{strlen($sym_rem)-1} == "%") {
			$sym_rem = substr_replace($sym_rem, "", -1, 1);
		}    // % at the end will be deleted
		$sym_rem = preg_replace('/\s+/u', ' ', $sym_rem);  // delete whitespace
		$sym_rem = explode("%", $sym_rem);  // put the script lines in an array
		foreach ($sym_rem as $key => $value) {
			$value = str_replace("&percent;", "%", $value);
			if (strpos($value, ":")) {  // check for a colon
				list($symptom, $remedy) = explode(":", $value, 2);
				$symptom = trim ($symptom);
				if ($symptom != "alias" && $symptom != "source" && $symptom != "ref" && !empty($this->src_id)) {  // check if we don't have an alias or source/reference record
					$sympt_id = $this->extract_symptom($symptom);
					if ($sympt_id != 0) {
						if (preg_match('/\{[\s,]*(.+)\}[\s,]*/u', $remedy, $matches)) {
							$nonclassic = 1;
							$this->extract_remedies($matches[1], $sympt_id, $nonclassic);
						}
						$remedy = preg_replace('/\s*\{.*\}\s*/u', "", $remedy);
						if ($remedy != "") {
							$nonclassic = 0;
							$this->extract_remedies($remedy, $sympt_id, $nonclassic);
						}
					} else {
						$this->script .= $symptom . ": " . $remedy . "\n";
					}
				} elseif ($symptom == "alias") {  // if alias record
					$this->extract_alias($remedy);
				} elseif ($symptom == "source" || $symptom == "ref") {  // if source/reference record
					if ($symptom == "source") {
						$primary_src = 1;
					} else {
						$primary_src = 0;
					}
					$this->extract_source($remedy, $primary_src);
				} else {
					$this->script .= $symptom . ": " . $remedy . "\n";
					$this->count_ar['no_src']++;
				}
			} else {
				$this->script .= $value . "\n";
				$prev_symptom = "";
				$this->count_ar['rec']['nocolon']++;
			}
		}
	}  // end function parse_express_script


	/**
	 * extract_symptom extracts the symptoms from the symptom string of a parsed record and insert them in the express_symptoms table
	 * 
	 * @param string  $symptom_string the symptom string of a parsed record before the colon.
	 * @return integer The ID (sympt_id) of the inserted record to the express_symptoms table.
	 * @access private
	 */
	private function extract_symptom ($symptom_string) {

		global $db, $prev_symptom;
		$rubric_id = $this->rubric_id;
		$page = 0;
		$kuenzli = 0;
		$extra = "";
		$custom_rubric = false;
		$backref = 0;  // shows how many parent rubrics should be taken over from the last symptom (0=none, 1=all, 2=all until the last, etc.)

		$symptom_backup = $symptom_string;
		if (stripos($symptom_string, "s.") !== false || stripos($symptom_string, "p.") !== false) {
			preg_match('/[sp]\.\s*(\d+)/iu', $symptom_string, $matches);
			$page = $matches[1];  // page
			$symptom_string = preg_replace('/\s*[sp]\.\s*\d+\s*/iu', "", $symptom_string);  // delete page
		}
		if (strpos($symptom_string, "@") !== false) {
			$kuenzli = 1;
			$symptom_string = preg_replace('/\s*\@\s*/u', "", $symptom_string);  // delete @
		}
		if (strpos($symptom_string, "(") !== false && strpos($symptom_string, ")") !== false) {
			preg_match('/\((.*)\)/u', $symptom_string, $matches);
			$extra = $matches[1];  // Zusatzinfo in Klammern ()
			$symptom_string = preg_replace('/\(.*\)/u', "", $symptom_string);  // delete parentheses with content
		}
		$symptom_string = preg_replace('/\s*,\s*$/u', "", $symptom_string);  // delete comma at the end

		if (strpos($symptom_string, ">") === 0) {  // if '>' on first position
			preg_match('/^(>+)/u', $symptom_string, $arrows);
			$backref = strspn($arrows[1], ">");  // count the '>'-characters
			$symptom_string = preg_replace('/^>+\s*/u', "", $symptom_string);  // delete the '>'-characters
		}
		if ($backref != 0 && $prev_symptom != "") {
			if ($backref != 1) {
				$prev_symptom_ar = explode(" > ", $prev_symptom);
				array_splice($prev_symptom_ar, -($backref-1));
				$prev_symptom = implode(" > ", $prev_symptom_ar);
			}
			$symptom_string = $prev_symptom . " > " . $symptom_string;
		}
		if ($backref != 0 && $prev_symptom == "") {
			$this->count_ar['parent_noex']++;
			return 0;
		}
		if (strpos($symptom_string, ">>")) {  // >>, but not on first position
			preg_match('/^(.+)>>/u', $symptom_string, $matches);
			$rubric_name = $matches[1];  // main rubric
			$rubric_name = trim($rubric_name);
			$symptom_string = preg_replace('/^.+>>\s*/u', "", $symptom_string);  // delete main rubric
			$query = "SELECT rubric_id FROM main_rubrics WHERE rubric_" . $this->src_lang . " = '$rubric_name'";
			$db->send_query($query);
			list($custom_rubric_id) = $db->db_fetch_row();
			$db->free_result();
			if (!empty($custom_rubric_id)) {
				$rubric_id = $custom_rubric_id;
				$custom_rubric = true;
			} else {
				$this->count_ar['main_noex']++;
				$prev_symptom = "";
				return 0;
			}
		}
		if ($rubric_id == -1) {
			$this->count_ar['no_main']++;
			$prev_symptom = "";
			return 0;
		}
		$symptom_string = preg_replace('/\s*>\s*/u', " > ", $symptom_string);  // set a space before and after every '>'
		$prev_symptom = $symptom_string;
		if ($custom_rubric === true) {
			$prev_symptom = $rubric_name . " >> " . $prev_symptom;
		}
		$symptom_string = $db->escape_string($symptom_string);
		$extra = $db->escape_string($extra);
		$page = $db->escape_string($page);
		$query = "INSERT INTO express_symptoms (symptom, rubric_id, page, kuenzli, extra, backup) VALUES ('$symptom_string', $rubric_id, $page, $kuenzli, '$extra', '$symptom_backup')";
		$db->send_query($query);
		$id = $db->db_insert_id();
		return $id;
	} // end function extract_symptom


	/**
	 * extract_remedies extracts the remedies from the remedy string of a parsed record and insert them in the express_sym_rem table
	 * 
	 * @param string  $rem_string the remedy string of a parsed record after the colon
	 * @param integer $symt_id    the symptom ID
	 * @param integer $nonclassic 0|1 0: classic proving, 1: nonclassic proving
	 * @return void   
	 * @access private
	 */
	private function extract_remedies ($rem_string, $symt_id, $nonclassic) {

		global $db;

		$rem_string = preg_replace('/\s+/u', "", $rem_string);  // delete space
		$rem_string = preg_replace('/,+/u', ",", $rem_string);  // delete double comma
		if ($rem_string{strlen($rem_string)-1} == ",") {
			$rem_string = substr_replace($rem_string, "", -1, 1);
		}    // delete a comma at the end of the remedy list
		$rem_ar = explode(",", $rem_string);
		foreach ($rem_ar as $key => $remedy) {
			$kuenzli = 0;
			$status_id = 0;
			$grade = 1;
			unset($ref_ar);
			$rem_backup = $remedy;
			if (strpos($remedy, "@") !== false) {  // check Künzli-dot
				$kuenzli = 1;
				$remedy = str_replace("@", "", $remedy);  // delete @
			}
			$query = "SELECT status_id, status_symbol FROM sym_status";  // check status
			$db->send_query($query);
			while($status = $db->db_fetch_row()) {
				if (!empty($status[1]) && strpos($remedy, $status[1]) !== false) {
					$status_id = $status[0];
					$status_symbol = $status[1];
					$remedy = str_replace("$status_symbol", "", $remedy);  // delete status symbol
					break;
				}
			}
			$db->free_result();
			if (strpos($remedy, "#") !== false) {  // check '#'
				$remedy = preg_replace('/#+/u', "#", $remedy);  // delete double #
				if ($remedy{strlen($remedy)-1} == "#") {
					$remedy = substr_replace($remedy, "", -1, 1);
				}    // delete # at the end of the remedy-list
				$ref_ar = explode("#", $remedy);  // get references
				$remedy = array_shift($ref_ar);  // extract $ref_ar[0] into $remedy
				foreach ($ref_ar as $key2 => $ref) {
					$query = "SELECT COUNT(*) FROM sources WHERE src_id = '$ref'";
					$db->send_query($query);
					list($ref_count) = $db->db_fetch_row();
					$db->free_result();
					if ($ref_count == 0) {
						$this->ref_not_found_ar[] = $ref;
						unset($ref_ar[$key2]);
						$this->count_ar['ref_noex']++;
					}
				}
			}
			$remedy = str_replace(".", "", $remedy);    // delete dots ('.')
			if (strpos($remedy, "-") !== false) {  // check '-'
				if (preg_match('/-([1-5])$/u', $remedy, $matches)) {  // get grade
					$grade = $matches[1];
				}
				$remedy = preg_replace('/-[1-5]?$/u', "", $remedy);  // delete grade
			}
			if (!empty($ref_ar)) {
				$refs = implode("#", $ref_ar);
			} else {
				$refs = "";
			}
			$query = "INSERT INTO express_sym_rem (sympt_id, remedy, wert, status, kuenzli, ref, nonclassic, backup) VALUES ($symt_id, '$remedy', $grade, $status_id, $kuenzli, '$refs', $nonclassic, '$rem_backup')";
			$db->send_query($query);
		}
	} // end function extract_remedies


	/**
	 * extract_alias extract the alias if the record is an alias record
	 * 
	 * @param string $alias_string  the string from an alias record after the colon
	 * @return void   
	 * @access public 
	 */
	function extract_alias ($alias_string) {

		global $db;

		if (strpos($alias_string, "=")) {  // check '='
			$alias_string = preg_replace('/\s+/u', "", $alias_string);  // delete space
			list($remedy, $aliase) = explode("=", $alias_string, 2);
			$remedy = str_replace(".", "", $remedy);    //  delete dots ('.') from the remedy
			$aliase = preg_replace('/,+/u', ", ", $aliase);
			$aliase = rtrim($aliase);
			if ($aliase{strlen($aliase)-1} == ",") {
				$aliase = substr_replace($aliase, "", -1, 1);
			}    // delete a comma at the end of the alias list
			$query = "INSERT INTO express_alias (remedy, aliase) VALUES ('$remedy', '$aliase')";
			$db->send_query($query);
		} else {
			$this->script .= "alias: $remedy = $aliase\n";
			$this->count_ar['rec']['alias']['noequal']++;
		}
	} // end function extract_alias


	/**
	 * extract_source extract the source or reference if the record is an source or reference record
	 * 
	 * @param string  $source_string  the string from a source or reference record after the colon
	 * @param integer $primary_src   0|1 0: reference, 1: primary source
	 * @return void   
	 * @access private
	 */
	private function extract_source ($source_string, $primary_src) {

		global $db;
		$src_type = "Repertorium";
		$error = 0;
		$found_lang = 0;

		$source_string = preg_replace('/\s*#[\s#]*/u', "#", $source_string);  // delete space around '#' and double '#'
		$source_string = trim($source_string);
		if ($source_string{strlen($source_string)-1} == "#") {
			$source_string = substr_replace($source_string, "", -1, 1);
		}    // # at the end will be deleted
		$source_ar = explode("#",$source_string);
		foreach ($source_ar as $key => $value) {
			$source_ar[$key] = trim($value);
		}
		if (count($source_ar) == 6) {
			list($src_id, $author, $title, $year, $lang, $grade_max) = $source_ar;
		} elseif (count($source_ar) == 7) {
			list($src_id, $author, $title, $year, $lang, $grade_max, $src_type) = $source_ar;
		} else {
			$error = 1;
		}
		if (strlen($src_id) > 12) {
			$error = 1;
		}
		$author = ucfirst($author);
		$title = ucfirst($title);
		$year = preg_replace('/\s+/u', "", $year);  // delete space
		if (preg_match('/^\d\d\d\d-?$/u', $year) == 0 && preg_match('/^\d\d\d\d-\d\d\d\d$/u', $year) == 0) {
			$error = 1;
		}
		$lang = strtolower($lang);
		$query = "SELECT COUNT(*) FROM languages WHERE lang_id = '$lang'";
		$db->send_query($query);
		list($found_lang) = $db->db_fetch_row();
		$db->free_result();
		if ($found_lang == 0) {
			$error = 1;
		}
		if (preg_match('/^[1-5]$/u', $grade_max) == 0) {
			$error = 1;
		}
		$src_type = ucwords(strtolower($src_type));
		if ($error == 1) {
			if ($primary_src = 1) {
				$keyword = "source";
			} elseif ($primary_src = 0) {
				$keyword = "ref";
			}
			$this->script .= "$keyword: ". implode("#", $source_ar) . "\n";
			$this->count_ar['src']['err']++;
		} else {
			$author = $db->escape_string($author);
			$title = $db->escape_string($title);
			$year = $db->escape_string($year);
			$src_type = $db->escape_string($src_type);
			$query = "INSERT INTO express_source (src_id, author, title, year, lang, grade_max, src_type, primary_src) VALUES ('$src_id', '$author', '$title', '$year', '$lang', '$grade_max', '$src_type', '$primary_src')";
			$db->send_query($query);
		}
	} // end function extract_source


	/**
	 * insert_remedy retrieve the remedy records from the express_sym_rem table and insert in or update the sym_rem table
	 *
	 * After retrieving the remedy records they will be tested if there is a dublicate symptom,
	 * if the remedy abbreviation exists in the database
	 * and if the symptom-remedy-relation already exists in the sym_rem table.
	 *
	 * If there is a dublicate record the remedy will be written back tu the script for correction.
	 * If the remedy abbreviation doesn't exists, the backup of the record goes in the rem_error_ar array.
	 * If the symptom-remedy-relation already exists in the sym_rem table the record will be updated.
	 * Else a new record will be created in the sym_rem table.
	 * The sym_rem_refs table will be updated, if necessary.
	 * 
	 * @param integer $sympt_id              The ID of the symptom-record in the temporary express_symptoms table.
	 * @param integer $sym_id                The symptom ID in the database symptoms table.
	 * @param string  $current_user          Username of the currently logged in user.
	 * @param integer $is_duplicated_symptom 0|1 0: unique symptom, 1: possible dublicated symptom
	 * @return void   
	 * @access public 
	 */
	public function insert_remedy($sympt_id, $sym_id, $current_user, $is_duplicated_symptom) {

		global $db;
		$duplicated_ar = array();

		$query = "SELECT remedy, wert, status, kuenzli, ref, nonclassic, backup FROM express_sym_rem WHERE sympt_id = '$sympt_id'";
		$result_sym_rem = $db->send_query($query);
		while (list($rem_short, $grade, $status_id, $kuenzli, $refs, $nonclassic, $backup) = $db->db_fetch_row($result_sym_rem)) {
			$ref_ar = explode("#", $refs);  // Referenzen ermitteln
			if ($is_duplicated_symptom != 1) {
				$query = "SELECT rem_id FROM remedies WHERE rem_short = '$rem_short.' OR rem_short = '$rem_short'";
				$db->send_query($query);
				$rem_id = $db->db_fetch_row();
				$db->free_result();
				$rem_id = $rem_id[0];
				if (empty($rem_id)) {
					$query = "SELECT rem_id FROM rem_alias WHERE alias_short = '$rem_short.' OR alias_short = '$rem_short'";
					$db->send_query($query);
					$rem_id = $db->db_fetch_row();
					$db->free_result();
					$rem_id = $rem_id[0];
					if (!empty($rem_id)) {
						$this->count_ar['rem']['alias']++;
					}
				}
				if (!empty($rem_id)) {  // the remedy abbreviation was found
					$query = "SELECT rel_id, grade, status_id, kuenzli, username FROM sym_rem WHERE sym_id = '$sym_id' AND rem_id = '$rem_id' AND src_id = '$this->src_id'";
					$db->send_query($query);
					$beziehung = $db->db_fetch_row();
					$db->free_result();
					$rel_id = $beziehung[0];
					if (!empty($rel_id)) {  // the symptom-remedy-relation already exists from this source
						if ($beziehung[4] == $current_user) {  // the database record origins from the current user
							$update_wert = 0;
							$update_status = 0;
							$update_kuenzli = 0;
							if ($beziehung[1] != $grade) {
								$update_wert = 1;
								$this->count_ar['grade_ch']++;
							}
							if ($beziehung[2] != $status_id) {
								$update_status = 1;
								$this->count_ar['status_ch']++;
							}
							if ($beziehung[3] != $kuenzli) {
								$update_kuenzli = 1;
								$this->count_ar['kuenzli_ch']++;
							}
							if ($update_wert = 1 || $update_status = 1 || $update_kuenzli = 1) {
								$archive_type = "express_update";
								$where = "rel_id = $rel_id";
								$db->archive_table_row("sym_rem", $where, $archive_type);
								$query = "UPDATE sym_rem SET ";
								if ($update_wert = 1) {
									$query .= "grade = $grade, ";
								}
								if ($update_status = 1) {
									$query .= "status_id = $status_id, ";
								}
								if ($update_kuenzli = 1) {
									$query .= "kuenzli = $kuenzli, ";
								}
								if (substr($query, -2) == ", ") {
									$query = substr_replace($query, " ", -2); // replace the last ", " with " "
								}
								$query .= "WHERE $where";
								$db->send_query($query);
							}
						}
						$this->count_ar['symrem']['ex']++;
					} else {  // create a new symptom-remedy-relation
						$query = "INSERT INTO sym_rem (sym_id, rem_id, grade, src_id, status_id, kuenzli, username) VALUES ('$sym_id', '$rem_id', '$grade', '$this->src_id', '$status_id', '$kuenzli', '$current_user')";
						$db->send_query($query);
						$rel_id = $db->db_insert_id();
						$this->count_ar['symrem']['in']++;
						if ($nonclassic == 1) {
							$this->count_ar['sym']['nonclassic_in']++;
						}
					}
					if (!empty($ref_ar) || $nonclassic == 1) {
						if (empty($ref_ar)) {
							$ref_ar[0] = $this->src_id;
						}
						foreach ($ref_ar as $ref) {
							$query = "SELECT COUNT(*) FROM sym_rem_refs WHERE rel_id = '$rel_id' AND src_id = '$ref' AND nonclassic = '$nonclassic'";
							$db->send_query($query);
							list($ref_count) = $db->db_fetch_row();
							$db->free_result();
							if ($ref_count == 0) {
								$query = "INSERT INTO sym_rem_refs (rel_id,src_id,nonclassic,username) VALUES ('$rel_id', '$ref', '$nonclassic', '$current_user')";
								$db->send_query($query);
							}
						}
					}
				} else {
					if ($nonclassic == 1) {
						$nonclassic = "nonclassic";
					} else {
						$nonclassic = "classic";
					}
					$query = "SELECT symptom FROM symptoms WHERE sym_id = '$sym_id'";  // find the symptom
					$db->send_query($query);
					list($symptom) = $db->db_fetch_row();
					$db->free_result();
					$this->rem_error_ar[$symptom][$nonclassic][$rem_short] = $backup;
					$this->count_ar['rem']['noex']++;
				}
			} else {   // similar symptom in the database
				if ($nonclassic == 1) {
					$nonclassic = "nonclassic";
				} else {
					$nonclassic = "classic";
				}
				$duplicated_ar[$nonclassic][] = $backup;
			}
		}
		$db->free_result($result_sym_rem);
		if (!empty($duplicated_ar)) {
			if (!empty($duplicated_ar['classic'])) {
				foreach ($duplicated_ar['classic'] as $rem_backup) {
					$this->script .= $rem_backup . ", ";
				}
				if (empty($duplicated_ar['nonclassic'])) {
					$this->script = substr($this->script, 0, -2);  // delete the last ", "
				}
			}
			if (!empty($duplicated_ar['nonclassic'])) {
				$this->script .= "{";
				foreach ($duplicated_ar['nonclassic'] as $rem_backup) {
					$this->script .= $rem_backup . ", ";
				}
				$this->script = substr($this->script, 0, -2);  // delete the last ", "
				$this->script .= "}";
			}
			$this->script .= "\n";
		}
	}


	/**
	 * build_select_duplicated_symptoms_query builds the select query to select the record that can be similar to the record inserted
	 * 
	 * @param  string   $symptom                    The symptom extracted from the script.
	 * @param  array    &$symptom1_similar_ar       Array which hods the symptoms extracted from the script.
	 * @param  array    &$symptom2_similar_ar       Array which holds the similar symptom found in the database corresponding to the above array.
	 * @global number   $percentage_similarity      The percentage after that two strings are considered similar.
	 * @global integer  $number_duplicated_records  The maximum number of records to be displayed as duplicated.
	 * @return string   The SQL select query
	 * @access public 
	 */
	public function build_select_duplicated_symptoms_query($symptom, &$symptom1_similar_ar, &$symptom2_similar_ar) {

		global $percentage_similarity, $number_duplicated_records, $db;

		$query_select_all = "SELECT `sym_id`, `symptom` FROM `symptoms` WHERE `rubric_id` = '$this->rubric_id' AND lang_id = '$this->src_lang'";
		$db->send_query($query_select_all);
		$where_clause = "";
		while ($symptom_row = $db->db_fetch_row()){  // for each symptom in the table
			similar_text(strtolower($symptom), strtolower($symptom_row[1]), $percentage);
			if ($percentage < $percentage_similarity){
				$words_are_similar = $this->similar_words(strtolower($symptom), strtolower($symptom_row[1]));
			}
			if ($percentage >= $percentage_similarity || $words_are_similar === true){  // the two strings are similar
				$where_clause .= "`sym_id` = '".$symptom_row[0]."' OR ";
				$symptom1_similar_ar[]=$symptom;
				$symptom2_similar_ar[]=$symptom_row[1];
			} // end if the two strings are similar
		} // end while loop for each symptom
		$db->free_result();

		if (!empty($where_clause)){
			$where_clause = substr($where_clause, 0, -4);  // delete the last " OR "
			$query = "SELECT `symptoms`.`sym_id`, `symptoms`.`symptom`, `rubrics__1`.`rubric_" . $this->src_lang . "` AS `rubrics__rubric_" . $this->src_lang . "__1`, `symptoms`.`username` FROM `symptoms` LEFT JOIN `main_rubrics` AS `rubrics__1` ON `symptoms`.`rubric_id` = `rubrics__1`.`rubric_id` WHERE " . $where_clause;
		} // end if
		else {  // no dublication
			$query = "";
		} // end else
		return $query;
	} // end function build_select_dublicated_symptoms_query


	/**
	 * similar_words compare two strings and return true if the strings are similar, otherwise false
	 * 
	 * @param string $string          The given string from the script.
	 * @param string $compare_string  The string to compare from the database.
	 * @return boolean
	 * @access private
	 */
	private function similar_words ($string, $compare_string) {
		global $percentage_similarity, $similar_words_strict;
		$words_ar = $this->build_clean_words_array ($string);
		$compare_words_ar = $this->build_clean_words_array ($compare_string);
		if (($similar_words_strict && count($words_ar) == count($compare_words_ar)) || (!$similar_words_strict && (max(count($words_ar), count($compare_words_ar)) - min(count($words_ar), count($compare_words_ar))) <= (max(count($words_ar), count($compare_words_ar)) * $percentage_similarity / 100))) {
			$i = 0;
			$found_words_ar = array();
			if (!empty($words_ar)) {
				foreach ($words_ar as $word) {
					if (array_search($word, $found_words_ar) === false OR array_search($word, $compare_words_ar) !== false) {
						$count_words = count(array_keys($words_ar, $word));
						$count_compare_words = count(array_keys($compare_words_ar, $word));
						if (($similar_words_strict && $count_words == $count_compare_words) || (!$similar_words_strict && (max($count_words, $count_compare_words) - min($count_words, $count_compare_words)) <= (max($count_words, $count_compare_words) * $percentage_similarity / 100))) {
							$i += $count_words;
						}
						$found_words_ar[] = $word;
					}
				}
			}
			if (($similar_words_strict && count($words_ar) == $i) || (!$similar_words_strict && (count($words_ar) - $i <= count($words_ar) * $percentage_similarity / 100))) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * build_clean_words_array builds a word array for comparison from a given string and filter the words with a blacklist and whitelist
	 * 
	 * @param string $string The given string.
	 * @return array
	 * @access private
	 */
	private function build_clean_words_array ($string) {

		global $session;

		unset($clean_words_ar);
		$lang = $session->lang;
		if ($lang == "de") {
			$whitelist = array("rot");  // words with less then 4 letters that has some meaning
			$blacklist = array("aber", "oder", "wenn", "sind", "einer", "eine", "eines", "beim", "durch", "nach", "während", "gegen");  // words with more then 3 letters that has no meaning
		} elseif ($lang == "en") {
			$whitelist = array("red");  // words with less then 4 letters that has some meaning
			$blacklist = array("also");  // words with more then 3 letters that has no meaning
		}
		$words_ar = str_word_count($string, 1);  // convert string to array
		foreach ($words_ar as $word) {
			if ((strlen($word) > 3 AND array_search($word, $blacklist) === false) OR array_search($word, $whitelist) !== false) {
				$clean_words_ar[] = $word;
			}
		}
		return $clean_words_ar;
	}

	/**
	 * build_possible_duplication_table builds an HTML table for displaying possible dublications of symptoms
	 *
	 * @param resource $result Parameter description (if any) ...
	 * @return string  The resulting HTML-table.
	 * @access public 
	 */
	public function build_possible_duplication_table($result)
	// goal: build an HTML table for basicly displaying the results of a select query
	// input: $result, the results of the query
	// output: $results_table, the HTML results table
	// global: $edit_target_window, the target window for edit/details (self, new......), $delete_icon, $edit_icon, $details_icon (the image files to use as icons), $enable_edit, $enable_delete, $enable_details (whether to enable (1) or not (0) the edit, delete and details features
	{
		global $db, $enable_row_highlighting;

		// build the results HTML table
		///////////////////////////////

		$results_table = "";

		$tr_results_class = 'tr_results_1';

		// build the table body
		while ($symptom_row = $db->db_fetch_row($result)){

			if ($tr_results_class === 'tr_results_1') {
				$tr_results_class = 'tr_results_2';
			}
			else {
				$tr_results_class = 'tr_results_1';
			}

			// set where clause for delete and update
			///////////////////////////////////////////
			$where_value = $symptom_row[0];
			///////////////////////////////////////////
			// end build where clause for delete and update

			if ($enable_row_highlighting === 1) {
				$results_table .= "  <tr class='$tr_results_class' onmouseover=\"if (this.className!='tr_highlighted_onclick'){this.className='tr_highlighted_onmouseover'}\" onmouseout=\"if (this.className!='tr_highlighted_onclick'){this.className='$tr_results_class'}\" onclick=\"if (this.className == 'tr_highlighted_onclick'){ this.className='$tr_results_class';}else{ this.className='tr_highlighted_onclick';}\">";
			} else {
				$results_table .= "  <tr class='" . $tr_results_class . "'>";
			}

			$results_table .= "    <td>$symptom_row[0]</td>\n    <td>$symptom_row[1]</td>\n    <td>$symptom_row[2]</td>\n    <td>".$symptom_row[3]."</td>\n";
			$results_table .= "  </tr>\n";
		}

		return $results_table;

	}
}
