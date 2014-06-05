<?php

/**
 * forms/select_symptoms.php
 *
 * This file provides a form that shows the symptoms-tree, where you can select symptoms for repertorization.
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

if (!empty($_REQUEST['ajax'])) {
	chdir("..");
	include_once ("include/classes/login/session.php");
	$rubric_id = (empty($_REQUEST['rubric'])) ? -1 : $_REQUEST['rubric'];
	$symptoms_tbl = $db->get_custom_table("symptoms");
	$lang = $session->lang;
}
include ("include/classes/treeview_class.php");
if (!empty($_REQUEST['search'])) {
	include("include/classes/search_class.php");
	$search = new Search();
	$search->build_search();
	$query = "SELECT sym_id, symptom, pid, rubric_id FROM $symptoms_tbl WHERE ";
	if ($rubric_id != -1) {
		$query .= "rubric_id = '$rubric_id' AND ";
	}
	$query .= $search->search;
	$result = $db->send_query($query);
	$sym_count = $db->db_num_rows($result);
	if ($sym_count == 0 && isset($_REQUEST['rubric'])){
		$symptomtree = "        <p>&nbsp;- " . _("no corresponding symptom found") . " - </p>\n";
	} else {
		$query = "DROP TEMPORARY TABLE IF EXISTS search_result";
		$db->send_query($query);
		$query = "CREATE TEMPORARY TABLE search_result (
			sym_id mediumint(8) unsigned NOT NULL,
			symptom varchar(510) NOT NULL,
			pid mediumint(8) unsigned NOT NULL,
			rubric_id tinyint(3) unsigned NOT NULL,";
		$query .= "PRIMARY KEY(sym_id),
			KEY pid (pid)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		$db->send_query($query);
		$query = "CREATE TEMPORARY TABLE search_temp__1 (
			sym_id mediumint(8) unsigned NOT NULL,
			PRIMARY KEY(sym_id)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		$db->send_query($query);
		$query = "CREATE TEMPORARY TABLE search_temp__2 (
			sym_id mediumint(8) unsigned NOT NULL,
			pid mediumint(8) unsigned NOT NULL,
			PRIMARY KEY(sym_id),
			KEY pid (pid)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8";
		$db->send_query($query);
		while (list($sym_id, $symptom, $pid, $main_rubric_id) = $db->db_fetch_row($result)) {
			$query = "INSERT INTO search_result SET sym_id = $sym_id, symptom = '" . $db->escape_string($symptom) . "', pid  = $pid, rubric_id  = $main_rubric_id";
			$db->send_query($query);
			$query = "INSERT INTO search_temp__1 SET sym_id = $sym_id";
			$db->send_query($query);
			$query = "INSERT INTO search_temp__2 SET sym_id = $sym_id, pid  = $pid";
			$db->send_query($query);
		}
		do {
			$query = "SELECT DISTINCT pid FROM search_result WHERE pid != 0 AND NOT EXISTS (SELECT 1 FROM search_temp__1 WHERE search_temp__1.sym_id = search_result.pid) AND EXISTS (SELECT 1 FROM search_temp__2 WHERE search_temp__2.pid = search_result.pid AND search_temp__2.sym_id != search_result.sym_id)";
			$sub_result = $db->send_query($query);
			$num_rows = $db->db_num_rows($sub_result);
			if ($num_rows > 0) {
				while (list ($pid) = $db->db_fetch_row($sub_result)) {
					$query = "SELECT sym_id, symptom, pid, rubric_id FROM $symptoms_tbl WHERE sym_id = $pid";
					$sub_result2 = $db->send_query($query);
					list($sym_id, $symptom, $pid, $main_rubric_id) = $db->db_fetch_row($sub_result2);
					$db->free_result($sub_result2);
					$query = "INSERT INTO search_result SET sym_id = $sym_id, symptom = '" . $db->escape_string($symptom) . "', pid  = $pid, rubric_id  = $main_rubric_id";
					$db->send_query($query);
					$query = "INSERT INTO search_temp__1 SET sym_id = $sym_id";
					$db->send_query($query);
					$query = "INSERT INTO search_temp__2 SET sym_id = $sym_id, pid  = $pid";
					$db->send_query($query);
				}
			}
			$db->free_result($sub_result);
		} while ($num_rows > 0);
		$query = "UPDATE search_result SET pid  = 0 WHERE NOT EXISTS (SELECT 1 FROM search_temp__1 WHERE search_temp__1.sym_id = search_result.pid)";
		$db->send_query($query);
		$query = "DROP TEMPORARY TABLE IF EXISTS search_temp__1";
		$db->send_query($query);
		$query = "DROP TEMPORARY TABLE IF EXISTS search_temp__2";
		$db->send_query($query);
		$tree = new TreeView($rubric_id, "search_result");
		$static_tree = true;
		$symptomtree = $tree->build_symptomtree($static_tree);
	}
	$db->free_result($result);
} else {
	$tree = new TreeView($rubric_id, $symptoms_tbl);
	$symptomtree = $tree->build_symptomtree();
	$query = "SELECT COUNT(*) FROM $symptoms_tbl";
	if ($rubric_id != -1) {
		$query .= " WHERE rubric_id = '$rubric_id'";
	}
	$db->send_query($query);
	list ($sym_count) = $db->db_fetch_row();
	$db->free_result();
}
?>

    <div id='tree1' class='selection'>
<?php
echo $symptomtree;
?>
    </div>
<?php
printf ("    <p class='label'>" . ngettext("%d symptom", "%d symptoms", $sym_count) . "</p>\n", $sym_count);
?>
    <div class="button_area">
      <p class='alert_box' style='width:250px;'><?php echo _("Select the matching symptoms by clicking"); ?></p><br>
      <div class='alert_box' style='text-align:left; width:250px;'>
        <table class='legend2'>
          <tr>
            <td><img src='skins/original/img/main_folder.png' width='14' height='14'></td>
            <td><?php echo _("Main rubric");?></td>
          </tr>
          <tr>
            <td><img src='skins/original/img/folder_aeskulap.png' width='12' height='12'></td>
            <td><?php echo _("Symptom which contains sub-rubrics"); ?></td>
          </tr>
          <tr>
            <td><img src='skins/original/img/folder.png' width='12' height='12'></td>
            <td><?php echo _("Contains sub-rubrics, but doesn't count as a symptom"); ?></td>
          </tr>
          <tr>
            <td><img src='skins/original/img/aeskulap.png' width='12' height='12'></td>
            <td><?php echo _("Symptom"); ?></td>
          </tr>
          <tr>
            <td><img src='skins/original/img/info.gif' width='12' height='12'></td>
            <td><?php echo _("Symptom-Info");?></td>
          </tr>
        </table>
      </div>
    </div>
