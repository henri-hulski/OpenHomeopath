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

function current_user_is_owner($where_field, $where_value, $table_name, $fields_labels_ar) {
// goal: check if authentication is required and, if yes, if the current user is the owner so that he can delete/update the record.
// input: $where_field, $where_value, $table_name (to identify the record), $fields_labels_ar
// output: true|false

/****
 ****  If editors/admins should not have the possibility
 ****  to change or delete entries of other users,
 ****  delete "$current_user_is_editor, " from globals and
 ****  "$current_user_is_editor === 1 || " in the first if-close.
 ****  Henri Schumacher
 ****/
	global $current_user, $current_user_is_editor, $db;

	// get the name of the field that has ID_user type
	$ID_user_field_name = get_ID_user_field_name($fields_labels_ar);
	if ($current_user_is_editor === 1 || $ID_user_field_name === false) {
		return true; // no ID_user field type, no authentication needed
	} // end if
	else {
		// check if the owner of the record is current_user
		$sql = "SELECT `$ID_user_field_name` FROM `$table_name` WHERE `$where_field` = '$where_value' AND `$ID_user_field_name` = '".$db->escape_string($current_user)."'";

		$db->send_query($sql);
		$num_rows = $db->db_num_rows();
		$db->free_result();

		if ($num_rows === 1) {
			return true;
		} // end if
		else {
			return false;
		} // end else
	} // end else

} // end function current_user_is_owner()

function get_ID_user_field_name($fields_labels_ar)
// goal: get the name of the first ID_user type field
//input: $fields_labels_ar
//output: the field name or false if there aren't any ID_user field so the authentication is not needed
{
	$ID_user_field_name = false;

	$fields_labels_ar_count = count($fields_labels_ar);
	$i = 0;

	while ($i < $fields_labels_ar_count && $ID_user_field_name === false) {
		if ($fields_labels_ar[$i]['type_field'] === 'ID_user') {
			$ID_user_field_name = $fields_labels_ar[$i]['name_field'];
		} // end if
		$i++;
	} // end while

	return $ID_user_field_name;
} // end function get_ID_user_field_name()

function build_fields_names_array($table_name)
// goal: build an array ($fields_names_ar) containing the names of the fields of a specified table
// input:name of the table
// output: $fields_names_ar
{
	global $db;

	$sql = "DESCRIBE $table_name";
	$db->send_query($sql);
	while ($row = $db->db_fetch_assoc()) {
		$fields_names_ar[] = $row["Field"];
	}
	$db->free_result();
	return $fields_names_ar;
} // end build_fields_names_array function

function build_tables_names_array($exclude_not_allowed = 1, $exclude_not_installed = 1, $include_users_table = 0)
// goal: build an array ($tables_names_ar) containing the names of the tables of the db, excluding the internal tables, get the list from $table_list_name tab if $exclude_not_installed = 1, otherwise directly from the DBMS
// input: $exclude_not_allowed (1 if the tables excluded by the user are excluded), $exclude_not_installed (1 if the tables not installed are excluded), $include_users_table (1 if it is necessary to include the users table, even if the user is not admin (useful in admin.php)
// output: $tables_names_ar
{
	global $db, $prefix_internal_table, $table_list_name, $users_table_name, $current_user_is_editor;

	$z = 0;
	$tables_names_ar = array();

	if ( $exclude_not_installed == 1 ) { // get the list from $table_list_name tab
		$sql = "SELECT name_table FROM `$table_list_name`";
		if ( $exclude_not_allowed == 1) { // excluding not allowed if necessary
			$sql .= " WHERE allowed_table = '1'";
		} // end if
		$db->send_query($sql);
		while ($row = $db->db_fetch_row()) {

			if ($current_user_is_editor === 1 || $row[0] !== $users_table_name || $include_users_table === 1) {
				$tables_names_ar[$z] = $row[0];
				$z++;
			} // end if
		}
		$db->free_result();
	} // end if
	else { // get the list directly from db
		$sql = "SHOW TABLES";
		$db->send_query($sql);
		while ($row = $db->db_fetch_row()) {
			$table_name_temp = $row[0];
			// if the table is not internal
			if (substr($table_name_temp, 0, strlen($prefix_internal_table)) != $prefix_internal_table && $table_name_temp != $table_list_name && substr($table_name_temp, 0, 9) != 'archive__' && substr($table_name_temp, 0, 12) != 'homeophorum__' && substr($table_name_temp, 0, 7) != 'active_' && substr($table_name_temp, 0, 7) != 'banned_') {
				$tables_names_ar[$z] = $table_name_temp;
				$z++;
			} // end if
		}
		$db->free_result();
	} // end else
	return $tables_names_ar;
} // end build_tables_names_array function

function build_fields_labels_array($table_internal_name, $order_type)
// goal: build an array ($fields_labels_ar) containing the fields labels and other information about fields (e.g. the type, display/don't display) of a specified table to use in the form
// input: name of the internal table, $order_type: 0|1|2 no order| by order_form_field | by id_field
// output: fields_labels_ar, a 2 dimensions associative array: $fields_labels_ar[field_number]["internal table field (e.g. present_insert_form_field)"]
// global $error_messages_ar, the array containg the error messages
{
	global $db, $error_messages_ar;

	$table_alias_suffixes_ar = array();

	// put the labels and other information of the table's fields in an array
	$sql = "SELECT `name_field`, `present_insert_form_field`, `present_ext_update_form_field`, `present_search_form_field`, `required_field`, `present_results_search_field`, `present_details_form_field`, `check_duplicated_insert_field`, `type_field`, `other_choices_field`, `content_field`, `label_de_field`, `label_en_field`, `select_options_field`, `separator_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_de_field`, `hint_insert_en_field`, `order_form_field` FROM `$table_internal_name`";

	if ($order_type == "1") {
		$sql .= " ORDER BY `order_form_field`";
	} // end if
	elseif ($order_type == "2") {
		$sql .= " ORDER BY `id_field`";
	} // end else

	$db->send_query($sql);
	$num_rows = $db->db_num_rows();
	$i = 0;
	if ($num_rows > 0) { // at least one record
		while($field_row = $db->db_fetch_assoc()) {
			$fields_labels_ar[$i]["name_field"] = $field_row["name_field"]; // the name of the field
			$fields_labels_ar[$i]["present_insert_form_field"] = $field_row["present_insert_form_field"]; // 1 if the user want to display it in the insert form
			$fields_labels_ar[$i]["present_ext_update_form_field"] = $field_row["present_ext_update_form_field"]; // 1 if the user want to display it in the external update form
			$fields_labels_ar[$i]["present_search_form_field"] = $field_row["present_search_form_field"]; // 1 if the user want to display it in the search form
			$fields_labels_ar[$i]["required_field"] = $field_row["required_field"]; // 1 if the field is required in the insert (the field must be in the insert form, otherwise this flag hasn't any effect
			$fields_labels_ar[$i]["present_results_search_field"] = $field_row["present_results_search_field"]; // 1 if the user want to display it in the basic results page
			$fields_labels_ar[$i]["present_details_form_field"] = $field_row["present_details_form_field"]; // 1 if the user want to display it in the details page
			$fields_labels_ar[$i]["check_duplicated_insert_field"] = $field_row["check_duplicated_insert_field"]; // 1 if the field needs to be checked for duplicated insert

			$fields_labels_ar[$i]["label_de_field"] = $field_row["label_de_field"]; // the German label of the field
			$fields_labels_ar[$i]["label_en_field"] = $field_row["label_en_field"]; // the English label of the field

			$fields_labels_ar[$i]["type_field"] = $field_row["type_field"]; // the type of the field
			$fields_labels_ar[$i]["other_choices_field"] = $field_row["other_choices_field"]; // 0/1 the possibility to add another choice with select single menu
			$fields_labels_ar[$i]["content_field"] = $field_row["content_field"]; // the control type of the field (eg: numeric, alphabetic, alphanumeric)
			$fields_labels_ar[$i]["select_options_field"] = $field_row["select_options_field"]; // the options, separated by separator, possible in a select field
			$fields_labels_ar[$i]["separator_field"] = $field_row["separator_field"]; // the separator of different possible values for a select field
			$fields_labels_ar[$i]["primary_key_field_field"] = $field_row["primary_key_field_field"]; // the primary key field_name if this field is a foreign key
			$fields_labels_ar[$i]["primary_key_table_field"] = $field_row["primary_key_table_field"]; // the primary key table_name if this field is a foreign key
			$fields_labels_ar[$i]["primary_key_db_field"] = $field_row["primary_key_db_field"]; // the primary key database if this field is a foreign key
			$fields_labels_ar[$i]["linked_fields_field"] = $field_row["linked_fields_field"]; // the fields linked to through the pk
			$fields_labels_ar[$i]["linked_fields_order_by_field"] = $field_row["linked_fields_order_by_field"]; // the fields by which order when retreiving the linked fields
			$fields_labels_ar[$i]["linked_fields_order_type_field"] = $field_row["linked_fields_order_type_field"]; // the order type (ASC|DESC) to use in the order clause when retreiving the linked fields
			$fields_labels_ar[$i]["select_type_field"] = $field_row["select_type_field"]; // the type of select, exact match or like
			$fields_labels_ar[$i]["prefix_field"] = $field_row["prefix_field"]; // the prefix of the field (e.g. http:// - only for text, textarea and rich_editor)
			$fields_labels_ar[$i]["default_value_field"] = $field_row["default_value_field"]; // the default value of the field (only for text, textarea and rich_editor)
			$fields_labels_ar[$i]["width_field"] = $field_row["width_field"]; // the width size of the field in case of text, textarea or rich_editor
			$fields_labels_ar[$i]["height_field"] = $field_row["height_field"]; // the height size of the field in case of textarea or rich_editor
			$fields_labels_ar[$i]["maxlength_field"] = $field_row["maxlength_field"]; // the maxlength of the field in case of text
			$fields_labels_ar[$i]["hint_insert_de_field"] = $field_row["hint_insert_de_field"]; // the hint to display after the field in the insert form in German
			$fields_labels_ar[$i]["hint_insert_en_field"] = $field_row["hint_insert_en_field"]; // the hint to display after the field in the insert form (e.g. use only number here!!) in English
			$fields_labels_ar[$i]["order_form_field"] = $field_row["order_form_field"]; // the position of the field in the form

			if ($field_row["primary_key_field_field"] !== '' && $field_row["primary_key_field_field"] !== NULL) {
				$linked_fields_ar = explode($field_row["separator_field"], $field_row["linked_fields_field"]);

				if ( array_key_exists($field_row["primary_key_table_field"], $table_alias_suffixes_ar) === false) {
					$table_alias_suffixes_ar[$field_row["primary_key_table_field"]] = 1;
					$fields_labels_ar[$i]["alias_suffix_field"] = 1;
				} // end if
				else {
					$table_alias_suffixes_ar[$field_row["primary_key_table_field"]]++;
					$fields_labels_ar[$i]["alias_suffix_field"] = $table_alias_suffixes_ar[$field_row["primary_key_table_field"]];
				} // end else

			} // end if

			$i++;
		} // end while
	} // end if
	else { // no records
		echo $error_messages_ar["int_db_empty"];
	} // end else
	$db->free_result();
	return $fields_labels_ar;
} // end build_fields_labels_array function

