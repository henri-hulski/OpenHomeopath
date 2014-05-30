<?php

/**
 * frame.php
 *
 * The html frame with the navigation to include.
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
 * @category  Skin
 * @package   OriginalNavigation
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

include ("include/functions/layout.php");
if (TABBED) {
	$rep_url = "index.php?tab=0";
	$materia_url = "index.php?tab=2";
	$user_url = "index.php?tab=4";
} else {
	$rep_url = "repertori.php";
	$materia_url = "materia.php";
	$user_url = "userinfo.php?user=$session->username";
}
$lang = $session->lang;
?>
    <div id="container">
      <div id="ornateFrame">
        <img src="skins/<?php echo(SKIN_NAME);?>/img/hahnemann.jpg" width="90" height="120" alt="Samuel Hahnemann">
      </div>
      <div id="top">
        <a name="up" id="up" title="<?php echo _("Top of the page"); ?>"></a>
        <div id="banner">
          <img src="./skins/<?php echo(SKIN_NAME);?>/img/openhomeopath.gif" width="480" height="76" border="0" alt="OpenHomeopath">
        </div>
      </div>
      <div id="middle">
        <table summary="layout" cellpadding="0" cellspacing="0" id="middle_tbl">
          <tr>
            <td id="middle_cell01">
              <ul class="Navigation">
                <li>
                   &nbsp; 
                </li>
                <li>
                   &nbsp; 
                </li>
                <li>
                  <a href="http://openhomeo.org" style="color:#336633">OpenHomeo.org</a>
                </li>
                <li>
                  <a href="<?php echo($rep_url);?>"><?php echo _("Repertorization"); ?></a>
                </li>
                <li>
                  <a href="<?php echo($materia_url);?>"><?php echo _("Materia Medica"); ?></a>
                </li>
                <li>
                  <a href="datadmin.php"><?php echo _("Data maintenance"); ?></a>
                </li>
                <li>
                  <a href="support.php"><?php echo _("Support"); ?></a>
                </li>
                <li>
                  <a href="help/<?php echo $lang; ?>/index.php"><?php echo _("Help"); ?></a>
                </li>
                <li>
                  <a href="doc/<?php echo $lang; ?>/info.php"><?php echo _("Info"); ?></a>
                </li>
              </ul>
<?php
if($session->logged_in){
?>
              <br clear="all">
<div class='center'><p>
  <a href="javascript:popup_url('donations.php',960,720)"><img src='img/donate_<?php echo $lang; ?>.png' width='110' height='33' alt='<?php echo _("Donations"); ?>' title='<?php echo _("Every donation is very welcome and helps the development of OpenHomeopath."); ?>'></a>
</p></div>
              <ul class="user">
                <li>&nbsp;&nbsp;<img src="<?php echo(USER_ICON);?>" width="16" height="16" alt="<?php echo _("User"); ?>"><a href="<?php echo($user_url);?>"><?php echo _("My account"); ?></a></li>
                <li><a href="useredit.php"><?php echo _("Settings"); ?></a></li>
<?php
	if($session->isAdmin()){
		echo ("                <li><a href='useradmin.php'>" . _("Administration") . "</a></li>\n");
	}
	echo ("                <li><a href='include/classes/login/process.php'>" . _("Logout") . "</a></li>\n");
?>
<!-- Beginn Skin-Auswahl -->
                <li class = 'center'><br><label for="skin"><?php echo _("Skin:"); ?> </label>
<?php
	select_skin('drop-down4');
?>
                </li>
<!-- Ende Skin-Auswahl -->
              </ul>
<?php
	if($session->showActive()){
		echo ("              <br>&nbsp;<p class='user'>" . _("active users:") . "<br>\n");
		include ("include/classes/login/view_active.php");
		echo ("              </p>\n");
	}
} else {
?>
              <br clear="all">
<div class='center'><p>
  <a href="javascript:popup_url('donations.php',960,720)"><img src='img/donate_<?php echo $lang; ?>.png' width='110' height='33' alt='<?php echo _("Donations"); ?>' title='<?php echo _("Every donation is very welcome and helps the development of OpenHomeopath."); ?>'></a>
</p></div>
              <ul class="user">
                <li>&nbsp;&nbsp;<img src="<?php echo(USER_ICON);?>" width="16" height="16" alt="<?php echo _("User"); ?>"><a href="./login.php">&nbsp;&nbsp;<?php echo _("Log in"); ?></a></li>

<!-- Beginn Skin-Auswahl -->
                <li class = 'center'><br><label for="skin"><?php echo _("Skin:"); ?> </label>
<?php
	select_skin('drop-down4');
?>
                </li>
<!-- Ende Skin-Auswahl -->

              </ul>
              <br>
<?php
}
?>
            </td>
<?php
popup();
?>
            <td id="middle_cell02">
              <div id="pagecontent">
