<?php

/**
 * rep_class.php
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
 * @package   Rep
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

/**
 * The Rep class is responsible for the repertorization process including saving and printing the result
 *
 * @category  Homeopathy
 * @package   Rep
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 */
class Rep {

	/**
	 * Repertorization ID
	 * @var string
	 * @access public
	 */
	public $rep_id = '';
	
	
	/**
	 * Repertorization date
	 * @var string
	 * @access public
	 */
	public $date = '';
	
	
	/**
	 * Patient ID
	 * @var string
	 * @access public
	 */
	public $patient = '';
	
	
	/**
	 * Repertorization note
	 * @var string
	 * @access public
	 */
	public $note = '';
	
	
	/**
	 * Prescription
	 * @var string
	 * @access public
	 */
	public $prescription = '';
	
	
	/**
	 * Symptom selection array: key: sym_id, value: degree
	 * @var array
	 * @access public
	 */
	public $sym_select = array();
	
	
	/**
	 * Remedies array: The remedies we found during reperorization: [0] = grade, [1] = hits, [2] = rem_short, [3] = rem_name, [4] = rem_id
	 * @var array
	 * @access public
	 */
	public $remedies_ar = array();
	
	
	/**
	 * Symptom-remedies-relation array - keys: [0] = rem_short, [1] = grade, [2] = sym_id
	 * @var array
	 * @access private
	 */
	private $rel_ar = array();
	
	
	/**
	 * Number of found symptoms
	 * @var integer
	 * @access public
	 */
	public $sym_count;
	
	
	/**
	 * Number of found remedies
	 * @var integer
	 * @access public
	 */
	public $rem_count;
	
	
	/**
	 * Number of found symptom-remedies-relations
	 * @var integer
	 * @access public
	 */
	public $rel_count;
	
	
	/**
	 * Symptoms table
	 * @var string
	 * @access private
	 */
	private $symptoms_tbl;
	
	
	/**
	 * Symptom-remedy-relations table
	 * @var string
	 * @access private
	 */
	private $sym_rem_tbl;
	
	
	
	/**
	 * Class constructor
	 *
	 * @return void
	 * @access public
	 */
	function __construct() {
		global $db;
		if (isset($_REQUEST['rep'])) {
			$this->rep_id = $_REQUEST['rep'];
			$query = "SELECT sym_id, degree FROM rep_sym WHERE rep_id='" . $this->rep_id . "' ORDER BY degree DESC, sym_id";
			$db->send_query($query);
			while(list($sym_id, $degree) = $db->db_fetch_row()) {
				$this->sym_select[$sym_id] = $degree;
			}
			$db->free_result();
			$query = "SELECT UNIX_TIMESTAMP(rep_timestamp), patient_id, rep_note, rep_prescription, sym_table FROM repertorizations WHERE rep_id='" . $this->rep_id . "'";
			$db->send_query($query);
			list($timestamp, $this->patient, $this->note, $this->prescription, $sym_table) = $db->db_fetch_row();
			$db->free_result();
			$this->date = date("d.m.Y", $timestamp);
			$this->symptoms_tbl = $db->table_exists($sym_table) ? $sym_table : 'symptoms';
			if ($this->symptoms_tbl === 'symptoms' || $this->symptoms_tbl === 'sym__de' || $this->symptoms_tbl === 'sym__en') {
				$this->sym_rem_tbl = 'sym_rem';
			} else {
				$clean_symptoms_tbl = $this->symptoms_tbl;
				if (strpos($this->symptoms_tbl, '_de') !== false || strpos($this->symptoms_tbl, '_en') !== false) {
					$clean_symptoms_tbl = substr($this->symptoms_tbl, 0, -3);
				}
				$sym_rem_tbl = str_replace('sym__', 'sym_rem__', $clean_symptoms_tbl);
				$this->sym_rem_tbl = $db->table_exists($sym_rem_tbl) ? $sym_rem_tbl : 'sym_rem';
			}
		} else {
			$this->sym_rem_tbl = $db->get_custom_table("sym_rem");
			$this->symptoms_tbl = $db->get_custom_table("symptoms");
		}
		if (!empty($_REQUEST['symsel'])) {
			$sym_ar = explode("_", $_REQUEST["symsel"]);
			foreach ($sym_ar as $symptom) {
				list($sym_id, $degree) = explode('-', $symptom);
				$this->sym_select[$sym_id] = $degree;
			}
			arsort($this->sym_select);
		}
		if (!empty($_REQUEST['date'])) {
			$this->date = urldecode($_REQUEST['date']);
		} else {
			$this->date = date("d.m.Y");
		}
		if (!empty($_REQUEST['patient'])) {
			$this->patient = urldecode($_REQUEST['patient']);
		}
		if (!empty($_REQUEST['note'])) {
			$this->note = urldecode($_REQUEST['note']);
		}
		if (!empty($_REQUEST['prescription'])) {
			$this->prescription = urldecode($_REQUEST['prescription']);
		}
		if (!empty($this->sym_select)) {
			$this->get_result_data();
		}
		if (!empty($this->remedies_ar)) {
			$this->get_symptoms_ar();
		}
	}
	