function build_form($table_name, $action, $fields_labels_ar, $form_type, $res_details, $where_field, $where_value, $show_insert_form_after_error, $show_edit_form_after_error)
// goal: build a tabled form by using the info specified in the array $fields_labels_ar
// input: $table_name, array containing labels and other info about fields, $action (the action of the form), $form_type, $res_details, $where_field, $where_value (the last three useful just for update forms), $_POST, $_FILES (the last two useful for insert and edit to refill the form), $show_insert_form_after_error (0|1), $show_edit_form_after_error (0|1), tha last two useful to know if the inser or edit forms are showed after a not successful insert and update and so it is necessary to refill the fields
// global: $submit_buttons_ar, the array containing the values of the submit buttons, $normal_messages_ar, the array containig the normal messages, $select_operator_feature, wheter activate or not displaying "and/or" in the search form, $default_operator, the default operator if $select_operator_feature is not activated, $size_multiple_select, the size (number of row) of the select_multiple_menu fields, $table_name
// output: $form, the html tabled form
{
	global $db, $submit_buttons_ar, $normal_messages_ar, $select_operator_feature, $default_operator, $size_multiple_select, $show_top_buttons, $enable_authentication, $enable_browse_authorization, $current_user, $year_field_suffix, $month_field_suffix, $day_field_suffix, $start_year, $lang;

	switch ($form_type) {
		case 'insert':
			$function = 'insert';
			break;
		case 'update':
			$function = 'update';
			break;
		case 'ext_update':
			$function = 'ext_update';
			break;
		case 'search':
			$function = 'search';
			break;
	} // end switch

	$form = "";
	$form .= "<form id='dadabik_main_form' name='contacts_form' method='post' action='$action?table_name=".urlencode($table_name)."&function=$function";

	if ( $form_type == "update" or $form_type == "ext_update") {
		$form .= "&where_field=".urlencode($where_field)."&where_value=".urlencode($where_value);
	}

	if ( $form_type == "search") {
		$form .= "&execute_search=1";
	}

	$form .= "' enctype='multipart/form-data'><table>";

	switch($form_type) {
		case "insert":
			$number_cols = 3;
			$field_to_ceck = "present_insert_form_field";
			break;
		case "update":
			$number_cols = 3;
			$field_to_ceck = "present_insert_form_field";

			if ($show_edit_form_after_error === 0) {
				$details_row = $db->db_fetch_assoc($res_details); // get the values of the details
			} // end if
			if ( $show_top_buttons == 1) {
				$form .= "<tr class='tr_button_form'><td colspan='$number_cols' class='td_button_form'><input class='button_form' type='submit' value='".$submit_buttons_ar[$form_type]."'></td></tr>";
			}
			break;
		case "ext_update":
			$number_cols = 4;
			$field_to_ceck = "present_ext_update_form_field";
			$details_row = $db->db_fetch_assoc($res_details); // get the values of the details
			if ( $show_top_buttons == 1) {
				$form .= "<tr class='tr_button_form'><td colspan='$number_cols' class='td_button_form'><input class='button_form' type='submit' value='".$submit_buttons_ar[$form_type]."'></td></tr>";
			}
			break;
		case "search":
			$number_cols = 2;
			$field_to_ceck = "present_search_form_field";
			if ($select_operator_feature == "1") {
				$form .= "<tr class='tr_operator_form'><td colspan='$number_cols' class='td_button_form'><select name='operator'><option value='and'>".$normal_messages_ar["all_conditions_required"]."</option><option value='or'>".$normal_messages_ar["any_conditions_required"]."</option></select></td></tr>";
			} // end if
			else {
				$form .= "<input type='hidden' name='operator' value='$default_operator'>";
			} // end else
			if ( $show_top_buttons == 1) {
				$form .= "<tr class='tr_button_form'><td colspan='$number_cols'><input  class='button_form' type='submit' value='".$submit_buttons_ar[$form_type]."'></td></tr>";
			}
			break;
	} // end switch
	for ($i=0; $i<count($fields_labels_ar); $i++) {
		if ($fields_labels_ar[$i][$field_to_ceck] == "1") { // the user want to display the field in the form

			// build the first coloumn (label)
			//////////////////////////////////
			// I put a table inside the cell to get the same margin of the second coloumn
			$form .= "<tr><td style='text-align: right; vertical-align: top;'><table><tr><td class='td_label_form'>";
			if ($fields_labels_ar[$i]["required_field"] == "1" and $form_type != "search") {
				$form .= "*";
			} // end if
			$form .= $fields_labels_ar[$i]["label_" . $lang . "_field"]." ";
			$form .= "</td></tr></table></td>";
			//////////////////////////////////
			// end build the first coloumn (label)

			$field_name_temp = $fields_labels_ar[$i]["name_field"];

			// build an empty cell

						$form .= "<td style='text-align: right; vertical-align: top;'><table><tr><td class='td_null_checkbox_form'>";
						$form .= "</td></tr></table></td>";


			// build the second coloumn (input field)
			/////////////////////////////////////////
			$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
			if ($primary_key_field_field != "") {
				$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
				$primary_key_table_field = $fields_labels_ar[$i]["primary_key_table_field"];
				$primary_key_db_field = $fields_labels_ar[$i]["primary_key_db_field"];
				$linked_fields_field = $fields_labels_ar[$i]["linked_fields_field"];
				$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_field);
				$linked_fields_order_by_field = $fields_labels_ar[$i]["linked_fields_order_by_field"];
				if ($linked_fields_order_by_field !== '' && $linked_fields_order_by_field !== NULL) {
					$linked_fields_order_by_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_order_by_field);
				} // end if
				else {
					unset($linked_fields_order_by_ar);
				} // end else

				$linked_fields_order_type_field = $fields_labels_ar[$i]["linked_fields_order_type_field"];

				$sql = "SELECT `$primary_key_field_field`";

				$count_temp = count($linked_fields_ar);
				for ($j=0; $j<$count_temp; $j++) {
					$sql .= ", `".$linked_fields_ar[$j]."`";
				}
				$sql .= " FROM `$primary_key_table_field`";

				if (isset($linked_fields_order_by_ar)) {
					$sql .= " ORDER BY ";
					$count_temp = count($linked_fields_order_by_ar);
					for ($j=0; $j<$count_temp; $j++) {
						$sql .= "`".$linked_fields_order_by_ar[$j]."`, ";
					}
					$sql = substr($sql, 0, -2); // delete the last ","
					$sql .= " ".$linked_fields_order_type_field;
				} // end if
				$res_primary_key = $db->send_query($sql);
				$fields_number = $db->db_num_fields();
			} // end if

			if ($form_type == "search") {
				$select_type_select = build_select_type_select($field_name_temp, $fields_labels_ar[$i]["select_type_field"], 0); // build the select type select form (is_equal....)
				$select_type_date_select = build_select_type_select($field_name_temp, $fields_labels_ar[$i]["select_type_field"], 1); // build the select type select form (is_equal....) for date fields, with the first option blank
			} // end if
			else {
				$select_type_select = "";
				$select_type_date_select = "";
			} // end else
			$form .= "<td><table><tr>";
			switch ($fields_labels_ar[$i]["type_field"]) {
				case "text":
				case "ID_user":
					$form .= "<td class='td_input_form'>$select_type_select<input type='text' name='$field_name_temp'";
					if ($fields_labels_ar[$i]["width_field"] != "") {
						$form .= " size='".$fields_labels_ar[$i]["width_field"]."'";
					} // end if
					$form .= " maxlength='".$fields_labels_ar[$i]["maxlength_field"]."'";
					if ($form_type == "update" or $form_type == "ext_update") {
						if ($show_edit_form_after_error === 1) {
							if (isset($_POST[$field_name_temp])) {
								$form .= " value='".htmlspecialchars(stripslashes($_POST[$field_name_temp]))."'";
							} // end if
						} // end if
						else {
							$form .= " value='".htmlspecialchars($details_row[$field_name_temp])."'";
						} // end else
					} // end if
					if ($form_type == "insert") {
						if ($show_insert_form_after_error === 1 && isset($_POST[$field_name_temp])) {
							$form .= ' value="'.htmlspecialchars(stripslashes($_POST[$field_name_temp])).'"';
						} // end if
						else {
							$form .= " value='".$fields_labels_ar[$i]["prefix_field"].$fields_labels_ar[$i]["default_value_field"]."'";
						} // end else
					} // end if
					$form .= ">";
					$form .= "</td>"; // add the second coloumn to the form
					break;
				case "textarea":
					$form .= "<td class='td_input_form'>$select_type_select</td>";
					$form .= "<td class='td_input_form'><textarea cols='".$fields_labels_ar[$i]["width_field"]."' rows='".$fields_labels_ar[$i]["height_field"]."' name='".$field_name_temp."'>";
					if ($form_type == "update" or $form_type == "ext_update") {
						if ($show_edit_form_after_error === 1) {
							if (isset($_POST[$field_name_temp])) {
								$form .= htmlspecialchars(stripslashes($_POST[$field_name_temp]));
							} // end if
						} // end if
						else {
							$form .= htmlspecialchars($details_row[$field_name_temp]);
						} // end else
					} // end if
					if ($form_type == "insert") {

						if ($show_insert_form_after_error === 1 && isset($_POST[$field_name_temp])) {
							$form .= htmlspecialchars(stripslashes($_POST[$field_name_temp]));
						} // end if
						else {
							$form .= $fields_labels_ar[$i]["prefix_field"].$fields_labels_ar[$i]["default_value_field"];
						} // end else

					} // end if

					$form .= "</textarea></td>"; // add the second coloumn to the form
					break;
				case "insert_timestamp":
				case "update_timestamp":
					$date_select = "";
					switch($form_type) {
						case "search":
							$date_select = build_date_select($field_name_temp,"","","");
							break;
					} // end switch
					$form .= "<td class='td_input_form'>$select_type_date_select</td>$date_select</td>"; // add the second coloumn to the form
					break;
				case "select_single":
					$form .= "<td class='td_input_form'>$select_type_select<select name='$field_name_temp'>"; // first part of the second coloumn of the form

					$form .= "<option value=''></option>"; // first blank option

					$field_temp = substr($fields_labels_ar[$i]["select_options_field"], 1, -1); // delete the first and the last separator

					if (trim($field_temp) !== '') {
						$select_values_ar = explode($fields_labels_ar[$i]["separator_field"],$field_temp);

						$count_temp = count($select_values_ar);
						for ($j=0; $j<$count_temp; $j++) {
							$form .= "<option value='".htmlspecialchars($select_values_ar[$j])."'";

							if ($form_type === 'update' or $form_type === 'ext_update') {
								if ($show_edit_form_after_error === 1) {
									if (isset($_POST[$field_name_temp]) && $select_values_ar[$j] == stripslashes($_POST[$field_name_temp])) {
										$form .= " selected";
									} // end if
								} // end if
								else {
									if ($select_values_ar[$j] == $details_row[$field_name_temp]) {
										$form .= " selected";
									} // end if
								} // end else
							} // end if

							if ($form_type === 'insert' && $show_insert_form_after_error === 1 && isset($_POST[$field_name_temp]) && $select_values_ar[$j] == stripslashes($_POST[$field_name_temp])) {
								$form .= " selected";
							} // end if

							$form .= ">".$select_values_ar[$j]."</option>"; // second part of the form row
						} // end for
					} // end if

					if ($fields_labels_ar[$i]["primary_key_field_field"] != "") {
						if ($db->db_num_rows($res_primary_key) > 0) {
							while ($primary_key_row = $db->db_fetch_row($res_primary_key)) {

								$primary_key_value = $primary_key_row[0];
								$linked_fields_value = "";
								for ($z=1; $z<$fields_number; $z++) {
									$linked_fields_value .= $primary_key_row[$z];
									$linked_fields_value .= " - ";
								} // end for
								$linked_fields_value = substr($linked_fields_value, 0, -3); // delete the last " -

								$form .= "<option value='".htmlspecialchars($primary_key_value)."'";

								if ($form_type === 'update' or $form_type === 'ext_update') {
									if ($show_edit_form_after_error === 1) {
										if (isset($_POST[$field_name_temp]) && $primary_key_value == stripslashes($_POST[$field_name_temp])) {
											$form .= " selected";
										} // end if
									} // end if
									else {
										if ($primary_key_value == $details_row[$field_name_temp]) {
											$form .= " selected";
										} // end if
									} // end else
								} // end if

								if ($form_type === 'insert' && $show_insert_form_after_error === 1 && isset($_POST[$field_name_temp]) && $primary_key_value == stripslashes($_POST[$field_name_temp])) {
									$form .= " selected";
								} // end if

								$form .= ">$linked_fields_value</option>"; // second part of the form row
							} // end while
						} // end if
					} // end if ($fields_labels_ar[$i]["primary_key_field_field"] != "")

					if ($fields_labels_ar[$i]["other_choices_field"] == "1" and ($form_type == "insert" or $form_type == "update")) {
						$form .= "<option value='......'";
						if ($form_type === 'insert' && $show_insert_form_after_error === 1 && isset($_POST[$field_name_temp]) && $_POST[$field_name_temp] === '......') {
							$form .= " selected";
						} // end if
						if ($form_type === 'update' && $show_edit_form_after_error === 1 && isset($_POST[$field_name_temp]) && $_POST[$field_name_temp] === '......') {
							$form .= " selected";
						} // end if
						$form .= ">".$normal_messages_ar["other...."]."</option>"; // last option with "other...."
					} // end if

					$form .= "</select>";

					if ($fields_labels_ar[$i]["other_choices_field"] == "1" and ($form_type == "insert" or $form_type == "update")) {
						$form .= "<input type='text' name='".$field_name_temp."_other____"."' maxlength='".$fields_labels_ar[$i]["maxlength_field"]."'";

						if ($fields_labels_ar[$i]["width_field"] != "") {
							$form .= " size='".$fields_labels_ar[$i]["width_field"]."'";
						} // end if

						if ($form_type == "insert" && $show_insert_form_after_error === 1) {
							if (isset($_POST[$field_name_temp."_other____"])) {
								if (isset($_POST[$field_name_temp]) && $_POST[$field_name_temp] === '......') {
									$form .= ' value="'.htmlspecialchars(stripslashes($_POST[$field_name_temp."_other____"])).'"';
								} // end if
							} // end if
						} // end if

						if ($form_type == "update" && $show_edit_form_after_error === 1) {
							if (isset($_POST[$field_name_temp."_other____"])) {
								if (isset($_POST[$field_name_temp]) && $_POST[$field_name_temp] === '......') {
									$form .= ' value="'.htmlspecialchars(stripslashes($_POST[$field_name_temp."_other____"])).'"';
								} // end if
							} // end if
						} // end if

						$form .= ">"; // text field for other....
					} // end if

					$form .= "</td>"; // last part of the second coloumn of the form
					break;
			} // end switch
			/////////////////////////////////////////
			// end build the second coloumn (input field)

			if ($form_type == "insert" or $form_type == "update" or $form_type == "ext_update") {
				$form .= "<td class='td_hint_form'>".$fields_labels_ar[$i]["hint_insert_" . $lang . "_field"]."</td>"; // display the insert hint if it's the insert form
			} // end if
			$form .= "</tr></table></td></tr>";
		} // end if ($fields_labels_ar[$i]["$field_to_ceck"] == "1")
	} // enf for loop for each field in the label array

	$form .= "<tr><td class='tr_button_form' colspan='$number_cols'><input type='submit' class='button_form' value='".$submit_buttons_ar[$form_type]."'></td></tr></table></form>";
	return $form;
} // end build_form function

