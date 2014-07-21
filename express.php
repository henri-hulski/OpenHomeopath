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
if (!empty($_POST['sym_rem'])) {
	include("include/classes/express_class.php");
	$express = new Express($_POST['sym_rem']);
	$query = "SELECT COUNT(*) FROM express_symptoms";
	$db->send_query($query);
	list($count_symptoms) = $db->db_fetch_row();
	$db->free_result();
	if ($count_symptoms != 0) {
		$query = "SELECT sympt_id, symptom, rubric_id, page, extra, kuenzli, backup FROM express_symptoms";
		$result_symptoms = $db->send_query($query);
		while (list($sympt_id, $symptom, $rubric_id, $page, $extra, $sym_kuenzli, $backup) = $db->db_fetch_row($result_symptoms)) {
			$express->count_ar['rec']['all']++;
			$is_duplicated_symptom = 0;
			$query = "SELECT sym_id FROM symptoms WHERE rubric_id = '$rubric_id' AND lang_id = '" . $express->src_lang . "' AND symptom = '$symptom'";
			$db->send_query($query);
			list($sym_id) = $db->db_fetch_row();
			$db->free_result();
			if (!empty($sym_id)) {   // Symptom in der Datenbank gefunden
				if (!empty($page) || !empty($extra) || !empty($sym_kuenzli)) {
					$query = "SELECT COUNT(*) FROM sym_src WHERE sym_id = $sym_id AND src_id = '$express->src_id'";
					$db->send_query($query);
					list($count) = $db->db_fetch_row();
					$db->free_result();
					if ($count == 0) {
						$query = "INSERT INTO sym_src (sym_id, src_id, src_page, extra, kuenzli, username) VALUES ($sym_id, '$express->src_id', $page, '$extra', $sym_kuenzli, '$current_user')";
						$db->send_query($query);
					} else {
						$query = "SELECT username, src_page, extra, kuenzli FROM sym_src WHERE sym_id = $sym_id AND src_id = '$express->src_id'";
						$db->send_query($query);
						$sym_src = $db->db_fetch_row();
						$db->free_result();
						if ($sym_src[0] == $current_user && ((!empty($page) && $sym_src[1] != $page) || (!empty($extra) && $sym_src[2] != $extra) || (!empty($sym_kuenzli) && $sym_src[3] != $sym_kuenzli))) {
							$archive_type = "express_update";
							$where = "sym_id = $sym_id AND src_id = '$express->src_id'";
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
				$express->count_ar['sym']['ex']++;
			} else {
				// check for similar symptoms in the database
				$query = $express->build_select_duplicated_symptoms_query($symptom, $symptom1_similar_ar, $symptom2_similar_ar);

				if ($query != "" && empty($_POST['insert_duplicated'])) { // if there are some duplication
					$express->count_ar['sym']['sim']++;
					// execute the select query
					$result = $db->send_query_limit($query, $number_duplicated_records, 0);
					$results_table_ar[] = $express->build_possible_duplication_table($result);
					$db->free_result($result);
					$duplicated_symptoms_ar[] = $symptom;
					$is_duplicated_symptom = 1;
					$express->log .= "$backup: ";
				} else {
					if ($query != "" && !empty($_POST['insert_duplicated'])) {
						$express->count_ar['sym']['sim_in']++;
					}
					$symptom = $db->escape_string($symptom);
					$query = "INSERT INTO symptoms (symptom, rubric_id, lang_id, username) VALUES ('$symptom', '$rubric_id', '" . $express->src_lang . "', '$current_user')";
					$db->send_query($query);
					$sym_id = $db->db_insert_id();
					if (!empty($page) || !empty($extra) || !empty($sym_kuenzli)) {
						$query = "INSERT INTO sym_src (sym_id, src_id, src_page, extra, kuenzli, username) VALUES ('$sym_id', '$express->src_id', '$page', '$extra', '$sym_kuenzli', '$current_user')";
						$db->send_query($query);
					}
					$inserted_symptoms_ar[] = $symptom;
					$express->count_ar['sym']['in']++;
				}
			}
			$express->insert_remedy($sympt_id, $sym_id, $current_user, $is_duplicated_symptom);
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
			$express->count_ar['rec']['all']++;
			$express->count_ar['rec']['alias']['all']++;
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
						$express->count_ar['alias']['in']++;
					} else {
						$alias_double_ar[] = $alias_short;
						$express->count_ar['alias']['ex']++;
					}
				}
			} else {
				$express->log .= "alias: $rem_short = $alias\n";
				$express->count_ar['alias']['noex']++;
			}
		}
		$db->free_result($result_alias);
	}
	$query = "SELECT COUNT(*) FROM express_source";
	$db->send_query($query);
	list($count_source) = $db->db_fetch_row();
	$db->free_result();
	if ($count_source != 0) {
		$query = "SELECT src_id, author, title, year, lang, grade_max, src_type, primary_src FROM express_source";
		$result_source = $db->send_query($query);
		while (list($src_id, $author, $title, $year, $lang, $grade_max, $src_type, $primary_src) = $db->db_fetch_row($result_source)) {
			$express->count_ar['rec']['all']++;
			$express->count_ar['rec']['src']++;
			$query = "SELECT COUNT(*) FROM sources WHERE src_id = '$src_id'";
			$db->send_query($query);
			list($count) = $db->db_fetch_row();
			$db->free_result();
			if ($count == 0) {
				$query = "INSERT INTO sources (src_id,src_title,lang_id,src_type,grade_max,src_author,src_year,primary_src,username) VALUES ('$src_id', '$title', '$lang', '$src_type', '$grade_max', '$author', '$year', '$primary_src', '$current_user')";
				$db->send_query($query);
				$express->count_ar['no_main']++;
			} else {
				$sources_double_ar[] = $src_id;
				$express->count_ar['src']['ex']++;
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
  <form action="express.php" method="post" name="express" accept-charset="utf-8">
    <table style="width:100%; border:0;">
      <tr>
        <td style="width:30%;" class="center">
          <label for="sources"><span class="label"><?php echo _("Select source"); ?></span></label>
        </td>
        <td style="width:40%;">
        </td>
        <td style="width:30%;" class="center">
          <label for="rubrics"><span class="label"><?php echo _("Select main rubric"); ?></span></label>
        </td>
      </tr>
      <tr>
        <td class="center">
          <select class="drop-down3" name="src_id" id="src_id" size="1" onchange="document.express.submit()">
<?php
if (!empty($_POST['src_id'])) {
	$current_src_id = $_POST['src_id'];
	$query = "SELECT src_title, lang_id FROM sources WHERE src_id = '$current_src_id'";
	$db->send_query($query);
	list($current_src_title, $src_lang) = $db->db_fetch_row();
	$db->free_result();
	echo ("          <option value='$current_src_id' selected='selected'>$current_src_title ($current_src_id)</option>\n");
} else {
	$current_src_id = '';
	echo ("          <option value=''></option>\n");
}
$query = "SELECT src_id, src_title FROM sources WHERE primary_src = 1 ORDER BY src_no";
$db->send_query($query);
while (list($src_id, $src_title) = $db->db_fetch_row()) {
	echo ("          <option value='$src_id'>$src_title ($src_id)</option>\n");
}
$db->free_result();
?>
          </select>
        </td>
        <td></td>
        <td class="center">
<?php
if (!empty($current_src_id)) {
	echo ("        <select class='drop-down' name='rubric_id' id='rubric_id' size='1'>\n");
	$current_rubric_id = 0;
	if (isset($_POST['rubric_id'])) {
		$current_rubric_id = $_POST['rubric_id'];
	}
	if (!empty($current_rubric_id)) {
		$query = "SELECT rubric_$src_lang FROM main_rubrics WHERE rubric_id = $current_rubric_id";
		$db->send_query($query);
		list($current_rubric_name) = $db->db_fetch_row();
		$db->free_result();
		echo ("          <option value='$current_rubric_id' selected='selected'>$current_rubric_name</option>\n");
	} else {
		echo ("          <option value=''></option>\n");
	}
	$query = "SELECT rubric_$src_lang, rubric_id FROM main_rubrics ORDER BY rubric_$src_lang";
	$db->send_query($query);
	while (list($rubric_name, $rubric_id) = $db->db_fetch_row()) {
		echo ("          <option value='$rubric_id'>$rubric_name</option>\n");
	}
	$db->free_result();
	echo ("        </select>\n");
} else {
	echo ("        <select class='drop-down' name='rubric_id' id='rubric_id' size='1' disabled='disabled'></select>\n");
}
?>
        </td>
      </tr>
    </table>
    <div class="clear"></div>
<?php
if (isset($express)) {
	if (empty($express->rubric_id) || empty($express->src_id)) {
		if (empty($express->rubric_id) && empty($express->src_id)) {
			$error_message = _("Please select rubric and source!");
		} elseif (empty($express->rubric_id)) {
			$error_message = _("Please select rubric!");
		} else {
			$error_message = _("Please select source!");
		}
		echo "    <span class='error_message'><strong>!*** " . _("Error:") . "</strong> $error_message</span><br><br>";
		$express->log = $_POST['sym_rem'];
	} else {
		echo "    <br>\n";
		if ($express->count_ar['rem']['noex'] == 0 && $express->count_ar['no_src'] == 0 && $express->count_ar['no_main'] == 0 && $express->count_ar['sym']['sim'] == 0 && $express->count_ar['sym']['sim_in'] == 0 && $express->count_ar['rec']['nocolon'] == 0  && $express->count_ar['rec']['alias']['noequal'] == 0 && $express->count_ar['alias']['noex'] == 0 && $express->count_ar['main_noex'] == 0 && $express->count_ar['parent_noex'] == 0 && $express->count_ar['src']['err'] == 0 && $express->count_ar['ref_noex'] == 0) {
			printf("    <span class='success'><strong>" . _("Congratulations:") . " </strong>  " . ngettext("The <strong>Record</strong> has been processed correctly:", "All <strong>%d records</strong> have been processed correctly:", $express->count_ar['rec']['all']) . "</span><br>\n", $express->count_ar['rec']['all']);
		} else {
			if ($express->count_ar['no_src'] != 0) {
				printf("    <span class='error_message'><strong>" . _("Error: ") . _("Please select a source!") . "</strong><br>\n" . ngettext("<strong>One symptom</strong> couldn't be parsed.", "<strong>%d symptoms</strong> couldn't be parsed.", $express->count_ar['no_src']) . " </span><br>\n", $express->count_ar['no_src']);
			}
			if ($express->count_ar['no_main'] != 0) {
				printf("    <span class='error_message'><strong>" . _("Error: ") . _("Please select a main rubric!") . "</strong><br>\n" . ngettext("<strong>One symptom</strong> couldn't be parsed.", "<strong>%d symptoms</strong> couldn't be parsed.", $express->count_ar['no_main']) . " </span><br>\n", $express->count_ar['no_main']);
			}
			if ($express->count_ar['rec']['nocolon'] != 0) {
				printf("    <span class='error_message'><strong>" . _("Syntax error:") . " </strong>" . ngettext("On <strong>a record</strong> the colon (':') is missing.", "On <strong>%d records</strong> colons (':') are missing.", $express->count_ar['rec']['nocolon']) . " </span><br> " . _("Please <strong>correct</strong> in the text box and submit form again!") . "<br>\n", $express->count_ar['rec']['nocolon']);
			}
			if ($express->count_ar['rec']['alias']['noequal'] != 0) {
				printf("    <span class='error_message'><strong>" . _("Syntax error:") . " </strong>" . ngettext("In <strong>an alias allocation</strong> the equal sign ('=') is missing.", "In <strong>%d alias allocations</strong> equal signs ('=') are missing.", $express->count_ar['rec']['alias']['noequal']) . " </span><br> " . _("Please <strong>correct</strong> in the text box and submit form again!") . "<br>\n", $express->count_ar['rec']['alias']['noequal']);
			}
			if ($express->count_ar['src']['err'] != 0) {
				printf("    <span class='error_message'><strong>" . _("Syntax error:") . " </strong>" . ngettext("In <strong>%d source-/reference-entry</strong> the syntax is incorrect.", "In <strong>%d source-/reference-entries</strong> the syntax is incorrect.", $express->count_ar['src']['err']) . " </span><br> " . _("Please <strong>correct</strong> in the text box and submit form again!") . "<br>\n", $express->count_ar['src']['err']);
			}
			if ($express->count_ar['sym']['sim'] != 0) {
				$duplicated_symptoms = implode ('", "', $duplicated_symptoms_ar);
?>
    <p><span class='error_message'><strong>!*** <?php echo _("Warning:"); ?> <?php echo _("Duplication possible"); ?> ***!</strong></span><br>
    <?php echo _("You can either <strong>all </strong> similar records <strong>still insert</strong>, or <strong>correct</strong> the records in the text box and submit the form again."); ?></p>
    <p><?php printf(ngettext("The symptom to insert", "The %d symptoms to insert", $express->count_ar['sym']['sim']), $express->count_ar['sym']['sim']); ?> <strong>"<?php echo $duplicated_symptoms ?>"</strong> <?php echo _("is similar to the following symptoms in the same main rubric"); ?> (<em>"<?php echo $express->rubric_name ?>"</em>):</p>
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
			if ($express->count_ar['rem']['noex'] != 0) {
				foreach ($express->rem_error_ar as $key => $error_ar) {
					$express->log .= "$key: ";
					if (!empty($error_ar['classic'])) {
						foreach ($error_ar['classic'] as $rem => $rem_backup) {
							$rem_list[] = "<strong>" . $rem . "</strong>";
							if (empty($error_ar['nonclassic'])) {
								$express->log .= $rem_backup . ", ";
							}
						}
						$express->log = substr($express->log, 0, -2); // delete the last ", "
					}
					if (!empty($error_ar['nonclassic'])) {
						$express->log .= "{";
						foreach ($error_ar['nonclassic'] as $rem => $rem_backup) {
							$rem_list[] = "<strong>" . $rem . "</strong>";
							$express->log .= $rem_backup . ", ";
						}
						$express->log = substr($express->log, 0, -2); // delete the last ", "
						$express->log .= "}";
					}
					$express->log .= "\n";
				}
				$rem_list = implode (", ", $rem_list);
				printf("    <span class='error_message'><strong>!*** " . _("Error:") . "</strong> " . ngettext("%d remedy-abbreviation was not found in the database:", "%d remedy-abbreviations were not found in the database:", $express->count_ar['rem']['noex']), $express->count_ar['rem']['noex']);
				echo "</span>&nbsp;$rem_list<br>\n";
				echo "    " . _("Check with the help of <a href='./datadmin.php?function=show_search_form&table_name=remedies'> search </a>, whether used in the remedies-table to another abbreviation. In this case, you can <a href='./datadmin.php?function=show_insert_form&table_name=rem_alias'>add the alternative abbreviation</a> to the  alias-table. Otherwise, you have to <a href='./datadmin.php?function=show_insert_form&table_name=remedies'>add the remedy</a> to the remedies-table.") . " " . _("\tPlease <strong>correct it if necessary in the text box</strong> and <strong>submit</strong> the form again!") . "\n";
			}
			if ($express->count_ar['alias']['noex'] != 0) {
				printf("    <span class='error_message'><strong>!*** " . _("Error:") . "</strong> " . ngettext("%d remedy-abbreviation on alias assignments was not found in the database.", "%d remedy-abbreviations on alias assignments were not found in the database.", $express->count_ar['alias']['noex']) . "</span><br>\n", $express->count_ar['alias']['noex']);
				echo "    " . _("\tPlease <strong>correct it if necessary in the text box</strong> and <strong>submit</strong> the form again!") . "\n";
			}
			if ($express->count_ar['main_noex'] != 0) {
				printf("    <span class='error_message'><strong>" . _("Error:") . " </strong>" . ngettext("%d <strong>main rubric</strong> was not found in the database.", "%d <strong>main rubrics</strong> were not found in the database.", $express->count_ar['main_noex']) . " </span><br> " . _("Please <strong>correct</strong> in the text box and submit form again, or add the main rubric to the database!") . "<br>\n", $express->count_ar['main_noex']);
			}
			if ($express->count_ar['parent_noex'] != 0) {
				printf("    <span class='error_message'><strong>" . _("Error:") . " </strong>" . ngettext("With %d record, the with '>' referenced parent rubric could not be determined.", "With %d records, the with '>' referenced parent rubric could not be determined.", $express->count_ar['parent_noex']) . " </span><br> " . _("Please <strong>correct</strong> in the text box and submit form again!") . "<br>\n", $express->count_ar['parent_noex']);
			}
			printf("    <div class='clear'></div><br>" . ngettext("<strong>%d record</strong> was restored successfully:", "<strong>%d records</strong> were restored successfully:",$express->count_ar['rec']['all']),$express->count_ar['rec']['all']);
		}
		echo "    <ul>\n";
		if ($express->count_ar['sym']['in'] > 0) {
			printf("      <li>" . ngettext("<strong>%d new symptom</strong> has been added to the database:", "<strong>%d new symptoms</strong> have been added to the database:", $express->count_ar['sym']['in']) . "\n", $express->count_ar['sym']['in']);
			$inserted_symptoms_string = implode("</li>\n        <li>", $inserted_symptoms_ar);
			echo "      <ul style='list-style-type:circle'>\n";
			echo "        <li>$inserted_symptoms_string</li>\n";
			echo "      </ul></li>\n";
		}
		if ($express->count_ar['sym']['sim_in'] > 0) {
			printf("      <li>" . ngettext("Thereof <strong>%d symptom</strong> for which similar symptoms were found in the database <strong>was inserted anyway</strong>.", "Thereof <strong>%d symptoms</strong> for which similar symptoms were found in the database <strong>were inserted anyway</strong>.", $express->count_ar['sym']['sim_in']) . "</li>\n", $express->count_ar['sym']['sim_in']);
		}
		if ($express->count_ar['sym']['ex'] > 0) {
			printf("      <li>" . ngettext("<strong>%d symptom</strong> already exists under the main rubric <em>%2\$s</em> in the database.", "<strong>%d symptoms</strong> already exist under the main rubric <em>%2\$s</em> in the database.", $express->count_ar['sym']['ex']) . "</li>\n", $express->count_ar['sym']['ex'], $express->rubric_name);
		}
		if ($express->count_ar['sym']['sim'] > 0) {
			printf("      <li>" . ngettext("For <strong>%d symptom</strong> similar symptoms in the same main rubric (<em>%2\$s</em>) have been found in the database.", "For <strong>%d symptoms</strong> similar symptoms in the same main rubric (<em>%2\$s</em>) have been found in the database.", $express->count_ar['sym']['sim']) . "</li>\n", $express->count_ar['sym']['sim'], $express->rubric_name);
		}
		if ($express->count_ar['symrem']['in'] > 0) {
			printf("      <li>" . ngettext("<strong>%d symptom-remedy-relation</strong> has been inserted into the database.", "<strong>%d symptom-remedy-relations</strong> have been inserted into the database.", $express->count_ar['symrem']['in']) . "</li>\n", $express->count_ar['symrem']['in']);
		}
		if ($express->count_ar['sym']['nonclassic_in'] > 0) {
			printf("      <li>" . ngettext("Of these, %d symptom-remedy-relation from <strong>nonclassical proving</strong> (e.g. dreamproving).", "Of these, %d symptom-remedy-relations from <strong>nonclassical provings</strong> (e.g. dreamprovings).", $express->count_ar['sym']['nonclassic_in']) . "</li>\n", $express->count_ar['sym']['nonclassic_in']);
		}
		if ($express->count_ar['symrem']['ex'] > 0) {
			printf("      <li>" . ngettext("<strong>%d symptom-remedy-relation</strong> already exists in the database.", "<strong>%d symptom-remedy-relations</strong> already exist in the database.", $express->count_ar['symrem']['ex']) . "</li>\n", $express->count_ar['symrem']['ex']);
		}
		if ($express->count_ar['grade_ch'] > 0) {
			printf("      <li>" . ngettext("In %d symptom-remedy-relation the grade was updated.", "In %d symptom-remedy-relations the grade was updated.", $express->count_ar['grade_ch']) . "</li>\n", $express->count_ar['grade_ch']);
		}
		if ($express->count_ar['status_ch'] > 0) {
			printf("      <li>" . ngettext("In %d symptom-remedy-relation the state was updated.", "In %d symptom-remedy-relations the state was updated.", $express->count_ar['status_ch']) . "</li>\n", $express->count_ar['status_ch']);
		}
		if ($express->count_ar['kuenzli_ch'] > 0) {
			printf("      <li>" . ngettext("In %d symptom-remedy-relation the Künzli-dot was updated.", "In %d symptom-remedy-relations the Künzli-dots were updated.", $express->count_ar['kuenzli_ch']) . "</li>\n", $express->count_ar['kuenzli_ch']);
		}
		if ($express->count_ar['rem']['noex'] > 0) {
			printf("      <li>" . ngettext("<strong>%d remedy-abbreviation</strong> was not found in the remedies-table.", "<strong>%d remedy-abbreviations</strong> were not found in the remedies-table.", $express->count_ar['rem']['noex']) . "</li>\n", $express->count_ar['rem']['noex']);
		}
		if ($express->count_ar['rem']['alias'] > 0) {
			printf("      <li>" . ngettext("<strong>%d remedy-abbreviation</strong> has been determined using the alias table.", "<strong>%d remedy-abbreviations</strong> have been determined using the alias table.", $express->count_ar['rem']['alias']) . "</li>\n", $express->count_ar['rem']['alias']);
		}
		if ($express->count_ar['ref_noex'] > 0) {
			$ref_not_found_string = implode(", ", $express->ref_not_found_ar);
			if ($express->count_ar['ref_noex'] == 1) {
				$rel = $rel_si;
			} else {
				$rel = $express->count_ar['ref_noex'] . $rel_pl;
			}
			printf("      <li>" . ngettext("In %d symptom-remedy-relation the reference source was not found in the database:", "In %d symptom-remedy-relations the reference source was not found in the database:", $express->count_ar['ref_noex']), $express->count_ar['ref_noex']);
			echo "<br><strong>$ref_not_found_string</strong><br>" . _("Where necessary <a href='./datadmin.php?function=show_insert_form&amp;table_name=sources'>add the source to database</a>") . "</li>\n";
		}
		if ($express->count_ar['rec']['alias']['all'] > 0) {
			printf("    <li>" . ngettext("<strong>%d alias assignment</strong> has been processed.", "<strong>%d alias assignments</strong> have been processed.", $express->count_ar['rec']['alias']['all']) . "</li>\n", $express->count_ar['rec']['alias']['all']);
		}
		if ($express->count_ar['alias']['in'] > 0) {
			printf("      <li>" . ngettext("<strong>%d new alias</strong> has been inserted into the database.", "<strong>%d new aliases</strong> have been inserted into the database.", $express->count_ar['alias']['in']) . "</li>\n", $express->count_ar['alias']['in']);
		}
		if ($express->count_ar['alias']['ex'] > 0) {
			$alias_double = implode("</em>, <em>", $alias_double_ar);
			printf("      <li>" . ngettext("<strong>%d alias</strong> already exists in the database", "<strong>%d aliases</strong> already exist in the database", $express->count_ar['alias']['ex']) . " (<em>$alias_double</em>).</li>\n", $express->count_ar['alias']['ex']);
		}
		if ($express->count_ar['alias']['noex'] > 0) {
			printf("      <li>" . ngettext("<strong>%d remedy-abbreviation by alias assignment</strong> was not found in the remedies-table.", "<strong>%d remedy-abbreviations by alias assignment</strong> were not found in the remedies-table.", $express->count_ar['alias']['noex']) . "</li>\n", $express->count_ar['alias']['noex']);
		}
			if ($express->count_ar['src']['err'] != 0) {
				printf("    <span class='error_message'><strong>" . _("Syntax error:") . " </strong>" . ngettext("In <strong>%d source-/reference-entry</strong> the syntax is incorrect.", "In <strong>%d source-/reference-entries</strong> the syntax is incorrect.", $express->count_ar['src']['err']) . " </span><br> " . _("Please <strong>correct</strong> in the text box and submit form again!") . "<br>\n", $express->count_ar['src']['err']);
			}
		if ($express->count_ar['rec']['src'] > 0) {
			printf("    <li>" . ngettext("<strong>%d source-/reference-record</strong> has been processed.", "<strong>%d source-/reference-records</strong> have been processed.", $express->count_ar['rec']['src']) . "</li>\n", $express->count_ar['rec']['src']);
		}
		if ($express->count_ar['no_main'] > 0) {
			printf("    <li>" . ngettext("<strong>%d new source-/reference-record</strong> has been inserted into the database.", "<strong>%d new source-/reference-records</strong> have been inserted into the database.", $express->count_ar['no_main']) . "</li>\n", $express->count_ar['no_main']);
		}
		if ($express->count_ar['src']['ex'] > 0) {
			$sources_double = implode("</em>, <em>", $sources_double_ar);
			printf("    <li>" . ngettext("<strong>%d source-/reference-record</strong> already exists in the database", "<strong>%d source-/reference-records</strong> already exist in the database", $express->count_ar['src']['ex']) . " (<em>$sources_double</em>).</li>\n", $express->count_ar['src']['ex']);
		}
		if ($express->count_ar['src']['err'] > 0) {
			printf("    <li>" . ngettext("Syntax errors were found in <strong>%d source-/reference-record</strong>.", "Syntax errors were found in <strong>%d source-/reference-records</strong>.", $express->count_ar['src']['err']) . "</li>\n", $express->count_ar['src']['err']);
		}
		echo "    </ul>\n";
	}
}
$log = (empty($express->log)) ?  "" : $express->log;
if (!empty($log)) {
	$log = preg_replace("/\n+/u", "\n", $log);
	if ($log{strlen($log)-1} == "\n") {
		$log = substr_replace($log, "", -1, 1);
	}    // Zeilensprung am Ende wird entfernt
}
?>
    <label for='sym_rem'><span class='label3'><?php echo _("p."); ?>123 <?php echo _("Symptom"); ?> @: <?php echo _("Remedy"); ?>1-<?php echo _("Grade"); ?>[<?php echo _("Statesymbol"); ?>]@#<?php echo _("Reference"); ?>#<?php echo _("Reference"); ?>,<?php echo _("Remedy"); ?>2-<?php echo _("Grade"); ?>,sulf-2^@#k1#kk1.de,...</span></label>
    <br>
    <div class = 'center'>
      <textarea class="input_text" name="sym_rem" id="sym_rem"  cols="100" rows="16" wrap="off"><?php echo($log) ?></textarea>
      <div class="clear"><br></div>
      <input class='submit' type='submit' value=' <?php echo _("Send"); ?> '>
    </div>
  </form>
  <div style = 'text-align: right;'>
    <input type='button' onClick="popup_url('help/<?php echo $lang; ?>/expresstool.php',1200,960)" value=' <?php echo _("Help"); ?> '>
    <a href='help/<?php echo $lang; ?>/expresstool_tut.php' target='_blank'><input type='button' value=' <?php echo _("Tutorial"); ?> ' title='<?php echo _("Tutorial from Thomas Bochmann"); ?>'></a>
  </div>
</fieldset>
<?php
popup();
include("skins/$skin/footer.php")
?>
