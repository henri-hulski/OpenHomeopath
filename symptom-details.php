<?php

/**
 * symptom-details.php
 *
 * An alternative symptom-details page from Thomas Bochmann that works together with his materia-medica.php
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
 * @package   SymptomDetails
 * @author    Thomas Bochmann <thomas.bochmann@gmail.com>
 * @copyright 2009 Thomas Bochmann
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

include_once("include/classes/login/session.php");
$skin = $session->skin;
$lang = $session->lang;
include_once("mm-include/lang/$lang.php");
include("mm-include/functions.php");
include("mm-include/functions_symptoms.php");
header("Expires: Mon, 1 Dec 2006 01:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-Type: text/html;charset=utf-8"); 
$letters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
$materia_table = "materia";
$sym_rem = "sym_rem";
$grade = 1;
$limit = 8;
$start = 0;

$sym_rem = "sym_rem";
$sym_id = $_REQUEST['sym'];
if ($session->logged_in) {  // user logged in
	$username = $session->username;
}

if (isset($_REQUEST['start'])) {
	$start = $_REQUEST['start'];
}
if (isset($_REQUEST['rubric'])) {
	  $rubric_id = $_REQUEST['rubric'];
	  $where_query_rubric = $where_query_rubric."AND symptoms.rubric_id = $rubric_id ";
}
if (isset($_REQUEST['grade'])) {
	$grade = $_REQUEST['grade'];
	if ($grade >= 3) {
		$where_query_grade = $where_query_grade."AND $sym_rem.grade >= 3 ";
	} else {
		$where_query_grade = $where_query_grade."AND $sym_rem.grade >= $grade ";
	}
}

// check letters
if (!empty($_GET['letter'])) {
	if (check_letter($_GET['letter']) != FALSE) {
		$letter = check_letter($_GET['letter']);
		$check_letter= TRUE;
	} else {
		$error_msg = $error_msg."Buchstabe ".$_GET['letter']." nicht gefunden.<br/>";
		$check_letter= FALSE;
	}
}

if (isset($check_letter) && $check_letter == TRUE) {
	$where_query = $where_query_rubric;
	$head_title = "$translations[list_of_remedies_in_homeopathy_letter]: $letter - OpenHomeo.org";
	$meta_description = "$translations[list_of_remedies_in_homeopathy_letter]: $letter";
	include("skins/$skin/header.php");
	include 'mm-include/popup.html';
	include 'mm-include/materia-medica.css';
	echo ("<div style='text-align:right;font-size:10px;'>".view_lang_menu('symptom-details')."</div>");
	echo ("<div class='mm-letters-menu'>$translations[General_symptoms]: ".get_letters_menu($letter)."</div>");
	$symptoms_arr= get_symptoms($letter, $where_query, $start, $limit);
	echo view_repertory($symptoms_arr, $letter,$limit,$start);
} else {
	// check translations
	unset($translation_arr);
	$query = "SELECT symptom, lang_id FROM sym_translations WHERE sym_id = $sym_id";
	$db->send_query($query);
	while($translation = $db->db_fetch_row()) {
		$translation_arr[] = $translation;
	}
	$db->free_result();
	$query = "SELECT symptoms.symptom, main_rubrics.rubric_$lang, languages.lang_$lang FROM symptoms, main_rubrics, languages WHERE symptoms.sym_id = $sym_id AND main_rubrics.rubric_id = symptoms.rubric_id AND languages.lang_id = symptoms.lang_id";
	$db->send_query($query);
	list ($symptom_details['symptoms'][$sym_id]['symptom_name'], $symptom_details['symptoms'][$sym_id]['rubric_name'], $symptom_details['symptoms'][$sym_id]['lang_name']) = $db->db_fetch_row();
	$db->free_result();
	
	// remedies + sources
	$query = "SELECT remedies.rem_id, remedies.rem_short, remedies.rem_name, $sym_rem.grade, $sym_rem.src_id, $sym_rem.rel_id, $sym_rem.timestamp, $sym_rem.status_id, $sym_rem.kuenzli  FROM remedies, $sym_rem WHERE $sym_rem.sym_id = $sym_id AND $sym_rem.rem_id = remedies.rem_id ORDER BY remedies.rem_short ASC"; //orig
	$db->send_query($query);
	$i=0;
	while($symptominfo = $db->db_fetch_row()) {
		$symptom_details['symptoms'][$sym_id]['source'][$symptominfo[4]]['sym_rem_relation'][$symptominfo[5]]['remedies'][$symptominfo[0]]['rem_short']=$symptominfo[1];
		$symptom_details['symptoms'][$sym_id]['source'][$symptominfo[4]]['sym_rem_relation'][$symptominfo[5]]['remedies'][$symptominfo[0]]['rem_name']=$symptominfo[2];
		$symptom_details['symptoms'][$sym_id]['source'][$symptominfo[4]]['sym_rem_relation'][$symptominfo[5]]['remedies'][$symptominfo[0]]['grade']=$symptominfo[3];
		$symptom_details['symptoms'][$sym_id]['source'][$symptominfo[4]]['sym_rem_relation'][$symptominfo[5]]['remedies'][$symptominfo[0]]['timestamp']=$symptominfo[6];
		$symptom_details['symptoms'][$sym_id]['source'][$symptominfo[4]]['sym_rem_relation'][$symptominfo[5]]['remedies'][$symptominfo[0]]['status_id']=$symptominfo[7];
		$symptom_details['symptoms'][$sym_id]['source'][$symptominfo[4]]['sym_rem_relation'][$symptominfo[5]]['remedies'][$symptominfo[0]]['kuenzli']=$symptominfo[8];
		$sourceArr[]=$symptominfo[4];
		$i++;
	}
	$sourceArr = array_unique($sourceArr);
	$num_rows = $db->db_num_rows();
	$db->free_result();
	
	// references
	// sym_rem_refs.src_id sym_rem_refs.nonclassic
	foreach ($sourceArr as $val) {
		$symptom_details['symptoms'][$sym_id]['sources'][$val]= array ();
		$symptom_details['sources'][$val]['reference']= array ();
		$sources_arr[]= $val;
		foreach ($symptom_details['symptoms'][$sym_id]['source'][$val]['sym_rem_relation'] as $key => $val2) {
			$query = "SELECT sym_rem_refs.rel_id, sym_rem_refs.src_id, sym_rem_refs.nonclassic FROM sym_rem_refs WHERE sym_rem_refs.rel_id = $key";
			$db->send_query($query);
			$i=0;
			unset($ref_info_arr);
			while ($symptominfo = $db->db_fetch_row()) {
				$ref_info_arr[$i] = $symptominfo;
				$i++;
			}
			$db->free_result();
			if (!empty($ref_info_arr)) {
				$i=0;
				foreach ($ref_info_arr as $key2 => $ref_info) {
					$symptom_details['symptoms'][$sym_id]['sources'][$ref_info[1]]= array ();
					$symptom_details['sources'][$val]['reference'][$ref_info[1]]= "";
					$i++;
					$sources_arr[]= $ref_info[1];
					$symptom_details['symptoms'][$sym_id]['source'][$val]['sym_rem_relation'][$key]['reference'][$ref_info[1]]['nonclassic']=$ref_info[2];
				}
			}
		
		}
	}
	$sources_arr = array_unique($sources_arr);
	foreach ($sources_arr as $key=>$val) {
		$query = "SELECT sources.src_title, sources.lang_id, sources.src_type, sources.src_author, sources.src_year, sources.src_edition_version, sources.src_copyright, sources.src_isbn FROM sources WHERE sources.src_id= '$val' ";
		$db->send_query($query);
		$ref_info = $db->db_fetch_row();
		$db->free_result();
		$symptom_details['sources_info'][$val]['src_title']=$ref_info[0];
		// alias
		if ($ref_info[2] == "alias") {
			$origId = trim(substr($ref_info[0],strpos($ref_info[0], "=")+1,strpos($ref_info[0], "=",1)+0- strpos($ref_info[0], "=")-1));
			echo "= Alias von: ";
			echo "<b>".$origId."</b>";
			$ref_info="";
			$query = "SELECT sources.src_title, sources.lang_id, sources.src_type, sources.src_author, sources.src_year, sources.src_edition_version, sources.src_copyright, sources.src_isbn FROM sources WHERE sources.src_id= '$origId' ";
			$db->send_query($query);
			$ref_info_orig = $db->db_fetch_row();
			$ref_info_orig = $ref_info_orig[0];
			$db->free_result();
			$symptom_details['sources_info'][$val]['alias_of']=$ref_info_orig[0];
			$symptom_details['sources_info'][$val]['lang_id']=$ref_info_orig[1];
			$symptom_details['sources_info'][$val]['src_type']=$ref_info_orig[2];
			$symptom_details['sources_info'][$val]['src_author']=$ref_info_orig[3];
			$symptom_details['sources_info'][$val]['src_year']=$ref_info_orig[4];
			$symptom_details['sources_info'][$val]['src_edition_version']=$ref_info_orig[5];
			$symptom_details['sources_info'][$val]['src_copyright']=$ref_info_orig[6];
			$symptom_details['sources_info'][$val]['src_isbn']=$ref_info_orig[7];
		} else {
			$symptom_details['sources_info'][$val]['lang_id']=$ref_info[1];
			$symptom_details['sources_info'][$val]['src_type']=$ref_info[2];
			$symptom_details['sources_info'][$val]['src_author']=$ref_info[3];
			$symptom_details['sources_info'][$val]['src_year']=$ref_info[4];
			$symptom_details['sources_info'][$val]['src_edition_version']=$ref_info[5];
			$symptom_details['sources_info'][$val]['src_copyright']=$ref_info[6];
			$symptom_details['sources_info'][$val]['src_isbn']=$ref_info[7];
		}
	}
	foreach ($symptom_details['sources'] as $key=>$val) {
		$query = "SELECT src_page FROM sym_src WHERE sym_id = $sym_id AND src_id = '$key'";
		$db->send_query($query);
		$src_page = $db->db_fetch_row();
		$db->free_result();
		if ($src_page) {
			$src_page = $src_page[0];
			$symptom_details['sources_info'][$key]['page']=$src_page;
		}
	}
	
	// view symptom details
	$head_title = $symptom_details['symptoms'][$sym_id]['rubric_name']." &gt; ".$symptom_details['symptoms'][$sym_id]['symptom_name']." - OpenHomeo.org";
	$meta_description = "Symptom-Details: ".$symptom_details['symptoms'][$sym_id]['rubric_name']." - ".$symptom_details['symptoms'][$sym_id]['symptom_name'];
	$meta_keywords = $symptom_details['symptoms'][$sym_id]['rubric_name'].", ".$symptom_details['symptoms'][$sym_id]['symptom_name'].", Rubrik, Symptom, ";
	include("skins/$skin/header.php");
	echo ("<script src=\"mm-include/wz_tooltip/wz_tooltip.js\"></script>");
	include 'mm-include/popup.html';
	include 'mm-include/materia-medica.css';
	echo ("<div style='text-align:right;font-size:10px;'>".view_lang_menu('symptom-details')."</div>");
	foreach ($symptom_details['symptoms'] as $sym_id=>$val) {
		echo ("<div class='mm-info-box-head'>\n");
		echo (" <h2>".$symptom_details['symptoms'][$sym_id]['rubric_name']." &gt; ".$symptom_details['symptoms'][$sym_id]['symptom_name']."</h2><b>".$translations['General_original_lang'].":</b> ".$symptom_details['symptoms'][$sym_id]['lang_name']."<br />");
		if (isset($translation_arr)) {
			echo "<b>".$translations['General_translation'].":</b>";
			foreach ($translation_arr as $trans_val) {
				echo "<br />".$trans_val[0]." (".$trans_val[1].")";
			}
		}
		echo "<br /><strong>" . _("More information") . ":</strong> <a href='symptominfo.php?sym=$sym_id&lang=$lang' target='_blank'>OpenHomeopath</a>\n";
		echo "<hr/></div>";
		if (!empty($symptom_details['sources'])) {
			foreach ($symptom_details['sources'] as $src_id=>$src_val) {
				echo ("<div class='mm-info-box'>");
				echo ("<div class='mm-info-box-repertory'>");
				echo ("    <span class=\"mm-info-box-reference-title\">".$symptom_details['sources_info'][$src_id]['src_title']."</span>");
				if (!empty($symptom_details['sources_info'][$src_id]['src_author']) && $symptom_details['sources_info'][$src_id]['src_author'] != "-") {
					echo (" <strong>".$symptom_details['sources_info'][$src_id]['src_author']."</strong>");
				}
				if (!empty($symptom_details['sources_info'][$src_id]['src_copyright']) && $symptom_details['sources_info'][$src_id]['src_copyright'] != "-") {
					echo ("      <strong>&copy; </strong><span class='gray'>");
					if (!empty($symptom_details['sources_info'][$src_id]['src_year']) && $symptom_details['sources_info'][$src_id]['src_year'] != "-") {
						echo $symptom_details['sources_info'][$src_id]['src_year'];
					}
					echo ("  ".$symptom_details['sources_info'][$src_id]['src_copyright']."</span>");
				} elseif (!empty($symptom_details['sources_info'][$src_id]['src_year']) && $symptom_details['sources_info'][$src_id]['src_year'] != "-") {
					echo (", <span class='gray'>".$symptom_details['sources_info'][$src_id]['src_year']."</span>");
				}
				if (!empty($symptom_details['sources_info'][$src_id]['src_edition_version']) && $symptom_details['sources_info'][$src_id]['src_edition_version'] != "-") {
					echo (", $translations[edition_release]: <span class='gray'>".$symptom_details['sources_info'][$src_id]['src_edition_version']."</span>");
				}
				if (!empty($source_info[6]) && $source_info[6] != "-") {
					$source_info[6] = str_replace("\r\n", "<br />", $source_info[6]);
					$source_info[6] = str_replace("\r", "<br />", $source_info[6]);
					$source_info[6] = str_replace("\n", "<br />", $source_info[6]);
				}
				if (!empty($symptom_details['sources_info'][$src_id]['src_isbn']) && $symptom_details['sources_info'][$src_id]['src_isbn'] != "-") {
					echo ("      <strong>ISBN: </strong><span class='gray'>".$symptom_details['sources_info'][$src_id]['src_isbn']."</span>");
				}
				echo ("<br/>");
				// print references
				$ref_all="";
				if (!empty($symptom_details['sources'][$src_id]['reference'])) {
					$ref_all= "      <div class='mm-info-box-part-title'><strong>".count($symptom_details['sources'][$src_id]['reference'])." $translations[General_references]: </strong></div>";
					$ref_all=$ref_all."<div class='mm-info-box-reference'>";
					foreach ($symptom_details['sources'][$src_id]['reference'] as $reference_id=>$ref_val) {
						$ref_all=$ref_all."<strong>$reference_id</strong><span class='gray'> = ".$symptom_details['sources_info'][$reference_id]['src_title']." / ".$symptom_details['sources_info'][$reference_id]['src_author']."</span><br/>";
					}
					$ref_all=$ref_all."</div>";
				}
				
				// remedy overview
				
				$rem_count = count($symptom_details['symptoms'][$sym_id]['source'][$src_id]['sym_rem_relation']);
				echo "<b>".$rem_count."</b> $translations[General_entries]";
				if (!empty($symptom_details['sources_info'][$src_id]['page'])) {
					echo (" $translations[on_page] <strong>".$symptom_details['sources_info'][$src_id]['page']."</strong>");
				}
				echo "</div>"; //end rep info
				echo "<div class='mm-info-box-remedies'>";
				foreach ($symptom_details['symptoms'][$sym_id]['source'][$src_id]['sym_rem_relation'] as $sym_rem_relation=>$remedy) {
					$ref_tooltip = "";
					$ref_details = "";
					if(!empty($remedy['reference'])) {
						$ref_details = "Ref: ";
						$ref_tooltip = "<strong>$translations[General_references]</strong><br/>";
						foreach ($remedy['reference']  as $ref_id=>$ref_detail) {
							$ref_details =$ref_details." ".$ref_id.", ";
							$ref_tooltip = $ref_tooltip."<span style=\'font-size:0.8em;\'><strong>$ref_id</strong><span class=\'gray\'> = ".str_replace("'","&nbsp;",$symptom_details['sources_info'][$ref_id]['src_title'])." / ".$symptom_details['sources_info'][$ref_id]['src_author']."</span></span><br/>";
						}
					}
					$tooltip = "onmouseover=\"Tip('";
					$rem_name = "";
					foreach ($remedy['remedies']  as $rem_id=>$remedy_detail) {
						$rem_name = str_replace(" ","&nbsp;",$remedy_detail['rem_name']);
						$rem_name = str_replace("\'","&nbsp;",$rem_name);
						$remedy_tooltip="<strong><a href=\'materia-medica.php?rem=".$remedy_detail['rem_short']."\' title=\'materia medica\'>".$rem_name."</a>&nbsp;(".$remedy_detail['rem_short'].")</strong><br><span style=\'font-size:0.8em;\'>$translations[grade]: ".$remedy_detail['grade']."<br/></span>";
						$text_tooltip = $remedy_tooltip.$ref_tooltip;
						$tooltip = $tooltip.$text_tooltip."', CLOSEBTN, true, TITLE, '".$symptom_details['symptoms'][$sym_id]['rubric_name']." &gt; ".$symptom_details['symptoms'][$sym_id]['symptom_name']."', STICKY, true, PADDING, 5, TITLEBGCOLOR, '#D9E7BA', CLOSEBTNCOLORS, ['', '#66ff66', 'white', '#D9E7BA'], BGCOLOR, 'white', FONTCOLOR, 'black', FONTSIZE, '14px', TITLEFONTCOLOR, '#043C7F',BORDERCOLOR, '#D9E7BA', BORDERWIDTH, 5, OFFSETX, 0, DELAY, 600, FADEIN, 0, TITLEFONTFACE, 'times,serif', TITLEFONTSIZE, '18px' )\"  ";
						echo ("<a href=\"materia-medica.php?rem=".$remedy_detail['rem_short']."\" ><span class=\"grade".$remedy_detail['grade']."\" ".$tooltip." style=\"cursor:pointer;\">".$remedy_detail['rem_short']."</span></a> ");
					}
				}
				echo ("</div>");
				
				// ref all
				echo ($ref_all);
				
				echo "</div>";
			}
		}
	}
	echo "<br><br>";
} // else check_letter

include("skins/$skin/footer.php");