function build_select_type_select($field_name, $select_type, $first_option_blank)
// goal: build a select with the select type of the field (e.g. is_equal, contains....)
// input: $field_name, $select_type (e.g. is_equal/contains), $first_option_blank(0|1)
// output: $select_type_select
// global: $normal_messages_ar, the array containing the normal messages
{
	global $normal_messages_ar, $select_type_select_suffix, $year_field_suffix, $month_field_suffix, $day_field_suffix;

	$select_type_select = "";

	$operators_ar = explode("/",$select_type);

	if (count($operators_ar) > 1) { // more than one operator, need a select
		$select_type_select .= "<select onchange=\"javascript:enable_disable_input_box_search_form('$field_name', '$select_type_select_suffix', '$year_field_suffix', '$month_field_suffix', '$day_field_suffix')\" name='".$field_name.$select_type_select_suffix."'>";
		$count_temp = count($operators_ar);
		if ($first_option_blank === 1) {
			$select_type_select .= "<option value=''></option>";
		} // end if
		for ($i=0; $i<$count_temp; $i++) {
			$select_type_select .= "<option value='".$operators_ar[$i]."'>".$normal_messages_ar[$operators_ar[$i]]."</option>";
		} // end for
		$select_type_select .= "</select>";
	} // end if
	else { // just an hidden
		$select_type_select .= "<input type='hidden' name='".$field_name.$select_type_select_suffix."' value='".$operators_ar[0]."'>";
	}

	return $select_type_select;
} // end function build_select_type_select

function check_required_fields($fields_labels_ar)
// goal: check if the user has filled all the required fields
// input: all the fields values ($_POST), $_FILES (for uploaded files) and the array containing infos about fields ($fields_labels_ar)
// output: $check, set to 1 if the check is ok, otherwise 0
{
	global $null_checkbox_prefix;
	$i =0;
	$check = 1;
	$count_temp = count($fields_labels_ar);
	while ($i<$count_temp and $check == 1) {
		if ($fields_labels_ar[$i]["required_field"] == "1" and $fields_labels_ar[$i]["present_insert_form_field"] == "1") {
			$field_name_temp = $fields_labels_ar[$i]["name_field"];

			if (isset($_POST[$null_checkbox_prefix.$field_name_temp]) && $_POST[$null_checkbox_prefix.$field_name_temp] === '1') { // NULL checkbox selected
				$check = 0;
			} // end if
			else {
				switch($fields_labels_ar[$i]["type_field"]) {
					case "select_single":
						if ($fields_labels_ar[$i]["other_choices_field"] == "1" and $_POST[$field_name_temp] == "......") {
							$field_name_other_temp = $field_name_temp."_other____";
							if ($_POST["$field_name_other_temp"] == "") {
								$check = 0;
							} // end if
						} // end if
						else {
							if ($_POST[$field_name_temp] == "") {
								$check = 0;
							} // end if
						} // end else
						break;
					default:
						if ($_POST[$field_name_temp] == $fields_labels_ar[$i]["prefix_field"]) {
							$_POST[$field_name_temp] = "";
						} // end if
						if ($_POST[$field_name_temp] == "") {
							$check = 0;
						} // end if
						break;
				} // end switch
			} // end else
		} // end if
		$i++;
	} // end while
	return $check;
} // end function check_required_fields

function check_length_fields($fields_labels_ar)
// goal: check if the text, password, textarea, rich_editor, select_single, select_multiple_checkbox, select_multiple_menu fields contains too much text
// input: all the fields values ($_POST) and the array containing infos about fields ($fields_labels_ar)
// output: $check, set to 1 if the check is ok, otherwise 0
{
	$i =0;
	$check = 1;
	$count_temp = count($fields_labels_ar);
	while ($i<$count_temp and $check == 1) {
		$field_name_temp = $fields_labels_ar[$i]["name_field"];
		// I use isset for select_multiple because could be unset
		if ($fields_labels_ar[$i]["maxlength_field"] != "" && isset($_POST[$field_name_temp])) {
			switch($fields_labels_ar[$i]["type_field"]) {
				case "text":
				case "textarea":
					if (strlen($_POST[$field_name_temp]) > $fields_labels_ar[$i]["maxlength_field"]) {
						$check = 0;
					} // end if
					break;
				case "select_single":
					if ($fields_labels_ar[$i]["other_choices_field"] == "1" and $_POST[$field_name_temp] == "......") {
						$field_name_other_temp = $field_name_temp."_other____";
						if (strlen($_POST[$field_name_other_temp]) > $fields_labels_ar[$i]["maxlength_field"]) {
							$check = 0;
						} // end if
					} // end if
					else {
						if (strlen($_POST[$field_name_temp]) > $fields_labels_ar[$i]["maxlength_field"]) {
							$check = 0;
						} // end if
					} // end else
					break;
			} // end switch
		} // end if
		$i++;
	} // end while
	return $check;
} // end function check_length_fields

function check_fields_types($fields_labels_ar, &$content_error_type)
// goal: check if the user has well filled the form, according to the type of the field (e.g. no numbers in alphabetic fields, emails and urls correct)
// input: all the fields values ($_POST) and the array containing infos about fields ($fields_labels_ar), &$content_error_type, a string that change according to the error made (alphabetic, numeric, email, phone, url....)
// output: $check, set to 1 if the check is ok, otherwise 0
{
	global $year_field_suffix, $month_field_suffix, $day_field_suffix, $null_checkbox_prefix;

	$i =0;
	$check = 1;
	$count_temp = count($fields_labels_ar);
	while ($i<$count_temp and $check == 1) {
		$field_name_temp = $fields_labels_ar[$i]["name_field"];

		if (isset($_POST[$null_checkbox_prefix.$field_name_temp]) && $_POST[$null_checkbox_prefix.$field_name_temp] === '1') { // NULL checkbox selected
			$check = 1;
		} // end if
		elseif (isset($_POST[$field_name_temp])) { // otherwise it has not been filled
			if ($_POST[$field_name_temp] == $fields_labels_ar[$i]["prefix_field"]) {
				$_POST[$field_name_temp] = "";
			} // end if
			if ($fields_labels_ar[$i]["type_field"] == "select_single" && $fields_labels_ar[$i]["other_choices_field"] == "1" and $_POST[$field_name_temp] == "......") { // other field filled
				$field_name_temp = $field_name_temp."_other____";
			} // end if
			if (($fields_labels_ar[$i]["type_field"] == "text" || $fields_labels_ar[$i]["type_field"] == "textarea" ||  $fields_labels_ar[$i]["type_field"] == "select_single") and $fields_labels_ar[$i]["present_insert_form_field"] == "1" and $_POST[$field_name_temp] != "") {

				switch ($fields_labels_ar[$i]["content_field"]) {
					case "alphabetic":
						if (contains_numerics($_POST[$field_name_temp])) {
							$check = 0;
							$content_error_type = $fields_labels_ar[$i]["content_field"];
						} // end if
						break;
					case "numeric":
						if (!is_numeric($_POST[$field_name_temp])) {
							$check = 0;
							$content_error_type = $fields_labels_ar[$i]["content_field"];
						} // end if
						break;
					case "email":
						if (!is_valid_email($_POST[$field_name_temp])) {
							$check = 0;
							$content_error_type = $fields_labels_ar[$i]["content_field"];
						} // end if
						break;
					case "url":
						if (!is_valid_url($_POST[$field_name_temp])) {
							$check = 0;
							$content_error_type = $fields_labels_ar[$i]["content_field"];
						} // end if
						break;
				} // end switch
			} // end if
		} // end elseif
		$i++;
	} // end while
	return $check;
} // end function check_fields_types

function build_select_duplicated_query($table_name, $fields_labels_ar, &$string1_similar_ar, &$string2_similar_ar)
// goal: build the select query to select the record that can be similar to the record inserted
// input: all the field values ($_POST), $table_name, $fields_labels_ar, &$string1_similar_ar, &$string2_similar_ar (the two array that will contain the similar string found)
// output: $sql, the sql query
// global $percentage_similarity, the percentage after that two strings are considered similar, $number_duplicated_records, the maximum number of records to be displayed as duplicated
{
	global $percentage_similarity, $number_duplicated_records, $db, $enable_authentication, $enable_browse_authorization, $current_user, $null_checkbox_prefix;

	// get the unique key of the table
	$unique_field_name = $db->get_primary_key($table_name);

	if ($unique_field_name != "" && $unique_field_name != NULL) { // a unique key exists, ok, otherwise I'm not able to select the similar record, which field should I use to indicate it?

		$sql = "";
		$sql_select_all = "";
		$sql_select_all = "SELECT `$unique_field_name`, "; // this is used to select the records to check similiarity
		//$select = "SELECT * FROM `$table_name`";
		$select = build_select_part($fields_labels_ar, $table_name);
		$where_clause = "";

		// build the sql_select_all clause
		$j = 0;
		// build the $fields_to_check_ar array, containing the field to check for similiarity
		$fields_to_check_ar = array();
		$count_temp = count($fields_labels_ar);
		for ($i=0; $i<$count_temp; $i++) {
			if ($fields_labels_ar[$i]["check_duplicated_insert_field"] == "1") {
				if (!empty(${$fields_labels_ar[$i]["name_field"]})) {
					$fields_to_check_ar[$j] = $fields_labels_ar[$i]["name_field"]; // I put in the array only if the field is non empty, otherwise I'll check it even if I don't need it
				} // end if
				$sql_select_all .= "`".$fields_labels_ar[$i]["name_field"]."`, ";
				$j++;
			} // end if
		} // end for
		$sql_select_all = substr ($sql_select_all, 0, -2); // delete the last ", "
		$sql_select_all .= " FROM `$table_name`";

		if ($enable_authentication === 1 && $enable_browse_authorization === 1) { // $ID_user_field_name = '$current_user' where clause part in order to select only the records the current user owns
			$ID_user_field_name = get_ID_user_field_name($fields_labels_ar);

			if ($ID_user_field_name !== false) { // no ID_user fields available, don't use authorization
				if ($where_clause === '') {
					$sql_select_all .= " WHERE `$table_name`.`$ID_user_field_name` = '".$db->escape_string($current_user)."'";
				} // end if
			} // end if
		} // end if
		// end build the sql_select_all clause

		// at the end of the above procedure I'll have, for example, "select ID, name, email from table" if ID is the unique key, name and email are field to check

		// execute the select query
		$res_contacts = $db->send_query($sql_select_all);

		if ($db->db_num_rows($res_contacts) > 0) {
			while ($contacts_row = $db->db_fetch_row($res_contacts)) { // *A* for each record in the table
				$count_temp = count($fields_to_check_ar);
				for ($i=0; $i<$count_temp; $i++) { // *B* and for each field the user has inserted
					if (!isset($_POST[$null_checkbox_prefix.$fields_to_check_ar[$i]]) || $_POST[$null_checkbox_prefix.$fields_to_check_ar[$i]] !== '1') { // NULL checkbox  is not selected
						$z=0;
						$found_similarity =0; // set to 1 when a similarity is found, so that it's possible to exit the loop (if I found that a record is similar it doesn't make sense to procede with other fields of the same record)

						// *C* check if the field inserted are similiar to the other fields to be checked in this record (*A*)
						$count_temp_2 = count($fields_to_check_ar);
						while ($z<$count_temp_2 and $found_similarity == 0) {
							$string1_temp = $_POST[$fields_to_check_ar[$i]]; // the field the user has inserted
							$string2_temp = $contacts_row[$z+1]; // the field of this record (*A*); I start with 1 because 0 is alwais the unique field (e.g. ID, name, email)

							similar_text(strtolower($string1_temp), strtolower($string2_temp), $percentage);
							if ($percentage >= $percentage_similarity) { // the two strings are similar
								$where_clause .= "`$unique_field_name` = '".$contacts_row[0]."' OR ";
								$found_similarity = 1;
								$string1_similar_ar[]=$string1_temp;
								$string2_similar_ar[]=$string2_temp;
							} // end if the two strings are similar
							$z++;
						} // end while
					} // end if
				} // end for loop for each field to check
			} // end while loop for each record
		} // end if ($db->db_num_rows($res_contacts) > 0)
		$db->free_result($res_contacts);

		$where_clause = substr($where_clause, 0, -4); // delete the last " OR "
		if ($where_clause != "") {
			$sql = $select." WHERE ".$where_clause;
		} // end if
		else { // no duplication
			$sql = "";
		} // end else*
	} // end if if ($unique_field_name != "")
	else { // no unique keys
		$sql = "";
	} // end else
	return $sql;
} // end function build_select_duplicated_query