	/**
	 * get_result_data retrieves the repertorization data.
	 *
	 * get_result_data takes the selected symptoms and retrieves from the database the correspodending remedies and grades and count them.
	 *
	 * @return void
	 * @access private
	 */
	private function get_result_data() {
		global $db;
		$query = "DROP TEMPORARY TABLE IF EXISTS result";
		$db->send_query($query);
		$query = "CREATE TEMPORARY TABLE result (
			rem_id smallint(4) unsigned NOT NULL,
			points mediumint(8) unsigned NOT NULL,
			hits mediumint(8) unsigned NOT NULL,
			PRIMARY KEY(rem_id)
			) ENGINE=MEMORY DEFAULT CHARSET=utf8";
		$db->send_query($query);
		foreach ($this->sym_select as $sym_id => $degree) {
			$sym_id_query_ar[] = $this->sym_rem_tbl . ".sym_id = $sym_id";
			$query = "SELECT rem_id, MAX(grade) FROM " . $this->sym_rem_tbl . " WHERE sym_id = $sym_id GROUP BY sym_id, rem_id ORDER BY NULL";
			$result = $db->send_query($query);
			while(list($rem_id, $grade) = $db->db_fetch_row($result)) {
				$points = $grade * $degree;
				$query = "INSERT INTO result SET rem_id = $rem_id, points = $points, hits = 1 ON DUPLICATE KEY UPDATE points = points + $points, hits = hits + 1";
				$db->send_query($query);
			}
			$db->free_result($result);
		}
		$sym_id_query = implode(" OR ", $sym_id_query_ar);
		$query = "SELECT remedies.rem_short, " . $this->sym_rem_tbl . ".grade, " . $this->sym_rem_tbl . ".sym_id FROM " . $this->sym_rem_tbl . ", remedies WHERE " . $this->sym_rem_tbl . ".rem_id = remedies.rem_id AND ($sym_id_query) ORDER BY remedies.rem_short, " . $this->sym_rem_tbl . ".grade DESC, " . $this->sym_rem_tbl . ".sym_id ASC";
		$db->send_query($query);
		while($rel = $db->db_fetch_row()) {
			$this->rel_ar[] = $rel;
		}
		$db->free_result();
		$query = "SELECT result.points, result.hits, remedies.rem_short, remedies.rem_name, remedies.rem_id FROM result, remedies WHERE remedies.rem_id = result.rem_id ORDER BY result.points DESC, result.hits DESC, remedies.rem_short ASC";
		$db->send_query($query);
		$this->rel_count = 0;
		$this->remedies_ar = array();
		while($rem = $db->db_fetch_row()) {
			$this->remedies_ar[] = $rem;
			$this->rel_count += $rem[1];
		}
		$db->free_result();
		$query = "SELECT COUNT(*) FROM result";
		$db->send_query($query);
		list($this->rem_count) = $db->db_fetch_row();
		$db->free_result();
		$query = "DROP TEMPORARY TABLE result";
		$db->send_query($query);
		$this->sym_count = count($this->sym_select);
	}
	
	/**
	 * get_symptoms_ar builds a sorted symptoms array.
	 *
	 * @return void
	 * @access private
	 */
	private function get_symptoms_ar() {
		global $db, $session;
		$query = "DROP TEMPORARY TABLE IF EXISTS sym_sort";
		$db->send_query($query);
		$query = "CREATE TEMPORARY TABLE sym_sort (
			id mediumint(8) unsigned NOT NULL,
			name varchar(510) NOT NULL,
			degree tinyint(1) unsigned NOT NULL,
			kuenzli tinyint(1) unsigned NOT NULL,
			PRIMARY KEY(id)
			) ENGINE=MEMORY DEFAULT CHARSET=utf8";
		$db->send_query($query);
		foreach ($this->sym_select as $sym_id => $degree) {
			$lang = $session->lang;
			$query = "SELECT " . $this->symptoms_tbl . ".symptom, main_rubrics.rubric_$lang, sym_src.kuenzli FROM (" . $this->symptoms_tbl . ", main_rubrics) LEFT JOIN sym_src ON sym_src.sym_id = " . $this->symptoms_tbl . ".sym_id WHERE main_rubrics.rubric_id = " . $this->symptoms_tbl . ".rubric_id AND " . $this->symptoms_tbl . ".sym_id = $sym_id";
			$db->send_query($query);
			list ($symptom, $main_rubric, $kuenzli) = $db->db_fetch_row();
			$db->free_result();
			$full_name = $db->escape_string($main_rubric . " >> " . $symptom);
			$kuenzli = (empty($kuenzli)) ? 0 : $kuenzli;
			$query = "INSERT INTO sym_sort VALUES ($sym_id, '$full_name', $degree, $kuenzli)";
			$db->send_query($query);
		}
		$query = "SELECT * FROM sym_sort ORDER BY degree DESC, name ASC";
		$db->send_query($query);
		for ($sym_ar = array(); $row = $db->db_fetch_assoc(); $sym_ar[array_shift($row)] = $row);
		$db->free_result();
		$query = "DROP TEMPORARY TABLE sym_sort";
		$db->send_query($query);
		$this->symptoms_ar = $sym_ar;
	}
	
	/**
	 * rep_result_table builds a nicely formatted interactive table from the repertory result.
	 *
	 * The first version of the rep_result_table function was written by Thomas Bochmann.
	 *
	 * @return string html table from the repertory result
	 * @access public
	 */
	function rep_result_table() {
	
		global $session, $db;
	
		foreach ($this->symptoms_ar as $sym_id => $symptom) {
			foreach ($this->remedies_ar as $rem_ar) {
				$row_ar[$sym_id][$rem_ar[2]] = "<td class='main_cols'></td>";
			}
		}
		foreach ($this->remedies_ar as $rem_ar) {
			foreach ($this->rel_ar as $symrem_ar) {
				if($symrem_ar[0] == $rem_ar[2]){
					$max_grade = 0;
					$kuenzli = 0;
					$sym_rem_src = $this->get_sym_rem_src($symrem_ar[2], $rem_ar[4], $max_grade, $kuenzli);
					$rowtxt = $max_grade;
					if ($kuenzli == 1) {
						$rowtxt .= "<strong>&nbsp;&bull;</strong>";
					}
					$rowtxt = "<td class='main_cols center' title='$sym_rem_src'><a href=\"javascript:popup_url('details.php?sym=$symrem_ar[2]&rem=$rem_ar[4]&sym_rem_tbl=" . $this->sym_rem_tbl . "',540,380)\">$rowtxt</a></td>";
					$row_ar[$symrem_ar[2]][$rem_ar[2]] = $rowtxt;
				}
			}
		}
		$result_table = "";
		$result_table .= "    <table>\n";
		$result_table .= "      <tr>\n";
		$result_table .= "        <th class='deg_col'><div class='deg_col' title='" . _("Rubric degree") . "'>" . _("Deg.") . "</div></th>\n";
		$result_table .= "        <th class='symptom_col'><div class='symptom_col'>" . _("Symptoms") . "</div></th>\n";
		foreach ($this->remedies_ar as $rem_ar) {
			if (isset($_REQUEST['tab'])) {
				$url = "javascript:tabOpen(\"materia.php?rem=\", $rem_ar[4], \"GET\", 2)";
			} else {
				$url = "materia.php?rem=$rem_ar[4]";
			}
			$result_table .= "        <th class='main_cols'><a title='$rem_ar[3]' href='$url'>$rem_ar[2]</a></th>\n";
		}
		$result_table .= "      </tr>\n";
		$tr_results_class = 'tr_results_1';
		$result_table .= "      <tr class='$tr_results_class'>\n";
		$result_table .= "        <td></td>\n";
		$result_table .= "        <td><strong>" . _("Total (Grades/Hits)") . "</strong></td>\n";
		foreach ($this->remedies_ar as $rem_ar) {
			$result_table .= "        <td class='main_cols center'><strong>$rem_ar[0]/$rem_ar[1]</strong></td>\n";
		}
		$result_table .= "      </tr>\n";
		foreach ($this->symptoms_ar as $sym_id => $symptom) {
			if ($tr_results_class === 'tr_results_1') {
				$tr_results_class = 'tr_results_2';
			}
			else {
				$tr_results_class = 'tr_results_1';
			}
			if ($symptom['kuenzli'] == 1) {
				$symptom['name'] .= " <strong>&bull;</strong>";
			}
			if (isset($_REQUEST['tab'])) {
				$url = "javascript:tabOpen(\"symptominfo.php?sym=\", $sym_id, \"GET\", 3)";
			} else {
				$url = "symptominfo.php?sym=$sym_id";
			}
			$result_table .= "      <tr class='$tr_results_class'>\n";
			$result_table .= "        <td class='center' title='" . _("Rubric degree") . ": $symptom[degree]'><strong>$symptom[degree]</strong></td>\n";
			$result_table .= "        <td><a href='$url'>$symptom[name]</a></td>\n        ";
			$row = $row_ar[$sym_id];
			$row = implode("\n        ",$row);
			$result_table .= "$row\n      </tr>\n";
		}
		$result_table .= "    </table>\n";
		return $result_table;
	}
	
	/**
	 * get_sym_rem_src returns the sources that exists for a given symptom-remedy-relation.
	 *
	 * @param integer $sym_id         Symptom ID
	 * @param integer $rem_id         Remedy ID
	 * @param integer &$max_grade     The max. grade of the symptom-remedy-relation
	 * @param integer &$kuenzli_dot   0|1 if the symptom-remedy-relation has a Künzli-dot 1 otherwise 0.
	 * @return string
	 * @access private
	 */
	private function get_sym_rem_src($sym_id, $rem_id, &$max_grade, &$kuenzli_dot) {
		global $db;
		$sources = "";
		$kuenzli_dot = 0;
		$max_grade = $this->get_max_grade($sym_id, $rem_id);
		$query = "SELECT sr.src_id, sr.grade, sr.rel_id, sr.kuenzli, ss.status_symbol FROM " . $this->sym_rem_tbl . " sr, sym_status ss WHERE sr.sym_id = $sym_id AND sr.rem_id = $rem_id AND ss.status_id = sr.status_id ORDER BY ss.status_grade DESC, sr.grade DESC, sr.src_id ASC";
		$result = $db->send_query($query);
		while (list($src_id, $grade, $rel_id, $kuenzli, $status_symbol) = $db->db_fetch_row($result)) {
			$source = "$src_id";
			if ($grade != $max_grade) {
				$source .= "($grade" . _("-gr.") . ")";
			}
			$query = "SELECT src_id, nonclassic FROM sym_rem_refs WHERE rel_id = $rel_id ORDER BY nonclassic, src_id";
			$db->send_query($query);
			unset($ref_array);
			while (list ($ref_id, $nonclassic) = $db->db_fetch_row()) {
				$ref = "$ref_id";
				if ($nonclassic == 1) {
					$ref .= "_(nk)";
				}
				$ref_array[] = $ref;
			}
			$db->free_result();
			if (!empty($ref_array)) {
				$refs = implode(",", $ref_array);
				$source .= ":$refs";
			}
			if (!empty($status_symbol)) {
				$source .= $status_symbol;
			}
			if ($kuenzli == 1) {
				$kuenzli_dot = 1;
			}
			$sources_ar[] = $source;
		}
		$db->free_result($result);
		if (!empty($sources_ar)) {
			$sources =  implode(" / ", $sources_ar);
		}
		return $sources;
	}
	
	/**
	 * get_max_grade returns the max. grade of a given symptom-remedy-relation
	 *
	 * @param integer $sym_id Symptom ID
	 * @param integer $rem_id Remedy ID
	 * @return integer
	 * @access public
	 */
	function get_max_grade($sym_id, $rem_id) {
		global $db;
		$query = "SELECT MAX(grade) FROM " . $this->sym_rem_tbl . " WHERE sym_id = $sym_id AND rem_id = $rem_id";
		$db->send_query($query);
		list($max_grade) = $db->db_fetch_row();
		$db->free_result();
		return (empty($max_grade)) ? 0 : $max_grade;
	}
	
	/**
	 * Save a repertorization to the database
	 *
	 * @return void
	 * @access public
	 */
	function save_rep() {
		global $db;
		$user = $_REQUEST['user'];
		$patient = (empty($this->patient)) ? _("n.a.") : $this->patient;
		if (!empty($this->rep_id)) {
			$query = "UPDATE repertorizations SET patient_id = '" . $patient . "', rep_timestamp = FROM_UNIXTIME('" . $this->date_to_timestamp($this->date) . "'), rep_note = '" . $this->note . "', rep_prescription = '" . $this->prescription . "' WHERE rep_id = '" . $this->rep_id . "'";
			$db->send_query($query);
		} else {
			$query = "INSERT INTO repertorizations (patient_id, rep_timestamp, rep_note, rep_prescription, sym_table, username) VALUES ('" . $patient . "', FROM_UNIXTIME('" . $this->date_to_timestamp($this->date) . "'), '" . $this->note . "', '" . $this->prescription . "', '" . $this->symptoms_tbl . "', '$user')";
			$db->send_query($query);
			$this->rep_id = $db->db_insert_id();
		}
		foreach ($this->sym_select as $sym_id => $degree) {
			$query = "INSERT INTO rep_sym (rep_id, sym_id, degree) VALUES ({$this->rep_id}, $sym_id, $degree)";
			$db->send_query($query);
		}
	}
	
	/**
	 * print_PDF prints the Repertory result table in an PDF file.
	 *
	 * @param string $task 'save_PDF'|'print_PDF': if 'save_PDF' the PDF should get downloaded, if 'print_PDF' the PDF should open in the browser
	 * @return void
	 * @access public
	 */
	function print_PDF($task) {
		if ($task == 'save_PDF') {
			$dest = 'D';
		} else {
			$dest = 'I';
		}
		$pdf = new PDF('L');
		$pdf->SetTopMargin(20);
		$pdf->SetFont('Arial','',12);
		$pdf->SetTitle(_("Repertorization result"), true);
		$pdf->SetAuthor(_("OpenHomeopath"), true);
		$pdf->SetCreator(_("openhomeo.org"), true);
		$pdf->AddPage();
		$w1 = $pdf->GetStringWidth(_('Patient:') . '  ' . _('Rep.-Date:') . '  ');
		$w3 = $pdf->GetStringWidth(_('Case taking:') . '  ');
		$w4 = $pdf->GetStringWidth(_('Prescription:') . '  ' . _('Rep.-No.:') . '  ');
		$pdf->SetFont('', 'B');
		$w2 = $pdf->GetStringWidth(iconv('UTF-8', 'windows-1252', $this->patient) . $this->date . $this->date);
		$pdf->SetFont('', '');
		$pdf->write(7, _('Patient:') . '  ');
		$pdf->SetFont('', 'B');
		$pdf->write(7, iconv('UTF-8', 'windows-1252', $this->patient));
		$pdf->SetFont('', '');
		// Move to the right
		$pdf->Cell(295 - ($w1 + $w2));
		$pdf->Cell(0, 7 , _('Rep.-Date:') . '  ');
		$pdf->SetFont('', 'B');
		$pdf->Cell(0, 7, $this->date, 0, 1, 'R');
		$pdf->SetFont('', '');
		$pdf->write(7, _('Prescription:') . '  ');
		$pdf->SetFont('', 'B');
		$pdf->write(7, iconv('UTF-8', 'windows-1252', $this->prescription));
		if (!empty($this->rep_id)) {
			$pdf->SetFont('', '');
			// Move to the right
			$pdf->Cell(295 -($w1 + $w2));
			$pdf->Cell(0, 7, _('Rep.-No.:') . '  ');
			$pdf->SetFont('', 'B');
			$pdf->Cell(0, 7, $this->rep_id, 0, 1, 'R');
		} else {
			$pdf->Ln(7);
		}
		$pdf->SetFont('', '');
		$pdf->write(7, _('Case taking:') . '  ');
		$pdf->SetFont('', 'I', 11);
		$pdf->Cell(0, 1, "", 0, 2);
		$note_ar = explode("%br", $this->note);
		foreach ($note_ar as $note_line) {
			$pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', $note_line), 0, 2);
		}
		// Line break
		$pdf->Ln(10);
		$header_ar = array();
		$first_row_ar = array();
		$data_ar = array();
		$this->get_table_data($header_ar, $first_row_ar, $data_ar, $summary);
		$pdf->create_result_table($header_ar, $first_row_ar, $data_ar, 70);
		$pdf->SetFont('Arial', '', 10);
		$pdf->Ln(5);
		$pdf->write(7, iconv('UTF-8', 'windows-1252', $summary));
		$pdf->Output("OpenHomeopath_" . _("Repertorization result") . "_" . date("Y-m-d_H-i") . ".pdf", $dest);
	}
	
	/**
	 * date_to_timestamp returns an UNIX-timestamp from a given date in German format
	 *
	 * @param string  $date date in German format
	 * @return integer UNIX-timestamp
	 * @access public
	 */
	function date_to_timestamp($date) {
		list($tag, $monat, $jahr) = explode(".", $date, 3);
		if ($tag < 10) {
			$tag = "0" . $tag;
			$tag = str_replace("00", "0", $tag);
		}
		if ($monat < 10) {
			$monat = "0" . $monat;
			$monat = str_replace("00", "0", $monat);
		}
		if ($jahr <= 66) {
			$jahr += 2000;
		} elseif ($jahr < 100) {
			$jahr += 1900;
		}
		$date = "$jahr-$monat-$tag";
		$timestamp = strtotime($date);
		return $timestamp;
	}

	/**
	 * get_table_data retrieves the data for printing the repertorization-result-table in a PDF-file.
	 *
	 * @param array  &$header_ar    holds the table headers
	 * @param array  &$first_row_ar holds the first rows of the result-table
	 * @param array  &$data_ar      holds the rows of the table body
	 * @param string &$summary      contains a summary of the repertorization result
	 * @return void
	 * @access private
	 */
	private function get_table_data(&$header_ar, &$first_row_ar, &$data_ar, &$summary) {
		global $db;
		$limit = 20;
		if (!empty($this->sym_select)) {
			$remedies_ar = array_slice($this->remedies_ar, 0, $limit);
			foreach ($this->symptoms_ar as $sym_id => $symptom) {
				foreach ($remedies_ar as $rem_ar) {
					$row_ar[$sym_id][$rem_ar[2]] = "";
				}
			}
			foreach ($remedies_ar as $rem_ar) {
				foreach ($this->rel_ar as $symrem_ar) {
					if($symrem_ar[0] == $rem_ar[2]){
						$max_grade = 0;
						$kuenzli = 0;
						$sym_rem_src = $this->get_sym_rem_src($symrem_ar[2], $rem_ar[4], $max_grade, $kuenzli);
						$rowtxt = $max_grade;
						if ($kuenzli == 1) {
							$rowtxt .= " •";
						}
						$row_ar[$symrem_ar[2]][$rem_ar[2]] = $rowtxt;
					}
				}
			}
			$header_ar[] = iconv('UTF-8', 'windows-1252', _("Deg."));
			$header_ar[] = iconv('UTF-8', 'windows-1252', _("Symptoms"));
			foreach ($remedies_ar as $rem_ar) {
				$header_ar[] = iconv('UTF-8', 'windows-1252', $rem_ar[2]);
			}
			$first_row_ar[] = "";
			$first_row_ar[] = iconv('UTF-8', 'windows-1252', _("Total (Grades/Hits)"));
			foreach ($remedies_ar as $rem_ar) {
				$first_row_ar[] = "$rem_ar[0]/$rem_ar[1]";
			}
			$i = 0;
			foreach ($this->symptoms_ar as $sym_id => $symptom) {
				if ($symptom['kuenzli'] == 1) {
					$symptom['name'] .= " •";
				}
				$data_ar[$i][] = $symptom['degree'];
				$data_ar[$i][] = iconv('UTF-8', 'windows-1252', $symptom['name']);
				foreach ($row_ar[$sym_id] as $row) {
					$data_ar[$i][] = $row;
				}
				$i++;
			}
		}
		$sym_txt = sprintf(ngettext("%d selected symptom", "%d selected symptoms", $this->sym_count), $this->sym_count);
		$rem_txt = sprintf(ngettext("%d remedy", "%d remedies", $this->rem_count), $this->rem_count);
		$rel_txt = sprintf(ngettext("%d symptom-remedy-relation", "%d symptom-remedy-relations", $this->rel_count), $this->rel_count);
		$summary = sprintf(_("For %s there are %s and %s.") . "\n", $sym_txt, $rem_txt, $rel_txt);
		if ($this->rem_count > $limit) {
			$summary .= sprintf(_("The %d most important remedies were printed. ") . "\n", $limit);
		}
	}
}
?>
