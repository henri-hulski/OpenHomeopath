<?php

/**
 * source.php
 *
 * This file shows information about a source.
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
 * @package   Source
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

include_once ("include/classes/login/session.php");
if (empty($_GET['popup'])) {
	$head_title = _("Source-Info") . " :: OpenHomeopath";
	$skin = $session->skin;
	include("./skins/$skin/header.php");
}

if ($src_id = $_GET['src']){
	$lang = $session->lang;
	$query = "SELECT sources.src_title, languages.lang_$lang, sources.src_author, sources.src_copyright, sources.src_year, sources.src_edition_version, sources.src_license, sources.src_url, sources.src_isbn, sources.src_note, sources.src_contact, sources.src_type, sources.src_proving FROM sources, languages WHERE sources.src_id = '$src_id' AND languages.lang_id = sources.lang_id";
	$db->send_query($query);
	$src_info = $db->db_fetch_row();
	$db->free_result();
	$query = "SELECT src_translations.src_translated, languages.lang_$lang FROM src_translations, sources, languages WHERE src_translations.src_translated = sources.src_id AND languages.lang_id = sources.lang_id AND src_translations.src_native = '$src_id'";
	$db->send_query($query);
	$i = 0;
	while (list($src_translationss_ar[$i]['id'], $src_translationss_ar[$i]['lang']) = $db->db_fetch_row()) {
		$i++;
	}
	$db->free_result();
	echo ("    <h1>" . _("Source-Info") . "</h1>\n");
	echo ("    <ul class='blue'>\n");
	echo ("      <li><strong>" . _("Title:") . " </strong><span class='gray'><strong>$src_info[0]</strong></span></li>\n");
	echo ("      <li><strong>" . _("Contraction:") . " <span class='gray'>$src_id</span></strong></li>\n");
	echo ("      <li><strong>" . _("Language:") . " </strong><span class='gray'>$src_info[1]</span></li>\n");
	if (!empty($src_translationss_ar[0]['id'])) {
		echo ("      <li><strong>" . _("Translations:") . " </strong>\n");
		echo ("        <ul class='blue'>\n");
		foreach ($src_translationss_ar as $src_translations) {
			if (!empty($src_translations['id'])) {
				echo ("          <li><strong>$src_translations[lang]: </strong><span class='gray'><a href=\"javascript:popup_url('source.php?src=$src_translations[id]',600,400)\">$src_translations[id]</a></span></li>\n");
			}
		}
		echo ("        </ul>\n");
		echo ("      </li>\n");
	}
	echo ("      <li><strong>" . _("Type of source:") . " </strong><span class='gray'>$src_info[11]</span></li>\n");
	if (!empty($src_info[2]) && $src_info[2] != "-") {
		echo ("      <li><strong>" . _("Autor:") . " </strong><span class='gray'>$src_info[2]</span></li>\n");
	}
	if (!empty($src_info[3]) && $src_info[3] != "-") {
		echo ("      <li><strong>" . _("Copyright:") . " </strong><span class='gray'>&copy; ");
		if (!empty($src_info[4]) && $src_info[4] != "-") {
			echo ("$src_info[4] ");
		}
		echo ("by $src_info[3]</span></li>\n");
	} elseif (!empty($src_info[4]) && $src_info[4] != "-") {
		echo ("      <li><strong>" . _("Year:") . " </strong><span class='gray'>$src_info[4]</span></li>\n");
	}
	if (!empty($src_info[5]) && $src_info[5] != "-") {
		echo ("      <li><strong>" . _("Edition / Version:") . " </strong><span class='gray'>$src_info[5]</span></li>\n");
	}
	if (!empty($src_info[6]) && $src_info[6] != "-") {
		$src_info[6] = str_replace("\r\n", "<br />", $src_info[6]);
		$src_info[6] = str_replace("\r", "<br />", $src_info[6]);
		$src_info[6] = str_replace("\n", "<br />", $src_info[6]);
		echo ("      <li><strong>" . _("License:") . " </strong><span class='gray'>$src_info[6]</span></li>\n");
	}
	if (!empty($src_info[7]) && $src_info[7] != "-") {
		echo ("      <li><strong>" . _("Web page:") . " </strong><span class='gray'><a href='$src_info[7]' target='_blank'>$src_info[7]</a></span></li>\n");
	}
	if (!empty($src_info[8]) && $src_info[8] != "-") {
		echo ("      <li><strong>" . _("ISBN:") . " </strong><span class='gray'>$src_info[8]</span></li>\n");
	}
	if (!empty($src_info[12]) && $src_info[12] != "-") {
		$src_info[12] = str_replace("\r\n", "<br />", $src_info[12]);
		$src_info[12] = str_replace("\r", "<br />", $src_info[12]);
		$src_info[12] = str_replace("\n", "<br />", $src_info[12]);
		echo ("      <li><strong>" . _("Proving:") . " </strong><span class='gray'>$src_info[12]</span></li>\n");
	}
	if (!empty($src_info[9]) && $src_info[9] != "-") {
		$src_info[9] = str_replace("\r\n", "<br />", $src_info[9]);
		$src_info[9] = str_replace("\r", "<br />", $src_info[9]);
		$src_info[9] = str_replace("\n", "<br />", $src_info[9]);
		echo ("      <li><strong>" . _("Note:") . " </strong><span class='gray'>$src_info[9]</span></li>\n");
	}
	if (!empty($src_info[10]) && $src_info[10] != "-") {
		$src_info[10] = str_replace("\r\n", " <br> ", $src_info[10]);
		$src_info[10] = str_replace("\r", " <br> ", $src_info[10]);
		$src_info[10] = str_replace("\n", " <br> ", $src_info[10]);
		$src_info[10] = preg_replace("/\s+/u", " ", $src_info[10]);
		if (strpos($src_info[10], "@")) {
			$src_info[10] = str_replace(",", " , ", $src_info[10]);
			$src_info[10] = str_replace(";", " ; ", $src_info[10]);
			$src_info[10] = explode(" ", $src_info[10]); // wandele string in array um
			foreach ($src_info[10] as $key => $value) {
				if (strpos($value, "@")) {
					$src_info[10][$key] = "<a href='mailto:$value'>$value</a>";
				}
			}
			$src_info[10] = implode(" ", $src_info[10]);
			$src_info[10] = str_replace(" , ", ",", $src_info[10]);
			$src_info[10] = str_replace(" ; ", ";", $src_info[10]);
		}
		$src_info[10] = str_replace(" <br> ", "<br />", $src_info[10]);
		echo ("      <li><strong>" . _("Contact:") . " </strong><span class='gray'>$src_info[10]</span></li>\n");
	}
	echo ("    </ul>\n");
}
if (empty($_GET['popup'])) {
	include("./skins/$skin/footer.php");
}
?>