function build_insert_duplication_form($fields_labels_ar, $table_name)
// goal: build a tabled form composed by two buttons: "Insert anyway" and "Go back"
// input: all the field values ($_POST), $fields_labels_ar, $table_name
// output: $form, the form
// global $submit_buttons_ar, the array containing the caption on submit buttons
{
	global $submit_buttons_ar, $dadabik_main_file, $year_field_suffix, $month_field_suffix, $day_field_suffix;

	$form = "";

	$form .= "<table><tr><td>";

	$form .= "<form action='$dadabik_main_file?table_name=".urlencode($table_name)."&function=insert&insert_duplication=1' method='post'>";

	$count_temp = count($fields_labels_ar);
	for ($i=0; $i<$count_temp; $i++) {

		$field_name_temp = $fields_labels_ar[$i]["name_field"];

		if ($fields_labels_ar[$i]["present_insert_form_field"] == "1") {

			switch ($fields_labels_ar[$i]["type_field"]) {
				case "select_single":
ob_start();
$time = date("j.n.Y - G:i");
echo "\n$time\n";
var_dump($field_name_temp);
echo "\n";
var_dump($_POST);
$buffer = ob_get_flush();
file_put_contents("/tmp/variable.txt", $buffer, FILE_APPEND);
					if ($fields_labels_ar[$i]["other_choices_field"] == "1" and $_POST[$field_name_temp] == "......") { // other choice filled
						$field_name_other_temp = $field_name_temp."_other____";
						$form .= "<input type='hidden' name='$field_name_temp' value='".htmlspecialchars(stripslashes($_POST[$field_name_temp]))."'>";
						$form .= "<input type='hidden' name='$field_name_other_temp' value='".htmlspecialchars(stripslashes($_POST[$field_name_other_temp]))."'>";
					} // end if
					else {
						$form .= "<input type='hidden' name='$field_name_temp' value='".htmlspecialchars(stripslashes($_POST[$field_name_temp]))."'>";
					} // end else
					break;
				default: // textual field
					if ($_POST[$fields_labels_ar[$i]["name_field"]] == $fields_labels_ar[$i]["prefix_field"]) { // the field contain just the prefix
						$_POST[$fields_labels_ar[$i]["name_field"]] = "";
					} // end if

					$form .= "<input type='hidden' name='$field_name_temp' value='".htmlspecialchars(stripslashes($_POST[$fields_labels_ar[$i]["name_field"]]))."'>";
					break;
			} // end switch
		} // end if
	} // end for
	$form .= "<input type='submit' value='".$submit_buttons_ar["insert_anyway"]."'></form>";

	$form .= "</td><td>";

	$form .= "</td></tr></table>";

	return $form;
} // end function build_insert_duplication_form

function build_change_table_form()
// goal: build a form to choose the table
// input:
// output: the listbox
{
	global $table_name, $autosumbit_change_table_control, $dadabik_main_file;

	$change_table_form = '<form method="get" action="'.$dadabik_main_file.'" name="change_table_form">';
	if ( $autosumbit_change_table_control == 0) {
		$change_table_form .= '<input type="submit" class="button_change_table" value="'.$submit_buttons_ar["change_table"].'">';
	} // end if
	$change_table_form .= "<select name='table_name' class='select_change_table'";
	if ( $autosumbit_change_table_control == 1) {
		$change_table_form .= " onchange=\"javascript:document.change_table_form.submit()\"";
	}
	$change_table_form .= ">\n";

	$only_include_allowed = 1;
	$allowed_table_infos_ar = build_installed_table_infos_ar($only_include_allowed, 1);

	$count_temp = count($allowed_table_infos_ar);
	for($i=0; $i<$count_temp; $i++) {
		$change_table_form .= "<option value='".htmlspecialchars($allowed_table_infos_ar[$i]['name_table'])."'";
		if ($table_name == $allowed_table_infos_ar[$i]['name_table']) {
			$change_table_form .= " selected";
		}
		$change_table_form .= ">".$allowed_table_infos_ar[$i]['alias_table']."</option>\n";
	} // end for
	$change_table_form .= "</select>\n";
	$change_table_form .= "</form>\n";

	if ($count_temp == 1) {
		return "";
	} // end if
	else {
		return $change_table_form;
	} // end else

} // end function build_change_table_form

function build_change_table_select()
// goal: build a select to choose the table
// output: $select, the html select
{
	global $table_name, $autosumbit_change_table_control;
	$change_table_select = "";
	$change_table_select .= "<select name='table_name' class='select_change_table'";
	if ( $autosumbit_change_table_control == 1) {
		$change_table_select .= " onchange=\"javascript:document.change_table_form.submit()\"";
	}
	$change_table_select .= ">";

	// get the array containing the names of the tables installed
	$tables_names_ar = build_tables_names_array(0, 1, 1);

	$count_temp = count($tables_names_ar);
	for($i=0; $i<$count_temp; $i++) {
		$change_table_select .= "<option value='".htmlspecialchars($tables_names_ar[$i])."'";
		if ($table_name == $tables_names_ar[$i]) {
			$change_table_select .= " selected";
		}
		$change_table_select .= ">".$tables_names_ar[$i]."</option>";
	} // end for
	$change_table_select .= "</select>";
	if ($count_temp == 1) {
		return "";
	} // end if
	else {
		return $change_table_select;
	} // end else
} // end function build_change_table_select

function table_contains($table_name, $field_name, $value)
// goal: check if a table contains a record which has a field set to a specified value
// input: $table_name, $field_name, $value
// output: true or false
{
	global $db;
	$sql = "SELECT COUNT(`$field_name`) FROM `$table_name` WHERE `$field_name` = '$value'";
	$res_count = $db->send_query($sql);
	$count_row = $db->db_fetch_row($res_count);
	if ($count_row[0] > 0) {
		return true;
	} // end if
	return false;
} // end function table_contains

function insert_record($fields_labels_ar, $table_name, $table_internal_name)
// goal: insert a new record in the table
// input $_FILES (needed for the name of the files), $_POST (the array containing all the values inserted in the form), $fields_labels_ar, $table_name, $table_internal_name
// output: nothing
{
	global $db, $current_user, $null_checkbox_prefix, $year_field_suffix, $month_field_suffix, $day_field_suffix;

	$uploaded_file_names_count = 0;

	// build the insert statement
	/////////////////////////////
	$sql = "";
	$sql .= "INSERT INTO `$table_name` (";

	$count_temp=count($fields_labels_ar);
	for ($i=0; $i<$count_temp; $i++) {
		if ($fields_labels_ar[$i]["present_insert_form_field"] == "1" || $fields_labels_ar[$i]["type_field"] == "insert_timestamp" || $fields_labels_ar[$i]["type_field"] == "update_timestamp" || $fields_labels_ar[$i]["type_field"] == "ID_user") { // if the field is in the form or need to be inserted because it's an insert data, an update data, an ID_user or a unique_ID
			$sql .= "`".$fields_labels_ar[$i]["name_field"]."`, "; // add the field name to the sql statement
		} // end if
	} // end for

	$sql = substr($sql, 0, (strlen($sql)-2));

	$sql .= ") VALUES (";

	for ($i=0; $i<$count_temp; $i++) {
		if ($fields_labels_ar[$i]["present_insert_form_field"] == "1") { // if the field is in the form

			$name_field_temp = $fields_labels_ar[$i]["name_field"];

			switch ($fields_labels_ar[$i]["type_field"]) {
				case "select_single":
					$field_name_temp = $fields_labels_ar[$i]["name_field"];
					$field_name_other_temp = $fields_labels_ar[$i]["name_field"]."_other____";

					if ($fields_labels_ar[$i]["other_choices_field"] == "1" and $_POST[$field_name_temp] == "......" and $_POST[$field_name_other_temp] != "") { // insert the "other...." choice
						$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
						if ($primary_key_field_field != "") {

							$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $fields_labels_ar[$i]["linked_fields_field"]);

							$primary_key_field_field = insert_other_field($fields_labels_ar[$i]["primary_key_table_field"], $linked_fields_ar[0], $_POST[$field_name_other_temp]);
							$sql .= "'".$primary_key_field_field."', "; // add the last ID inserted to the sql statement
						} // end if ($foreign_key_temp != "")
						else { // no foreign key field
							$sql .= "'".$_POST[$field_name_other_temp]."', "; // add the field value to the sql statement
							if ( strpos($fields_labels_ar[$i]["select_options_field"], $fields_labels_ar[$i]["separator_field"].$_POST[$field_name_other_temp].$fields_labels_ar[$i]["separator_field"] === false) ) { // the other field inserted is not already present in the $fields_labels_ar[$i]["select_options_field"] so we have to add it

								update_options($fields_labels_ar[$i], $field_name_temp, $_POST[$field_name_other_temp]);

								// re-get the array containg label ant other information about the fields changed with the above instruction
								$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");
							} // end if
						} // end else
					} // end if
					else {
						$sql .= "'".$_POST[$field_name_temp]."', "; // add the field value to the sql statement
					} // end else
					break;
				default: // textual field
					if ($_POST[$fields_labels_ar[$i]["name_field"]] == $fields_labels_ar[$i]["prefix_field"]) { // the field contain just the prefix
						$_POST[$fields_labels_ar[$i]["name_field"]] = "";
					} // end if
					$sql .= "'".$_POST[$fields_labels_ar[$i]["name_field"]]."', "; // add the field value to the sql statement
					break;
			} // end switch
		} // end if
		elseif ($fields_labels_ar[$i]["type_field"] == "insert_timestamp" or $fields_labels_ar[$i]["type_field"] == "update_timestamp") { // if the field is not in the form but need to be inserted because it's an update data
			$timestamp = time();
			$sql .= "'".$timestamp."', "; // add the field name to the sql statement

		} // end elseif
		elseif ($fields_labels_ar[$i]["type_field"] == "ID_user") { // if the field is not in the form but need to be inserted because it's an ID_user
			$sql .= "'".$current_user."', "; // add the field name to the sql statement
		} // end elseif
	} // end for

	$sql = substr($sql, 0, (strlen($sql)-2));

	$sql .= ")";
	/////////////////////////////
	// end build the insert statement

	display_sql($sql);

	// insert the record
	$db->send_query($sql);
} // end function insert_record

