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
$url = "admin%admin.php";
include ("include/datadmin/check_admin_login.php");
include ("include/datadmin/header_admin.php");
?>

<?php
// variables:
// GET
// $table_name
// 
// POST
// $allow_table_ar from this page
// $deleted_fields from this page
// field_to_change_name from this page
// field_to_change_new_position from this page
// old_field_name
// new_field_name
// $function from this page ("delete_records", "refresh_table",......)
// $table_name from this page
// $enable_insert from this file
// $enable_edit from this file
// $enable_delete from this file
// $enable_details from this file

if (isset($_POST["allow_table_ar"])){
	$allow_table_ar = $_POST["allow_table_ar"];
} // end if
if (isset($_POST["deleted_fields_ar"])){
	$deleted_fields_ar = $_POST["deleted_fields_ar"];
} // end if
if (isset($_POST["field_to_change_name"])){
	$field_to_change_name = $_POST["field_to_change_name"];
} // end if
if (isset($_POST["field_to_change_name"])){
	$field_to_change_name = $_POST["field_to_change_name"];
} // end if
if (isset($_POST["field_to_change_new_position"])){
	$field_to_change_new_position = $_POST["field_to_change_new_position"];
} // end if
if (isset($_POST["old_field_name"])){
	$old_field_name = $_POST["old_field_name"];
} // end if
if (isset($_POST["new_field_name"])){
	$new_field_name = $_POST["new_field_name"];
} // end if
if (isset($_POST["new_field_name"])){
	$new_field_name = $_POST["new_field_name"];
} // end if
if (isset($_REQUEST["function"])){
	$function = $_REQUEST["function"];
} // end if
else {
	$function = "";
} // end else
if (isset($_POST["enable_insert"])){
	$enable_insert = $_POST["enable_insert"];
} // end if
if (isset($_POST["enable_edit"])){
	$enable_edit = $_POST["enable_edit"];
} // end if
if (isset($_POST["enable_delete"])){
	$enable_delete = $_POST["enable_delete"];
} // end if
if (isset($_POST["enable_details"])){
	$enable_details = $_POST["enable_details"];
} // end if
if (isset($_POST["alias_table"])){
	$alias_table = $_POST["alias_table"];
} // end if

$confirmation_message = "";

// get the array containing the names of the tables installed
$installed_tables_ar = build_tables_names_array(0, 1, 1);

// get the table name to use in the second part of the administration
if (isset($_GET["table_name"])){
	$table_name = $_GET["table_name"];
} // end if
else {
	if (count($installed_tables_ar)>0){
		// get the first table
		$table_name = $installed_tables_ar[0];
	} // end if
} // end else

if (isset($table_name)){
	// build the select with all installed table
	$change_table_select = build_change_table_select();
	$table_internal_name = $prefix_internal_table.$table_name;
} // end if

// this is useful to display the tables that could be installed
$complete_tables_names_ar = build_tables_names_array(0, 0, 1);

