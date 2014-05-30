<?php

/**
 * details.php
 *
 * This file shows details of symptom-remedy-relations.
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
 * @package   Details
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

include_once ("include/classes/login/session.php");
include_once ("include/classes/rep_class.php");
$rep = new Rep();
if (empty($_GET['popup'])) {
	$head_title = _("Symptom-remedy-details") . " :: OpenHomeopath";
	$skin = $session->skin;
	include("./skins/$skin/header.php");
}
?>
<h1>
  <?php echo _("Symptom-remedy-details"); ?>
</h1>
<?php
$kuenzli = 0;
unset($sources);
$sym_id = $_GET['sym'];
$rem_id = $_GET['rem'];
if (!empty($_GET['sym_rem_tbl'])) {
	$sym_rem_tbl = $_GET['sym_rem_tbl'];
} else {
	$sym_rem_tbl = "sym_rem";
}
$symptoms_tbl = $db->get_custom_table("symptoms");
$query = "SELECT $symptoms_tbl.symptom, remedies.rem_name, remedies.rem_short FROM $symptoms_tbl, remedies WHERE $symptoms_tbl.sym_id = $sym_id AND remedies.rem_id = $rem_id";
$db->send_query($query);
list($symptom, $rem_name, $rem_short) = $db->db_fetch_row();
$db->free_result();
$max_grade = $rep->get_max_grade($sym_id, $rem_id, $sym_rem_tbl);
$query = "SELECT $sym_rem_tbl.src_id, $sym_rem_tbl.grade, $sym_rem_tbl.rel_id, $sym_rem_tbl.kuenzli, sym_status.status_symbol, sym_status.status_$lang FROM $sym_rem_tbl, sym_status WHERE $sym_rem_tbl.sym_id = $sym_id AND $sym_rem_tbl.rem_id = $rem_id AND sym_status.status_id = $sym_rem_tbl.status_id ORDER BY sym_status.status_grade DESC, $sym_rem_tbl.grade DESC, $sym_rem_tbl.src_id ASC";
$result = $db->send_query($query);
$i = 0;
while (list($src_id, $grade, $rel_id, $kuenzli_dot, $status_symbol, $status_name) = $db->db_fetch_row($result)) {
	$i++;
	$sources[$i]['id'] = $src_id;
	$sources[$i]['grade'] = $grade;
	$query = "SELECT src_id, nonclassic FROM sym_rem_refs WHERE rel_id = $rel_id ORDER BY nonclassic, src_id";
	$db->send_query($query);
	unset($ref_array);
	$j = 0;
	while (list ($ref_id, $nonclassic) = $db->db_fetch_row()) {
		$j++;
		$sources[$i]['refs'][$j] = "<a href=\"javascript:popup_url('source.php?src=$ref_id',600,400)\">$ref_id</a>";
		if ($nonclassic == 1) {
			$sources[$i]['refs'][$j] .= " (nicht klassisch)";
		}
	}
	$db->free_result();
	if (!empty($status_symbol)) {
		$sources[$i]['status'] = "$status_name (<strong>$status_symbol</strong>)";
	}
	if ($kuenzli_dot == 1) {
		$kuenzli = 1;
	}
}
$db->free_result($result);
echo ("  <ul class='blue'>\n");
echo ("    <li><strong>" . _("Symptom:") . " </strong><span class='gray'>$symptom</span></li>\n");
echo ("    <li><strong>" . _("Remedy:") . " </strong><span class='gray'>$rem_name ($rem_short)</span></li>\n");
echo ("    <li><strong>" . _("Max. grade:") . " </strong><span class='gray'>$max_grade</span></li>\n");
if ($kuenzli_dot == 1) {
	echo ("    <li><strong>" . _("Künzli-dot:") . " </strong><span class='gray'>" . _("This section has been awarded a Künzli-dot. This means that it is therapeutically significant and often leads directly to the choice of remedy or such substantially narrow down.") . "</span></li>\n");
}
if (!empty($sources)) {
	$src_count = count($sources);
	echo ("    <li><strong>" . ngettext("Source:", "Sources:", $src_count) . "</strong>\n");
	echo ("    <ul>\n");
	foreach ($sources as $src_ar) {
		echo ("      <li><strong><a href=\"javascript:popup_url('source.php?src=" . $src_ar['id'] . "',540,380)\">" . $src_ar['id'] . "</a>:</strong>\n");
		echo ("      <ul>\n");
		echo ("        <li><strong>" . _("Grade:") . " </strong><span class='gray'>" . $src_ar['grade'] . "</span></li>\n");
		if (!empty($src_ar['status'])) {
			echo ("        <li><strong>" . _("State:") . " </strong><span class='gray'>" . $src_ar['status'] . "</span></li>\n");
		}
		if (!empty($src_ar['refs'])) {
			echo ("        <li><strong>" . _("References:") . "</strong>\n");
			echo ("        <ul>\n");
			foreach ($src_ar['refs'] as $ref) {
				echo ("          <li>$ref</li>\n");
			}
			echo ("        </ul></li>\n");
		}
		echo ("      </ul></li>\n");
	}
	echo ("    </ul></li>\n");
}
echo ("  </ul>\n");
if (empty($_GET['popup'])) {
	popup();
	include("./skins/$skin/footer.php");
}
?>