function update_record($fields_labels_ar, $table_name, $table_internal_name, $where_field, $where_value)
// goal: insert a new record in the main database
// input $_FILES (needed for the name of the files), $_POST (the array containing all the values inserted in the form, $fields_labels_ar, $table_name, $table_internal_name, $where_field, $where_value
// output: nothing
{
	global $null_checkbox_prefix, $year_field_suffix, $month_field_suffix, $day_field_suffix, $db;
	$uploaded_file_names_count = 0;

	$field_to_check = "present_insert_form_field";

	// build the update statement
	/////////////////////////////
	$where = "$where_field = '$where_value'";
	$archive_type = "datadmin_update";
	$db->archive_table_row($table_name, $where, $archive_type);
	$sql = "";
	$sql .= "UPDATE `$table_name` SET ";

	$count_temp = count($fields_labels_ar);
	for ($i=0; $i<$count_temp; $i++) {
		$field_name_temp = $fields_labels_ar[$i]["name_field"];
		if ($fields_labels_ar[$i][$field_to_check] == "1" or $fields_labels_ar[$i]["type_field"] == "update_date" or $fields_labels_ar[$i]["type_field"] == "update_timestamp") { // if the field is in the form or need to be inserted because it's an update data

			switch ($fields_labels_ar[$i]["type_field"]) {
				case "update_timestamp":
					$sql .= "`$field_name_temp` = "; // add the field name to the sql statement
					$timestamp = time();
					$sql .= "'".$timestamp."', "; // add the field name to the sql statement
					break;
				case "select_single":
					$field_name_other_temp = $field_name_temp."_other____";

					if ($fields_labels_ar[$i]["other_choices_field"] == "1" and $_POST[$field_name_temp] == "......" and $_POST[$field_name_other_temp] != "") { // insert the "other...." choice

						$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
						if ($primary_key_field_field != "") {
							$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $fields_labels_ar[$i]["linked_fields_field"]);

							$primary_key_field_field = insert_other_field($fields_labels_ar[$i]["primary_key_table_field"], $linked_fields_ar[0], $_POST[$field_name_other_temp]);
							$sql .= "`".$field_name_temp."` = "; // add the field name to the sql statement
							$sql .= "'".$primary_key_field_field."', "; // add the field value to the sql statement
						} // end if ($foreign_key_temp != "")
						else { // no foreign key field
							$sql .= "`".$field_name_temp."` = "; // add the field name to the sql statement
							$sql .= "'".$_POST[$field_name_other_temp]."', "; // add the field value to the sql statement
							if (strpos($fields_labels_ar[$i]["select_options_field"], $fields_labels_ar[$i]["separator_field"].$_POST[$field_name_other_temp].$fields_labels_ar[$i]["separator_field"]) === false) { // the other field inserted is not already present in the $fields_labels_ar[$i]["select_options_field"] so we have to add it

								update_options($fields_labels_ar[$i], $field_name_temp, $_POST[$field_name_other_temp]);

								// re-get the array containg label ant other information about the fields changed with the above instruction
								$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");
							} // end if
						} // end else
					} // end if
					else {
						$sql .= "`".$field_name_temp."` = "; // add the field name to the sql statement
						$sql .= "'".$_POST[$field_name_temp]."', "; // add the field value to the sql statement
					} // end else

					break;
				default: // textual field
					$sql .= "`".$field_name_temp."` = "; // add the field name to the sql statement
					$sql .= "'".$_POST[$field_name_temp]."', "; // add the field value to the sql statement
					break;
			} // end switch
		} // end if
	} // end for
	$sql = substr($sql, 0, -2); // delete the last two characters: ", "
	$sql .= " WHERE `".$where_field."` = '".$where_value."'";
	/////////////////////////////
	// end build the update statement

	display_sql($sql);

	// update the record
	$db->send_query($sql);
} // end function update_record

function build_where_clause($fields_labels_ar, $table_name)
// goal: build the where clause of a select sql statement e.g. "field_1 = 'value' AND field_2 LIKE '%value'"
// input: $_POST, $fields_labels_ar, $table_name
{
	global $select_type_select_suffix, $year_field_suffix, $month_field_suffix, $day_field_suffix;

	$where_clause = "";

	$count_temp = count($fields_labels_ar);
	// build the where clause
	for ($i=0; $i<$count_temp; $i++) {
		$field_type_temp = $fields_labels_ar[$i]["type_field"];
		$field_name_temp = $fields_labels_ar[$i]["name_field"];
		$field_separator_temp = $fields_labels_ar[$i]["separator_field"];
		$field_select_type_temp = $fields_labels_ar[$i]["select_type_field"];

		if ($fields_labels_ar[$i]["present_search_form_field"] == "1") {
			if ($_POST[$field_name_temp.$select_type_select_suffix] === 'is_empty') { // is_empty listbox option selected
				$where_clause .= "`$table_name`.`$field_name_temp`  =''"; // add the = '' value to the sql statement

				$where_clause .= " ".$_POST["operator"]." ";
			} // end if
			else {
				switch ($field_type_temp) {
					case "insert_timestamp":
					case "update_timestamp":
						$select_type_field_name_temp = $field_name_temp.$select_type_select_suffix;
						if ($_POST[$select_type_field_name_temp] != "") {
							$year_field = $field_name_temp.$year_field_suffix;
							$month_field = $field_name_temp.$month_field_suffix;
							$day_field = $field_name_temp.$day_field_suffix;
							$day_beginning = mktime(0, 0, 0, $_POST[$month_field], $_POST[$day_field], $_POST[$year_field]);
							$day_end = mktime(24, 0, 0, $_POST[$month_field], $_POST[$day_field], $_POST[$year_field]);
							switch ($_POST[$select_type_field_name_temp]) {
								case "is_equal":
									$where_clause .= "`$table_name`.`$field_name_temp` >= '$day_beginning' AND `$table_name`.`$field_name_temp` <= '$day_end'";
									break;
								case "greater_than":
									$where_clause .= "`$table_name`.`$field_name_temp` >= '$day_beginning'";
									break;
								case "less_then":
									$where_clause .= "`$table_name`.`$field_name_temp` <= '$day_end'";
									break;
							} // end switch
							//} // end else
							$where_clause .= " ".$_POST["operator"]." ";
						} // end if
						break;
					default:
						$select_type_field_name_temp = $field_name_temp.$select_type_select_suffix;
						if ($_POST[$field_name_temp] != "") { // if the user has filled the field
							switch ($_POST[$select_type_field_name_temp]) {
								case "is_equal":
									$where_clause .= "`$table_name`.`$field_name_temp` = '".$_POST[$field_name_temp]."'";
									break;
								case "contains":
									$where_clause .= "`$table_name`.`$field_name_temp` LIKE '%".$_POST[$field_name_temp]."%'";
									break;
								case "starts_with":
									$where_clause .= "`$table_name`.`$field_name_temp` LIKE '".$_POST[$field_name_temp]."%'";
									break;
								case "ends_with":
									$where_clause .= "`$table_name`.`$field_name_temp` LIKE '%".$_POST[$field_name_temp]."'";
									break;
								case "greater_than":
									$where_clause .= "`$table_name`.`$field_name_temp` > '".$_POST[$field_name_temp]."'";
									break;
								case "less_then":
									$where_clause .= "`$table_name`.`$field_name_temp` < '".$_POST[$field_name_temp]."'";
									break;
							} // end switch
							//} // end else
							$where_clause .= " ".$_POST["operator"]." ";
						} // end if
						break;
				} //end switch
			} // end else
		} // end if
	} // end for ($i=0; $i<count($fields_labels_ar); $i++)

	if ($where_clause !== '') {
		$where_clause = substr($where_clause, 0, -(strlen($_POST["operator" ])+2)); // delete the last " and " or " or "
	} // end if

	return $where_clause;
} // end function build_where_clause

function get_field_correct_displaying($field_value, $field_type, $field_content, $display_mode)
// get the correct mode to display a field, according to its content (e.g. format data, display select multiple in different rows without separator and so on
// input: $field_value, $field_type, $field_content, $display_mode (results_table|details_page|plain_text)
// output: $field_to_display, the field value ready to be displayed
// global: $word_wrap_col, the coloumn at which a string will be wrapped in the results
{
	global $word_wrap_col, $enable_word_wrap_cut, $null_word;
	$field_to_display = "";

	if (is_null($field_value)) {
		$field_to_display = $null_word;
	} // end if
	else {
		switch ($field_type) {
			case "insert_timestamp":
			case "update_timestamp":
				if (substr($field_value, 0, 10) !== '0000-00-00') {
					$unix_timestamp = strtotime($field_value);
					if ($display_mode === 'plain_text') {
						$field_to_display = date ("d.m.Y  H:i:s", $unix_timestamp);
					} // end if
					else {
						$field_to_display = date ("d.m.Y", $unix_timestamp) . " &nbsp; " . date ("H:i:s", $unix_timestamp);
					} // end else
				} else {
					$field_to_display = " 0 ";
				}
				break;

			default: // e.g. text, textarea and select sinlge
				if ($display_mode === 'plain_text') {
					$field_to_display = $field_value;
				} // end if
				else {
					if ($field_content !== 'html') {
						$field_value = htmlspecialchars($field_value);

						if ( $display_mode == "results_table") {
							$displayed_part = wordwrap($field_value, $word_wrap_col, "\n", $enable_word_wrap_cut);
						} // end if
						else {
							$displayed_part = $field_value;
						} // end else

					} // end if
					else {
						$displayed_part = $field_value;
					} // end else

					if ($field_content == "email" && $field_value != "") {
						$field_to_display = "<a href='mailto:".$field_value."'>".$displayed_part."</a>";
					} // end if
					elseif ($field_content == "url" && $field_value != "") {
						$field_to_display = "<a href='".$field_value."'>".$displayed_part."</a>";
					} // end elseif
					elseif (substr($displayed_part, 0, 8) !== "restore_") {
						$field_to_display = nl2br($displayed_part);
					} else {
						$field_to_display = $displayed_part;
					}
				} // end else
				break;
		} // end switch
	} // end else
	return $field_to_display;
} // function get_field_correct_displaying

function get_field_correct_csv_displaying($field_value)
// get the correct mode to display a field in a csv, according to its content (e.g. format data, display select multiple in different rows without separator and so on
// input: $field_value, $field_type, $field_content
// output: $field_to_display, the field value ready to be displayed
{
	$field_to_display = str_replace("\r", '', $field_value);
	return $field_to_display;
} // function get_field_correct_csv_displaying

