<?php
/*
***********************************************************************************
DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) http://www.dadabik.org/
Copyright (C) 2001-2007  Eugenio Tacchini

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

If you want to contact me by e-mail, this is my address: eugenio.tacchini@unicatt.it
***********************************************************************************
*/
header("Expires: Mon, 1 Dec 2006 01:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-Type: text/html;charset=utf-8"); 
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo _("Data Maintenance Administration") . " :: OpenHomeopath"; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="skins/<?php echo(SKIN_NAME);?>/css/openhomeopath.css">
<link rel="stylesheet" type="text/css" media="print" href="skins/<?php echo(SKIN_NAME);?>/css/print.css">
<link rel="stylesheet" href="css/styles_screen.css" type ="text/css" media="screen">
<link rel="stylesheet" href="css/styles_print.css" type ="text/css" media="print">
</head>

<body 
<?php
if (isset($_GET["type_mailing"])){
	if ($_GET["type_mailing"] == "labels") {
		echo " leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' onload=\"javascript:alert('".$normal_messages_ar["print_warning"]."')\"";
	} // end if
} // end if
?>
>
<table class="main_table" cellpadding="10">
<tr>
<td valign="top">
<a href="<?php echo $dadabik_main_file; ?>">Exit the administration area</a>
<h1><?php echo "OpenHomeopath - " . _("Data Maintenance Administration"); ?></h1>