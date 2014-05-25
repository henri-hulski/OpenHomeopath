<?php

/**
 * repertori.php
 *
 * This file contains the repertorization form for query the database
 * and select the rubrics you want to repertorize.
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
 * @package   Repertorization
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

if (!isset($tabbed) || !$tabbed) {
	include_once ("include/classes/login/session.php");
}
if (!$tabbed && !isset($_REQUEST['tab'])) {
	$head_title = _("Repertorization") . " :: OpenHomeopath";
	$skin = $session->skin;
	include("./skins/$skin/header.php");
}
if (isset($_REQUEST['rep'])) {
	$rep_id = $_REQUEST['rep'];
	$query = "SELECT sym_id, degree FROM rep_sym WHERE rep_id='$rep_id' ORDER BY degree DESC, sym_id ASC";
	$db->send_query($query);
	unset($symselect);
	while(list($symtom_id, $degree) = $db->db_fetch_row()) {
		$symselect[] = "$symtom_id-$degree";
	}
	$db->free_result();
}
?>
<h1>
   <?php echo _("Repertorization"); ?>
</h1>
<?php
$symptoms_tbl = $db->get_custom_table("symptoms");
include ("forms/symptom_select_form.php");
if ((isset($_REQUEST['rubrics']) && !empty($num_rows)) || !empty($_REQUEST['symsel']) || !empty($symselect)) {
	$display = "block";
} else {
	$display = "none";
}
?>
<div id='selected_symptoms' style='display:<?php echo($display);?>;'>
<fieldset>
  <legend class='legend'>
    <?php echo _("Selected symptoms"); ?>
  </legend>
  <form id='selected_symptoms_form' action='rep_result.php?' accept-charset='utf-8'>
    <div class='select'>
      <div class='selection' id='symSelect'>
<?php
	if (isset($_REQUEST['symsel']) || isset($symselect)) {
		if (empty($symselect)) {
			$symselect = explode("_", $_REQUEST["symsel"]);
		}
		$i = 0;
		$selected_symptoms_list = "";
		foreach ($symselect as $symptom) {
			list($sym_id, $grade) = explode('-', $symptom);
			$selected = array("","","","","");
			$selected[$grade] = " selected='selected'";
			$i++;
			$query = "SELECT symptoms.symptom, main_rubrics.rubric_$lang FROM symptoms, main_rubrics WHERE symptoms.sym_id = $sym_id AND main_rubrics.rubric_id = symptoms.rubric_id";
			$db->send_query($query);
			list ($symptom, $rubric_name) = $db->db_fetch_row();
			$db->free_result();
			$selected_symptoms_list .= "        <div id='sympt_$i'>";
// 			$selected_symptoms_list .= "<input id='check_$i' type='checkbox' value='$sym_id'>";
 			$selected_symptoms_list .= "<input type='hidden' value='$sym_id'>";
			$selected_symptoms_list .= "&nbsp;<select size='1' title='" . _("Rubric degree") . "'><option value='0'$selected[0]>0</option><option value='1'$selected[1]>1</option><option value='2'$selected[2]>2</option><option value='3'$selected[3]>3</option><option value='4'$selected[4]>4</option></select>";
			$selected_symptoms_list .= "&nbsp;<a href='javascript:symptomData($sym_id);' title='" . _("Symptom-Info") . "'><img src='skins/original/img/info.gif' width='12' height='12'></a>";
			$selected_symptoms_list .= "&nbsp;<a href=\"javascript:symDeselect('sympt_$i');\" title='" . _("Deselect symptom") . "'><img src='skins/original/img/del.png' width='12' height='12'></a>";
//			$selected_symptoms_list .= "&nbsp;&nbsp;<label for='check_$i' title='$rubric_name >> $symptom'>$rubric_name >> $symptom</label>";
			$selected_symptoms_list .= "&nbsp;&nbsp;$rubric_name >> $symptom";
			$selected_symptoms_list .= "</div>\n";
		}
		echo $selected_symptoms_list;
	}
?>
      </div>
<?php
	if (isset($_REQUEST['patient'])) {
		echo "      <input type='hidden' name='patient' value='" . $_REQUEST['patient'] . "'>";
	}
	if (isset($_REQUEST['prescription'])) {
		echo "      <input type='hidden' name='prescription' value='" . $_REQUEST['prescription'] . "'>";
	}
	if (isset($_REQUEST['note'])) {
		echo "      <input type='hidden' name='note' value='" . $_REQUEST['note'] . "'>";
	}
	if (!$tabbed && !isset($_REQUEST['tab'])) {
		$tab = -1;
	} else {
		$tab = 1;
	}
?>
      <div class="button_area">
        <div class='alert_box' style='text-align:left; width:250px;'>
          <table class='legend2'>
            <tr>
              <td class='center'><select size='1' style='color:#666;'><option>1</option></select></td>
              <td><?php echo _("Rubric degree");?></td>
            </tr>
            <tr>
              <td class='center'><img src='skins/original/img/info.gif' width='12' height='12'></td>
              <td><?php echo _("Symptom-Info");?></td>
            </tr>
            <tr>
              <td class='center'><img src='skins/original/img/del.png' width='12' height='12'></td>
              <td><?php echo _("Deselect symptom"); ?></td>
            </tr>
          </table>
        </div>
        <br><br>
        <input type="button" class="submit" name="submit" value=" <?php echo _("Repertorize"); ?> " onclick="rep(<?php echo($tab);?>)">
      </div>
    </div>
  </form>
</fieldset>
</div>
<?php
if (!$tabbed && !isset($_REQUEST['tab'])) {
	popup();
	include("./skins/$skin/footer.php");
}
?>