function build_results_table($fields_labels_ar, $table_name, $res_records, $results_type, $action, $where_clause, $page, $order, $order_type)
// goal: build an HTML table for basicly displaying the results of a select query
// input: $table_name, $res_records, the results of the query, $results_type (search, possible_duplication......), $action (e.g. index.php), $where_clause, $page (o......n), $order, $order_type
// output: $results_table, the HTML results table
// global: $submit_buttons_ar, the array containing the values of the submit buttons, $edit_target_window, the target window for edit/details (self, new......), $delete_icon, $edit_icon, $details_icon (the image files to use as icons), $enable_edit, $enable_delete, $enable_details (whether to enable (1) or not (0) the edit, delete and details features
{
	global $submit_buttons_ar, $normal_messages_ar, $edit_target_window, $delete_icon, $edit_icon, $details_icon, $enable_edit, $enable_delete, $enable_details, $db, $ask_confirmation_delete, $word_wrap_col, $word_wrap_fix_width, $alias_prefix, $dadabik_main_file, $enable_row_highlighting, $prefix_internal_table, $current_user_is_editor, $current_user, $lang;

	$function = "search";

	$unique_field_name = $db->get_primary_key($table_name);

	// build the results HTML table
	///////////////////////////////

	$results_table = "";
	$results_table .= "<table class='results'>";

	// build the table heading
	$results_table .= "<tr>";


	$results_table .= "<th class='results'>&nbsp;</th>"; // skip the first column for edit, delete and details

	$count_temp = count($fields_labels_ar);
	for ($i=0; $i<$count_temp; $i++) {
		if ($fields_labels_ar[$i]["present_results_search_field"] == "1") { // the user want to display the field in the basic search results page

			$label_to_display = $fields_labels_ar[$i]["label_" . $lang . "_field"];

			if ($word_wrap_fix_width === 1) {

				$spaces_to_add = $word_wrap_col-strlen($label_to_display);

				if ( $spaces_to_add > 0) {
					for ($j=0; $j<$spaces_to_add; $j++) {
						$label_to_display .= '&nbsp;';
					}
				}
			} // end if

			$results_table .= "<th class='results'>";

			$field_is_current_order_by = 0;

			if ( $results_type == "search") {
				if ($order != $fields_labels_ar[$i]["name_field"]) { // the results are not ordered by this field at the moment
					$link_class="order_link";
					$new_order_type = "ASC";
				}
				else {
					$field_is_current_order_by = 1;
					$link_class="order_link_selected";
					if ( $order_type == "DESC") {
						$new_order_type = "ASC";
					}
					else {
						$new_order_type = "DESC";
					}
				} // end elseif ($order != $fields_labels_ar[$i]["name_field"])

				$results_table .= "<a class='$link_class' href='$action?table_name=". urlencode($table_name)."&function=$function&where_clause=".urlencode($where_clause)."&page=$page&order=".urlencode($fields_labels_ar[$i]["name_field"])."&amp;order_type=$new_order_type'>";

				if ($field_is_current_order_by === 1) {
					if ($order_type === 'ASC') {
						$results_table .= '<span class="arrow">&uarr;</span> ';
					} // end if
					else {
						$results_table .= '<span class="arrow">&darr;</span> ';
					} // end if
				} // end if

				$results_table .= $label_to_display."</a></th>"; // insert the linked name of the field in the <th>
			}
			else {
				$results_table .= $label_to_display."</th>"; // insert the  name of the field in the <th>
			} // end if

		} // end if
	} // end for
	$results_table .= "</tr>";

	$tr_results_class = 'tr_results_1';
	$td_controls_class = 'controls_1';

	// build the table body
	while ($records_row = $db->db_fetch_assoc($res_records)) {

		if ($tr_results_class === 'tr_results_1') {
			$td_controls_class = 'controls_2';
			$tr_results_class = 'tr_results_2';
		} // end if
		else {
			$td_controls_class = 'controls_1';
			$tr_results_class = 'tr_results_1';
		} // end else

		// set where clause for delete and update
		///////////////////////////////////////////
		if (!empty($unique_field_name)) { // exists a unique number
			$where_field = $unique_field_name;
			$where_value = $records_row[$unique_field_name];
		} // end if
		///////////////////////////////////////////
		// end build where clause for delete and update

		if ($enable_row_highlighting === 1) {
			$results_table .= "<tr class='".$tr_results_class."' onmouseover=\"if (this.className!='tr_highlighted_onclick') {this.className='tr_highlighted_onmouseover'}\" onmouseout=\"if (this.className!='tr_highlighted_onclick') {this.className='".$tr_results_class."'}\" onclick=\"if (this.className == 'tr_highlighted_onclick') { this.className='".$tr_results_class."';}else { this.className='tr_highlighted_onclick';}\">";
		} // end if
		else {
			$results_table .= "<tr class='".$tr_results_class."'>";
		} // end else

		$results_table .= "<td class='".$td_controls_class."'>";

		if (!empty($unique_field_name) and ($results_type == "search" or $results_type == "possible_duplication")) { // exists a unique number: edit, delete, details make sense
			$show_edit_delete = "1";
			if ($current_user_is_editor !== 1 && ($enable_edit == "1" || $enable_delete == "1")) {
				if ($records_row['username'] !== $current_user) {
					$show_edit_delete = "0";
				}
			}
			if ($enable_edit == "1" && $show_edit_delete == "1") { // display the edit icon
				$results_table .= "<a class='onlyscreen' target='_".$edit_target_window."' href='".$dadabik_main_file."?table_name=".urlencode($table_name)."&function=edit&where_field=".urlencode($where_field)."&where_value=".urlencode($where_value)."'><img src='".$edit_icon."' alt='".$submit_buttons_ar["edit"]."' title='".$submit_buttons_ar["edit"]."'></a>";
			} // end if

			if ($enable_delete == "1" && $show_edit_delete == "1") { // display the delete icon
				$results_table .= "<a class='onlyscreen'";
				if ( $ask_confirmation_delete == 1) {
					$results_table .= " onclick=\"if (!confirm('".str_replace('\'', '\\\'', $normal_messages_ar['confirm_delete?'])."')) { return false;}\"";
				}
				$results_table .= " href='".$dadabik_main_file."?table_name=".urlencode($table_name)."&function=delete&where_field=".urlencode($where_field)."&where_value=".urlencode($where_value)."'><img src='".$delete_icon."' alt='".$submit_buttons_ar["delete"]."' title='".$submit_buttons_ar["delete"]."'>";
			} // end if

			if ($enable_details == "1") { // display the details icon
				$results_table .= "<a class='onlyscreen' target='_".$edit_target_window."' href='".$dadabik_main_file."?table_name=".urlencode($table_name)."&function=details&where_field=".urlencode($where_field)."&where_value=".urlencode($where_value)."'><img src='".$details_icon."' alt='".$submit_buttons_ar["details"]."' title='".$submit_buttons_ar["details"]."'></a>";
			} // end if

		} // end if
		$results_table .= "</td>";
		for ($i=0; $i<$count_temp; $i++) {
			if ($fields_labels_ar[$i]["present_results_search_field"] == "1") { // the user want to display the field in the search results page
				$results_table .= "<td>"; // start the cell

				$field_name_temp = $fields_labels_ar[$i]["name_field"];
				$field_type = $fields_labels_ar[$i]["type_field"];
				$field_content = $fields_labels_ar[$i]["content_field"];
				$field_separator = $fields_labels_ar[$i]["separator_field"];

				$field_values_ar = array(); // reset the array containing values to display, otherwise for each loop I have the previous values

				$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
				if (!empty($primary_key_field_field)) {
					$primary_key_table_field = $fields_labels_ar[$i]["primary_key_table_field"];
					$primary_key_db_field = $fields_labels_ar[$i]["primary_key_db_field"];
					$linked_fields_field = $fields_labels_ar[$i]["linked_fields_field"];
					$alias_suffix_field = $fields_labels_ar[$i]["alias_suffix_field"];
					$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_field);

					// get the list of all the installed tables
					$tables_names_ar = build_tables_names_array(0);

					// if the linked table is installed I can get type content and separator of the linked field
					if (in_array($primary_key_table_field, $tables_names_ar)) {
						$linked_table_installed = 1;

						$fields_labels_linked_field_ar = build_fields_labels_array($prefix_internal_table.$primary_key_table_field, "1");
					} // end if
					else {
						$linked_table_installed = 0;
					} // end else

					for ($j=0;$j<count($linked_fields_ar);$j++) {
						////*$field_values_ar[$j] = $records_row[$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];
						$field_values_ar[$j] = $records_row[$primary_key_table_field.$alias_prefix.$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];
					} // end for
				}
				else {
					//$field_value = $records_row[$field_name_temp];
					$field_values_ar[0] = $records_row[$field_name_temp];

				}

				$count_temp_2 = count($field_values_ar);
				for ($j=0; $j<$count_temp_2; $j++) {

					// if it's a linked field and the linked table is installed, get the correct $field_type $field_content $field_separator
					if ($primary_key_field_field != "" && $primary_key_field_field != NULL && $linked_table_installed === 1) {

						foreach ($fields_labels_linked_field_ar as $fields_labels_linked_field_ar_element) {
							if ($fields_labels_linked_field_ar_element['name_field'] === $linked_fields_ar[$j]) {
								$linked_field_type = $fields_labels_linked_field_ar_element['type_field'];
								$linked_field_content = $fields_labels_linked_field_ar_element['content_field'];
								$linked_field_separator = $fields_labels_linked_field_ar_element['separator_field'];
							} // end if
						} // end foreach

						reset($fields_labels_linked_field_ar);

						$field_to_display = get_field_correct_displaying($field_values_ar[$j], $linked_field_type, $linked_field_content, "results_table"); // get the correct display mode for the field
					} // end if
					else {
						$field_to_display = get_field_correct_displaying($field_values_ar[$j], $field_type, $field_content, "results_table"); // get the correct display mode for the field
					} // end else

					if (empty($field_to_display)) {
						$field_to_display = "&nbsp;";
					}
					$results_table .= $field_to_display."&nbsp;"; // at the field value to the table
				}
				$results_table = substr($results_table, 0, -6); // delete the last &nbsp;
				$results_table .= "</td>"; // end the cell
			} // end if
		} // end for

		$results_table .= "</tr>";
	} // end while
	$results_table .= "</table>";

	return $results_table;

} // end function build_results_table

function build_csv($res_records, $fields_labels_ar)
// build a csv, starting from a recordset
// input: $res_record, the recordset, $fields_labels_ar
{
	global $csv_separator, $alias_prefix, $db, $lang;
	$csv = "";
	$count_temp = count($fields_labels_ar);

	// write heading
	for ($i=0; $i<$count_temp; $i++) {
		if ( $fields_labels_ar[$i]["present_results_search_field"] == "1") {
			$csv .= "'".str_replace("'", "''", $fields_labels_ar[$i]["label_" . $lang . "_field"])."'".$csv_separator;
		}
	}
	$csv = substr($csv, 0, -1); // delete the last ","
	$csv .= "\n";

	// write other rows
	while ($records_row = $db->db_fetch_assoc($res_records)) {
		for ($i=0; $i<$count_temp; $i++) {
			if ( $fields_labels_ar[$i]["present_results_search_field"] == "1") {

				$field_name_temp = $fields_labels_ar[$i]["name_field"];
				$field_type = $fields_labels_ar[$i]["type_field"];
				$field_content = $fields_labels_ar[$i]["content_field"];
				$field_separator = $fields_labels_ar[$i]["separator_field"];
				$field_values_ar = array(); // reset the array containing values to display, otherwise for each loop I have the previous values

				$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
				if ($primary_key_field_field != "") {

					$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
					$primary_key_table_field = $fields_labels_ar[$i]["primary_key_table_field"];
					$primary_key_db_field = $fields_labels_ar[$i]["primary_key_db_field"];
					$linked_fields_field = $fields_labels_ar[$i]["linked_fields_field"];
					$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_field);
					$alias_suffix_field = $fields_labels_ar[$i]["alias_suffix_field"];

					for ($j=0;$j<count($linked_fields_ar);$j++) {
						////*$field_values_ar[$j] .= $records_row[$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];
						$field_values_ar[$j] .= $records_row[$primary_key_table_field.$alias_prefix.$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];
					} // end for
				}
				else {
					$field_values_ar[0] = $records_row[$field_name_temp];
				}
				$csv .= "'";

				$count_temp_2 = count($field_values_ar);
				for ($j=0; $j<$count_temp_2; $j++) {

					$field_to_display = get_field_correct_csv_displaying($field_values_ar[$j]);

					$csv .= str_replace("'", "''", $field_to_display)." ";
				}
				$csv = substr($csv, 0, -1); // delete the last space
			$csv .= "'".$csv_separator;
			}
		} // end for
		$csv = substr($csv, 0, -1); // delete the last ","
		$csv .= "\n";
	}
	return $csv;
} // end function build_csv

function build_details_table($fields_labels_ar, $res_details)
// goal: build an html table with details of a record
// input: $fields_labels_ar $res_details (the result of the query)
// ouptut: $details_table, the html table
{
	global $db, $alias_prefix, $prefix_internal_table, $lang;

	// build the table
	$details_table = "";

	$details_table .= "<table>";

	while ($details_row = $db->db_fetch_assoc($res_details)) { // should be just one

		$count_temp = count($fields_labels_ar);
		for ($i=0; $i<$count_temp; $i++) {
			if ($fields_labels_ar[$i]["present_details_form_field"] == "1") {
				$field_name_temp = $fields_labels_ar[$i]["name_field"];

				$field_values_ar = array(); // reset the array containing values to display, otherwise for each loop if I don't call build_linked_field_values_ar I have the previous values

				$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
				if ($primary_key_field_field != "") {
					$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
					$primary_key_table_field = $fields_labels_ar[$i]["primary_key_table_field"];
					$primary_key_db_field = $fields_labels_ar[$i]["primary_key_db_field"];
					$linked_fields_field = $fields_labels_ar[$i]["linked_fields_field"];
					$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_field);
					$alias_suffix_field = $fields_labels_ar[$i]["alias_suffix_field"];

					// get the list of all the installed tables
					$tables_names_ar = build_tables_names_array(0);

					// if the linked table is installed I can get type content and separator of the linked field
					if (in_array($primary_key_table_field, $tables_names_ar)) {
						$linked_table_installed = 1;

						$fields_labels_linked_field_ar = build_fields_labels_array($prefix_internal_table.$primary_key_table_field, "1");
					} // end if
					else {
						$linked_table_installed = 0;
					} // end else

					for ($j=0;$j<count($linked_fields_ar);$j++) {
						////*$field_values_ar[$j] = $details_row[$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];
						$field_values_ar[$j] = $details_row[$primary_key_table_field.$alias_prefix.$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];

					} // end for
				}
				else {
					$field_values_ar[0] = $details_row[$field_name_temp];
				}

				$count_temp_2 = count($field_values_ar);
				$details_table .= "<tr><td class='td_label_details'><b>".$fields_labels_ar[$i]["label_" . $lang . "_field"]."</b></td><td class='td_value_details'>";
				for ($j=0; $j<$count_temp_2; $j++) {

					// if it's a linked field and the linked table is installed, get the correct $field_type $field_content $field_separator
					if ($primary_key_field_field != "" && $primary_key_field_field != NULL && $linked_table_installed === 1) {

						foreach ($fields_labels_linked_field_ar as $fields_labels_linked_field_ar_element) {
							if ($fields_labels_linked_field_ar_element['name_field'] === $linked_fields_ar[$j]) {
								$linked_field_type = $fields_labels_linked_field_ar_element['type_field'];
								$linked_field_content = $fields_labels_linked_field_ar_element['content_field'];
								$linked_field_separator = $fields_labels_linked_field_ar_element['separator_field'];
							} // end if
						} // end foreach

						reset($fields_labels_linked_field_ar);

						$field_to_display = get_field_correct_displaying($field_values_ar[$j], $linked_field_type, $linked_field_content, "details_table"); // get the correct display mode for the field
					} // end if
					else {
						$field_to_display = get_field_correct_displaying($field_values_ar[$j], $fields_labels_ar[$i]["type_field"], $fields_labels_ar[$i]["content_field"], "details_table"); // get the correct display mode for the field
					} // end else

					$details_table .= $field_to_display."&nbsp;"; // at the field value to the table
				}
				$details_table = substr($details_table, 0, -6); // delete the last &nbsp;
				$details_table .= "</td></tr>";
			} // end if
		} // end for
	} // end while

	$details_table .= "</table>";

	return $details_table;
} // end function build_details_table

