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
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

header("Expires: Mon, 1 Dec 2006 01:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-Type: text/html;charset=utf-8"); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"
>
<html>
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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="author" content="Henri Schumacher">
<?php
if(!empty($meta_content_language)) {
	echo "      <meta http-equiv='Content-Language' content='$meta_content_language'>\n";
} else {
	echo "      <meta http-equiv='Content-Language' content='de,en'>\n";
}
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
    <link rel="stylesheet" type="text/css" href="skins/<?php echo(SKIN_NAME);?>/css/main.css">
    <link rel="stylesheet" type="text/css" href="skins/<?php echo(SKIN_NAME);?>/css/dropdownmenu.css">
    <link rel="stylesheet" type="text/css" media="screen" href="skins/<?php echo(SKIN_NAME);?>/css/openhomeopath.css">
    <link rel="stylesheet" type="text/css" media="print" href="skins/<?php echo(SKIN_NAME);?>/css/print.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<?php
if ($tabbed) {
?>
    <link rel="stylesheet" type="text/css" media="screen" href="skins/<?php echo(SKIN_NAME);?>/css/tabber.css">
    <script src='./javascript/tabber.js' type='text/javascript'></script>
<?php
}
include("./javascript/locale.php");
?>
    <script src="./javascript/openhomeopath.js" type="text/javascript"></script>
<?php
if (isset($current_page) && $current_page == "express") {
?>
    <link rel="stylesheet" href="css/styles_screen.css" type ="text/css" media="screen">
    <link rel="stylesheet" href="css/styles_print.css" type ="text/css" media="print">
<?php
}
if ($tabbed || (isset($current_page) && $current_page == "rep_result")) {
?>
    <script type='text/javascript'>
      var sideFrameWidth = <?php echo(SIDE_FRAME_WIDTH);?>;
      window.onload = resizeResultTable;
      window.onresize = resizeResultTable;
    </script>
<?php
}
?>
    <!-- thb -->
    <script src="../scriptaculous-js-1.8.2/lib/prototype.js" type="text/javascript"></script>
    <script src="../scriptaculous-js-1.8.2/src/scriptaculous.js" type="text/javascript"></script>
    <script src="../scriptaculous-js-1.8.2/menu.js" type="text/javascript"></script>
  </head>
  <body id="default">
    <div id="onwork"><span class='onwork'><?php echo _("I'm on work ...."); ?></span></div>
<?php
include("./skins/$skin/frame.php")
?>
