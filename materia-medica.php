<?php

/**
 * symptom-details.php
 *
 * An alternative Materia Medica from Thomas Bochmann with the possibility to browse remedies by first letter and groups
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
 * @package   MateriaMedicaBrowsable
 * @author    Thomas Bochmann <thomas.bochmann@gmail.com>
 * @copyright 2009 Thomas Bochmann
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

include_once("include/classes/login/session.php");
if ($session->lang) {
	if ($session->lang == "en") {
		$lng = "en";
		$meta_content_language ="en_us";
	} else {
		$lng = "de";
		$meta_content_language ="de";
	}
} else {
	$lng = "de";
	$meta_content_language ="de";
}
include_once("./mm-include/lang/$lng.php");
include("./mm-include/functions.php");
include("./mm-include/functions_groups.php");
$letters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
$materia_table = "materia";
$sym_rem = "sym_rem";
$grade = 1;
$limit = 100;
$start = 0;
$min_in = 3;

$skin = $session->skin;
if ($session->logged_in) {  // user logged in
	$username = $session->username;
}
if (isset($_REQUEST['min_in'])) {
	$min_in = $_REQUEST['min_in'];
}
if (isset($_REQUEST['start'])) {
	$start = $_REQUEST['start'];
}
$where_query_rubric = "";
if (!empty($_REQUEST['rubric'])) {
	  $rubric_id = $_REQUEST['rubric'];
	  $where_query_rubric = "AND symptoms.rubric_id = $rubric_id ";
}
$where_query_grade = "";
if (!empty($_REQUEST['grade'])) {
	$grade = $_REQUEST['grade'];
	if ($grade >= 3) {
		$where_query_grade = "AND $sym_rem.grade >= 3 ";
	} else {
		$where_query_grade = "AND $sym_rem.grade >= $grade ";
	}
}

// buchstabe checken
$letter = "";
if (!empty($_GET['letter'])) {
	if (check_letter($_GET['letter']) != FALSE) {
		$letter = check_letter($_GET['letter']);
		$remedies_ar= get_rem_by_letter($letter);
		$check_letter= TRUE;
	} else {
		$error_msg = $error_msg."Buchstabe ".$_GET['letter']." nicht gefunden.<br/>";
		$check_letter= FALSE;
	}
} elseif (!empty($_GET['gletter'])) {
	if (check_letter($_GET['gletter']) != FALSE) {
		$letter = check_letter($_GET['gletter']);
		$group_arr= get_rem_groups_by_letter($letter);
		$check_letter= TRUE;
	} else {
		$error_msg = $error_msg."Buchstabe ".$_GET['gletter']." nicht gefunden.<br/>";
		$check_letter= FALSE;
	}
}

// search remedies
$rem_get = "";
if (!empty($_GET['rem'])) {
	$rem_get = $_GET['rem'];
	$remedies_ar= get_rem_by_rem_short($rem_get);
	if ($remedies_ar != FALSE) {
		$remedies_ar = get_rem_info($remedies_ar);
	} else {
		$error_msg = $error_msg."Mittelabkürzung ".$rem_get." nicht gefunden.<br/>";
		unset($remedies_ar);
	}
}
if (!empty($_GET['remid'])) {
	$remedies_ar= get_rem_by_rem_id($_GET['remid']);
	if ($remedies_ar != FALSE) {
		$remedies_ar = get_rem_info($remedies_ar);
	} else {
		$error_msg = $error_msg."Mittelabkürzung ".$rem_get." nicht gefunden.<br/>";
		unset($remedies_ar);
	}
}
if (isset($remedies_ar) && $remedies_ar == FALSE) {
	unset($remedies_ar);
}




// view
$where_query = $where_query_rubric.$where_query_grade;
if (isset($remedies_ar) AND (!isset($check_letter) || $check_letter == FALSE)) {
	foreach ($remedies_ar as $rem_id=>$remedy) {
		$head_title = $remedy['rem_name']." (".$remedy['rem_short'].") - OpenHomeo.org";
		$meta_description = $remedy['rem_name']." (".$remedy['rem_short'].")";
		$meta_keywords = $remedy['rem_name'].", ".$remedy['rem_short'].", ";
	}
	include("./skins/$skin/header.php");
	include './mm-include/popup.html';
	include './mm-include/materia-medica.css';
	echo ("<div style='text-align:right;font-size:10px;'>".view_lang_menu('materia-medica')."</div>");
	echo ("<div class='mm-letters-menu'>$translations[General_remedies]: ".get_letters_menu($letter)."</div>");
	echo "<div class='mm-letters-menu'>".get_rem_searchform($rem_get)."</div>";
	$remedies_ar = get_rem_repertory_count($remedies_ar, $where_query);
	$remedies_ar = get_rem_repertory_symptoms($remedies_ar, $where_query, $start, $limit);
	$remedies_ar = get_rem_repertory_rubrics($remedies_ar, $where_query_grade);
	$remedies_ar = get_rem_repertory_sources($remedies_ar);
	$remedies_ar = get_groups_by_remedy($remedies_ar);
	echo view_rem_info($remedies_ar);
	echo view_rem_repertory($remedies_ar,$limit,$start);
} elseif (isset($remedies_ar) AND isset($check_letter) AND $check_letter != FALSE) {
	$head_title = $translations['list_of_remedies_in_homeopathy_letter'].": ".$letter." - OpenHomeo.org";
	$meta_description = "$translations[list_of_remedies_in_homeopathy_letter]: ".$letter;
	include("./skins/$skin/header.php");
	include './mm-include/popup.html';
	include './mm-include/materia-medica.css';
	echo ("<div style='text-align:right;font-size:10px;'>".view_lang_menu('materia-medica')."</div>");
	echo ("<div class='mm-letters-menu'>$translations[General_remedies]: ".get_letters_menu($letter)."</div>");
	if (!empty($rem_get)) {
		echo "<div class='mm-letters-menu'>".get_rem_searchform($rem_get)."</div>";
	}
	$remedies_ar = get_rem_repertory_count($remedies_ar, $where_query);
	$remedies_ar = get_groups_by_remedy($remedies_ar);
	echo view_rem_list($remedies_ar);
	echo "</div>";
} elseif (isset($_GET['taxon'])) {
	$head_title = "Taxonomie - OpenHomeo.org";
	$meta_description = "Taxonomie homöopatischer Heilmittel";
	include("./skins/$skin/header.php");
	include './mm-include/popup.html';
	include './mm-include/materia-medica.css';
	echo ("<div style='text-align:right;font-size:10px;'>".view_lang_menu('materia-medica')."</div>");
	echo ("<div class='mm-letters-menu'>$translations[General_remedies]: ".get_letters_menu($letter)."</div>");
	echo "<div class='mm-letters-menu'>".get_rem_searchform($rem_get)."</div>";
	echo $_GET['taxon'];
	$taxon = get_family($_GET['taxon']);
	if(!empty($taxon['hierarchy'])){
		foreach ($taxon['hierarchy'] as $key =>$hierarchy) {
			$hierarchy_html .= "<span class='".strtolower($hierarchy['rank_name'])."' title='".$hierarchy['rank_name']."'><a href='?taxon=$hierarchy[completename]'>".$hierarchy['completename']."</a></span> - ";
		}
		$hierarchy_html .= "<span class='".strtolower($taxon['rank_name'])."' title='".$taxon['rank_name']."'><b>".$taxon['completename']."</b></span>\n";
	}
	echo "<br/>$hierarchy_html<br/>";
	$itis_children = get_itis_child($taxon['tsn']);
	if (!empty($itis_children)) {
		foreach ($itis_children as $child) {
			echo "<a href='?taxon=$child[completename]'>$child[completename]</a>";
			echo "<br>";
		}
	}
} elseif (isset($_GET['group_id'])) {
	$limit = 100000;
	$gruppe = get_rem_groups_by_id($_GET['group_id']);
	$head_title = "$gruppe[title] $translations[General_group] - OpenHomeo.org";
	$meta_description = "$gruppe[title], $translations[groups_of_remedies_in_homeopathy]";
	include("./skins/$skin/header.php");
	include './mm-include/popup.html';
	include './mm-include/materia-medica.css';
	echo ("<div style='text-align:right;font-size:10px;'>".view_lang_menu('materia-medica')."</div>");
	echo ("<div class='mm-letters-menu'><b>$translations[General_groups]:</b> ".get_letters_menu($letter,"gletter")."</div>");
	echo "<div class='mm-letters-menu'>".get_rem_groups_searchform($_REQUEST['group_id'])."</div>";
	$rem_id = NULL;
	$relation_arr = get_kraque_table2table("remedies", 3, "rem_groups", $rem_id, $_GET['group_id']);
	foreach($relation_arr as $val){
		$remedies_ar[$val['src_id']]['id'] = $val['src_id'];
	}
	$remedies_ar = get_rem_by_rem_id($remedies_ar);
	$remedies_ar = get_rem_info($remedies_ar);
	
	$where_query = $where_query_rubric.$where_query_grade;
	$remedies_ar = get_rem_repertory_count($remedies_ar, $where_query);
	echo "<div class=\"mm-info-box-head\">";
	echo "<h2>$gruppe[title] <span style='font-weight:normal;font-size:0.7em;'>$translations[General_group]</span></h2><hr/>";
	$remedies_ar = get_groups_by_remedy($remedies_ar);
	echo view_rem_list($remedies_ar);
	echo "</div>";
	if ($session->logged_in){
		if (isset($_GET['show']) && $_GET['show'] == 'repertory') {
			echo get_group_repertory_symptoms($gruppe, $remedies_ar, $where_query, $start, $limit);
		} else {
			echo "<a href='?group_id=".$_REQUEST['group_id']."&show=repertory&lang=$lng'>$translations[General_show_repertory]</a><br/>";
		}
	} else {
		echo "<a href='login.php?url=materia-medica.php?group_id=".$_REQUEST['group_id']."&show=repertory&lang=$lng'>$translations[General_show_repertory]</a><br/>";
	}
} elseif (isset($_GET['gletter'])) {
	$head_title = "$translations[groups_of_remedies_in_homeopathy_letter]: ".$letter." - OpenHomeo.org";
	$meta_description = "$translations[groups_of_remedies_in_homeopathy_letter]: ".$letter;
	include("./skins/$skin/header.php");
	include './mm-include/popup.html';
	include './mm-include/materia-medica.css';
	echo ("<div style='text-align:right;font-size:10px;'>".view_lang_menu('materia-medica')."</div>");
	echo ("<div class='mm-letters-menu'><b>$translations[General_groups]:</b> ".get_letters_menu($letter, "gletter")."</div>");
	echo "<div class='mm-letters-menu'>".get_rem_groups_searchform()."</div>";
	echo (view_group_list($group_arr));
	echo "</div>";
} else {
	$head_title = "$translations[remedies_in_homeopathy] - OpenHomeo.org";
	$meta_description = "Liste homöopatischer Heilmittel";
	include("./skins/$skin/header.php");
	include './mm-include/popup.html';
	include './mm-include/materia-medica.css';
	echo ("<div style='text-align:right;font-size:10px;'>".view_lang_menu('materia-medica')."</div>");
	echo ("<div class='mm-letters-menu'>$translations[General_remedies]: ".get_letters_menu($letter)."</div>");
	echo "<div class='mm-letters-menu'>".get_rem_searchform($rem_get)."</div>";
}

include("./skins/$skin/footer.php");

function get_family($longname) {
	global $db;
	$query = "SELECT itis__taxonomic_units.parent_tsn, itis__taxonomic_units.rank_id, itis__taxon_unit_types.rank_name, itis__longnames.tsn, itis__longnames.completename, itis__kingdoms.kingdom_name FROM itis__taxonomic_units, itis__longnames, itis__kingdoms, itis__taxon_unit_types WHERE  itis__longnames.completename = '$longname' AND itis__taxonomic_units.tsn = itis__longnames.tsn AND itis__kingdoms.kingdom_id = itis__taxonomic_units.kingdom_id AND itis__taxon_unit_types.kingdom_id = itis__taxonomic_units.kingdom_id AND itis__taxon_unit_types.rank_id = itis__taxonomic_units.rank_id ";
	$db->send_query($query);
	$itis = $db->db_fetch_assoc();
	$db->free_result();
	if(!empty($itis)){
		$itis['hierarchy'] = get_itis_parents($itis['parent_tsn']);
		$itis['synonyms'] = get_itis_synonym($itis['tsn']);
		$itis['vernaculars'] = get_itis_vernaculars($itis['tsn']);
	}
	return $itis;
}
?>