function build_insert_update_notice_email_record_details($fields_labels_ar, $res_details)
// goal: build the detail information about the record just inserted or updated, to use in the insert or update notice email
// input: $fields_labels_ar $res_details (the recordset produced by the SELECT query on the record just inserted or just updated)
// ouptut: $details_table, the html table
{
	global $db, $alias_prefix, $normal_messages_ar, $lang;

	$notice_email = '';

	$count_temp = count($fields_labels_ar);
	while ($details_row = $db->db_fetch_assoc($res_details)) { // should be just one
		$notice_email .= $normal_messages_ar['details_of_record']."\n";
		$notice_email .= "--------------------------------------------\n\n";

		for ($i=0; $i<$count_temp; $i++) {

			if ($fields_labels_ar[$i]['present_details_form_field'] === '1') {
				$field_name_temp = $fields_labels_ar[$i]['name_field'];

				$field_values_ar = array(); // reset the array containing values to display, otherwise for each loop if I don't call build_linked_field_values_ar I have the previous values

				$primary_key_field_field = $fields_labels_ar[$i]['primary_key_field_field'];

				if ($primary_key_field_field != '') { // it is a foreign key field

					$primary_key_table_field = $fields_labels_ar[$i]['primary_key_table_field'];
					$linked_fields_field = $fields_labels_ar[$i]['linked_fields_field'];
					$linked_fields_ar = explode($fields_labels_ar[$i]['separator_field'], $linked_fields_field);
					$alias_suffix_field = $fields_labels_ar[$i]['alias_suffix_field'];

					for ($j=0; $j<count($linked_fields_ar); $j++) {
						$field_values_ar[$j] = $details_row[$primary_key_table_field.$alias_prefix.$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];
					} // end for
				} // end if
				else {
					$field_values_ar[0] = $details_row[$field_name_temp];
				} // end else

				$count_temp_2 = count($field_values_ar);

				$notice_email .= $fields_labels_ar[$i]["label_" . $lang . "_field"].':'; // add the label

				for ($j=0; $j<$count_temp_2; $j++) {
					$field_to_display = get_field_correct_displaying($field_values_ar[$j], $fields_labels_ar[$i]['type_field'], $fields_labels_ar[$i]['content_field'], 'plain_text'); // get the correct display mode for the field

					$notice_email .= ' '.$field_to_display; // add the field value
				} // end for

				$notice_email .= "\n"; // add the return

			} // end if
		} // end for
		$notice_email .= "\n\n--------------------------------------------\n" . _("The OpenHomeopath-Team") . " ;-)";
	} // end while
	return $notice_email;
} // end function build_insert_update_notice_email_record_details()

function build_navigation_tool($table_name, $where_clause, $pages_number, $page, $action, $order, $order_type)
// goal: build a set of link to go forward and back in the result pages
// input: $where_clause, $pages_number (total number of pages), $page (the current page 0....n), $action, the action page (e.g. index.php), $order, the field used to order the results, $order_type
// output: $navigation_tool, the html navigation tool
{
	$function = "search";

	$navigation_tool = "";

	$page_group = (int)($page/10); // which group? (from 0......n) e.g. page 12 is in the page_group 1
	$total_groups = ((int)(($pages_number-1)/10))+1; // how many groups? e.g. with 32 pages 4 groups
	$start_page = $page_group*10; // the navigation tool start with $start_page, end with $end_page
	if ($start_page+10 > $pages_number) {
		$end_page = $pages_number;
	} // end if
	else {
		$end_page = $start_page+10;
	} // end else

	$variables_to_pass = 'table_name='. urlencode($table_name).'&function='.$function.'&where_clause='.urlencode($where_clause).'&order='.urlencode($order).'&amp;order_type='.urlencode($order_type);

	if ($page_group > 1) {
		$navigation_tool .= "<a class='navig' href='$action?".$variables_to_pass."&page=0' title='1'>&lt;&lt;</a> ";
	} // end if
	if ($page_group > 0) {
		$navigation_tool .= "<a class='navig' href='$action?".$variables_to_pass."&page=".((($page_group-1)*10)+9)."' title='".((($page_group-1)*10)+10)."'>&lt;</a> ";
	} // end if

	for($i=$start_page; $i<$end_page; $i++) {
		if ($i != $page) {
			$navigation_tool .= "<a class='navig' href='$action?".$variables_to_pass."&page=".$i."'>".($i+1)."</a> ";
		} // end if
		else {
			$navigation_tool .= "<span class='navig'>".($i+1)."</span> ";
		} //end else
	} // end for

	if(($page_group+1) < ($total_groups)) {
		$navigation_tool .= "<a class='navig' href='$action?".$variables_to_pass."&page=".(($page_group+1)*10)."' title='".((($page_group+1)*10)+1)."'>&gt;</a> ";
	} // end elseif
	if (($page_group+1) < ($total_groups-1)) {
		$navigation_tool .= "<a class='navig' href='$action?".$variables_to_pass."&page=".($pages_number-1)."' title='".$pages_number."'>&gt;&gt;</a> ";
	} // end if
	return $navigation_tool;
} // end function build_navigation_tool

function delete_record($table_name, $where_field, $where_value)
// goal: delete one record
{
	global $db;
	$where = "$where_field = '$where_value'";
	$archive_type = "datadmin_delete";
	$db->archive_table_row($table_name, $where, $archive_type);
	$sql = "DELETE FROM `$table_name` WHERE `$where_field` = '$where_value'";
	display_sql($sql);

	// execute the select query
	$db->send_query($sql);

} // end function delete_record

function delete_multiple_records ($table_name, $where_clause, $ID_user_field_name)
// goal: delete a group of record according to a where clause
// input: $table_name, $where_clause, $ID_user_field_name (if it is not false, delete only the records that the current user owns
{
	global $current_user, $enable_authentication, $enable_delete_authorization, $db;

	if ($enable_authentication === 1 && $enable_delete_authorization === 1 && $ID_user_field_name !== false) { // check also the user
		if ($where_clause !== '') {
			$where_clause .= ' AND ';
		} // end if
		$where_clause .= "`$ID_user_field_name` = '$current_user'";
	} // end if
	$archive_type = "datadmin_multi_delete";
	$db->archive_table_row($table_name, $where_clause, $archive_type);
	$sql = '';
	$sql .= "DELETE FROM `$table_name`";
	if ($where_clause !== '') {
		$sql .= " WHERE $where_clause";
	} // end if
	display_sql($sql);

	// execute the select query
	$db->send_query($sql);

} // end function delete_multiple_records

