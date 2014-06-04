<?php

/**
 * index.php
 *
 * The main file of OpenHomeopath containing tabs for the most important parts of the program.
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
 * @package   OpenHomeopath
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

include_once ("include/classes/login/session.php");
$tabbed = true;
$skin = $session->skin;
include("./skins/$skin/header.php");
?>
<h1>
  OpenHomeopath
</h1>
<div class="tabber" id="tabber">
<?php
$class = "";
if (isset($_GET['tab']) && $_GET['tab'] == 0) {
	$class = " tabdefault";
}
?>
  <div class="tab<?php echo($class);?>" title="<?php echo _("Repertorization"); ?>" id="tab_0">
<?php
include("./repertori.php");
?>
  </div>
  <div class="tab tabinactive" title="<?php echo _("Repertorization result"); ?>" id="tab_1">
  </div>
<?php
$class = "";
if (isset($_GET['tab']) && $_GET['tab'] == 2) {
	$class = " tabdefault";
}
?>
  <div class="tab<?php echo($class);?>" title="<?php echo _("Materia Medica"); ?>" id="tab_2">
<?php
include("./materia.php");
?>
  </div>
<?php
$class = "";
if (isset($_GET['tab']) && $_GET['tab'] == 3) {
	$class = " tabdefault";
} else {
	$class = " tabinactive";
}
?>
  <div class="tab<?php echo($class);?>" title="<?php echo _("Symptom-Info"); ?>" id="tab_3">
<?php
if (isset($_GET['tab']) && $_GET['tab'] == 3 && isset($_REQUEST['symptom'])) {
	include("symptominfo.php");
}
?>
  </div>
<?php
if ($session->logged_in) {
	$class = "";
	if (isset($_GET['tab']) && $_GET['tab'] == 4) {
		$class = " tabdefault";
	}
?>
  <div class="tab<?php echo($class);?>" title="<?php echo _("My account"); ?>" id="tab_4">
<?php
	$req_user = $session->username;
	include("userinfo.php");
?>
  </div>
<?php
}
?>
</div>
<script type="text/javascript">
  var tabberArgs = {};
  tabberArgs.div = document.getElementById("tabber");
  document.getElementById("tabber").tabber = new tabberObj(tabberArgs);
</script>
<?php
popup(1);
include("./skins/$skin/footer.php");
?>
