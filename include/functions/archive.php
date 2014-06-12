<?php
/**
 * functions/archive.php
 *
 * This script is an enhancement to DaDaBIK from Eugenio Tacchini,
 * which automatically archives all updates and deletions of records from the database
 * and gives the possibility of restore previous versions.
 *
 * PHP version 5
 *
 * LICENSE: This program is free software: you can redistribute itThis script is an enhancement to DaDaBIK from Eugenio Tacchini  and/or modify
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
 * @category  Database
 * @package   Archive
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 * @see       datadmin.php
 */

function build_change_table_form_archiv($table_infos_ar, $table_name)
// goal: build a form to choose the table
// input: $table_infos_ar, $table_name
// output: the listbox
{
	global $url;
	$change_table_form = "    <form method='get' action='$url' name='change_table_form'>\n";
	$change_table_form .= "      <select name='table_name' class='select_change_table' onchange=\"document.change_table_form.submit()\">\n";

	$count_temp = count($table_infos_ar);
	for($i=0; $i<$count_temp; $i++){
		$change_table_form .= "        <option value='".htmlspecialchars($table_infos_ar[$i]['table_name'])."'";
		if ($table_name == $table_infos_ar[$i]['table_name']){
			$change_table_form .= " selected";
		}
		$change_table_form .= ">".$table_infos_ar[$i]['table_alias']."</option>\n";
	} // end for
	$change_table_form .= "      </select>\n";
	$change_table_form .= "    </form>\n";

	if ($count_temp == 1){
		return "";
	} // end if
	else{
		return $change_table_form;
	} // end else

} // end function build_change_table_form_archiv

