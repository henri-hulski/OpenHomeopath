<?php

/**
 * forms/auto_all_remedies.php
 *
 * This file provides a autosuggest form for searching all remedies.
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
 * @author    Thomas Bochmann <thomas.bochmann@gmail.com>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

if (!empty($_REQUEST["ajax"])) {
	chdir("..");
	include_once ("include/classes/login/session.php");
	$materia_tbl = $db->get_custom_table("materia");
	$sym_rem_tbl = $db->get_custom_table("sym_rem");
}
$from = "remedies";
$where = "rem_short";
$search = strip_tags($_GET["q"]);
$search_reg = str_replace('.', '\\\.', $search);
if(strlen($search) > 0) {
	$query = "SELECT remedies.rem_short, remedies.rem_name, remedies.rem_id FROM remedies  WHERE rem_short LIKE '$search%' OR rem_name REGEXP '[[:<:]]$search_reg' ORDER BY rem_short";
	$db->send_query($query);
	echo "<ul>";
	while(list($rem_short, $rem_name) = $db->db_fetch_row()) {
		echo ("      <li><a onclick=\"setRemShort('$rem_short');document.searchform.submit();\">$rem_short&nbsp;&nbsp;<small>$rem_name</small></a></li>\n");
	}
	$db->free_result();
	echo "</ul>";
}
