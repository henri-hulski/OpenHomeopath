<?php

/**
 * materia.php
 *
 * This file presents the Materia Medica with the reversed repertory.
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
 * @package   MateriaMedica
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

if (!isset($tabbed) || !$tabbed) {
	include_once ("include/classes/login/session.php");
}
$head_title = "Materia Medica :: OpenHomeopath";
if (!empty($_REQUEST['rem'])) {
	$rem_id = $_REQUEST['rem'];
	$query = "SELECT rem_short, rem_name FROM remedies WHERE rem_id = $rem_id";
	$db->send_query($query);
	list ($rem_short, $rem_name) = $db->db_fetch_row();
	$db->free_result();
	$head_title = "$rem_name :: $head_title";
	$meta_description = "$rem_name ($rem_short)";
	$meta_keywords = "$rem_name, $rem_short";
}
if (!$tabbed && !isset($_GET['tab'])) {
	$current_page = "materia";
	$skin = $session->skin;
	include("./skins/$skin/header.php");
} else {
?>
  <div style='float: right; margin: 25px;'>
      <a id='history_back_tab_2' style='padding: 7px;'><img alt=""  id='arrow_left_tab_2' height='24' width='28' src='./img/arrow_left_inactive.gif' border='0'></a><a id='history_forward_tab_2' style='padding: 7px;'><img alt=""  id='arrow_right_tab_2' height='24' width='28' src='./img/arrow_right_inactive.gif' border='0'></a>
  </div>
<?php
}
?>
<h1>
  <?php echo _("Materia Medica"); ?>
</h1>
<?php
if ($session->logged_in) {  // user logged in
	if (!$tabbed && !isset($_REQUEST['tab'])) {
		$url = "userinfo.php?user=" . $session->username . "#materia_custom";
	} else {
		$url = 'javascript:userTabOpen("materia_custom")';
	}
	if ($db->is_custom_table("materia") === false) {
		$display_personal_materia = "none";
		$display_all_materia = "block";
	} else {
		$display_personal_materia = "block";
		$display_all_materia = "none";
	}
	printf ("<p class='center' id='all_materia' style='display:%s;'><span class='alert_box'>" . _("In <a href='%s'>My account</a> you can customize the Materia Medica to your personal needs.") . "</span></p>\n", $display_all_materia, $url);
	printf ("<p class='center' id='personalized_materia' style='display:%s;'><span class='alert_box'>" . _("You're using a personalized Materia Medica.") . " " . _("You can change the preferences in <a href='%s'>My account</a>.") . "</span></p>\n", $display_personal_materia, $url);
} else {
	echo ("<p class='center''><span class='alert_box'>" . _("When <a href='http://openhomeo.org/openhomeopath/login.php'>logged in</a> you can customize the Materia Medica to your personal needs.") . "</span></p>\n");
}
?>
<fieldset>
  <legend class="legend">
    <?php echo _("Materia Medica"); ?>
  </legend>
  <form action="" accept-charset="utf-8" onsubmit="return false">
    <label for="query"><span class="label"><?php echo _("Type the beginning of the word and select the remedy"); ?></span></label>
    <div style='position:relative;top:0;left:0;'>
      <input name="query" id="query" type="text" autocomplete="off" onkeyup="autosuggest('auto_rem')" onclick='cleanRem()'
<?php
if (!empty($_REQUEST['rem'])) {
	echo ("value='$rem_short&nbsp;&nbsp;$rem_name'");
}
?>
      >
      <div id="search_icon" onclick='getMateria(-1)'><img alt=""  src='./skins/original/img/search.png' width='24' height='24'></div>
<?php
if (isset($rem_id)) {
	echo ("      <input id='remId' type='hidden' value='$rem_id'>");
} else {
	echo ("      <input id='remId' type='hidden' value=''>");
}
?>
      <div id="results"></div>
    </div>
  </form>
  <div id='materia_medica'>
<?php
if (isset($rem_id)) {
	include ("./forms/materia_medica.php");
}
?>
  </div>
</fieldset>
<?php
if (!$tabbed && !isset($_GET['tab'])) {
	popup(1);
	include("./skins/$skin/footer.php");
}
?>
