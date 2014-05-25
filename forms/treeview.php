<?php

/**
 * forms/treeview.php
 *
 * This file returns a JSON-string to an AJAX request for building the symptoms tree-view.
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
 * @package   TreeView
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

chdir("..");
include_once ("include/classes/login/session.php");
if (empty($_REQUEST['id'])) {
	include ("include/classes/treeview_class.php");
	$rubric_id = $_REQUEST['rubric'];
	$tree = new TreeView($rubric_id);
	$pid = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : 0;
	$symptom_ar = $tree->get_treeview($pid);
	$return_ar['data'] = $symptom_ar;
	$return_ar['value'] = $pid;
	$return_ar['rubric'] = $rubric_id;
} else {
	$sym_id = $_REQUEST['id'];
	$return_ar['id'] = $sym_id;
	$return_ar['name'] = $db->get_symptomname($sym_id);
}
$json_string = json_encode($return_ar);
echo $json_string;
?>
