<?php

/**
 * forms/sym_rems.php
 *
 * This file provides a form that shows the remedies for a given symptom in the symptominfo.
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
 * @package   SymRem
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

if (!empty($_REQUEST['getSymRems'])) {
	chdir("..");
	include_once ("include/classes/login/session.php");
	$lang = $session->lang;
}
include_once ("include/classes/symrem_class.php");
$symrem = new SymRem();
if (!$session->logged_in) {
	echo ("<p class='center'><span class='alert_box'><strong>" . _("Important!") . "</strong> " . _("Guests are limited to the Homeopathic Repertory from Kent (kent.en). For activating more repertories an customizing OpenHomeopath you've to <a href='http://openhomeo.org/openhomeopath/register.php'>register for free</a> and <a href='http://openhomeo.org/openhomeopath/login.php'>log in</a>.") . "</span></p>\n");
}
?>
  <form accept-charset="utf-8">
    <table class='rem_symptoms_head'>
      <tr>
        <td>
          <label for="sort"><span class="label"><?php echo _("Sort by"); ?></span></label>
        </td>
        <td>
          <span class="label"><?php echo _("Grade"); ?></span>
        </td>
      </tr>
      <tr>
        <td>
<?php
echo $symrem->get_sort_select();
?>
        </td>
        <td>
<?php
echo $symrem->get_grade_select();
?>
        </td>
      </tr>
    </table>
	<div class="clear"></div>
    <input id='symId' type='hidden' value='<?php echo $symrem->sym_id; ?>'>
    <br>
  </form>
  <div class = 'select'>
    <div class='selection' id='rems_list'>
<?php
if ($symrem->rem_count > 0)   {
	echo $symrem->get_rems_list();
} else {
	echo "    <p>&nbsp;- " . _("no corresponding remedies found") . " - </p>\n";
}

?>
    </div>
<?php
printf("    <p class='label'>" . ngettext("%d remedy-relation", "%d remedy-relations", $symrem->rem_count) . "</p>\n", $symrem->rem_count);
?>
    <div class="button_area_4">
      <div class='alert_box' style='width:120px;'>
        <p><img src='skins/original/img/materia.png' alt='Materia Medica' width='12' height='12'>&nbsp;<?php echo _("Materia Medica");?></p>
<?php
for ($i = 5; $i > 0; $i--) {
	echo "        <div class='grade_$i'>$i" . _("-grade") . "</div>\n";
}
?>
      </div>
    </div>
  </div>
