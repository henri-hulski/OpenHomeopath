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
?>
<?php
chdir("..");
include ("include/datadmin/config.php");
include_once ("include/classes/login/session.php");
include ("include/datadmin/functions.php");
include ("include/datadmin/common_start.php");
$url = "admin%internal_table_manager.php";
include ("include/datadmin/check_admin_login.php");
include ("include/datadmin/header_admin.php");
?>

<?php
// variables:
// GET
// $table_name from admin.php and internal_table_manager.php

if (!isset($_GET["table_name"])){
	exit;
} // end if
else{
	$table_name = urldecode($_GET["table_name"]);
} // end else

$table_internal_name = $prefix_internal_table.$table_name;

 // POST
// $save (1 if the user submitted the form) from this file
// $show_all_fields (1 if the user want to show all the fields) from this file
// $field_position (the position in the internal table of the field the user want to show) from this file


if (!isset($_POST["show_all_fields"])){
	$show_all_fields = "";
} // end if
else{
	$show_all_fields = $_POST["show_all_fields"];
} // end else

// the position of the field the user wants to manage
if (!isset($_POST["field_position"])){
	$field_position = "";
} // end if
else{
	$field_position = $_POST["field_position"];
} // end else

// I need this the first time I load the page, $save is unset
if (isset($_POST["save"])){
	$save = $_POST["save"];
} // end if
else{
	$save = "0";
} // end if

/*
reset ($_POST);
while (list($key, $value) = each ($_POST)){
	$$key = $value;
} // end while
*/
// include internal table fields definition
include ("include/datadmin/internal_table.php");

// get the array containg label ant other information about the fields
$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");

if ($field_position == "" and $show_all_fields != "1"){
	$field_position = 0; // set the $field_name to the first field
} // end if

if ($save == "1"){
	// save the configuration of the internal table
	for ($i=0; $i<count($fields_labels_ar); $i++){
		if (isset($_POST[$int_fields_ar[1][1]."_".$i])){ // if isset the variable (it means that this field was in the form){

			$sql = "";
			$sql .= "UPDATE `$table_internal_name` SET ";

			for ($j=1; $j<count($int_fields_ar); $j++){ // from 1 because the first is the name of the field ".${$int_fields_ar[$j][1]."_".$i};
				$sql .= "`".$int_fields_ar[$j][1]."` = '".$_POST[$int_fields_ar[$j][1]."_".$i]."', ";
			} // end for
			$sql = substr($sql, 0, strlen($sql)-2);

			$sql .= " WHERE name_field = '".$fields_labels_ar[$i]["name_field"]."'";

			// execute the update select
			$db->send_query($sql);
		} // end if
	} // end for

	echo "<p><b>Configuration correctly saved.</b>";
} // end if

// re-get the array containg label ant other information about the fields
$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");

$change_field_select = build_change_field_select($fields_labels_ar, $field_position);

$int_table_form = "";

$int_table_form .= "<table><tr><td><form method='post' action='internal_table_manager.php?table_name=".urlencode($table_name)."'>".$change_field_select."<input type='submit' value='Change field'></form></td><td><form method='post' action='internal_table_manager.php?table_name=".urlencode($table_name)."'><input type='hidden' name='show_all_fields' value='1'><input type='submit' value='Show all fields in a page'></form></td></tr></table><form method='post' action='internal_table_manager.php?table_name=".urlencode($table_name)."'>";


if ($show_all_fields == "1"){
	// main loop through each record of the internal table
	for ($i=0; $i<count($fields_labels_ar); $i++){
		$int_table_form .= build_int_table_field_form($i, $int_fields_ar, $fields_labels_ar);
	} // end for
} // end if
else{
	$int_table_form .= build_int_table_field_form($field_position, $int_fields_ar, $fields_labels_ar);
} // end else

$int_table_form .= "<input type='hidden' name='field_position' value='".$field_position."'>";
$int_table_form .= "<input type='hidden' name='show_all_fields' value='".$show_all_fields."'>";
$int_table_form .= "<input type='submit' value='Save configuration'>";
$int_table_form .= "<input type='hidden' name='save' value='1'>";
$int_table_form .= "</form>";

// display the tabled form
echo $int_table_form;
?>
<hr noshade size="1">
<div align="right"><a href="admin.php">Admin home</a></div>
<?php
// include footer
include ("include/datadmin/footer_admin.php");
?>