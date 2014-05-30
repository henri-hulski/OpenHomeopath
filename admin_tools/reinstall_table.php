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
$url = "admin%reinstall_table.php";
include ("include/datadmin/check_admin_login.php");
include ("include/datadmin/header_admin.php");

// variables:
// GET
// table_name form admin.php
if (isset($_GET["table_name"])){
	$table_name = urldecode($_GET["table_name"]);
} // end if
else{
	$table_name = "";
} // end else

// POST
// $install from reinstall_table.php (set to 1 when user click on install)

if (isset($_POST["install"])){
	$install = $_POST["install"];
} // end if
else{
	$install = "";
} // end else


if ($install == "1"){
	if ($table_name != ""){
		$tables_names_ar[0] = $table_name;
		
		if (!$db->table_exists($table_list_name)){
			// drop (if present) the old table list table and create the new one
			create_table_list_table();
		} // end if
	} // end if
	else{
		// drop (if present) the old users table and create the new one
		create_users_table();

		// get the array containing the names of the tables (excluding "dadabik_" ones)
		$tables_names_ar = build_tables_names_array(0, 0, 1);

		// drop (if present) the old table list table and create the new one
		create_table_list_table();
	} // end else

	for ($i=0; $i<count($tables_names_ar); $i++){
		$table_name_temp = $tables_names_ar[$i];
		$table_internal_name_temp = $prefix_internal_table.$table_name_temp;

		$unique_field_name = $db->get_primary_key($table_name_temp);
	
		// get the array containing the names of the fields
		$fields_names_ar = build_fields_names_array($table_name_temp);

		// drop (if present) the old internal table and create the new one.
		create_internal_table ($table_internal_name_temp);


		// delete the previous record about the table
		$sql = "DELETE FROM `$table_list_name` WHERE `name_table` = '$table_name_temp'";			
		$db->send_query($sql);

		// add the table to the table list table and set allowed to 1
		//$sql = "insert into `" . $table_list_name."` (`name_table`, `allowed_table`, `enable_insert_table`, `enable_edit_table`, `enable_delete_table`, `enable_details_table`) values ('" . $table_name_temp."', '1', '1', '1', '1', '1')";
		$sql = "INSERT INTO `$table_list_name` (`name_table`, `allowed_table`, `enable_insert_table`, `enable_edit_table`, `enable_delete_table`, `enable_details_table`, `alias_table_en`) VALUES ('$table_name_temp', '1', '1', '1', '1', '1', '$table_name_temp')";
		$db->send_query($sql);

		if ($table_name_temp === $users_table_name) {
			$sql = "INSERT INTO `$table_internal_name_temp` VALUES (1, 'id_user', 'id_user', 'text', 'alphanumeric', '0', '0', '0', '0', '1', '0', '0', '0', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 1, '~')";
			$db->send_query($sql);
			
			$sql = "INSERT INTO `$table_internal_name_temp` VALUES (2, '$users_table_user_type_field', 'User type', 'select_single', 'alphanumeric', '1', '1', '1', '1', '1', '1', '0', '0', '~admin~normal~', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 2, '~')";
			$db->send_query($sql);

			$sql = "INSERT INTO `$table_internal_name_temp` VALUES (3, '$users_table_username_field', 'Username', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '1', '1', '0', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 3, '~')";
			$db->send_query($sql);

			$sql = "INSERT INTO `$table_internal_name_temp` VALUES (4, '$users_table_password_field', 'Password (md5 hash)', 'text', 'alphanumeric', '0', '0', '1', '1', '1', '1', '0', '0', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 4, '~')";
			$db->send_query($sql);
		} // end if
		else {
			for ($j=0; $j<count($fields_names_ar); $j++){
				// insert a new record in the internal table with the name of the field as name and label
				$sql = "INSERT INTO `$table_internal_name_temp` (`name_field`, `label_de_field`, `label_en_field`, `order_form_field`) VALUES ('" . $fields_names_ar[$j] . "', '" . $fields_names_ar[$j] . "', '" . $fields_names_ar[$j] . "', '" . ($j+1) . "')";
				$db->send_query($sql);
			} // end for
		} // end else
		
		if ($db->table_exists($table_internal_name_temp)){ // just a check if all is fine
			echo "<p>Internal table <b>$table_internal_name_temp</b> correctly created......";
		} // end if
		else{
			echo "<p>An error during installation occured";
			exit;
		} // end else

		if ($unique_field_name == ""){
			echo "<p><b>Warning:</b> your table <b>$table_name_temp</b> hasn't any primary keys set, if you don't set a primary key DaDaBIK won't show the edit/delete/details buttons.";
		} // end if
	} // end for
	echo "<p>......DaDaBIK correctly installed!!";
	echo "<p>You can now manage your database with DaDaBIK, starting from <a href='$dadabik_main_file'>" . $dadabik_main_file . "</a>";
	echo "<p>In order to customize DaDaBIK go to the <a href='admin.php'>homepage</a> of the administration area.";
} // end if ($install == "1")
else{
	echo "<p><form name='install_form' action='reinstall_table.php?table_name=" . urlencode($table_name) . "' method='post'>";
	echo "<input type='hidden' name='install' value='1'>";
	if ( $table_name != "") {
		echo "<input type='submit' value='Click this button to install $table_name table'>";
	} // end if
	if ( $table_name == "") {
		echo "<input type='submit' value='Click this button to install DaDaBIK'>";
		echo "<p>Please note that if DaDaBIK is already installed in the ".DB_NAME." database, the installation will overwrite the previous DaDaBIK configuration. The installation will also overwrite the users table $users_table_name, if you want to keep the pre-existent one, just backup it and import it after the installation.";
	}
	echo "</form>";
} // end else

// include footer
include ("include/datadmin/footer_admin.php");
?>