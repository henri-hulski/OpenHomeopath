<?php

/**
 * functions/layout.php
 *
 * Some layout functions.
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
 * @category  Layout
 * @package   Layout
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

function select_skin($class = "") {
	global $session, $db;
	$current_skin = "";
	if (!empty($session->skin)) {
		$current_skin = $session->skin;
	} elseif (isset($_SESSION['skin'])) {
		$current_skin = $_SESSION['skin'];
	} elseif ($session && $session->logged_in) {
		$user_skin  = $db->getUserInfo($session->username, 'skin_name');
		if (!empty($user_skin[0])) {
			$current_skin = $user_skin[0];
		}
	}
	if (empty($current_skin)) {
		$current_skin = DEFAULT_SKIN;
	}
	$select = "<select ";
	if (!empty($class)) {
		$select .= "class='$class' ";
	}
	$select .= "name='skin' id='skin' size='1' onchange='changeSkin(this.value)'>\n";
	echo ($select);
	echo ("  <option selected='selected' value='$current_skin'>$current_skin</option>\n");
	$query = "SELECT skin_name FROM skins ORDER BY skin_id";
	$db->send_query($query);
	while($skin_name = $db->db_fetch_row()) {
		if ($skin_name[0] != $current_skin) {
			echo ("  <option value='$skin_name[0]'>$skin_name[0]</option>\n");
		}
	}
	$db->free_result();
	echo ("</select>\n");
}

function add_donate_button($button_nr = 1) {
	global $session;
	$lang = 'de';
	if (isset($session->lang)) {
		$lang = $session->lang;
	}
	switch ($button_nr) {
		case 1:
			$value = '5VCBZJRKXM9EC';
			$button = "spenden_$lang.gif";
			break;
		case 2:
			$value = '936L8TD998HF2';
			$button = "spenden_big_$lang.gif";
			break;
		case 3:
			$value = 'VJ9J7Z8MY7PBN';
			$button = "spenden_big_$lang.gif";
			break;
		case 4:
			$value = '5VCBZJRKXM9EC';
			$button = "spenden_big_$lang.gif";
			break;
	}
	echo ("<form action='https://www.paypal.com/cgi-bin/webscr' method='post' target='_blank'>\n");
	echo ("  <input type='hidden' name='cmd' value='_s-xclick'>\n");
	echo ("  <input type='hidden' name='hosted_button_id' value='$value'>\n");
	echo ("  <input type='image' onclick=\"_paq.push(['trackGoal', 6])\" src='img/$button' name='submit' alt='" . _("Donations") . "' title='" . _("Every donation is very welcome and helps the development of OpenHomeopath.") . "'>\n");
	echo ("  <img alt='' src='https://www.paypal.com/de_DE/i/scr/pixel.gif' width='1' height='1'>\n");
	echo ("</form>\n");
}

function begin_popup($history = 0) {
	echo ("<div id='popup' style='position: fixed; display:none; z-index:13;'>\n");
	echo ("  <div class='dragme'>\n");
	echo ("    <div id='popup-icon' style='position: absolute; top: 0; left: 0; width: 30px; height: 25px;'><img height='25' width='30' src='img/popup-icon.gif'></div>\n");
	echo ("    <div id='popup-title' style='position: absolute; top: 0; left: 30px; height: 25px; background: url(./img/popup-title-bg.gif) repeat-x; text-align: center;'><img height='25' width='140' src='img/popup-title.gif' alt='Popup title'></div>\n");
	echo ("  </div>\n");
	echo ("  <div id='popup-close' style='position: absolute; top: 0; width: 30px; height: 25px;'><a style='padding: 0;' href='javascript:popupClose();'><img height='25' width='30' src='img/popup-close.gif' alt='Popup close'></a></div>\n");
	echo ("  <div id='popup-lu' style='position: absolute; left: 0; width: 5px; height: 6px; background-color: transparent;'><img height='6' width='5' src='img/popup-lu.gif' alt='Popup frame'></div>\n");
	echo ("  <div id='popup-u' class='popup-background' style='position: absolute; left: 5px; height: 6px; background-image: url(./img/popup-u.gif); background-repeat: repeat-x;'></div>\n");
	echo ("  <div class='resize' id='popup-ru' style='position: absolute; width: 16px; height: 16px; background-color:transparent; z-index:12;'><img height='16' width='16' src='img/popup-resize.gif' alt='Popup resize'></div>\n");
	echo ("  <div id='popup-l' style='position: absolute; top: 25px; left: 0; width: 2px; background: url(./img/popup-l.gif) repeat-y;'></div>\n");
	echo ("  <div id='popup-r' style='position: absolute; top: 25px; width: 2px; background: url(./img/popup-r.gif) repeat-y;'></div>\n");
	echo ("  <div id='popup-m' class='popup-background' style='position: absolute; top: 25px; left: 2px; overflow:auto;'>\n");
	if ($history != 0) {
		echo ("      <div style='float: right; margin: 25px'>\n");
		echo ("        <a id='history_back' style='padding: 7px;'><img id='arrow_left' height='24' width='38' src='img/arrow_left_inactive.gif' alt='History back'></a><a id='history_forward' style='padding: 7px;'><img id='arrow_right' height='24' width='38' src='img/arrow_right_inactive.gif' alt='History forward'></a>\n");
		echo ("      </div>\n");
	}
	echo ("    <div id='popup-body'>\n");
}

function end_popup() {
	echo ("    </div>\n");
	echo ("  </div>\n");
	echo ("</div>\n");
}

/* Anwendung:
<input type='button' onClick='popupOpen(0,0)' value=' Hilfe '>
*/
function popup($history = 0) {
	begin_popup($history);
	end_popup();
}