switch($function){
	case "uninstall_table":
		// delete the table from table_list_name
		$sql = "DELETE FROM `$table_list_name` WHERE name_table = '$table_name'";
		$db->send_query($sql);

		// drop the internal table
		$sql = "DROP TABLE IF EXISTS `$table_internal_name`";
		$db->send_query($sql);

		$confirmation_message .= "Table $table_name uninstalled.";

		// re-get the array containing the names of the tables installed
		$installed_tables_ar = build_tables_names_array(0, 1, 1);

		if (count($installed_tables_ar)>0){
			// get the first table
			$table_name = $installed_tables_ar[0];
		} // end if

		if (isset($table_name)){
			// build the select with all installed table
			$change_table_select = build_change_table_select();
			$table_internal_name = $prefix_internal_table.$table_name;
		} // end if
		break;
	case "include_tables":
		for ($i=0; $i<count($installed_tables_ar); $i++){
			if (isset($allow_table_ar[$i])){
				if ($allow_table_ar[$i] == "1"){
					$sql = "UPDATE `$table_list_name` SET `allowed_table` = '1' WHERE `name_table` = '".$installed_tables_ar[$i]."'";
				} // end if
			} // en if
			else {
				$sql = "UPDATE `$table_list_name` SET `allowed_table` = '0' WHERE `name_table` = '".$installed_tables_ar[$i]."'";
			} // end else
			
			$db->send_query($sql);
		} // end for

		$confirmation_message .= "Changes correctly saved.";

		break; // break case "include tables"
	case "change_field_name":
		// change the name of the field
		$sql = "UPDATE `".$table_internal_name."` SET `name_field` = '$new_field_name' WHERE `name_field` = '$old_field_name'";

		$db->send_query($sql);

		$confirmation_message .= "$old_field_name correctly changed to $new_field_name.";
		
		break;
	case "enable_features":
		if (!isset($enable_insert)){
			$enable_insert = "0";
		} // end if

		if (!isset($enable_edit)){
			$enable_edit = "0";
		} // end if

		if (!isset($enable_delete)){
			$enable_delete = "0";
		} // end if

		if (!isset($enable_details)){
			$enable_details = "0";
		} // end if

		// save the configuration about features enabled
		$sql = "UPDATE `$table_list_name` SET `enable_insert_table` = '$enable_insert', `enable_edit_table` = '$enable_edit', `enable_delete_table` = '$enable_delete', `enable_details_table` = '$enable_details' WHERE `name_table` = '$table_name'";

		// execute the update
		$db->send_query($sql);

		$confirmation_message .= "Changes correctly saved.";
		break;
	case "save_table_alias":

		// save the configuration about table alias
		$sql = "UPDATE `$table_list_name` SET `alias_table_en` = '$alias_table' WHERE `name_table` = '$table_name'";

		// execute the update
		$db->send_query($sql);

		$confirmation_message .= "Changes correctly saved.";
		break;
	case "delete_records":
		// get the array containing labels and other information about the fields
		$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");
		
		if (isset($deleted_fields_ar)){
			for ($i=0; $i<count($deleted_fields_ar); $i++){
				// delete the record of the internal table
				$sql = "DELETE FROM `$table_internal_name` WHERE `name_field` = '".$deleted_fields_ar[$i]."'";
				$db->send_query($sql);

				// get the order_form_field of the field
				for ($j=0; $j<count($fields_labels_ar); $j++){
					if ($deleted_fields_ar[$i] == $fields_labels_ar[$j]["name_field"]){
						$order_form_field_temp = $fields_labels_ar[$j]["order_form_field"];
					} // end if
				} // end for

				// re-get the array containing labels and other information about the fields
				$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");

				if (isset($order_form_field_temp)){ // otherwise I could have done a reload of a delete page
					// decrease the order_form_field of all the following record by one
					for ($j=($order_form_field_temp+1); $j<=(count($fields_labels_ar)+1); $j++){
						$sql ="UPDATE `$table_internal_name` SET `order_form_field` = order_form_field-1 WHERE `order_form_field` = $j";
						$db->send_query($sql);
					} // end for
				} // end if

				// re-get the array containing labels and other information about the fields
				$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");
			} // end for

			$confirmation_message .= "$i fields correctly deleted from the internal table $table_internal_name.";
		} // end if
		else {
			$confirmation_message .= "Please select one or more fields to delete.";
		} // end else
		break;
	case "refresh_table":
		// get the array containing the names of the fields
		$fields_names_ar = build_fields_names_array($table_name);

		// get the array containg label and other information about the fields
		$fields_labels_ar = build_fields_labels_array($table_internal_name, "2");

		// get the max order from the table
		$sql_max = "SELECT MAX(order_form_field) FROM `$table_internal_name`";
		$db->send_query($sql_max);
		while ($max_row = $db->db_fetch_row()){
			$max_order_form = $max_row[0];
		} // end while
		$db->free_result();

		// drop (if present) the old internal table and create the new one.
		// create_internal_table($table_internal_name);

		$j = 0;  // set to 0 the counter for the $fields_labels_ar
		$new_fields_num = 0; // set to 0 the counter for the number of new fields inserted

		for ($i=0; $i<count($fields_names_ar); $i++){
			if (isset($fields_labels_ar[$j]["name_field"]) and $fields_names_ar[$i] == $fields_labels_ar[$j]["name_field"]){


				// insert a previous present record in the internal table
				$name_field_temp = $db->escape_string($fields_labels_ar[$j]["name_field"]);
				$present_insert_form_field_temp = $db->escape_string($fields_labels_ar[$j]["present_insert_form_field"]);
				$present_search_form_field_temp = $db->escape_string($fields_labels_ar[$j]["present_search_form_field"]);
				$present_ext_update_form_field_temp = $db->escape_string($fields_labels_ar[$j]["present_ext_update_form_field"]);
				$required_field_temp = $db->escape_string($fields_labels_ar[$j]["required_field"]);
				$present_results_search_field_temp = $db->escape_string($fields_labels_ar[$j]["present_results_search_field"]);
				$present_details_form_field_temp = $db->escape_string($fields_labels_ar[$j]["present_details_form_field"]);
				$check_duplicated_insert_field_temp = $db->escape_string($fields_labels_ar[$j]["check_duplicated_insert_field"]);
				$type_field_temp = $db->escape_string($fields_labels_ar[$j]["type_field"]);
				$content_field_temp = $db->escape_string($fields_labels_ar[$j]["content_field"]);
				$separator_field_temp = $db->escape_string($fields_labels_ar[$j]["separator_field"]);
				$select_options_field_temp = $db->escape_string($fields_labels_ar[$j]["select_options_field"]);
				$select_type_field_temp = $db->escape_string($fields_labels_ar[$j]["select_type_field"]);
				$prefix_field = $db->escape_string($fields_labels_ar[$j]["prefix_field"]);
				$default_value_field = $db->escape_string($fields_labels_ar[$j]["default_value_field"]);
				$label_de_field_temp = $db->escape_string($fields_labels_ar[$j]["label_de_field"]);
				$label_en_field_temp = $db->escape_string($fields_labels_ar[$j]["label_en_field"]);
				$width_field_temp = $db->escape_string($fields_labels_ar[$j]["width_field"]);
				$height_field_temp = $db->escape_string($fields_labels_ar[$j]["height_field"]);
				$maxlength_field_temp = $db->escape_string($fields_labels_ar[$j]["maxlength_field"]);
				$hint_insert_de_field_temp = $db->escape_string($fields_labels_ar[$j]["hint_insert_de_field"]);
				$hint_insert_en_field_temp = $db->escape_string($fields_labels_ar[$j]["hint_insert_en_field"]);
				$order_form_field_temp = $db->escape_string($fields_labels_ar[$j]["order_form_field"]);
				
				$other_choices_field_temp = $db->escape_string($fields_labels_ar[$j]["other_choices_field"]);

				$primary_key_field_field_temp = $db->escape_string($fields_labels_ar[$j]["primary_key_field_field"]);
				$primary_key_table_field_temp  = $db->escape_string($fields_labels_ar[$j]["primary_key_table_field"]);
				$primary_key_db_field_temp = $db->escape_string($fields_labels_ar[$j]["primary_key_db_field"]);

				$linked_fields_field_temp = $db->escape_string($fields_labels_ar[$j]["linked_fields_field"]);
				$linked_fields_order_by_field_temp = $db->escape_string($fields_labels_ar[$j]["linked_fields_order_by_field"]);
				$linked_fields_order_type_field_temp = $db->escape_string($fields_labels_ar[$j]["linked_fields_order_type_field"]);
			

				$sql = "INSERT INTO `$table_internal_name` (`name_field`, `present_insert_form_field`, `present_search_form_field`, `required_field`, `present_results_search_field`, `present_details_form_field`, `present_ext_update_form_field`, `check_duplicated_insert_field`, `type_field`, `content_field`, `separator_field`, `select_options_field`, `select_type_field`, `prefix_field`, `default_value_field`, `label_de_field`, `label_en_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_de_field`, `hint_insert_en_field`, `order_form_field`, `other_choices_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`) VALUES ('$name_field_temp', '$present_insert_form_field_temp', '$present_search_form_field_temp', '$required_field_temp', '$present_results_search_field_temp', '$present_details_form_field_temp', '$present_ext_update_form_field_temp', '$check_duplicated_insert_field_temp', '$type_field_temp', '$content_field_temp', '$separator_field_temp', '$select_options_field_temp', '$select_type_field_temp', '$prefix_field', '$default_value_field', '$label_de_field_temp', '$label_en_field_temp', '$width_field_temp', '$height_field_temp', '$maxlength_field_temp', '$hint_insert_de_field_temp', '$hint_insert_en_field_temp', '$order_form_field_temp', '$other_choices_field_temp', '$primary_key_field_field_temp', '$primary_key_table_field_temp', '$primary_key_db_field_temp', '$linked_fields_field_temp', '$linked_fields_order_by_field_temp', '$linked_fields_order_type_field_temp')";

				$j++; // go to the next record in the internal table
			} // end if
			else {
				$max_order_form++;
				// insert a new record in the internal table with the name of the field
				$sql = "INSERT INTO `$table_internal_name` (`name_field`, `label_en_field`, `label_de_field`, `order_form_field`) VALUES ('$fields_names_ar[$i]', '$fields_names_ar[$i]', '$fields_names_ar[$i]', '$max_order_form')";
				
				$new_fields_ar[$new_fields_num] = $fields_names_ar[$i]; // insert the name of the new field in the array to display it in the confirmation message
				$new_fields_num++; // increment the counter of the $new_fields_ar array
			} // end else	
			$db->send_query($sql);
		} // end for
		$confirmation_message .= "Internal table correctly refreshed.<br>$new_fields_num field/s added";
		if ($new_fields_num > 0){
			$confirmation_message .= " (";
			for ($i=0; $i<count($new_fields_ar); $i++){
				$confirmation_message .= $new_fields_ar[$i].", ";
			} // end for
			$confirmation_message = substr($confirmation_message, 0, -2); // delete the last ", "
			$confirmation_message .= ")";
		} // end if
		$confirmation_message .= ".";
		break;
	case "change_position":
		// get the array containg label and other information about the fields
		$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");

		// get the order_form_field of the field
		for ($i=0; $i<count($fields_labels_ar); $i++){
			if ($field_to_change_name == $fields_labels_ar[$i]["name_field"]){
				$field_to_change_old_position = $fields_labels_ar[$i]["order_form_field"];
			} // end if
		} // end for

		if ($field_to_change_new_position < $field_to_change_old_position){
			// increase the order_form_field of all the following record by one
			for ($i=$field_to_change_old_position-1; $i>=$field_to_change_new_position; $i--){
				$sql ="UPDATE `".$table_internal_name."` SET `order_form_field` = `order_form_field`+1 WHERE `order_form_field` = '".$i."'";
				$db->send_query($sql);
			} // end for
		} // end if
		else {
			// decrease the order_form_field of all the previous record by one
			for ($i=$field_to_change_old_position+1; $i<=$field_to_change_new_position; $i++){
				$sql ="UPDATE `".$table_internal_name."` SET `order_form_field` = `order_form_field`-1 WHERE `order_form_field` = '".$i."'";
				$db->send_query($sql);
			} // end for
		} // end if

		// change the order_form_field of the field selected
		$sql ="UPDATE `".$table_internal_name."` SET `order_form_field` = '".$field_to_change_new_position."' WHERE `name_field` = '".$field_to_change_name."'";
		$db->send_query($sql);
		$confirmation_message .= "Field $field_to_change_name position correctly changed from $field_to_change_old_position to $field_to_change_new_position.";		
		break;
	default:
		break;
} // end switch
?>

