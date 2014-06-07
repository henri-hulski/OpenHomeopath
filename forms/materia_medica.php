<?php

/**
 * forms/materia_medica.php
 *
 * This file provides a form for the reversed repertory of the Materia Medica.
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

if (!empty($_REQUEST['ajax']) || !empty($_REQUEST['tab'])) {
	if (!empty($_REQUEST['ajax'])) {
		chdir("..");
	}
	include_once ("include/classes/login/session.php");
	$sort = (empty($_REQUEST['sort'])) ? "" : $_REQUEST['sort'];
	$rem_id = (empty($_REQUEST['rem'])) ? "" : $_REQUEST['rem'];
	$query = "SELECT rem_short, rem_name FROM remedies WHERE rem_id = $rem_id";
	$db->send_query($query);
	list ($rem_short, $rem_name) = $db->db_fetch_row();
	$db->free_result();
}
$materia_tbl = $db->get_custom_table("materia");
$sym_rem_tbl = $db->get_custom_table("sym_rem");
$symptoms_tbl = $db->get_custom_table("symptoms");
$lang = $session->lang;

$alias_rows = 0;
$query = "SELECT alias_short FROM rem_alias WHERE rem_id = $rem_id ORDER BY alias_short";
$db->send_query($query);
$alias_rows = $db->db_num_rows();
if ($alias_rows > 0) {
	while ($alias = $db->db_fetch_row()) {
		$alias[0] = ucfirst($alias[0]);  // first letter uppercase
		$alias_ar[] = $alias[0];
	}
	$aliase  =  implode(", ", $alias_ar);
}
$db->free_result();

echo ("  <br>\n");
echo ("  <h2>$rem_name ($rem_short)</h2>\n");
echo ("  <ul class='blue'>\n");
echo ("    <li><strong>" . _("Remedy name:") . " </strong><span class='gray'>$rem_name</span></li>\n");
echo ("    <li><strong>" . _("Remedy-No.:") . " </strong><span class='gray'>$rem_id</span></li>\n");
echo ("    <li><strong>" . _(" Abbreviation:") . " </strong><span class='gray'>$rem_short</span></li>\n");
if ($alias_rows > 0) {
	echo ("    <li><strong>" . ngettext("Alternative abbreviation:", "Alternative abbreviations:", $alias_rows) . " </strong><span class='gray'>$aliase</span></li>\n");
}
$url_systemsat = "http://system-sat.de/" . str_replace(" ", "_", strtolower($rem_name)) . ".html";
if (!url_exists($url_systemsat)) {
	$url_systemsat = "http://system-sat.de/" . str_replace(" ", "_", strtolower($rem_name)) . ".htm";
}
if (url_exists($url_systemsat) && $lang == "de") {
	echo ("    <li><strong>" . _("More informations:") . " </strong><span class='gray'><b>$rem_name</b> " . _("at") . " <a href='$url_systemsat' target='_blank'>system-sat.de</a>.</span></li>\n");
}
if ($lang == "en") {
	$url_provings_main = "http://www.provings.info/en/substanz/";
} elseif ($lang == "de") {
	$url_provings_main = "http://www.provings.info/substanz/";
}
$url_provings = $url_provings_main . str_replace(".", "", strtolower($rem_short));
if(url_exists($url_provings) && !empty($url_provings_main)) {
	echo ("    <li><strong>" . _("Systematics and provings:") . " </strong><span class='gray'><b>$rem_name</b> " . _("at") . " <a href='$url_provings' target='_blank'>provings.info</a>.</span></li>\n");
}
echo ("    <li><strong>" . _("More links and information:") . " </strong><span class='gray'><b>$rem_name</b> " . _("at") . " <a href='http://openhomeo.org/openhomeopath/materia-medica.php?rem=$rem_short&lang=$lang' target='_blank'>OpenHomeo.org</a>.</span></li>\n");
echo ("  </ul>\n");

$query = "SELECT  $materia_tbl.rem_related, $materia_tbl.rem_incomp, $materia_tbl.rem_antidot, $materia_tbl.rem_note, $materia_tbl.rem_description, $materia_tbl.src_id, sources.src_title FROM $materia_tbl, sources WHERE $materia_tbl.rem_id = $rem_id AND $materia_tbl.src_id = sources.src_id AND ($materia_tbl.rem_related != '' || $materia_tbl.rem_incomp != '' || $materia_tbl.rem_antidot != '' || $materia_tbl.rem_note != '' || $materia_tbl.rem_description != '') ORDER BY sources.src_title";
$db->send_query($query);
$num_rows = $db->db_num_rows();
if ($num_rows > 0){
	while ($rem_info = $db->db_fetch_row()) {
		echo ("  <br><h3>" . _("Remedy description") . " <span class='source'>(" . _("Source:") . " <em><a href=\"javascript:popup_url('source.php?src=$rem_info[5]',600,400)\">$rem_info[6]</a></em>)</span></h3>\n");
		echo ("  <ul class='blue'>\n");
		if (!empty($rem_info[0])) {
			echo ("    <li><strong>" . _("related remedies:") . " </strong><span class='gray'>$rem_info[0]</span></li>\n");
		}
		if (!empty($rem_info[1])) {
			echo ("    <li><strong>" . _("incompatible remedies:") . " </strong><span class='gray'>$rem_info[1]</span></li>\n");
		}
		if (!empty($rem_info[2])) {
			echo ("    <li><strong>" . _("Antidotes:") . " </strong><span class='gray'>$rem_info[2]</span></li>\n");
		}
		if (!empty($rem_info[3])) {
			$rem_info[3] = str_replace("\r\n", "<br />", $rem_info[3]);
			$rem_info[3] = str_replace("\r", "<br />", $rem_info[3]);
			$rem_info[3] = str_replace("\n", "<br />", $rem_info[3]);
			echo ("    <li><strong>" . _("preparation / origin / synonyms:") . " </strong><span class='gray'>$rem_info[3]</span></li>\n");
		}
		if (!empty($rem_info[4])) {
			$rem_info[4] = str_replace("\r\n", "<br />", $rem_info[4]);
			$rem_info[4] = str_replace("\r", "<br />", $rem_info[4]);
			$rem_info[4] = str_replace("\n", "<br />", $rem_info[4]);
			echo ("    <li><strong>" . _("general description of the remedy:") . " </strong><span class='gray'>$rem_info[4]</span></li>\n");
		}
		echo ("  </ul>\n");
	}
}
$db->free_result();

$query = "SELECT $materia_tbl.rem_leadsym_general, $materia_tbl.rem_leadsym_mind, $materia_tbl.rem_leadsym_body, $materia_tbl.src_id, sources.src_title FROM $materia_tbl, sources WHERE $materia_tbl.rem_id = $rem_id AND $materia_tbl.src_id = sources.src_id AND ($materia_tbl.rem_leadsym_general != '' || $materia_tbl.rem_leadsym_mind != '' || $materia_tbl.rem_leadsym_body != '') ORDER BY sources.src_title";
$db->send_query($query);
$num_rows = $db->db_num_rows();
if ($num_rows > 0){
	while ($leadsymptoms = $db->db_fetch_row()) {
	
		echo ("  <br><h3>" . _("Leading symptoms") . " <span class='source'>(" . _("Source:") . " <em><a href=\"javascript:popup_url('source.php?src=$leadsymptoms[3]',600,400)\">$leadsymptoms[4]</a></em>)</span></h3>\n");
		if (!empty($leadsymptoms[0])) {
			echo ("  <h4>&nbsp;&nbsp;" . _("general") . "</h4>\n");
			echo ("  <ul class='blue'>\n");
			$leadsymptoms[0] = preg_replace('/\s*;\s*/u', ';', $leadsymptoms[0]);
			$leadsymptoms[0] = str_replace("\r\n", "<br />", $leadsymptoms[0]);
			$leadsymptoms[0] = str_replace("\r", "<br />", $leadsymptoms[0]);
			$leadsymptoms[0] = str_replace("\n", "<br />", $leadsymptoms[0]);
			$leadsymptoms[0] = preg_replace('/\s\s+/u', ' ', $leadsymptoms[0]); // entferne überzähligen whitespace
			$rem_leadsym_general = explode(";", $leadsymptoms[0]);
			foreach ($rem_leadsym_general as $value) {
				echo ("    <li><span class='gray'>$value</span></li>\n");
			}
			echo ("  </ul>\n");
		}
		if (!empty($leadsymptoms[1])) {
			echo ("  <h4>&nbsp;&nbsp;" . _("mind") . "</h4>\n");
			echo ("  <ul class='blue'>\n");
			$leadsymptoms[1] = preg_replace('/\s*;\s*/u', ';', $leadsymptoms[1]);
			$leadsymptoms[1] = str_replace("\r\n", "<br />", $leadsymptoms[1]);
			$leadsymptoms[1] = str_replace("\r", "<br />", $leadsymptoms[1]);
			$leadsymptoms[1] = str_replace("\n", "<br />", $leadsymptoms[1]);
			$leadsymptoms[1] = preg_replace('/\s\s+/u', ' ', $leadsymptoms[1]);
			$rem_leadsym_mind = explode(";", $leadsymptoms[1]);
			foreach ($rem_leadsym_mind as $value) {
				echo ("    <li><span class='gray'>$value</span></li>\n");
			}
			echo ("  </ul>\n");
		}
		if (!empty($leadsymptoms[2])) {
			echo ("  <h4>&nbsp;&nbsp;" . _("Body") . "</h4>\n");
			echo ("  <ul class='blue'>\n");
			$leadsymptoms[2] = preg_replace('/\s*;\s*/u', ';', $leadsymptoms[2]);
			$leadsymptoms[2] = str_replace("\r\n", "<br />", $leadsymptoms[2]);
			$leadsymptoms[2] = str_replace("\r", "<br />", $leadsymptoms[2]);
			$leadsymptoms[2] = str_replace("\n", "<br />", $leadsymptoms[2]);
			$leadsymptoms[2] = preg_replace('/\s\s+/u', ' ', $leadsymptoms[2]);
			$rem_leadsym_body = explode(";", $leadsymptoms[2]);
			foreach ($rem_leadsym_body as $value) {
				echo ("    <li><span class='gray'>$value</span></li>\n");
			}
			echo ("  </ul>\n");
		}
	}
}
$db->free_result();
?>
  <br>
  <h3><?php echo _("Corresponding symptoms"); ?></h3>
  <div id='reversed_rep'>
<?php
include("forms/reversed_rep.php");
?>
  </div>
