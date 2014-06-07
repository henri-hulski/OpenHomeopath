<?php

/**
 * view_active.php
 *
 * Print the active users as html link
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
 * @category  Login
 * @package   PackageName
 * @author    Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 * @see       login.php
 */

if(!defined('TBL_ACTIVE_USERS')) {
	die(_("Cannot access the user-table!"));
}
$query = "SELECT username FROM ". TBL_ACTIVE_USERS ." ORDER BY timestamp DESC,username";
$db->send_query($query);
/* Error occurred, return given name by default */
$num_rows = $db->db_num_rows();
if($num_rows > 0){
	/* Display active users, with link to their info */
	while($uname = $db->db_fetch_row()) {
		$uname_ar[] = "<a href='userinfo.php?user=$uname[0]'>&nbsp;&nbsp;$uname[0]&nbsp;&nbsp;</a>";
	}
	$db->free_result();
	echo (implode("<br>", $uname_ar));
	echo "<br>\n";
}