<?php
if ($confirmation_message != ""){
	echo "<p><strong><span style='color:#f00'>$confirmation_message</span></strong>";
} // end if
?>
<table border="1">l
<tr>
<td>
<p style="font-size: larger">Manage the list of tables of the <span style='color:#f00'><?php echo DB_NAME; ?></span> database you want to use in DaDaBIK</p>
<table border="0" cellpadding="6" width="100%">
<tr bgcolor="#F0F0F0">
<td>
<p><span style='color:#f00'><strong>Here is the list of the tables installed on DaDaBIK:</strong></span><br>
Uncheck include to exclude a table from DaDaBIK.<br>Click Uninstall if you have dropped the table from the database.
<form name="include_tables_form" method="post" action="admin.php">
<input type="hidden" name="function" value="include_tables">
<?php if (count($installed_tables_ar) != 0){ ?>

<table>
<tr>
<th>Include</th>
<th>&nbsp;</th>
</tr>
<?php
for ($i=0; $i<count($installed_tables_ar); $i++){
	echo "<tr><td><input type='checkbox' name='allow_table_ar[$i]' value='1'";
	if (table_allowed($installed_tables_ar[$i])){
		echo " checked";
	} // end if
	echo "></td><td>".$installed_tables_ar[$i]." <a href='admin.php?function=uninstall_table&table_name=".urlencode($installed_tables_ar[$i])."'>Uninstall</a></td></tr>";
} // end for
?>
</table>
<input type="submit" value="Save changes">

<?php } // end if
else {	
	echo "No tables installed.";
} // end else
?>

