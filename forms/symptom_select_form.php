<?php

/**
 * forms/symptom_select_form.php
 *
 * This file provides a form for retrieving symptoms for selection with several filters and search.
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
 * @package   SelectSymptoms
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

if ($session->logged_in && !$magic_hat->restricted_mode) {  // user logged in
	if (!$tabbed && !isset($_REQUEST['tab'])) {
		$url = "userinfo.php?user=" . $session->username . "#rep_custom";
	} else {
		$url = "javascript:userTabOpen('rep_custom')";
	}
	$is_custom_table = $db->is_custom_table("symptoms");
	if ($is_custom_table === false) {
		$display_personal_rep = "none";
		$display_all_rep = "block";
		$display_lang_rep = "none";
	} elseif ($is_custom_table === true) {
		$display_personal_rep = "block";
		$display_all_rep = "none";
		$display_lang_rep = "none";
	} else {
		$display_personal_rep = "none";
		$display_all_rep = "none";
		$display_lang_rep = "block";
	}
	printf ("<p class='center' id='all_rep' style='display:%s;'><span class='alert_box'>" . _("In <a href='%s'>My account</a> you can customize the Repertory to your personal needs.") . "</span></p>\n", $display_all_rep, $url);
	printf ("<p class='center' id='personalized_rep' style='display:%s;'><span class='alert_box'>" . _("You are using a personalized Repertory.") . " " . _("You can change the preferences in <a href='%s'>My account</a>.") . "</span></p>\n", $display_personal_rep, $url);
	printf ("<p class='center' id='lang_rep' style='display:%s;'><span class='alert_box'>" . _("You're using at the moment all <strong>symptoms in %s</strong>.") . " " . _("You can change the preferences in <a href='%s'>My account</a>.") . "</span></p>\n", $display_lang_rep, $is_custom_table, $url);
} elseif (!$session->logged_in) {
	echo ("<p class='center'><span class='alert_box'><strong>" . _("Important!") . "</strong> " . _("Guests are limited to the Homeopathic Repertory from Kent (kent.en). For activating more repertories an customizing OpenHomeopath you've to <a href='http://openhomeo.org/openhomeopath/register.php'>register for free</a> and <a href='http://openhomeo.org/openhomeopath/login.php'>log in</a>.") . "</span></p>\n");
} elseif ($magic_hat->restricted_mode) {
	echo ("<p class='center'><span class='alert_box'><strong>" . _("Important!") . "</strong> " . _("At the moment only the Homeopathic Repertory from Kent (kent.en) is enabled.") . "<br>" . _("As long as the donation goal for this month is not reached some functions of OpenHomeopath are only available for users who have already donated.") . "<br><a href=\"javascript:popup_url('donations.php',960,720)\"><strong>" . _("Please donate now!") . "</strong></a></span></p>\n");
}
?>
<fieldset>
  <legend class="legend">
    <?php echo _("Available symptoms"); ?>
  </legend>
  <form accept-charset="utf-8" onsubmit='return searchSymptoms();'>
    <table style="width:100%; border:0; text-align:left;">
      <tr>
        <td style="width:30%;">
          <label for="rubrics"><span class="label"><?php echo _("Select main rubric"); ?></span></label>
        </td>
        <td style="width:50%;">
          <label for="search"><span class="label"><?php echo _("Searching for"); ?></span></label>
        </td>
        <td></td>
      </tr>
      <tr>
        <td>
          <select class="drop-down" name="rubrics" id="rubrics" size="1">
<?php
$lang = $session->lang;
$rubric_id = 0;
if (!empty($_REQUEST['rubrics'])) {
	$rubric_id = $_REQUEST['rubrics'];
	if ($rubric_id == -1) {
		$current_rubric = _("all rubrics");
	} else {
		$query = "SELECT rubric_$lang FROM main_rubrics WHERE rubric_id = $rubric_id";
		$db->send_query($query);
		list ($current_rubric) = $db->db_fetch_row();
		$db->free_result();
	}
}
if (!empty($rubric_id)) {
	echo ("          <option value='$rubric_id' selected='selected'>$current_rubric</option>\n");
}
echo ("          <option value='-1' style='font-weight: bold'>" . _("all rubrics") . "</option>\n");
$query = "SELECT DISTINCT main_rubrics.rubric_id, main_rubrics.rubric_$lang FROM main_rubrics, $symptoms_tbl WHERE main_rubrics.rubric_id = $symptoms_tbl.rubric_id ORDER BY main_rubrics.rubric_$lang";
$db->send_query($query);
while($rubric = $db->db_fetch_row()) {
	echo ("          <option value='$rubric[0]'>$rubric[1]</option>\n");
}
$db->free_result();
?>
          </select>
        </td>
        <td>
<?php
echo ("          <input class='input' type='search' name='search' id='search' placeholder='" . _("Type words or part of words to filter the symptom") . "' autofocus size='52' maxlength='600'");
$search = "";
if (isset($_REQUEST['search'])) {
	$search = $_REQUEST['search'];
}
$search_clean = str_replace('\\', '', $search);
if ($search != "") {
	echo (" value='$search_clean'");
	}
echo (">\n");
?>
        </td>
        <td>
          <input type='button' value=' <?php echo _("Show symptoms"); ?> ' onclick='searchSymptoms()'>
        </td>
      </tr>
<?php
if (!empty($rubric_id) && $rubric_id != -1) {
	echo ("      <tr><td class='caption' id='main_rubric'>" . _("Main rubric:") . " <br><strong>$current_rubric</strong></td>\n");
} elseif (isset($current_rubric) && $current_rubric == _("all rubrics")) {
	echo ("      <tr><td class='caption' id='main_rubric'><strong>" . _("all rubrics") . "</strong></td>\n");
} else {
	echo ("      <tr>\n");
	echo ("        <td class='caption' id='main_rubric'></td>\n");
}
echo ("        <td class='caption'>\n");
$and_or = "";
if (isset($_REQUEST['and_or'])) {
	$and_or = $_REQUEST['and_or'];
}
if ($and_or == 'OR') {
	echo ("          <input type='radio' class='button' name='and_or' id='and' value='AND'>" . _("and") . "\n");
	echo ("          <input type='radio' class='button' name='and_or' id='or'  checked='checked'value='OR'>" . _("or") . "\n");
} else {
	echo ("          <input type='radio' class='button' name='and_or' id='and' checked='checked' value='AND'>" . _("and") . "\n");
	echo ("          <input type='radio' class='button' name='and_or' id='or' value='OR'>" . _("or") . "\n");
}
$whole_word = "";
if (isset($_REQUEST['whole_word'])) {
	$whole_word = $_REQUEST['whole_word'];
}
if ($whole_word === 'true') {
	echo ("          <span class='gray'>|</span><input type='radio' class='button' name='whole_word' id='wordpart' value='false'><span title='" . _("&raquo;part of word&laquo; means, that it will find words, which include this expression.") . "'>" . _("part of word") . "</span>\n");
	echo ("          <input type='radio' class='button' name='whole_word' id='whole_word' checked='checked' value='true'><span title='" . _("Here you can use regular expressions!") . "'>" . _("whole word") . "</span>\n");
} else {
	echo ("          <span class='gray'>|</span><input type='radio' class='button' name='whole_word' id='wordpart' checked='checked' value='false'><span title='" . _("&raquo;part of word&laquo; means, that it will find words, which include this expression.") . "'>" . _("part of word") . "</span>\n");
	echo ("          <input type='radio' class='button' name='whole_word' id='whole_word' value='true'><span title='" . _("Here you can use regular expressions!") . "'>" . _("whole word") . "</span>\n");
}
echo ("          <span class='gray'>|</span> <a href=\"javascript:popup_url('help/$lang/search.php',800,600)\">" . _("Help") . "</a><br>\n");
if ($search != "") {
	echo ("        <span id='search_item'>" . _("items for expressions:") . " <strong>$search_clean</strong></span></td></tr>\n");
} else  if (isset($_REQUEST['rubrics'])) {
	echo ("        <span id='search_item'>- " . _("no item for searching") . " -</span></td></tr>\n");
} else {
	echo ("        <span id='search_item'></span></td><td></td></tr>\n");
}
?>
    </table>
	<div class="clear"><br></div>
    <div class='select' id='select_symptoms'>
<?php
if (isset($_REQUEST['rubrics'])) {
	include ("forms/select_symptoms.php");
}
?>
    </div>
  </form>
</fieldset>