function build_results_table_archiv($fields_labels_ar, $table_name, $result, $action, $where_clause, $page, $order, $order_type, $table_infos_ar, $details)
// goal: build an HTML table for basicly displaying the results of a select query or show a check mailing results
// input: $table_name, $result, the results of the query, $action (e.g. index.php), $where_clause, $page (o......n), $order, $order_type, $table_infos_ar
// output: $results_table, the HTML results table
// global: $submit_buttons_ar, the array containing the values of the submit buttons, $edit_target_window, the target window for edit/details (self, new......), $restore_icon, $details_icon (the image files to use as icons)
{
	global $submit_buttons_ar, $edit_target_window, $restore_icon, $details_icon, $word_wrap_col, $word_wrap_fix_width, $alias_prefix, $enable_row_highlighting, $prefix_internal_table, $db, $url, $lang;

	$function = "search";

	$unique_field_name = $db->get_primary_key($table_name);

	// build the results HTML table
	///////////////////////////////

	$results_table = "";
	$results_table .= "<table class='results'>\n";

	// build the table heading
	$results_table .= "<tr>\n";

	
	$results_table .= "<th class='results'>&nbsp;</th>\n"; // skip the first column for edit, delete and details
	$count_temp = count($fields_labels_ar);
	for ($i=0; $i<$count_temp; $i++){
		if ($fields_labels_ar[$i]["present_results_search_field"] == "1" || $fields_labels_ar[$i]["name_field"] == "timestamp" || $fields_labels_ar[$i]["name_field"] == "archive_type" || ($details == "1" && $fields_labels_ar[$i]["present_details_form_field"] == "1")) { // the user want to display the field in the basic search results page
			$label_to_display = $fields_labels_ar[$i]["label_" . $lang . "_field"];
			if ($word_wrap_fix_width === 1){
				$spaces_to_add = $word_wrap_col-strlen($label_to_display);
				if ( $spaces_to_add > 0) {
					for ($j=0; $j<$spaces_to_add; $j++) {
						$label_to_display .= '&nbsp;';
					}
				}
			} // end if
			
			$results_table .= "<th class='results'>";
			$field_is_current_order_by = 0;
			if ($order != $fields_labels_ar[$i]["name_field"]){ // the results are not ordered by this field at the moment
				$link_class="order_link";
				$new_order_type = "ASC";
			}
			else{
				$field_is_current_order_by = 1;
				$link_class="order_link_selected";
				if ( $order_type == "DESC") {
					$new_order_type = "ASC";
				}
				else{
					$new_order_type = "DESC";
				}
			} // end elseif ($order != $fields_labels_ar[$i]["name_field"])
			
			$results_table .= "<a class='".$link_class."' href='".$action."?table_name=". urlencode($table_name)."&function=search&where_clause=".urlencode($where_clause)."&page=$page&order=".urlencode($fields_labels_ar[$i]["name_field"])."&amp;order_type=$new_order_type'>";

			if ($field_is_current_order_by === 1) {
				if ($order_type === 'ASC') {
					$results_table .= '<span class="arrow">&uarr;</span> ';
				} // end if
				else {
					$results_table .= '<span class="arrow">&darr;</span> ';
				} // end if
			} // end if
			
			$results_table .= "$label_to_display</a></th>\n"; // insert the linked name of the field in the <th>
		} // end if
	} // end for
	$results_table .= "</tr>\n";
	if ($details == "1") {
		$sql = build_select_part($fields_labels_ar, $table_name);
		$where_clause_aktuell = str_replace("archive__", "", $where_clause);
		$sql .= " WHERE $where_clause_aktuell";
		display_sql($sql);
		// execute the select query
		$res_details = $db->send_query($sql);
		while ($details_row = $db->db_fetch_assoc($res_details)){
			$results_table .= "<tr class='tr_results_current'>\n";
			$results_table .= "<td class='controls_current'></td>\n";
			for ($i=0; $i<$count_temp; $i++){
				if ($fields_labels_ar[$i]["name_field"] == "timestamp" || $fields_labels_ar[$i]["name_field"] == "archive_type" || $fields_labels_ar[$i]["present_results_search_field"] == "1" || $fields_labels_ar[$i]["present_details_form_field"] == "1") {
					$results_table .= "<td>"; // start the cell
					
					$field_name_temp = $fields_labels_ar[$i]["name_field"];
					$field_type = $fields_labels_ar[$i]["type_field"];
					$field_content = $fields_labels_ar[$i]["content_field"];
					$field_separator = $fields_labels_ar[$i]["separator_field"];
	
					$field_values_ar = array(); // reset the array containing values to display, otherwise for each loop I have the previous values
	
					$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
					if (!empty($primary_key_field_field)){
						$primary_key_table_field = $fields_labels_ar[$i]["primary_key_table_field"];
						$primary_key_db_field = $fields_labels_ar[$i]["primary_key_db_field"];
						$linked_fields_field = $fields_labels_ar[$i]["linked_fields_field"];
						$alias_suffix_field = $fields_labels_ar[$i]["alias_suffix_field"];
						$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_field);
						
						// get the list of all the installed tables
						$k = 0;
						foreach ($table_infos_ar as $table_infos) {
							$tables_names_ar[$k] = $table_infos['table_name'];
							$k++;
						}
	
						// if the linked table is installed I can get type content and separator of the linked field
						if (in_array($primary_key_table_field, $tables_names_ar)) {
							$linked_table_installed = 1;
	
							$fields_labels_linked_field_ar = build_fields_labels_array($prefix_internal_table.$primary_key_table_field, 1);
						} // end if
						else {
							$linked_table_installed = 0;
						} // end else
						for ($j=0;$j<count($linked_fields_ar);$j++) {
							$field_values_ar[$j] = $details_row[$primary_key_table_field.$alias_prefix.$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];
						} // end for
					} elseif ($field_name_temp === "archive_type") {
						$field_values_ar[0] = "";
					} else {
						$field_values_ar[0] = $details_row[$field_name_temp];
					} // end else
					$count_temp_2 = count($field_values_ar);
					for ($j=0; $j<$count_temp_2; $j++) {
						// if it's a linked field and the linked table is installed, get the correct $field_type $field_content $field_separator
						if ($primary_key_field_field != "" && $primary_key_field_field != NULL && $linked_table_installed === 1){
							foreach ($fields_labels_linked_field_ar as $fields_labels_linked_field_ar_element){
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
						if ($field_name_temp === "archive_type") {
							$field_to_display = "<div class='center'><strong>" . _("Current record") . "</strong></div>";
						} elseif (empty($field_to_display)) {
							$field_to_display = "&nbsp;";
						}
						$results_table .= $field_to_display."&nbsp;"; // at the field value to the table
					} // end for
					$results_table = substr($results_table, 0, -6); // delete the last &nbsp;
					$results_table .= "</td>\n"; // end the cell
				} // end if
			} // end for
			$results_table .= "</tr>\n";
		} // end while
	}
	$tr_results_class = 'tr_results_1';
	$td_controls_class = 'controls_1';

	// build the table body
	while ($records_row = $db->db_fetch_assoc($result)){

		if ($details == "1" && strpos($records_row['archive_type'], "_delete") !== false) {
			$td_controls_class = 'controls_delete';
			$tr_results_class = 'tr_results_delete';
		} // end if
		elseif ($tr_results_class === 'tr_results_1') {
			$td_controls_class = 'controls_2';
			$tr_results_class = 'tr_results_2';
		} // end elseif
		else {
			$td_controls_class = 'controls_1';
			$tr_results_class = 'tr_results_1';
		} // end else
		// set where clause for details and update
		///////////////////////////////////////////
		if (!empty($unique_field_name)){ // exists a unique number
			$where_field = $unique_field_name;
			$where_value = $records_row[$unique_field_name];
		} // end if
		if (!empty($records_row['timestamp'])){
			$timestamp = $records_row['timestamp'];
		} // end if
		///////////////////////////////////////////
		// end build where clause for details and update

		if ($enable_row_highlighting === 1) {
			$results_table .= "<tr class='$tr_results_class' onmouseover=\"if (this.className!='tr_highlighted_onclick'){this.className='tr_highlighted_onmouseover'}\" onmouseout=\"if (this.className!='tr_highlighted_onclick'){this.className='$tr_results_class'}\" onclick=\"if (this.className == 'tr_highlighted_onclick'){ this.className='$tr_results_class';}else{ this.className='tr_highlighted_onclick';}\">\n";
		} // end if
		else {
			$results_table .= "<tr class='$tr_results_class'>\n";
		} // end else
		
		$results_table .= "<td class='$td_controls_class'>";
		if (!empty($unique_field_name)){ // exists a unique number: restore, details make sense
			// display the restore icon
			if ($details == "1") {
				$from_function = "details";
			} else {
				$from_function = "search";
			}
			$results_table .= "<a class='onlyscreen' onclick=\"if (!confirm('" . _("Restore record") . "?')){ return false;}\" href='$url?table_name=".urlencode($table_name)."&function=restore&where_field=".urlencode($where_field)."&where_value=".urlencode($where_value)."&timestamp=".urlencode($timestamp)."&from_function=$from_function";
			$results_table .= "'><img src='$restore_icon' alt='" . _("Restore record") . "' title='" . _("Restore record") . "'></a>";
			if ($details != "1") {  // display the details icon
				$results_table .= "<a class='onlyscreen' target='_$edit_target_window' href='$url?table_name=".urlencode($table_name)."&details=1&where_field=".urlencode($where_field)."&where_value=".urlencode($where_value)."'><img src='$details_icon' alt='".$submit_buttons_ar["details"]."' title='" . _("Details from this record") . "'></a>";
			}
		} // end if
		$results_table .= "</td>\n";
		for ($i=0; $i<$count_temp; $i++){
			if ($fields_labels_ar[$i]["present_results_search_field"] == "1" || $fields_labels_ar[$i]["name_field"] == "timestamp" || $fields_labels_ar[$i]["name_field"] == "archive_type" || ($details == "1" && $fields_labels_ar[$i]["present_details_form_field"] == "1")){ // the user want to display the field in the search results page
				$results_table .= "<td>"; // start the cell
				
				$field_name_temp = $fields_labels_ar[$i]["name_field"];
				$field_type = $fields_labels_ar[$i]["type_field"];
				$field_content = $fields_labels_ar[$i]["content_field"];
				$field_separator = $fields_labels_ar[$i]["separator_field"];

				$field_values_ar = array(); // reset the array containing values to display, otherwise for each loop I have the previous values

				$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
				if (!empty($primary_key_field_field)){
					$primary_key_table_field = $fields_labels_ar[$i]["primary_key_table_field"];
					$primary_key_db_field = $fields_labels_ar[$i]["primary_key_db_field"];
					$linked_fields_field = $fields_labels_ar[$i]["linked_fields_field"];
					$alias_suffix_field = $fields_labels_ar[$i]["alias_suffix_field"];
					$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_field);
					
					// get the list of all the installed tables
					$k = 0;
					foreach ($table_infos_ar as $table_infos) {
						$tables_names_ar[$k] = $table_infos['table_name'];
						$k++;
					}

					// if the linked table is installed I can get type content and separator of the linked field
					if (in_array($primary_key_table_field, $tables_names_ar)) {
						$linked_table_installed = 1;

						$fields_labels_linked_field_ar = build_fields_labels_array($prefix_internal_table.$primary_key_table_field, 1);
					} // end if
					else {
						$linked_table_installed = 0;
					} // end else
					for ($j=0;$j<count($linked_fields_ar);$j++) {
						$field_values_ar[$j] = $records_row[$primary_key_table_field.$alias_prefix.$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];
					} // end for
				} else {
					$field_values_ar[0] = $records_row[$field_name_temp];
				} // end else
				$count_temp_2 = count($field_values_ar);
				for ($j=0; $j<$count_temp_2; $j++) {
					// if it's a linked field and the linked table is installed, get the correct $field_type $field_content $field_separator
					if ($primary_key_field_field != "" && $primary_key_field_field != NULL && $linked_table_installed === 1){
						foreach ($fields_labels_linked_field_ar as $fields_labels_linked_field_ar_element){
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
					} elseif ($field_name_temp === "archive_type") {
						$archive_type_ar = array (
							"admin_delete" => _("Deleting records of a user by Administrator"),
							"datadmin_update"  => _("Updating the record by Data Maintenance"),
							"datadmin_delete"  => _("Deleting the record by Data Maintenance"),
							"datadmin_multi_delete"  => _("Deleting records by Data Maintenance"),
							"express_update" => _("Update the record by Express-Tool"),
							"BZH_restruct" => _("Restructuring of the symptoms from BZH")
						);
						if (substr($field_to_display, 0, 8) == "restore_") {
							$time = substr($field_to_display, 8);
							if (substr($time, 0, 10) !== '0000-00-00') {
								$time = date("d.m.Y  H:i", strtotime($time));
								if (substr($time, 0, 10) !== '01.01.1970') {
									$field_to_display = _("Replaced by version from") . " $time";
								} else {
									$field_to_display = _("Replaced by original version");
								}
							} else {
								$field_to_display = _("Replaced by original version");
							}
						} else {
							$field_to_display = $archive_type_ar[$field_to_display];
						}
					}
					$results_table .= $field_to_display."&nbsp;"; // at the field value to the table
				} // end for
				$results_table = substr($results_table, 0, -6); // delete the last &nbsp;
				$results_table .= "</td>\n"; // end the cell
			} // end if
		} // end for
		$results_table .= "</tr>\n";
	} // end while
	$results_table .= "</table>\n";
	
	return $results_table;

} // end function build_results_table_archiv