</form>
</td>
</tr>
</table>
<br>
<table border="0" cellpadding="6" width="100%">
<tr bgcolor="#F0F0F0">
<td>
<p><span style='color:#f00'><strong>Here is the list of the tables present in your database:</strong></span><br>Click Install to install each table and add it to the above list.<br>If you install an already present table, you'll overwrite its configuration.
<br><br>
<?php
for ($i=0; $i<count($complete_tables_names_ar); $i++){
	echo $complete_tables_names_ar[$i]."&nbsp;<a href='reinstall_table.php?table_name=".urlencode($complete_tables_names_ar[$i])."'>Install</a><br>";
} // end for
?>
</table>
</td>
</tr>
</table>
<?php if (count($installed_tables_ar)>0){ // otherwise it means that no internal tables are installed

// get the array containg label and other information about the fields
$fields_labels_ar = build_fields_labels_array($table_internal_name, "1"); // because I need it for the display of the select in the form

?>

<br><br>
<table border="1">
<tr>
<td>
<p style="font-size: larger">Configure the DaDaBIK interface of the table <strong><?php echo $table_name; ?></strong></p>

<?php
if ($change_table_select != ""){
?>
<form method="get" action="admin.php" name="change_table_form"><input type="hidden" name="function" value="change_table">
<?php echo $change_table_select; ?>
<?php
if ( $autosumbit_change_table_control == 0) {
?>
<input type="submit" value="Change table">
<?php
}
else {
?>
 Change table
<?php
}
?>

</form>
<?php
}
$enable_features_checkboxes = build_enable_features_checkboxes($table_name);

$only_include_allowed = 0;
$installed_table_infos_ar = build_installed_table_infos_ar($only_include_allowed, 1);

foreach ($installed_table_infos_ar as $installed_table_infos) {
	if ($installed_table_infos['name_table'] === $table_name) {
		$table_alias = $installed_table_infos['alias_table'];
	} // end if
} // end foreach
?>

<p><form method="post" action="admin.php?table_name=<?php echo urlencode($table_name); ?>"><input type="hidden" name="function" value="enable_features">For this table enable: <?php echo $enable_features_checkboxes ?><input type="submit" value="Enable/disable"></form>

<p><form method="post" action="admin.php?table_name=<?php echo urlencode($table_name); ?>"><input type="hidden" name="function" value="save_table_alias">English table alias (this is what DaDaBIK displays in the tables listbox) <input type="text" name="alias_table" value="<?php echo $table_alias; ?>"> <input type="submit" value="Save alias"></form>

<p>If you want to configure the interface of the table in detail (e.g. want to specify if a field should be included or not in the search/insert/update form, the content of the field......) you have to use the <a href="internal_table_manager.php?table_name=<?php echo urlencode($table_name); ?>">Interface configurator</a>.
<p>Directly from this page you can, instead, update DaDaBIK when you have modified some fields of your table (i.e. when you have added one or more fields, deleted one or more fields, renamed one or more fields from <strong><?php echo $table_name; ?></strong>).</p>

<p>Please follow these steps in the correct order:
<p>&nbsp;
<table border="0" cellpadding="6" width="100%">
  <tr bgcolor="#F0F0F0"> 
    <td><strong><span style='color:#f00'>Step 1:</span></strong><br>
      If you have renamed some fields of <strong><?php echo $table_name; ?></strong> you
      have to specify here the new names.

	   <p>Select the field name you want to change and specify the new name:<br>
      <form name="change_field_name_form" method="post" action="admin.php?table_name=<?php echo $table_name; ?>">
	  <input type="hidden" name="function" value="change_field_name">
        Old field name: <select name="old_field_name">
          <?php
for ($i=0; $i<count($fields_labels_ar); $i++){
	echo "<option value='".$fields_labels_ar[$i]["name_field"]."'>".$fields_labels_ar[$i]["name_field"]."</option>";	
} // end for
?> 
        </select>
		new field name: <input type="text" name="new_field_name">
		<input type="submit" value="Change">
		</form>
    </td>
  </tr>
</table>
<br>
<table border="0" cellpadding="6" width="100%">
  <tr bgcolor="#F0F0F0"> 
    <td>
      <p><strong><span style='color:#f00'>Step 2:</span></strong><br>
        If you have deleted some fields of <strong><?php echo $table_name; ?></strong> you
        have to specify here which fields you have deleted
        by selecting it/them and pressing the delete button. 
      <p>Select the field/s you want to delete:<br>
        (press CTRL for multiple selection) 
      <form name="deleted_fields_form" method="post" action="admin.php?table_name=<?php echo $table_name; ?>">
	  <input type="hidden" name="function" value="delete_records">
        <select multiple name="deleted_fields_ar[]" size="10">
          <?php
for ($i=0; $i<count($fields_labels_ar); $i++){
	echo "<option value='".$fields_labels_ar[$i]["name_field"]."'>".$fields_labels_ar[$i]["name_field"]."</option>";	
} // end for
?> 
        </select>
        <input type="submit" value="Delete this/these field/fields" name="submit">
      </form>
    </td>
  </tr>
</table>
<br>
<table border="0" cellpadding="6" width="100%">
  <tr bgcolor="#F0F0F0"> 
    <td>
      <p><strong><span style='color:#f00'>Step 3:</span></strong><br>
        If you have added some fields to <strong><?php echo $table_name; ?></strong> you
        have to update DaDaBIK by pressing the refresh installation button: 
      <form name="refresh_form" method="post" action="admin.php?table_name=<?php echo $table_name; ?>">
		<input type="hidden" name="function" value="refresh_table">
        <input type="submit" value="Refresh installation" name="submit">
      </form>
      <br>
      <br>
    </td>
  </tr>
</table>
<br>
<table border="0" cellpadding="6" width="100%">
  <tr bgcolor="#F0F0F0"> 
    <td>
      <p><strong><span style='color:#f00'>Step 4:</span></strong><br>
        If you want to change the displaying order of a field in the DaDaBIK interfaces, you can do it by selecting the field from the following menu and specifying the new position. All the other field positions will be shifted correctly.
		<form name="change_position_form" method="post" action="admin.php?table_name=<?php echo $table_name; ?>">
		<input type="hidden" name="function" value="change_position">
		Field name (position): 
        <select  name="field_to_change_name">
         <?php
		for ($i=0; $i<count($fields_labels_ar); $i++){
			echo "<option value='".$fields_labels_ar[$i]["name_field"]."'>".$fields_labels_ar[$i]["name_field"]." (".$fields_labels_ar[$i]["order_form_field"].")</option>";	
		} // end for
		?> 
        </select>
		 New position: 
		<select  name="field_to_change_new_position">
         <?php
		for ($i=0; $i<count($fields_labels_ar); $i++){
			echo "<option value='".$fields_labels_ar[$i]["order_form_field"]."'>".$fields_labels_ar[$i]["order_form_field"]."</option>";	
		} // end for
		?> 
        </select>
        <input type="submit" value="Change position" name="submit">
      </form>
	</td>
  </tr>
</table>
</td>
</tr>
</table>
<?php } // end if?>
<?php
// include footer
include ("include/datadmin/footer_admin.php");
?>
