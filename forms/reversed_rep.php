<?php

/**
 * forms/reversed_rep.php
 *
 * This file provides a form for the reversed repertory, that shows the symptoms for a given remedy in the Materia Medica.
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
 * @package   RevRep
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

if (!empty($_REQUEST['getRemSymptoms'])) {
	chdir("..");
	include_once ("include/classes/login/session.php");
	$lang = $session->lang;
}
include_once ("include/classes/revrep_class.php");
$revrep = new RevRep();
if ($session->logged_in && !$magic_hat->restricted_mode) {
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
	printf ("<p class='center' id='personalized_rep_1' style='display:%s;'><span class='alert_box'>" . _("You are using a personalized Repertory.") . " " . _("You can change the preferences in <a href='%s'>My account</a>.") . "</span></p>\n", $display_personal_rep, $url);
} elseif (!$session->logged_in) {
	echo ("<p class='center'><span class='alert_box'><strong>" . _("Important!") . "</strong> " . _("Guests are limited to the Homeopathic Repertory from Kent (kent.en). For activating more repertories an customizing OpenHomeopath you've to <a href='http://openhomeo.org/openhomeopath/register.php'>register for free</a> and <a href='http://openhomeo.org/openhomeopath/login.php'>log in</a>.") . "</span></p>\n");
} elseif ($magic_hat->restricted_mode) {
	echo ("<p class='center'><span class='alert_box'><strong>" . _("Important!") . "</strong> " . _("At the moment only the Homeopathic Repertory from Kent (kent.en) is enabled.") . "<br>" . _("As long as the donation goal for this month is not reached some functions of OpenHomeopath are only available for users who have already donated.") . "<br><a href=\"javascript:popup_url('donations.php',960,720)\"><strong>" . _("Please donate now!") . "</strong></a></span></p>\n");
}
?>
  <form accept-charset="utf-8">
    <table class='rem_symptoms_head'>
      <tr>
        <td>
          <label for="rem_rubrics"><span class="label"><?php echo _("Main rubric"); ?></span></label>
        </td>
        <td>
          <span class="label"><?php echo _("Grade"); ?></span>
        </td>
        <td></td>
      </tr>
      <tr>
        <td>
          <select class="drop-down" name="rem_rubrics" id="rem_rubrics" size="1">
<?php
$rem_rubrics_ar = array();
if (!empty($_REQUEST['rem_rubric']) && $_REQUEST['rem_rubric'] != -1) {
	$query = "SELECT rubric_$lang FROM main_rubrics WHERE rubric_id = $_REQUEST[rem_rubric]";
	$db->send_query($query);
	list($rem_rubrics_ar[$_REQUEST['rem_rubric']]) = $db->db_fetch_row();
	$db->free_result();
	echo ("          <option value='$_REQUEST[rem_rubric]' selected='selected'>" . $rem_rubrics_ar[$_REQUEST['rem_rubric']] . "</option>\n");
}
echo ("          <option value='-1' style='font-weight: bold'>" . _("all rubrics") . "</option>\n");
$query = "SELECT DISTINCT main_rubrics.rubric_id, main_rubrics.rubric_$lang FROM main_rubrics, {$revrep->symptoms_tbl}, {$revrep->sym_rem_tbl} WHERE main_rubrics.rubric_id = {$revrep->symptoms_tbl}.rubric_id AND {$revrep->sym_rem_tbl}.sym_id = {$revrep->symptoms_tbl}.sym_id AND {$revrep->sym_rem_tbl}.rem_id = {$revrep->rem_id} ORDER BY main_rubrics.rubric_$lang";
$db->send_query($query);
while(list($rem_rubric_id, $rem_rubric) = $db->db_fetch_row()) {
	if (empty($_REQUEST['rem_rubric']) || $_REQUEST['rem_rubric'] == -1) {
		$rem_rubrics_ar[$rem_rubric_id] = $rem_rubric;
	}
	echo ("          <option value='$rem_rubric_id'>$rem_rubric</option>\n");
}
$db->free_result();
?>
          </select>
        </td>
        <td>
<?php
echo $revrep->get_grade_select();
?>
        </td>
        <td>
          <input class='submit' type='button' value=' <?php echo _("Send request"); ?> ' onclick="getRemSymptoms('grade')">
        </td>
      </tr>
    </table>
	<div class="clear"></div>
    <input id='revRemId' type='hidden' value='<?php echo $revrep->rem_id; ?>'>
    <br>
  </form>
  <div class = 'select'>
    <div id='tree2' class='selection'>
<?php
$revrep->prepare_rem_symptoms($rem_rubrics_ar);
if ($revrep->sym_count > 0)   {
	$symptomtree = $revrep->build_symptomtree();
} else {
	$symptomtree =  "      <p>&nbsp;- " . _("no corresponding symptom found") . " - </p>\n";
}
echo $symptomtree;
?>
    </div>
<?php
printf("    <p class='label'>" . ngettext("%d symptom", "%d symptoms", $revrep->sym_count) . "</p>\n", $revrep->sym_count);
?>
    <div class="button_area_4">
      <div class='alert_box' style='width:120px;'>
<?php
for ($i = 5; $i > 0; $i--) {
	echo "        <span class='grade_$i'>$i" . _("-grade") . "</span><br>\n";
}
?>
      </div>
      <br>
      <div class='alert_box' style='text-align:left; width:250px;'>
        <table class='legend2'>
          <tr>
            <td><img src='skins/original/img/main_folder.png' alt='Main rubric' width='14' height='14'></td>
            <td><?php echo _("Main rubric");?></td>
          </tr>
          <tr>
            <td><img src='skins/original/img/folder_aeskulap.png' alt='Symptom rubric' width='12' height='12'></td>
            <td><?php echo _("Symptom which contains sub-rubrics"); ?></td>
          </tr>
          <tr>
            <td><img src='skins/original/img/folder.png' alt='Rubric' width='12' height='12'></td>
            <td><?php echo _("Contains sub-rubrics, but doesn't count itself"); ?></td>
          </tr>
          <tr>
            <td><img src='skins/original/img/aeskulap.png' alt='Symptom' width='12' height='12'></td>
            <td><?php echo _("Symptom"); ?></td>
          </tr>
          <tr>
            <td><img src='skins/original/img/info.gif' alt='Info' width='12' height='12'></td>
            <td><?php echo _("Symptom-Info");?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
