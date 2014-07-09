<?php

/**
 * frame.php
 *
 * The html header to include.
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
 * @package   KraqueHeader
 * @author    Thomas Bochmann <thomas.bochmann@gmail.com>
 * @copyright 2009 Thomas Bochmann
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

if (empty($lang)) {
	$lang = $session->lang;
}

header("Expires: Mon, 1 Dec 2006 01:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-Type: text/html;charset=utf-8"); 
?>
<!DOCTYPE html>
<html lang="<?php echo($lang); ?>">
  <head>
    <title>
<?php
if(!empty($head_title)) {
	echo "      $head_title\n";
} else {
	echo "      OpenHomeopath\n";
}
?>
    </title>
    <meta charset="utf-8">
    <meta name="author" content="Henri Schumacher">
<?php
if(!empty($meta_description)) {
	echo "      <meta name='description' content='$meta_description, Online Repertorium, Materia medica, Bibliothek, Hom&ouml;opathische Wissenssammlung'>\n";
} else {
	echo "      <meta name='description' content='Online Repertorium, Materia medica, Bibliothek, Hom&ouml;opathische Wissenssammlung'>\n";
}
if(!empty($meta_keywords)) {
	echo "      <meta name='keywords' content='$meta_keywords, Heilmittel, Remedy, Hom&ouml;opathie, Homeopathy, Homoeopathie, Homoeopathy, Repertorium, Online Repertorium,Repertory, Online Repertory, Materia Medica, Kent, Arzneimittellehre, Samuel Hahnemann, B&uuml;cher, Books, Book, Buch, Hom&ouml;opathische Online Bibliothek, Bibliothek, Library'>\n";
} else {
	echo "      <meta name='keywords' content='Hom&ouml;opathie, Homeopathy, Homoeopathie, Homoeopathy, Repertorium, Online Repertorium,Repertory, Online Repertory, Materia Medica, Kent, Arzneimittellehre, Samuel Hahnemann, B&uuml;cher, Books, Book, Buch, Hom&ouml;opathische Online Bibliothek, Bibliothek, Library'>\n";
}
?>
    <meta name="robots" content="all">
    <meta name="robots" content="index,follow">
    <link rel="stylesheet" href="skins/<?php echo(SKIN_NAME);?>/css/main.css">
    <link rel="stylesheet" href="skins/<?php echo(SKIN_NAME);?>/css/dropdownmenu.css">
    <link rel="stylesheet" media="screen" href="skins/<?php echo(SKIN_NAME);?>/css/openhomeopath.css">
    <link rel="stylesheet" media="print" href="skins/<?php echo(SKIN_NAME);?>/css/print.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<?php
if ($tabbed) {
?>
    <link rel="stylesheet" media="screen" href="skins/<?php echo(SKIN_NAME);?>/css/tabber.css">
	<!--[if lt IE 9]>
	  <script src="javascript/html5shiv.min.js"></script>
	<![endif]-->
    <script src='javascript/tabber.js'></script>
<?php
}
include("javascript/locale.php");
?>
    <script src="javascript/openhomeopath.js"></script>
<?php
if (isset($current_page) && $current_page == "express") {
?>
    <link rel="stylesheet" href="css/styles_screen.css" media="screen">
    <link rel="stylesheet" href="css/styles_print.css" media="print">
<?php
}
if ($tabbed || (isset($current_page) && $current_page == "rep_result")) {
?>
    <script>
      var sideFrameWidth = <?php echo(SIDE_FRAME_WIDTH);?>;
      window.onload = resizeResultTable;
      window.onresize = resizeResultTable;
    </script>
<?php
}
?>
    <!-- thb -->
    <script src="../scriptaculous-js-1.8.2/lib/prototype.js"></script>
    <script src="../scriptaculous-js-1.8.2/src/scriptaculous.js"></script>
    <script src="../scriptaculous-js-1.8.2/menu.js"></script>
  </head>
  <body>
    <div id="onwork"><span class='onwork'><?php echo _("I'm on work ...."); ?></span></div>
<?php
include("skins/$skin/frame.php")
?>
