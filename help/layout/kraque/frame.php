<?php

/**
 * frame.php
 *
 * The html frame with navigation to include in help and doc files.
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
 * @package   KraqueNavigation
 * @author    Thomas Bochmann <thomas.bochmann@gmail.com>
 * @copyright 2009 Thomas Bochmann
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

include ("include/functions/layout.php");
if (TABBED) {
	$rep_url = "../../index.php?tab=0";
	$materia_url = "../../index.php?tab=2";
	$user_url = "../../index.php?tab=4";
} else {
	$rep_url = "../../repertori.php";
	$materia_url = "../../materia.php";
	$user_url = "../../userinfo.php?user={$session->username}";
}
$lang = $session->lang;
?>
<a name="up" id="up" title="<?php echo _("Top of the page"); ?>"></a>
<?php
if($session->logged_in){
?>
<div id="menu">
		<span style="float: left;">
            <a style="color: #336633; font-size: 14px;" href="../../../index.php">
                <img height="17" border="0" alt="OpenHomeo.org" src="../../skins/<?php echo(SKIN_NAME);?>/img/punkte_d9b7ea.gif"/>
            </a>
        </span>
		<ul class="level1" id="root">
		<li>
		    <a href="../../../index.php" style="color: #336633; font-size: 13px;"><b>OpenHomeo.org</b></a>
		  </li>
		  <li class="sep">|</li>
		  <li>
		    <a href="<?php echo($rep_url);?>"><?php echo _("Repertorize"); ?></a>
		    <ul class="level2">
		      <li><a href="<?php echo($rep_url);?>"><?php echo _("New"); ?></a>
		      </li>
		      <?php
	           echo ("<li><a href='$user_url#reps'>" . _("Open") . "</a></li>");
		      echo ("<li><a href='$user_url#rep_custom'>" . _("Select sources") . "</a></li>");
		      ?>
		      </ul>
		  </li>
		  <li class="sep">|</li>
		  <li><a href="<?php echo($materia_url);?>"><?php echo _("Materia Medica"); ?></a>
		  <ul class="level2">
		  <?php
		      echo ("<li><a href='../../materia-medica.php'>" . _("Remedy") . ": A-Z</a></li>");
	           echo ("<li><a href='$user_url#materia_custom'>" . _("Select sources") . "</a></li>");
		  ?>
		  </ul>
		  </li>
		  <li class="sep">|</li>
		  <li>
		    <a href="../../datadmin.php"><?php echo _("Data maintenance"); ?></a>
		    <ul class="level2">
		    <li><a href="../../express.php"><?php echo _("Expresstool"); ?></a></li>
		      <li><a href="../../datadmin.php?table_name=remedies"><?php echo _("Remedy"); ?></a></li>
		      <li><a href="../../datadmin.php?table_name=rem_alias"><?php echo _("Remedy aliases"); ?></a></li>
		      <li><a href="../../datadmin.php?table_name=sources"><?php echo _("Sources"); ?></a></li>
		      <li><a href="../../datadmin.php?table_name=symptoms"><?php echo _("Symptoms"); ?></a></li>
		    </ul>
		  </li>
		  <li class="sep">|</li>
		  <li><a href="../../support.php"><?php echo _("Support"); ?></a></li>
		  <li class="sep">|</li>
		  <li><a href="../../homeophorum.php"><?php echo _("Forum"); ?></a></li>
		  <li class="sep">|</li>
		  <li>
		    <a href="../../help/<?php echo $lang; ?>/index.php"><?php echo _("Help"); ?></a>
		    <ul class="level2">
		    <li><a href="../../help/<?php echo $lang; ?>/manual.php#repertorization"><?php echo _("Repertorization"); ?></a></li>
		      <li><a href="../../help/<?php echo $lang; ?>/user.php"><?php echo _("User administration"); ?></a></li>
		      <li><a href="../../help/<?php echo $lang; ?>/datadmin.php"><?php echo _("Data maintenance"); ?></a></li>
		    </ul>
		  </li>
		  <li class="sep">|</li>
		  <li><a href="../../doc/<?php echo $lang; ?>/info.php"><?php echo _("Info"); ?></a></li>
		  <li class="sep">|</li>
		  <?php
	           echo ("<li><a href='$user_url'><img src='../../" . USER_ICON . "' width='13' height='12' border='0' alt='Benutzer'>" . _("My account") . "</a>");
	           echo ("<ul class='level2'>");
	               echo ("<li><a href='$user_url#rep_custom'>" . _("Settings") . "</a></li>");
	               echo ("<li><a href='$user_url#reps'>" . _("Repertorizations") . "</a></li>");
		      ?>
		  <li><a href="../../useredit.php"><?php echo _("Personal particulars"); ?></a></li>
		    </ul>
		  </li>
		  <li class="sep">|</li>
		  <li>
		    <a href="../../include/classes/login/process.php"><?php echo _("Logout"); ?></a>
		  </li>
<!-- Beginn Skin-Auswahl HS -->
		  <li class="sep">|</li>
		  <li style="padding: 0.1em 0.3em 0.1em 0.3em;"><label for="skin"><?php echo _("Skin:"); ?></label>
<?php
	select_skin('drop-down4');
?>
		  </li>
<!-- Ende Skin-Auswahl -->
		  <li class="sep">|</li>
		  <li style="padding-left: 0.3em;">
		    <div>
		      <a href="../../donations.php"><img src='../../img/donate_<?php echo $lang; ?>_mini.png'  width='80' height='24' alt='<?php echo _("Donations"); ?>' title='<?php echo _("Every donation is very welcome and helps the development of OpenHomeopath."); ?>'></a>
		    </div>
		  </li>
		  </li>
		  <li class='sep'>|</li>
		  <li>
		    <a href="../../donations.php"><strong><?php echo _("Received donations"); ?></strong></a>
		    <ul class='level2 donations'>
<?php
	echo $magic_hat->print_received_donations();
?>
		    </ul>
		  </li>
		</ul>
		</div>
<?php
}else{
?>
<div id="menu">
		<span style="float: left;">
            <a href="../../../index.php">
                <img height="17" border="0" alt="OpenHomeo.org" src="../../skins/<?php echo(SKIN_NAME);?>/img/punkte_d9b7ea.gif"/>
            </a>
        </span>
		<ul class="level1" id="root">
		<li>
		    <a href="../../../index.php" style="color: #336633; font-size: 13px;"><b>OpenHomeo.org</b></a>
		  </li>
		  <li class="sep">|</li>
		  <li>
		    <a href="<?php echo($rep_url);?>"><?php echo _("Repertorize"); ?></a>
		    <ul class="level2">
		      <li><a href="<?php echo($rep_url);?>"><?php echo _("New"); ?></a></li>
		  </ul>
		  </li>
		  <li class="sep">|</li>
		  <li><a href="<?php echo($materia_url);?>"><?php echo _("Materia Medica"); ?></a>
		  <ul class="level2">
		  <?php
		      echo ("<li><a href='../../materia-medica.php'>" . _("Remedy") . ": A-Z</a></li>");
		  ?>
		  </ul>
		  </li>
		  <li class="sep">|</li>
		  <li>
		    <a href="../../datadmin.php"><?php echo _("Data maintenance"); ?></a>
		    <ul class="level2">
		    <li><a href="../../express.php"><?php echo _("Expresstool"); ?></a></li>
		      <li><a href="../../datadmin.php?table_name=remedies"><?php echo _("Remedy"); ?></a></li>
		      <li><a href="../../datadmin.php?table_name=rem_alias"><?php echo _("Remedy aliases"); ?></a></li>
		      <li><a href="../../datadmin.php?table_name=sources"><?php echo _("Sources"); ?></a></li>
		      <li><a href="../../datadmin.php?table_name=symptoms"><?php echo _("Symptoms"); ?></a></li>
		    </ul>
		  </li>
		  <li class="sep">|</li>
		  <li><a href="../../support.php"><?php echo _("Support"); ?></a></li>
		  <li class="sep">|</li>
		  <li><a href="../../homeophorum.php"><?php echo _("Forum"); ?></a></li>
		  <li class="sep">|</li>
		  <li>
		    <a href="../../help/<?php echo $lang; ?>/index.php"><?php echo _("Help"); ?></a>
		    <ul class="level2">
		    <li><a href="../../help/<?php echo $lang; ?>/manual.php#repertorization"><?php echo _("Repertorization"); ?></a></li>
		      <li><a href="../../help/<?php echo $lang; ?>/user.php"><?php echo _("User administration"); ?></a></li>
		      <li><a href="../../help/<?php echo $lang; ?>/datadmin.php"><?php echo _("Data maintenance"); ?></a></li>
		    </ul>
		  </li>
		  <li class="sep">|</li>
		  <li><a href="../../info.php"><?php echo _("Info"); ?></a></li>
		  <li class="sep">|</li>
		  <li><a href="../../login.php"><img width="13" height="12" border="0" alt="Benutzer" src="../../<?php echo(USER_ICON);?>" border="0" /> <?php echo _("Log in"); ?></a>
		  </li>
<!-- Beginn Skin-Auswahl HS -->
		  <li class="sep">|</li>
		  <li style="padding: 0.1em 0.3em 0.1em 0.3em;"><label for="skin"><?php echo _("Skin:"); ?></label>
<?php
	select_skin('drop-down4');
?>
		  </li>
<!-- Ende Skin-Auswahl -->
		  <li class="sep">|</li>
		  <li style="padding-left: 0.3em;">
		    <div>
		      <a href="../../donations.php"><img src='../../img/donate_<?php echo $lang; ?>_mini.png'  width='80' height='24' alt='<?php echo _("Donations"); ?>' title='<?php echo _("Every donation is very welcome and helps the development of OpenHomeopath."); ?>'></a>
		    </div>
		  </li>
		  <li class='sep'>|</li>
		  <li>
		    <a href="../../donations.php"><strong><?php echo _("Received donations"); ?></strong></a>
		    <ul class='level2 donations'>
<?php
	echo $magic_hat->print_received_donations();
?>
		    </ul>
		  </li>
		</ul>
		</div>
<?php
}
?>

    <div class="cleara"></div>
    <!-- End Navigation -->
    <div id="container">
     <!-- thb -->
      <div id="middle">
        <table summary="layout" cellpadding="0" cellspacing="0" id="middle_tbl">
          <tr>
            <td id="middle_cell02">
              <div id="pagecontent">
