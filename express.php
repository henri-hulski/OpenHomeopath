<?php

/**
 * express.php
 *
 * This script provides the possibility of quick and straightforward insertion of new symptoms and symptom-remedy-relations into the database.
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

include_once("include/classes/login/session.php");
include ("include/datadmin/config.php");
include ("include/functions/express.php");

if (!$session->logged_in) {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = "login.php?url=express.php";
	header("Content-Type: text/html;charset=utf-8");
	header("Location: http://$host$uri/$extra");
	die();
}

$current_page = "express";
// get the current user
$current_user = $session->username;
$skin = $session->skin;
$head_title = _("Express-Tool") . " :: OpenHomeopath";
$meta_description = _("Here you can quickly insert new symptoms and symptom-remedy-relations into the database.");
include("skins/$skin/header.php");
?>
<h1>
  <?php echo _("Express-Tool"); ?>
</h1>
<p><?php echo _("Here you can quickly insert <strong>new symptoms</strong> and <strong>symptom-remedy-relations</strong> into the database."); ?></p>
<?php
$ref_not_found_ar = array();
$rem_error_ar = array();
$text = "";
$i = 0;  // Zähler für eingefügte Symptome
$ii = 0; // Zähler für schon vorhandene Symptome
$j = 0;  // Zähler für eingefügte Symptom-Mittel-Beziehungen
$jj = 0; // Zähler für schon vorhandene Symptom-Mittel-Beziehungen
$k = 0;  // Zähler für ähnliche Symptome
$kk = 0;  // Zähler für ähnliche Symptome, die trotzdem eingefügt wurden
$m = 0;  // Zähler für nicht vorhandene Mittel-Abkürzungen
$mm = 0; // Zähler für Mittel-Abkürzungen, die über Alias ermittelt wurden
$wu = 0; // Zähler für geänderte Wertigkeiten
$su = 0; // Zähler für geänderten Status
$ku = 0; // Zähler für geänderten Künzli-Punkte
$n = 0;  // Zähler für abgearbeitete Datensätze
$nn = 0;  // Zähler für Datensätze ohne Doppelpunkt
$a = 0;  // Zähler für abgearbeitete Alias-Datensätze
$aa = 0;  // Zähler für Alias-Datensätze ohne '='
$b = 0;  // Zähler für eingefügte Aliase
$bb = 0; // Zähler für schon vorhandene Aliase
$am = 0; // Zähler für nicht vorhandene Mittel-Abkürzungen bei Aliasen
$h = 0; // Zähler für nicht vorhandene Hauptrubriken
$r = 0; // Zähler für nicht identifizierbare übergordnete Rubriken
$nk = 0; // Zähler für eingefügte nicht klassische Symptome
$q = 0;  // Zähler für eingefügte Quellen/Referenzen
$qi = 0;  // Zähler für abgearbeitete Quellen/Referenzen-Datensätze
$qq = 0; // Zähler für schon vorhandene Quellen/Referenzen
$qe = 0; // Zähler für Fehler bei Quellen/Referenzen
$qn = 0; // Zähler für nicht vorhandene Referenz-Quellen
$nq = 0; // Zähler für nicht angegebene Quellen
$nr = 0; // Zähler für nicht angegebene Hauptrubriken


if (!empty($_POST['sym_rem'])) {
	$src_id = "";
	$lang_id = "";
	$rubric_id = -1;
	if (!empty($_POST['sources'])) {
		list($src_id, $src_title, $lang_id) = explode("%", $_POST['sources'], 3);
	}
	if (!empty($_POST['rubrics'])) {
		list($rubric_id, $rubric_name) = explode("%", $_POST['rubrics'], 2);
	}
	parse_express_script($_POST['sym_rem'], $src_id, $lang_id, $rubric_id);
	$query = "SELECT COUNT(*) FROM express_symptoms";
	$db->send_query($query);
	$count_symptoms = $db->db_fetch_row();
	$db->free_result();
	if ($count_symptoms[0] != 0) {
		$query = "SELECT sympt_id, symptom, rubric_id, page, extra, kuenzli, backup FROM express_symptoms";
		$result_symptoms = $db->send_query($query);
		while (list($sympt_id, $symptom, $rubric_id, $page, $extra, $sym_kuenzli, $backup) = $db->db_fetch_row($result_symptoms)) {
			$n++;
			$is_duplicated_symptom = 0;
			$query = "SELECT sym_id FROM symptoms WHERE rubric_id = '$rubric_id' AND lang_id = '$lang_id' AND symptom = '$symptom'";
			$db->send_query($query);
			$symptom_row = $db->db_fetch_row();
			$db->free_result();
			$sym_id = 0;
			if (!empty($symptom_row[0])) {   // Symptom in der Datenbank gefunden
				$sym_id = $symptom_row[0];
				if (!empty($page) || !empty($extra) || !empty($sym_kuenzli)) {
					$query = "SELECT COUNT(*) FROM sym_src WHERE sym_id = '$sym_id' AND src_id = '$src_id'";
					$db->send_query($query);
					list($count) = $db->db_fetch_row();
					$db->free_result();
					if ($count == 0) {
						$query = "INSERT INTO sym_src (sym_id, src_id, src_page, extra, kuenzli, username) VALUES ($sym_id, '$src_id', $page, '$extra', $sym_kuenzli, '$current_user')";
						$db->send_query($query);
					} else {
						$query = "SELECT username, src_page, extra, kuenzli FROM sym_src WHERE sym_id = '$sym_id' AND src_id = '$src_id'";
						$db->send_query($query);
						$sym_src = $db->db_fetch_row();
						$db->free_result();
						if ($sym_src[0] == $current_user && ((!empty($page) && $sym_src[1] != $page) || (!empty($extra) && $sym_src[2] != $extra) || (!empty($sym_kuenzli) && $sym_src[3] != $sym_kuenzli))) {
							$archive_type = "express_update";
							$where = "sym_id = '$sym_id' AND src_id = '$src_id'";
							$db->archive_table_row("sym_src", $where, $archive_type);
							$query = "UPDATE sym_src SET ";
							if (!empty($page)) {
								$query .= "src_page = $page, ";
							}
							if (!empty($extra)) {
								$query .= "extra = '$extra', ";
							}
							if (!empty($sym_kuenzli)) {
								$query .= "kuenzli = $sym_kuenzli, ";
							}
							if (substr($query, -2) == ", ") {
								$query = substr_replace($query, " ", -2); // replace the last ", " with " "
							}
							$query .= "WHERE $where";
							$db->send_query($query);
						}
					}
				}
				$ii++;
			} else {
				// check for similar symptoms in the database
				$query = build_select_duplicated_symptoms_query($symptom, $rubric_id, $lang_id, $symptom1_similar_ar, $symptom2_similar_ar);

				if ($query != "" && empty($_POST['insert_duplicated'])) { // if there are some duplication
					$k++;
					// execute the select query
					$result = $db->send_query_limit($query, $number_duplicated_records, 0);
					$results_table_ar[] = build_possible_duplication_table($result);
					$db->free_result($result);
					$duplicated_symptoms_ar[] = $symptom;
					$is_duplicated_symptom = 1;
					$text .= "$backup: ";
				} else {
					if ($query != "" && !empty($_POST['insert_duplicated'])) {
						$kk++;
					}
					$symptom = $db->escape_string($symptom);
					$query = "INSERT INTO symptoms (symptom, rubric_id, lang_id, username) VALUES ('$symptom', '$rubric_id', '$lang_id', '$current_user')";
					$db->send_query($query);
					$sym_id = $db->db_insert_id();
					if (!empty($page) || !empty($extra) || !empty($sym_kuenzli)) {
						$query = "INSERT INTO sym_src (sym_id, src_id, src_page, extra, kuenzli, username) VALUES ('$sym_id', '$src_id', '$page', '$extra', '$sym_kuenzli', '$current_user')";
						$db->send_query($query);
					}
					$inserted_symptoms_ar[] = $symptom;
					$i++;
				}
			}
			insert_remedy($sympt_id, $sym_id, $src_id, $current_user, $is_duplicated_symptom);
		}
		$db->free_result($result_symptoms);
	}
	$query = "SELECT COUNT(*) FROM express_alias";
	$db->send_query($query);
	$count_alias = $db->db_fetch_row();
	$db->free_result();
	if ($count_alias[0] != 0) {
		$query = "SELECT remedy, aliase FROM express_alias";
		$result_alias = $db->send_query($query);
		while (list($rem_short, $aliase) = $db->db_fetch_row($result_alias)) {
			$alias_ar = explode(", ", $aliase);
			$n++;
			$a++;
			$query = "SELECT rem_id FROM remedies WHERE rem_short = '$rem_short.' OR rem_short = '$rem_short'";
			$db->send_query($query);
			list($rem_id) = $db->db_fetch_row();
			$db->free_result();
			if (!empty($rem_id)) {
				foreach ($alias_ar as $alias_short) {
					$alias_short = ucfirst($alias_short); // erster Buchstabe wird großgeschrieben
					if ($alias_short{strlen($alias_short)-1} == ".") { // ein . am Ende wird entfernt
						$alias_short_ohne_punkt = substr_replace($alias_short, "", -1, 1);
					} else {
						$alias_short_ohne_punkt = $alias_short;
					}
					$query = "SELECT COUNT(*) FROM rem_alias WHERE alias_short = '$alias_short_ohne_punkt.'  OR alias_short = '$alias_short_ohne_punkt'";
					$db->send_query($query);
					$alias_count = $db->db_fetch_row();
					$db->free_result();
					if ($alias_count[0] == 0) {
						$query = "INSERT INTO rem_alias (alias_short,rem_id,username) VALUES ('$alias_short', '$rem_id','$current_user')";
						$db->send_query($query);
						$b++;
					} else {
						$alias_double_ar[] = $alias_short;
						$bb++;
					}
				}
			} else {
				$text .= "alias: $rem_short = $alias\n";
				$am++;
			}
		}
		$db->free_result($result_alias);
	}
	$query = "SELECT COUNT(*) FROM express_source";
	$db->send_query($query);
	$count_source = $db->db_fetch_row();
	$db->free_result();
	if ($count_source[0] != 0) {
		$query = "SELECT src_id, author, title, year, lang, grade_max, src_type, primary_src FROM express_source";
		$result_source = $db->send_query($query);
		while (list($src_id, $author, $title, $year, $lang, $grade_max, $src_type, $primary_src) = $db->db_fetch_row($result_source)) {
			$n++;
			$qi++;
			$query = "SELECT COUNT(*) FROM sources WHERE src_id = '$src_id'";
			$db->send_query($query);
			$count = $db->db_fetch_row();
			$db->free_result();
			if ($count[0] == 0) {
				$query = "INSERT INTO sources (src_id,src_title,lang_id,src_type,grade_max,src_author,src_year,primary_src,username) VALUES ('$src_id', '$title', '$lang', '$src_type', '$grade_max', '$author', '$year', '$primary_src', '$current_user')";
				$db->send_query($query);
				$q++;
			} else {
				$sources_double_ar[] = $src_id;
				$qq++;
			}
		}
		$db->free_result($result_source);
	}
}
?>
<fieldset>
  <legend class="legend">
    <?php echo _("Express-Tool"); ?>
  </legend>
  <form action="./express.php" method="post" name="express" accept-charset="utf-8">
    <table width="100%" border="0" summary="layout">
      <tr>
        <td width="30%" align="center">
          <label for="sources"><span class="label"><?php echo _("Select source"); ?></span></label>
        </td>
        <td width="40%">
        </td>
        <td width="30%" align="center">
          <label for="rubrics"><span class="label"><?php echo _("Select main rubric"); ?></span></label>
        </td>
      </tr>
      <tr>
        <td align="center">
          <select class="drop-down3" name="sources" id="sources" size="1" onchange="document.express.submit()">
<?php
$current_src = "";
if (isset($_POST['sources'])) {
	$current_src = $_POST['sources'];
}
if ($current_src != "") {
	list($current_src_id, $current_src_title, $lang_id) = explode("%", $current_src, 3);
	echo ("          <option value='$current_src' selected='selected'>$current_src_title ($current_src_id)</option>\n");
} else {
	echo ("          <option value=''></option>\n");
}
//$query = "SELECT src_title, src_id, lang_id FROM sources ORDER BY src_title";
$query = "SELECT src_title, src_id, lang_id FROM sources WHERE primary_src = 1 ORDER BY src_no"; // thb primary source

$db->send_query($query);
while($source = $db->db_fetch_row()) {
	echo ("          <option value='$source[1]%$source[0]%$source[2]'>$source[0] ($source[1])</option>\n");
}
$db->free_result();
?>
          </select>
        </td>
        <td></td>
        <td align="center">
<?php
if (!empty($lang_id)) {
	echo ("        <select class='drop-down' name='rubrics' id='rubrics' size='1'>\n");
	$current_rubric = "";
	if (isset($_POST['rubrics'])) {
		$current_rubric = $_POST['rubrics'];
	}
	if ($current_rubric != "") {
		list($current_rubric_id, $current_rubric_name) = explode("%", $current_rubric, 2);
		echo ("          <option value='$current_rubric' selected='selected'>$current_rubric_name</option>\n");
	} else {
		echo ("          <option value=''></option>\n");
	}
	$query = "SELECT rubric_$lang_id, rubric_id FROM main_rubrics ORDER BY rubric_$lang_id";
	$db->send_query($query);
	while(list($rubric_name, $rubric_id) = $db->db_fetch_row()) {
		echo ("          <option value='$rubric_id%$rubric_name'>$rubric_name</option>\n");
	}
	$db->free_result();
	echo ("        </select>\n");
} else {
	echo ("        <select class='drop-down' name='rubrics' id='rubrics' size='1' disabled='disabled'></select>\n");
}
?>
        </td>
      </tr>
    </table>
    <br clear='all'>
<?php
if (!empty($_POST['sym_rem'])) {
	if (!empty($sym_rem_ar) && empty($_POST['rubrics']) && empty($_POST['sources'])) {
		echo "    <span class='error_message'><strong>!*** " . _("Error:") . "</strong> " . _("Please select rubric and source!") . "</span><br><br>\n";
		$text = $_POST['sym_rem'];
	} elseif (!empty($sym_rem_ar) && empty($_POST['rubrics'])) {
		echo "    <span class='error_message'><strong>!*** " . _("Error:") . "</strong> " . _("Please select rubric!") . "</span><br><br>";
		$text = $_POST['sym_rem'];
	} elseif (!empty($sym_rem_ar) && empty($_POST['sources'])) {
		echo "    <span class='error_message'><strong>!*** " . _("Error:") . "</strong> " . _("Please select source!") . "</span><br><br>";
		$text = $_POST['sym_rem'];
	} else {
		if ($m == 0 && $nq == 0 && $nr == 0 && $k == 0 && $kk == 0 && $nn == 0  && $aa == 0 && $am == 0 && $h == 0 && $r == 0 && $qe == 0 && $qn == 0) {
			printf("    <span class='success'><strong>" . _("Congratulations:") . " </strong>  " . ngettext("The <strong>Record</strong> has been processed correctly:", "All <strong>%d records</strong> have been processed correctly:", $n) . "</span><br>", $n);
		} else {
			if ($nq != 0) {
				printf("    <span class='error_message'><strong>" . _("Error: ") . _("Please select a source!") . "</strong><br>\n" . ngettext("<strong>One symptom</strong> couldn't be parsed.", "<strong>%d symptoms</strong> couldn't be parsed.", $nq) . " </span><br>\n", $nq);
			}
			if ($nr != 0) {
				printf("    <span class='error_message'><strong>" . _("Error: ") . _("Please select a main rubric!") . "</strong><br>\n" . ngettext("<strong>One symptom</strong> couldn't be parsed.", "<strong>%d symptoms</strong> couldn't be parsed.", $nr) . " </span><br>\n", $nr);
			}
			if ($nn != 0) {
				printf("    <span class='error_message'><strong>" . _("Syntax error:") . " </strong>" . ngettext("On <strong>a record</strong> the colon (':') is missing.", "On <strong>%d records</strong> colons (':') are missing.", $nn) . " </span><br> " . _("Please <strong>correct</strong> in the text box and submit form again!") . "<br>\n", $nn);
			}
			if ($aa != 0) {
				printf("    <span class='error_message'><strong>" . _("Syntax error:") . " </strong>" . ngettext("In <strong>an alias allocation</strong> the equal sign ('=') is missing.", "In <strong>%d alias allocations</strong> equal signs ('=') are missing.", $aa) . " </span><br> " . _("Please <strong>correct</strong> in the text box and submit form again!") . "<br>\n", $aa);
			}
			if ($qe != 0) {
				printf("    <span class='error_message'><strong>" . _("Syntax error:") . " </strong>" . ngettext("In <strong>%d source-/reference-entry</strong> the syntax is incorrect.", "In <strong>%d source-/reference-entries</strong> the syntax is incorrect.", $qe) . " </span><br> " . _("Please <strong>correct</strong> in the text box and submit form again!") . "<br>\n", $qe);
			}
			if ($k != 0) {
				$duplicated_symptoms = implode ('", "', $duplicated_symptoms_ar);
?>
    <p><span class='error_message'><strong>!*** <?php echo _("Warning:"); ?> <?php echo _("Duplication possible"); ?> ***!</strong></span><br>
    <?php echo _("You can either <strong>all </strong> similar records <strong>still insert</strong>, or <strong>correct</strong> the records in the text box and submit the form again."); ?></p>
    <p><?php printf(ngettext("The symptom to insert", "The %d symptoms to insert", $k), $k); ?> <strong>"<?php echo $duplicated_symptoms ?>"</strong> <?php echo _("is similar to the following symptoms in the same main rubric"); ?> (<em>"<?php echo $rubric_name ?>"</em>):</p>
    <table class='results'>
      <tr>
        <th class='results'><?php echo _("Symptom-No."); ?></th>
        <th class='results'><?php echo _("Symptom"); ?></th>
        <th class='results'><?php echo _("Main rubric"); ?></th>
        <th class='results'><?php echo _("Username"); ?></th>
      </tr>
      <?php
        $results_table = implode(" ", $results_table_ar);
        echo $results_table;
      ?>
    </table>
    <table>
      <tr>
        <td>
            <br>&nbsp;&nbsp;&nbsp;<input type='submit'  name='insert_duplicated' value=' Trotzdem einfügen '>
        </td>
      </tr>
    </table>
<?php
			}
			if ($m != 0) {
				foreach ($rem_error_ar as $key => $error_ar) {
					$text .= "$key: ";
					if (!empty($error_ar['classic'])) {
						foreach ($error_ar['classic'] as $rem => $rem_backup) {
							$rem_list[] = "<strong>" . $rem . "</strong>";
							if (empty($error_ar['nonclassic'])) {
								$text .= $rem_backup . ", ";
							}
						}
						$text = substr($text, 0, -2); // delete the last ", "
					}
					if (!empty($error_ar['nonclassic'])) {
						$text .= "{";
						foreach ($error_ar['nonclassic'] as $rem => $rem_backup) {
							$rem_list[] = "<strong>" . $rem . "</strong>";
							$text .= $rem_backup . ", ";
						}
						$text = substr($text, 0, -2); // delete the last ", "
						$text .= "}";
					}
					$text .= "\n";
				}
				$rem_list = implode (", ", $rem_list);
				printf("    <span class='error_message'><strong>!*** " . _("Error:") . "</strong> " . ngettext("%d remedy-abbreviation was not found in the database:", "%d remedy-abbreviations were not found in the database:", $m), $m);
				echo "</span>&nbsp;$rem_list<br>\n";
				echo "    " . _("Check with the help of <a href='./datadmin.php?function=show_search_form&table_name=remedies'> search </a>, whether used in the remedies-table to another abbreviation. In this case, you can <a href='./datadmin.php?function=show_insert_form&table_name=rem_alias'>add the alternative abbreviation</a> to the  alias-table. Otherwise, you have to <a href='./datadmin.php?function=show_insert_form&table_name=remedies'>add the remedy</a> to the remedies-table.") . " " . _("\tPlease <strong>correct it if necessary in the text box</strong> and <strong>submit</strong> the form again!") . "\n";
			}
			if ($am != 0) {
				printf("    <span class='error_message'><strong>!*** " . _("Error:") . "</strong> " . ngettext("%d remedy-abbreviation on alias assignments was not found in the database.", "%d remedy-abbreviations on alias assignments were not found in the database.", $am) . "</span><br>\n", $am);
				echo "    " . _("\tPlease <strong>correct it if necessary in the text box</strong> and <strong>submit</strong> the form again!") . "\n";
			}
			if ($h != 0) {
				printf("    <span class='error_message'><strong>" . _("Error:") . " </strong>" . ngettext("%d <strong>main rubric</strong> was not found in the database.", "%d <strong>main rubrics</strong> were not found in the database.", $h) . " </span><br> " . _("Please <strong>correct</strong> in the text box and submit form again, or add the main rubric to the database!") . "<br>\n", $h);
			}
			if ($r != 0) {
				printf("    <span class='error_message'><strong>" . _("Error:") . " </strong>" . ngettext("With %d record, the with '>' referenced parent rubric could not be determined.", "With %d records, the with '>' referenced parent rubric could not be determined.", $r) . " </span><br> " . _("Please <strong>correct</strong> in the text box and submit form again!") . "<br>\n", $r);
			}
			printf("    <br clear='all'><br>" . ngettext("<strong>%d record</strong> was restored successfully:", "<strong>%d records</strong> were restored successfully:",$n),$n);
		}
		echo "    <ul>\n";
		if ($i > 0) {
			printf("      <li>" . ngettext("<strong>%d new symptom</strong> has been added to the database:", "<strong>%d new symptoms</strong> have been added to the database:", $i) . "\n", $i);
			$inserted_symptoms_string = implode("</li>\n        <li>", $inserted_symptoms_ar);
			echo "      <ul style='list-style-type:circle'>\n";
			echo "        <li>$inserted_symptoms_string</li>\n";
			echo "      </ul></li>\n";
		}
		if ($kk > 0) {
			printf("      <li>" . ngettext("Thereof <strong>%d symptom</strong> for which similar symptoms were found in the database <strong>was inserted anyway</strong>.", "Thereof <strong>%d symptoms</strong> for which similar symptoms were found in the database <strong>were inserted anyway</strong>.", $kk) . "</li>\n", $kk);
		}
		if ($ii > 0) {
			printf("      <li>" . ngettext("<strong>%d symptom</strong> already exists under the main rubric <em>%2\$s</em> in the database.", "<strong>%d symptoms</strong> already exist under the main rubric <em>%2\$s</em> in the database.", $ii) . "</li>\n", $ii, $rubric_name);
		}
		if ($k > 0) {
			printf("      <li>" . ngettext("For <strong>%d symptom</strong> similar symptoms in the same main rubric (<em>%2\$s</em>) have been found in the database.", "For <strong>%d symptoms</strong> similar symptoms in the same main rubric (<em>%2\$s</em>) have been found in the database.", $k) . "</li>\n", $k, $rubric_name);
		}
		if ($j > 0) {
			printf("      <li>" . ngettext("<strong>%d symptom-remedy-relation</strong> has been inserted into the database.", "<strong>%d symptom-remedy-relations</strong> have been inserted into the database.", $j) . "</li>\n", $j);
		}
		if ($nk > 0) {
			printf("      <li>" . ngettext("Of these, %d symptom-remedy-relation from <strong>nonclassical proving</strong> (e.g. dreamproving).", "Of these, %d symptom-remedy-relations from <strong>nonclassical provings</strong> (e.g. dreamprovings).", $nk) . "</li>\n", $nk);
		}
		if ($jj > 0) {
			printf("      <li>" . ngettext("<strong>%d symptom-remedy-relation</strong> already exists in the database.", "<strong>%d symptom-remedy-relations</strong> already exist in the database.", $jj) . "</li>\n", $jj);
		}
		if ($wu > 0) {
			printf("      <li>" . ngettext("In %d symptom-remedy-relation the grade was updated.", "In %d symptom-remedy-relations the grade was updated.", $wu) . "</li>\n", $wu);
		}
		if ($su > 0) {
			printf("      <li>" . ngettext("In %d symptom-remedy-relation the state was updated.", "In %d symptom-remedy-relations the state was updated.", $su) . "</li>\n", $su);
		}
		if ($ku > 0) {
			printf("      <li>" . ngettext("In %d symptom-remedy-relation the Künzli-dot was updated.", "In %d symptom-remedy-relations the Künzli-dots were updated.", $ku) . "</li>\n", $ku);
		}
		if ($m > 0) {
			printf("      <li>" . ngettext("<strong>%d remedy-abbreviation</strong> was not found in the remedies-table.", "<strong>%d remedy-abbreviations</strong> were not found in the remedies-table.", $m) . "</li>\n", $m);
		}
		if ($mm > 0) {
			printf("      <li>" . ngettext("<strong>%d remedy-abbreviation</strong> has been determined using the alias table.", "<strong>%d remedy-abbreviations</strong> have been determined using the alias table.", $mm) . "</li>\n", $mm);
		}
		if ($qn > 0) {
			$ref_not_found_string = implode(", ", $ref_not_found_ar);
			if ($qn == 1) {
				$rel = $rel_si;
			} else {
				$rel = $qn . $rel_pl;
			}
			printf("      <li>" . ngettext("In %d symptom-remedy-relation the reference source was not found in the database:", "In %d symptom-remedy-relations the reference source was not found in the database:", $qn), $qn);
			echo "<br><strong>$ref_not_found_string</strong><br>" . _("Where necessary <a href='./datadmin.php?function=show_insert_form&amp;table_name=sources'>add the source to database</a>") . "</li>\n";
		}
		if ($a > 0) {
			printf("    <li>" . ngettext("<strong>%d alias assignment</strong> has been processed.", "<strong>%d alias assignments</strong> have been processed.", $a) . "</li>\n", $a);
		}
		if ($b > 0) {
			printf("      <li>" . ngettext("<strong>%d new alias</strong> has been inserted into the database.", "<strong>%d new aliases</strong> have been inserted into the database.", $b) . "</li>\n", $b);
		}
		if ($bb > 0) {
			$alias_double = implode("</em>, <em>", $alias_double_ar);
			printf("      <li>" . ngettext("<strong>%d alias</strong> already exists in the database", "<strong>%d aliases</strong> already exist in the database", $bb) . " (<em>$alias_double</em>).</li>\n", $bb);
		}
		if ($am > 0) {
			printf("      <li>" . ngettext("<strong>%d remedy-abbreviation by alias assignment</strong> was not found in the remedies-table.", "<strong>%d remedy-abbreviations by alias assignment</strong> were not found in the remedies-table.", $am) . "</li>\n", $am);
		}
			if ($qe != 0) {
				printf("    <span class='error_message'><strong>" . _("Syntax error:") . " </strong>" . ngettext("In <strong>%d source-/reference-entry</strong> the syntax is incorrect.", "In <strong>%d source-/reference-entries</strong> the syntax is incorrect.", $qe) . " </span><br> " . _("Please <strong>correct</strong> in the text box and submit form again!") . "<br>\n", $qe);
			}
		if ($qi > 0) {
			printf("    <li>" . ngettext("<strong>%d source-/reference-record</strong> has been processed.", "<strong>%d source-/reference-records</strong> have been processed.", $qi) . "</li>\n", $qi);
		}
		if ($q > 0) {
			printf("    <li>" . ngettext("<strong>%d new source-/reference-record</strong> has been inserted into the database.", "<strong>%d new source-/reference-records</strong> have been inserted into the database.", $q) . "</li>\n", $q);
		}
		if ($qq > 0) {
			$sources_double = implode("</em>, <em>", $sources_double_ar);
			printf("    <li>" . ngettext("<strong>%d source-/reference-record</strong> already exists in the database", "<strong>%d source-/reference-records</strong> already exist in the database", $qq) . " (<em>$sources_double</em>).</li>\n", $qq);
		}
		if ($qe > 0) {
			printf("    <li>" . ngettext("Syntax errors were found in <strong>%d source-/reference-record</strong>.", "Syntax errors were found in <strong>%d source-/reference-records</strong>.", $qe) . "</li>\n", $qe);
		}
		echo "    </ul>\n";
	}
}
if (!empty($text)) {
	$text = preg_replace("/\n+/u", "\n", $text);
	if ($text{strlen($text)-1} == "\n") {
		$text = substr_replace($text, "", -1, 1);
	}    // Zeilensprung am Ende wird entfernt
}
?>
    <label for='sym_rem'><span class='label3'><?php echo _("p."); ?>123 <?php echo _("Symptom"); ?> @: <?php echo _("Remedy"); ?>1-<?php echo _("Grade"); ?>[<?php echo _("Statesymbol"); ?>]@#<?php echo _("Reference"); ?>#<?php echo _("Reference"); ?>,<?php echo _("Remedy"); ?>2-<?php echo _("Grade"); ?>,sulf-2^@#k1#kk1.de,...</span></label>
    <br>
    <div class = 'center'>
      <textarea class="input_text" name="sym_rem" id="sym_rem"  cols="100" rows="16" wrap="off"><?php echo($text) ?></textarea>
      <br clear="all"><br>
      <input class='submit' type='submit' value=' <?php echo _("Send"); ?> '>
    </div>
  </form>
  <div style = 'text-align: right;'>
    <input type='button' onClick="popup_url('help/<?php echo $lang; ?>/expresstool.php',1200,960)" value=' <?php echo _("Help"); ?> '>
    <a href='help/<?php echo $lang; ?>/expresstool_tut.php'><input type='button' value=' <?php echo _("Tutorial"); ?> ' title='<?php echo _("Tutorial from Thomas Bochmann"); ?>'></a>
  </div>
</fieldset>
<?php
popup();
include("skins/$skin/footer.php")
?>