function create_internal_table($table_internal_name)
// goal: drop (if present) the old internal table and create the new one.
// input: $table_internal_name
{
	global $db;

	$sql = "DROP TABLE IF EXISTS $table_internal_name";
	$db->send_query($sql);

	$fields = "(
		`id_field` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
		`name_field` varchar(50) DEFAULT NULL,
		`label_de_field` varchar(255) NOT NULL DEFAULT '',
		`label_en_field` varchar(255) NOT NULL DEFAULT '',
		`type_field` varchar(50) NOT NULL DEFAULT 'text',
		`content_field` varchar(50) NOT NULL DEFAULT 'alphanumeric',
		`present_search_form_field` varchar(1) NOT NULL DEFAULT '1',
		`present_results_search_field` varchar(1) NOT NULL DEFAULT '1',
		`present_details_form_field` varchar(1) NOT NULL DEFAULT '1',
		`present_insert_form_field` varchar(1) NOT NULL DEFAULT '1',
		`present_ext_update_form_field` varchar(1) NOT NULL DEFAULT '1',
		`required_field` varchar(1) NOT NULL DEFAULT '0',
		`check_duplicated_insert_field` varchar(1) NOT NULL DEFAULT '0',
		`other_choices_field` varchar(1) NOT NULL DEFAULT '0',
		`select_options_field` text,
		`primary_key_field_field` varchar(255) NOT NULL DEFAULT '',
		`primary_key_table_field` varchar(255) NOT NULL DEFAULT '',
		`primary_key_db_field` varchar(50) NOT NULL DEFAULT '',
		`linked_fields_field` text,
		`linked_fields_order_by_field` text,
		`linked_fields_order_type_field` text,
		`select_type_field` varchar(100) NOT NULL DEFAULT 'is_equal/contains/starts_with/ends_with/greater_than/less_then/is_null/is_empty',
		`prefix_field` text,
		`default_value_field` text,
		`width_field` varchar(5) NOT NULL DEFAULT '',
		`height_field` varchar(5) NOT NULL DEFAULT '',
		`maxlength_field` varchar(5) NOT NULL DEFAULT '100',
		`hint_insert_de_field` varchar(255) NOT NULL DEFAULT '',
		`hint_insert_en_field` varchar(255) NOT NULL DEFAULT '',
		`order_form_field` int(11) NOT NULL,
		`separator_field` varchar(2) NOT NULL DEFAULT '~',
		PRIMARY KEY (`id_field`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

	$sql = "CREATE TABLE  `$table_internal_name` $fields";
	$db->send_query($sql);

} // end function create_internal_table

function create_table_list_table()
// goal: drop (if present) the old table list and create the new one.
{
	global $db, $table_list_name;

	$sql = "DROP TABLE IF EXISTS $table_list_name";
	$db->send_query($sql);

	$fields = "(
		`name_table` varchar(255) NOT NULL DEFAULT '',
		`allowed_table` varchar(1) NOT NULL DEFAULT '',
		`enable_insert_table` varchar(1) NOT NULL DEFAULT '',
		`enable_edit_table` varchar(1) NOT NULL DEFAULT '',
		`enable_delete_table` varchar(1) NOT NULL DEFAULT '',
		`enable_details_table` varchar(1) NOT NULL DEFAULT '',
		`alias_table_de` varchar(255) NOT NULL DEFAULT '',
		`alias_table_en` varchar(255) NOT NULL DEFAULT '',
		`position` tinyint(3) unsigned NOT NULL DEFAULT '0',
		PRIMARY KEY (`name_table`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	";

	$sql = "CREATE TABLE  `$table_list_name` $fields";
	$db->send_query($sql);

} // end function create_table_list_table

function create_users_table()
// goal: drop (if present) the old users table and create the new one.
{
	global $db, $users_table_name;

	$fields = "(
		`id_user` MEDIUMINT UNSIGNED NOT NULL PRIMARY AUTOINCREMENT,
		`user_type_user` VARCHAR(50) NOT NULL,
		`username_user` VARCHAR(50) NOT NULL,
		`password_user` VARCHAR(32) NOT NULL,
		UNIQUE `username_user_index` (`username_user`)
	) ENGINE=MYISAM CHARACTER SET utf8
	";

	$sql = "CREATE TABLE `$users_table_name`  $fields";
	$db->send_query($sql);

	$sql = "INSERT INTO `".$users_table_name."` (user_type_user, username_user, password_user) VALUES ('admin', 'root', '".md5('admin')."')";

	$db->send_query($sql);

} // end function create_users_table


function table_allowed($table_name)
// goal: check if a table is allowed to be managed by DaDaBIK
// input: $table_name
// output: true or false
{
	global $db, $table_list_name;
	if ($db->table_exists($table_list_name)) {
		$sql = "SELECT `allowed_table` FROM `$table_list_name` WHERE `name_table` = '$table_name'";
		$res_allowed = $db->send_query($sql);
		if ($db->db_num_rows($res_allowed) == 1) {
			$row_allowed = $db->db_fetch_row($res_allowed);
			$allowed_table = $row_allowed[0];
			if ($allowed_table == "0") {
				return false;
			} // end if
			else {
				return true;
			} // end else
		} // end if
		elseif ($db->db_num_rows($res_allowed) == 0) { // e.g. I have an empty table or the table is not installed
			return false;
		} // end elseif
		else {
			exit;
		} // end else
	} // end if
	else {
		return false;
	} // end else
} // end function table_allowed()

function build_enabled_features_ar($table_name)
// goal: build an array containing "0" or "1" according to the fact that a feature (insert, edit, delete, details) is enabled or not
// input: $table_name
// output: $enabled_features_ar, the array
{
	global $db, $table_list_name;
	$sql = "SELECT `enable_insert_table`, `enable_edit_table`, `enable_delete_table`, `enable_details_table` FROM `$table_list_name` WHERE `name_table` = '$table_name'";
	$db->send_query($sql);
	$num_rows = $db->db_num_rows();
	if ($num_rows == 1) {
		$row_enable = $db->db_fetch_assoc();
		$enabled_features_ar["insert"] = $row_enable["enable_insert_table"];
		$enabled_features_ar["edit"] = $row_enable["enable_edit_table"];
		$enabled_features_ar["delete"] = $row_enable["enable_delete_table"];
		$enabled_features_ar["details"] = $row_enable["enable_details_table"];
		$db->free_result();
		return $enabled_features_ar;
	} // end if
	else {  //database error
		exit;
	} // end else
} // end function build_enabled_features_ar($table_name)

function build_enable_features_checkboxes($table_name)
// goal: build the form that enable features
// input: name of the current table
// output: the html for the checkboxes
{
	$enabled_features_ar = build_enabled_features_ar($table_name);

	$enable_features_checkboxes = "";
	$enable_features_checkboxes .= "<input type='checkbox' name='enable_insert' value='1'";
	$enable_features_checkboxes .= "";
	if ($enabled_features_ar["insert"] == "1") {
		$enable_features_checkboxes .= "checked";
	} // end if
	$enable_features_checkboxes .= ">Insert ";
	$enable_features_checkboxes .= "<input type='checkbox' name='enable_edit' value='1'";
	if ($enabled_features_ar["edit"] == "1") {
		$enable_features_checkboxes .= "checked";
	} // end if
	$enable_features_checkboxes .= ">Edit ";
	$enable_features_checkboxes .= "<input type='checkbox' name='enable_delete' value='1'";
	if ($enabled_features_ar["delete"] == "1") {
		$enable_features_checkboxes .= "checked";
	} // end if
	$enable_features_checkboxes .= ">Delete ";
	$enable_features_checkboxes .= "<input type='checkbox' name='enable_details' value='1'";
	if ($enabled_features_ar["details"] == "1") {
		$enable_features_checkboxes .= "checked";
	} // end if
	$enable_features_checkboxes .= ">Details ";

	return $enable_features_checkboxes;
} // end function build_enable_features_checkboxes

function build_change_field_select($fields_labels_ar, $field_position)
// goal: build an html select with all the field names
// input: $fields_labels_ar, $field_position (the current selected option)
// output: the select
{
	global $table_name;

	$change_field_select = "";
	$change_field_select .= "<select name='field_position'>";
	$count_temp = count($fields_labels_ar);
	for ($i=0; $i<$count_temp; $i++) {
		$change_field_select .= "<option value='".$i."'";
		if ($i == $field_position) {
			$change_field_select .= " selected";
		} // end if
		$change_field_select .= ">".$fields_labels_ar[$i]["name_field"]."</option>";
	} // end for
	$change_field_select .= "</select>";

	return $change_field_select;
} // end function build_change_field_select

function build_int_table_field_form($field_position, $int_fields_ar, $fields_labels_ar)
// goal: build a part of the internal table manager form relative to one field
// input: $field_position, the position of the field in the internal form, $int_field_ar, the array of the field of the internal table (with labels and properties), $fields_labels_ar, the array containing the fields labels and other information about fields
// output: the html form part
{
	$int_table_form = "";
	$int_table_form .= "<table><tr style='background-color: #F0F0F0'><td style='padding: 6px;'><table>";
	$count_temp = count($int_fields_ar);
	for ($i=0; $i<$count_temp; $i++) {
		$int_table_form .= "<tr>";
		$int_field_name_temp = $int_fields_ar[$i][1];
		$int_table_form .= "<td>".$int_fields_ar[$i][0]."</td><td>";
		if ($i==0) { // it's the name of the field, no edit needed, just show the name
			$int_table_form .= $fields_labels_ar[$field_position][$int_field_name_temp];
		} // end if
		else {
			switch ($int_fields_ar[$i][2]) {
				case "text":
					$int_table_form .= "<input type='text' name='".$int_field_name_temp."_".$field_position."' value='".$fields_labels_ar[$field_position][$int_field_name_temp]."' size='".$int_fields_ar[$i][3]."'>";
					break;
				case "select_yn":
					$int_table_form .= "<select name='".$int_field_name_temp."_".$field_position."'>";
					$int_table_form .= "<option value='1'";
					if ($fields_labels_ar[$field_position][$int_field_name_temp] == "1") {
						$int_table_form .= " selected";
					} // end if
					$int_table_form .= ">Y</option>";
					$int_table_form .= "<option value='0'";
					if ($fields_labels_ar[$field_position][$int_field_name_temp] == "0") {
						$int_table_form .= " selected";
					} // end if
					$int_table_form .= ">N</option>";
					$int_table_form .= "</select>";
					break;
				case "select_custom":
					$int_table_form .= "<select name='".$int_field_name_temp."_".$field_position."'>";
					$temp_ar = explode("/", $int_fields_ar[$i][3]);
					$count_temp_2 = count($temp_ar);
					for ($j=0; $j<$count_temp_2; $j++) {
						$int_table_form .= "<option value='".$temp_ar[$j]."'";
						if ($fields_labels_ar[$field_position][$int_field_name_temp] == $temp_ar[$j]) {
							$int_table_form .= " selected";
						} // end if
						$int_table_form .= ">".$temp_ar[$j]."</option>";
					} // end for
					$int_table_form .= "</select>";
					break;
			} // end switch
		} // end else
		$int_table_form .= "</td>";
		$int_table_form .= "</tr>"; // end of the row
	} // end for
	$int_table_form .= "</table></td></tr></table><p>&nbsp;</p>"; // end of the row

	return $int_table_form;
} // end function build_int_table_field_form($field_position, $int_fields_ar, $fields_labels_ar)

function insert_other_field($primary_key_table, $field_name, $field_value_other)
// goal: insert in the primary key table the other.... field
// input: $primary_key_table, $primary_key_db, $linked_fields, $field_value_other
// outpu: the ID of the record inserted
{
	global $db;

	if (!table_contains($primary_key_table, $field_name, $field_value_other)) { // check if the table doesn't contains the value inserted as other

		$sql_insert_other = "INSERT INTO `".$primary_key_table."` (`".$field_name."`) VALUES ('".$field_value_other."')";

		display_sql($sql_insert_other);

		// insert into the table of other
		$db->send_query($sql_insert_other);

		return $db->db_insert_id();
	} else {
		return false;
	}
} // end function insert_other_field

function update_options($fields_labels_ar_i, $field_name, $field_value_other)
// goal: upate the options of a field when a user select other....
// input: $fields_labels_ar_i (fields_labels_ar specific for a field), $field_name, $field_value_other
{
	global $db, $table_internal_name;
	$select_options_field_updated = $db->escape_string($fields_labels_ar_i["select_options_field"].stripslashes($field_value_other).$fields_labels_ar_i["separator_field"]);

	$sql_update_other = "UPDATE `".$table_internal_name."` SET `select_options_field` = '".$select_options_field_updated."' WHERE `name_field` = '".$field_name."'";
	display_sql($sql_update_other);

	// update the internal table
	$db->send_query($sql_update_other);
} // end function update_options($fields_labels_ar_i, $field_name, $field_value_other)

function build_select_part($fields_labels_ar, $table_name)
// goal: build the select part of a search query e.g.SELECT table_1.field_1, table_2.field2 from table_1 LEFT JOIN table_2 ON table_1.field_3 = table2.field_3
// input: $fields_labels_ar, $table_name
// output: the query
{
	global $alias_prefix, $db;

	// get the primary key
	$unique_field_name = $db->get_primary_key($table_name);

	$sql_fields_part = '';
	$sql_from_part = '';

	foreach($fields_labels_ar as $field) {
		if ($field['present_results_search_field'] === '1' || $field['present_details_form_field'] === '1' || $field['name_field'] === $unique_field_name || (substr($table_name, 0, 9) == "archive__" && ($field['name_field'] == "timestamp" || $field['name_field'] == "archive_type"))) { // include in the select statements just the fields present in results or the primary key (useful to pass to the edit form) or timestamp/archive_type for archiv-tables

			// if the field has linked fields, include each linked fields in the select statement and the corresponding table (wiht join) in the from part. Use alias for all in order to mantain name unicity, each field has is own alias_suffix_field so it is easy
			if ($field['primary_key_field_field'] !== '' && $field['primary_key_field_field'] !== NULL) {
				$linked_fields_ar = explode($field['separator_field'], $field['linked_fields_field']);

				foreach ($linked_fields_ar as $linked_field) {
					$sql_fields_part .= "`".$field['primary_key_table_field'].$alias_prefix.$field['alias_suffix_field']."`".'.'."`".$linked_field."`".' AS '."`".$field['primary_key_table_field'].$alias_prefix.$linked_field.$alias_prefix.$field['alias_suffix_field']."`".', ';
				} //end foreach

				$sql_from_part .= ' LEFT JOIN '."`".$field['primary_key_table_field']."`".' AS '."`".$field['primary_key_table_field'].$alias_prefix.$field['alias_suffix_field']."`";

				$sql_from_part .= ' ON ';
				$sql_from_part .= "`".$table_name."`".'.'."`".$field['name_field']."`".' = '."`".$field['primary_key_table_field'].$alias_prefix.$field['alias_suffix_field']."`".'.'."`".$field['primary_key_field_field']."`";
			} // end if
			// if the field has not linked field, include just the field in the select statement
			else {
				$sql_fields_part .= "`$table_name`.`".$field['name_field']."`, ";
			} // end else
		} // end if
	} // end foreach

	$sql_fields_part = substr($sql_fields_part, 0, -2); // delete the last ', '

	// compose the final statement
	$sql = "SELECT $sql_fields_part FROM `$table_name`$sql_from_part" ;

	return $sql;
} // end function build_select_part()

function build_records_per_page_form($action, $records_per_page, $table_name)
// goal: build the listbox that allows the user to choose the number of result record per page on the fly
// input: $records_per_page, the current number of record per page, $table_name, the current table
// output: the form
{
	global $records_per_page_ar, $normal_messages_ar;

	$records_per_page_form = "";

	$records_per_page_form .= "<form name='records_per_page_form' action='$action' method='GET'>";

	$records_per_page_form .= "<input type='hidden' name='table_name' value='$table_name'>";
	$records_per_page_form .= "<input type='hidden' name='function' value='search'>";

	$records_per_page_form .= "<select class='select_records_per_page' name='records_per_page' onchange=\"document.records_per_page_form.submit()\">";

	foreach ($records_per_page_ar as $records_per_page_item) {
		$records_per_page_form .= "<option value='$records_per_page_item'";
		if ($records_per_page_item === $records_per_page) {
			$records_per_page_form .= " selected";
		} // end if
		$records_per_page_form .= ">$records_per_page_item</option>";
	} // foreach

	$records_per_page_form .= "</select>";
	$records_per_page_form .= " ".$normal_messages_ar['records_per_page'];
	$records_per_page_form .= "</form>";

	return $records_per_page_form;
} // end function build_records_per_page_form()

function build_installed_table_infos_ar($only_include_allowed, $exclude_users_tab_if_not_admin)
// goal: build an an array containing infos about dadabik installed tables
// input: $only_include_allowed (0|1) $exclude_users_tab_if_not_admin(0|1)
// output: the array
{
	global $table_list_name, $users_table_name, $db, $current_user_is_editor, $lang;

	if ($only_include_allowed === 1) {
		$sql = "SELECT name_table, alias_table_$lang FROM `$table_list_name` WHERE allowed_table = '1'";
	} // end if
	else {
		$sql = "SELECT name_table, alias_table_$lang FROM `$table_list_name`";
	} // end else

	$res = $db->send_query($sql);

	$i=0;

	while ($row = $db->db_fetch_row($res)) {
		if ($current_user_is_editor === 1 || $row[0] !== $users_table_name || $exclude_users_tab_if_not_admin === 0) {
			$installed_table_infos_ar[$i]['name_table'] = $row[0];
			$installed_table_infos_ar[$i]['alias_table'] = $row[1];
			$i++;
		} // end if
	} // end while
	$db->free_result($res);

	return $installed_table_infos_ar;

} // end function build_installed_table_infos_ar()
?>
