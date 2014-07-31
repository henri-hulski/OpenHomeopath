<?php

/**
 * symptominfo.php
 *
 * This file shows the details of a rubric.
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
 * @package   RubricInfo
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

if (!isset($tabbed) || !$tabbed) {
	include_once ("include/classes/login/session.php");
}
$lang = $session->lang;
if (!empty($_REQUEST['sym'])) {
	$sym_id = $_REQUEST['sym'];
	$query = "SELECT symptoms.symptom, symptoms.rubric_id, main_rubrics.rubric_$lang, languages.lang_$lang, symptoms.translation, symptoms.xref_id, sym_src.kuenzli FROM (symptoms, main_rubrics, languages) LEFT JOIN sym_src ON sym_src.sym_id = symptoms.sym_id WHERE main_rubrics.rubric_id = symptoms.rubric_id AND languages.lang_id = symptoms.lang_id AND symptoms.sym_id = $sym_id";
	$db->send_query($query);
	list($symptom, $rubric_id, $rubric_name, $lang_name, $translation, $xref_id, $kuenzli) = $db->db_fetch_row();
	$db->free_result();
}
if (!$tabbed && !isset($_GET['tab']) && empty($_GET['popup'])) {
	if (!empty($sym_id)) {
		$head_title = "$rubric_name >> $symptom :: " . _("Symptom-Info") . " :: OpenHomeopath";
	}
	$current_page = "symptominfo";
	$skin = $session->skin;
	include("skins/$skin/header.php");
} elseif (empty($_GET['popup'])) {
?>
  <div style='float: right; margin: 25px;'>
      <a id='history_back_tab_3' style='padding: 7px;'><img alt=""  id='arrow_left_tab_3' height='24' width='38' src='./img/arrow_left_inactive.gif'></a><a id='history_forward_tab_3' style='padding: 7px;'><img alt=""  id='arrow_right_tab_3' height='24' width='38' src='./img/arrow_right_inactive.gif'></a>
  </div>
<?php
}
?>
<h1>
  <?php echo _("Symptom-Info"); ?>
</h1>
<?php
$sym_rem_tbl = $db->get_custom_table("sym_rem");
if ($session->logged_in) {
	if (!$tabbed && !isset($_REQUEST['tab'])) {
		$url = "userinfo.php?user=" . $session->username . "#rep_custom";
	} else {
		$url = 'javascript:userTabOpen("rep_custom")';
	}
	if ($db->is_custom_table("sym_rem") === false) {
		$display_personal_rep = "none";
	} else {
		$display_personal_rep = "block";
	}
	printf("<p class='center' id='personalized_rep_2' style='display:%s;'><span class='alert_box'>" . _("You are using a personalized Repertory. You can change the preferences in <a href='%s'>My account</a>.") . "</span></p>\n", $display_personal_rep, $url);
} else {
	echo ("<p class='center'><span class='alert_box'><strong>" . _("Important!") . "</strong> " . _("Guests are limited to the Homeopathic Repertory from Kent (kent.en). For activating more repertories an customizing OpenHomeopath you've to <a href='register.php'>register for free</a> and <a href='login.php'>log in</a>.") . "</span></p>\n");
}
?>
<fieldset>
<?php
$symptom_link = $symptom;
if (strpos($symptom, " > ") !== false) {
	$main_rubrics_ar = explode(" > ", $symptom);
	$main_rubrics_num = count($main_rubrics_ar);
	$main_rubric = array_pop($main_rubrics_ar);
	$main_rubrics_link_ar[$main_rubrics_num] = $main_rubric;
	while (count($main_rubrics_ar) > 0) {
		$main_rubrics_num = count($main_rubrics_ar);
		$main_rubrics = implode(" > ", $main_rubrics_ar);
		$query = "SELECT sym_id FROM symptoms WHERE rubric_id = $rubric_id AND symptom = '$main_rubrics'";
		$db->send_query($query);
		list($subrubric_id) = $db->db_fetch_row();
		$db->free_result();
		$main_rubric = array_pop($main_rubrics_ar);
		if (!empty($subrubric_id)) {
			if (!$tabbed && !isset($_GET['tab'])) {
				$main_rubric = "<a href='symptominfo.php?sym=$subrubric_id'>$main_rubric</a>";
			} else {
				$main_rubric = "<a href='javascript:tabOpen(\"symptominfo.php?sym=\", $subrubric_id, \"GET\", 3)'>$main_rubric</a>";
			}
		}
		$main_rubrics_link_ar[$main_rubrics_num] = $main_rubric;
	}
	ksort($main_rubrics_link_ar, SORT_NUMERIC);
	$symptom_link = implode(" > ", $main_rubrics_link_ar);
}
echo ("  <legend class='legend'>\n");
echo ("    $rubric_name >> $symptom_link\n");
echo ("  </legend>\n");
echo ("  <ul class='blue'>\n");
echo ("    <li><strong>" . _("Symptom:") . " </strong><span class='gray'>$symptom</span></li>\n");
echo ("    <li><strong>" . _("Symptom-No.:") . " </strong><span class='gray'>$sym_id</span></li>\n");
echo ("    <li><strong>" . _("Main rubric:") . " </strong><span class='gray'>$rubric_name</span></li>\n");
echo ("    <li><strong>" . _("Native language:") . " </strong><span class='gray'>$lang_name");
echo ("</span></li>\n");
if ($db->is_translated($sym_id)) {
	$query = "SELECT DISTINCT st.symptom, l.lang_$lang FROM sym_translations st, languages l WHERE st.sym_id = $sym_id AND l.lang_id = st.lang_id";
	$db->send_query($query);
	$num_rows = $db->db_num_rows();
	if ($num_rows > 0) {
		echo "    <li><strong>" . _("Translations:") . "</strong>\n";
		echo "      <ul>\n";
		while (list($trans_symptom, $trans_lang_name) = $db->db_fetch_row()) {
			echo "        <li><strong>" . $trans_lang_name . ": </strong><span class='gray'>$trans_symptom</span></li>\n";
		}
		echo "      </ul>\n";
		echo "    </li>\n";
	}
	$db->free_result();
}
if ($kuenzli == 1) {
	echo ("    <li><strong>" . _("Künzli-dot:") . " </strong><span class='gray'>" . _("This section has been awarded a Künzli-dot. This means that it is therapeutically significant and often leads directly to the choice of remedy or such substantially narrow down.") . "</span></li>\n");
}
echo ("    <li><strong>" . _("More details:") . " </strong><span class='gray'> <a href='http://openhomeo.org/openhomeopath/symptom-details.php?sym=$sym_id&lang=$lang' target='_blank'>OpenHomeo.org</a></span></li>\n");
echo ("  </ul>\n");
if (!empty($xref_id)) {
	$query = "SELECT DISTINCT symptoms.sym_id, symptoms.symptom, main_rubrics.rubric_$lang FROM symptoms, main_rubrics WHERE symptoms.xref_id = $xref_id AND symptoms.sym_id != $sym_id AND main_rubrics.rubric_id = symptoms.rubric_id";
	$db->send_query($query);
	$num_rows = $db->db_num_rows();
	if ($num_rows > 0) {
		echo ("  <h3>" . _("Cross references") . "</h3>\n");
		echo ("  <div id='xref' class='selection'>\n");
		echo ("    <ul>\n");
		while (list($xref_sym_id, $xref_symptom_name, $xref_rubric_name) = $db->db_fetch_row()) {
			if (!$tabbed && !isset($_GET['tab'])) {
				$xref_symptom = "<a href='symptominfo.php?sym=$xref_sym_id'><strong>$xref_rubric_name</strong> >> $xref_symptom_name</a>";
			} else {
				$xref_symptom = "<a href='javascript:tabOpen(\"symptominfo.php?sym=\", $xref_sym_id, \"GET\", 3)'><strong>$xref_rubric_name</strong> >> $xref_symptom_name</a>";
			}
			echo ("      <li>$xref_symptom</li>\n");
		}
		echo ("    </ul>\n");
		echo ("  </div>\n");
	}
	$db->free_result();
}

$query = "SELECT COUNT(*) FROM symptoms WHERE pid = $sym_id";
$db->send_query($query);
list ($num_children) = $db->db_fetch_row();
$db->free_result();
echo ("  <h3>" . _("Treeview") . "</h3>\n");
echo "  <div id='tree3' class='selection'>\n";
echo "    <div id='tree3-0' style='padding-left:20px;'>\n";
echo "      <span id='symbol_tree3-0'><a href=\"javascript:collapse_static('tree3_0',1,0);\" class='nodecls_main'><img src='skins/original/img/main_folder_open_arrow.png' alt='Collapse main rubric' width='14' height='14'> <img src='skins/original/img/main_folder_open.png' alt='Main rubric' width='14' height='14'> </a></span>\n";
echo "      <span class='nodecls_main'>$rubric_name</span>\n";
echo "    </div>\n";
echo "    <div id='tree3_0' style='padding-left:20px; display:block'>\n";
if ($num_children > 0) {
	include ("include/classes/treeview_class.php");
	$tree = new TreeView($rubric_id, "symptoms");
	$symptom_ar = $tree->get_treeview($sym_id);
	$child = $tree->generate_child("tree3_0_0", $symptom_ar);
	echo "      <div id='tree3-0-0' style='padding-left:20px'>\n";
	echo "        <span id='symbol_tree3-0-0'><a href=\"javascript:collapse_static('tree3_0_0',0,1);\" class='nodecls'><img src='skins/original/img/folder_open_arrow.png' alt='Collapse rubric' width='12' height='12'> <img src='skins/original/img/folder_open_aeskulap.png' alt='Symptom rubric' width='12' height='12'> </a></span>\n";
	echo "        <span class='nodecls'><strong>$symptom</strong></span>\n";
	echo "      </div>\n";
	echo "      <div id='tree3_0_0' style='padding-left:20px; display:block'>\n";
	echo $child;
	echo "      </div>\n";
} else {
	echo "      <div id='tree3-0-0' style='padding-left:20px'>\n";
	echo "        <span id='symbol_tree3-0-0' class='nodecls'><span style='visibility:hidden'><img src='skins/original/img/folder_arrow.png' alt='Expand rubric' width='12' height='12'> </span><img src='skins/original/img/aeskulap.png' alt='Symptom' width='12' height='12'></span>\n";
	echo "        <span class='nodecls'><strong>$symptom</strong></span>\n";
	echo "      </div>\n";
}
echo "      </div>\n";
echo "    </div>\n";
echo "  </div>\n";

echo ("  <h3>" . _("Corresponding remedies") . "</h3>\n");
?>
  <div id='sym_rems'>
<?php
include("forms/sym_rems.php");
?>
  </div>
</fieldset>
<?php
if (!$tabbed && !isset($_GET['tab']) && empty($_GET['popup'])) {
	popup(1);
	include("./skins/$skin/footer.php");
}
?>
