<?php

/**
 * forms/auto_rem.php
 *
 * This file provides a autosuggest form for searching remedies.
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
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

if (!empty($_REQUEST["ajax"])) {
	chdir("..");
	include_once ("include/classes/login/session.php");
	$materia_tbl = $db->get_custom_table("materia");
	$sym_rem_tbl = $db->get_custom_table("sym_rem");
}
$search = strip_tags($_GET["q"]);
$search_reg = str_replace('.', '\\\.', $search);
if(strlen($search) > 0) {
	$query = "SELECT DISTINCT r.rem_short, r.rem_name, r.rem_id FROM remedies r, rem_alias WHERE (EXISTS (SELECT 1 FROM $materia_tbl WHERE $materia_tbl.rem_id = r.rem_id) OR EXISTS (SELECT 1 FROM $sym_rem_tbl WHERE $sym_rem_tbl.rem_id = r.rem_id)) AND (r.rem_short LIKE '$search%' OR r.rem_name REGEXP '[[:<:]]$search_reg' OR (r.rem_id = rem_alias.rem_id AND rem_alias.alias_short LIKE '$search%')) ORDER BY r.rem_short";  // [[:<:]] matches the beginning of words
	$db->send_query($query);
	echo "<ul>";
	while(list($rem_short, $rem_name, $rem_id) = $db->db_fetch_row()) {
		echo ("      <li><a onclick=\"setRem('$rem_id', this)\">$rem_short&nbsp;&nbsp;<small>$rem_name</small></a></li>\n");
	}
	$db->free_result();
	echo "</ul>";
}
?>
