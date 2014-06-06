<?php

/**
 * rep_results.php
 *
 * This file presents the repertorization results and give the possibility to save or print them.
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
 * @package   RepertorizationResult
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

include_once ("include/classes/login/session.php");
$skin = $session->skin;
$current_page = "rep_result";
include_once ("include/classes/rep_class.php");
$rep = new Rep();
if (isset($_REQUEST['tab'])) {
	include ("include/functions/layout.php");
} else {
	$head_title = _("Repertorization result") . " :: OpenHomeopath";
	include("./skins/$skin/header.php");
}
if (isset($_REQUEST['tab'])) {
?>
  <div style='float: right; margin: 25px;'>
      <a id='history_back_tab_1' style='padding: 7px;'><img alt=""  id='arrow_left_tab_1' height='24' width='38' src='./img/arrow_left_inactive.gif' border='0'></a><a id='history_forward_tab_1' style='padding: 7px;'><img alt=""  id='arrow_right_tab_1' height='24' width='38' src='./img/arrow_right_inactive.gif' border='0'></a>
  </div>
<?php
}
?>
<h1>
   <?php echo _("Repertorization result"); ?>
</h1>
<div id="save_rep">
<?php
include ("./forms/save_rep.php");
?>
</div>
<br clear="all">
<fieldset id='result_fieldset'>
  <legend class='legend'>
    <?php echo _("Result table"); ?>
  </legend>
<?php
if (!empty($rep->remedies_ar)) {
	$result_table = $rep->rep_result_table();
?>
  <div id='result_table'>
    <div id='whole_table'>
<?php
	echo $result_table;
?>
    </div>
    <div  id='symptom_table'>
<?php
	echo $result_table;
?>
    </div>
  </div>
<?php
}
$sym_txt = sprintf(ngettext("%d selected symptom", "%d selected symptoms", $rep->sym_count), $rep->sym_count);
$rem_txt = sprintf(ngettext("%d remedy", "%d remedies", $rep->rem_count), $rep->rem_count);
$rel_txt = sprintf(ngettext("%d symptom-remedy-relation", "%d symptom-remedy-relations", $rep->rel_count), $rep->rel_count);
printf ("  <p class='center label'>" . _("For %s there are %s and %s.") . "</p>\n", $sym_txt, $rem_txt, $rel_txt);
?>
</fieldset>
<form action="" accept-charset="utf-8">
  <div class="center">
    <br clear='all'><br>
<?php
foreach ($rep->sym_select as $sym_id => $degree) {
	$sym_select_ar[] = "$sym_id-$degree";
}
echo "      <input type='hidden' name='patient' id='patient' value='{$rep->patient}'>";
echo "      <input type='hidden' name='prescription' id='prescription' value='{$rep->prescription}'>";
echo "      <input type='hidden' name='note' id='note' value='{$rep->note}'>";
echo "      <input type='hidden' name='sym_select' id='sym_select' value='" . implode("_", $sym_select_ar) . "'>";
if (!isset($_REQUEST['tab'])) {
	$tab = -1;
} else {
	$tab = 0;
}
?>
    <p><input class="submit" type="button" onclick='addSymptoms(<?php echo($tab);?>)' value=" <?php echo _("Add more symptoms"); ?> "></p>
  </div>
</form>
<?php
if (!isset($_REQUEST['tab'])) {
	popup(1);
	include("./skins/$skin/footer.php");
}
