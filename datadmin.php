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
include ("include/datadmin/config.php");
include ("include/datadmin/languages/".$language.".php");
include_once ("include/classes/login/session.php");
include ("include/datadmin/functions.php");
include ("include/datadmin/common_start.php");
$url = $dadabik_main_file;
include ("include/datadmin/check_login.php");
include ("include/datadmin/check_table.php");

// HTTP Variables:
/***************** GET ***************************
***************************************************/
// link export_to_csv, set to 1
if (isset($_GET["export_to_csv"])){
	$export_to_csv = $_GET["export_to_csv"];
} // end if

// I keep in session where_clause, page, order and order_by in order to save a results view, even if a change table
// contains the where clause, without limit and order e.g. "field = 'value'" (all url encoded)
// navigation bar, order, delete and delete all links, export to csv, show_all link, check existing mail
// why strepslashes? The first time $where_clause is calculated from build_where_clause, and it's ok because all the field contents come from POST with slashes, so when I pass it through links new slashes are added and I have to strip them
if (isset($_GET["where_clause"])){
	$where_clause = stripslashes($_GET["where_clause"]);
	$_SESSION['where_clause_'.$table_name] = $where_clause;
} // end if
elseif (isset($_SESSION['where_clause_'.$table_name])){
	$where_clause = $_SESSION['where_clause_'.$table_name];
} // end if

// the current page in records results (0......n)
// navigation bar, order, delete and delete all links, export to csv, show_all link
if (isset($_GET["page"])){
	$page = $_GET["page"];
	$_SESSION['page_'.$table_name] = $page;
} // end if
elseif (isset($_SESSION['page_'.$table_name])){
	$page = $_SESSION['page_'.$table_name];
} // end if

// the field used to order the results
// navigation bar, order, delete and delete all links, export to csv
// why strepslashes? The first time $order is calculated in the code, so when I pass it through links new slashes are added if the field name contains quotes and I have to strip them
if (isset($_GET["order"])){
	$order = stripslashes($_GET["order"]);
	$_SESSION['order_'.$table_name] = $order;
} // end
elseif (isset($_SESSION['order_'.$table_name])){
	$order = $_SESSION['order_'.$table_name];
} // end if

// the order type ('ASC'|'DESC')
// navigation bar, order, delete and delete all links, export to csv
if (isset($_GET["order_type"])){
	$order_type = $_GET["order_type"];
	$_SESSION['order_type_'.$table_name] = $order_type;
} // end
elseif (isset($_SESSION['order_type_'.$table_name])){
	$order_type = $_SESSION['order_type_'.$table_name];
} // end if

// the number of result records to be displayed in a page
// records_per_page listbox
if (isset($_GET["records_per_page"])){ // the user set a new value from the listbox
	$records_per_page = (int)$_GET["records_per_page"];
	$_SESSION['records_per_page_'.$table_name] = $records_per_page;
} // end
elseif (isset($_SESSION['records_per_page_'.$table_name])){ // otherwise use the value saved for this table
	$records_per_page = $_SESSION['records_per_page_'.$table_name];
} // end if
else{ // otherwise (first time the table is accessed or session expired) use the first value of the listbox
	$records_per_page = $records_per_page_ar[0];
} // end else

// set to 1 when the user click on "show all"
// show_all link (footer.php)
// why isset()? All variables should be set because if empty_search_variables the user comes from a show results page, but could ben unset if the session has expired
if (isset($_GET['empty_search_variables']) && (int)$_GET['empty_search_variables'] === 1) {
	if (isset($where_clause)) {
		unset($where_clause);
	} // end if
	if (isset($page)) {
		unset($page);
	} // end if
	if (isset($order)) {
		unset($order);
	} // end if
	if (isset($order_type)) {
		unset($order_type);
	} // end if
} // end if

// the function of this page I wanto to execute ('edit'|'delete'|'search'....)
// navigation bar, order, edit, detail, delete and delete all links, export to csv, bottom links, insert/edit/search form, insert_duplication form
if (isset($_GET["function"])){ // from the homepage
	$function = $_GET["function"];
} // end
else{
	$function = "search";
} // end else

// the function ('edit'|'delete') from which the user click on previous/next buttons
// previous/next links
if (isset($_GET["from_function"])){
	$from_function = $_GET["from_function"];
} // end

// the field used to identify a single record in edit, delete and detail functions
// edit, delete, detail links, edit form
if (isset($_GET["where_field"])){
	$where_field = $_GET["where_field"];
} // end if

// the value (of where_field) used to identify a single record in edit, delete and detail functions
// edit, delete, detail links, edit form
if (isset($_GET["where_value"])){
	$where_value = $_GET["where_value"];
} // end if

// set to 1 when a research has been just executed
// from the search form
if (isset($_GET["execute_search"])){
	$execute_search = $_GET["execute_search"];
} // end if

// set to 1 after an update
// redirect after update
if (isset($_GET["just_updated"])){
	$just_updated = $_GET["just_updated"];
} // end if

// set to 1 after an update with no authorization
// update case
if (isset($_GET["just_updated_no_authorization"])){
	$just_updated_no_authorization = $_GET["just_updated_no_authorization"];
} // end if

// set to 1 after a delete with no authorization
// delete case
if (isset($_GET["just_delete_no_authorization"])){
	$just_delete_no_authorization = $_GET["just_delete_no_authorization"];
} // end if

// set to 1 after an insert
// redirect after insert
if (isset($_GET["just_inserted"])){
	$just_inserted = $_GET["just_inserted"];
} // end if

// set to 1 after a delete multiple with authentication enabled
// redirect after delete_all
if (isset($_GET["just_delete_all_authorizated"])){
	$just_delete_all_authorizated = $_GET["just_delete_all_authorizated"];
} // end if

// set to 1 after a next record on the last one
// redirect after choose_next_record
if (isset($_GET["just_next_record_on_last_one"])){
	$just_next_record_on_last_one = (int)$_GET["just_next_record_on_last_one"];
} // end if

// set to 1 after a previous record on the first one
// redirect after choose_previous_record
if (isset($_GET["just_previous_record_on_first_one"])){
	$just_previous_record_on_first_one = (int)$_GET["just_previous_record_on_first_one"];
} // end if

// insert_duplication_form, set to 1 if the user want to insert anyway
if (isset($_GET["insert_duplication"])){
	$insert_duplication = $_GET["insert_duplication"];
} // end if

/***************** POST ***************************
All the field contents come from POST, and I use them directly ($_POST)


***************************************************/

$skin = $session->skin;
include ("skins/$skin/header_datadmin.php");

$action = $dadabik_main_file;

// get the array containg label and other information about the fields
$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");

switch($function){
	case "insert":
		if ($enable_insert == "1") {
//			if (!isset($insert_duplication) || $insert_duplication != '1'){ // otherwise would be checked for two times
				// check values
				$check = 0;
				$check = check_required_fields($fields_labels_ar);
				if ($check == 0){
					txt_out($normal_messages_ar["required_fields_missed"], "error_messages_form");
				} // end if ($check == 0)
				else{ // required fields are ok
					// check field lengths
					$check = 0;
					$check = check_length_fields($fields_labels_ar);
					if ($check == 0){
						txt_out($normal_messages_ar["fields_max_length"], "error_messages_form");
					} // end if ($check == 0)
					else{ // fields length are ok
						$check = 0;
						$content_error_type = "";
						$check = check_fields_types($fields_labels_ar, $content_error_type);
						if ($check == 0){
							txt_out($normal_messages_ar["{$content_error_type}_not_valid"], "error_messages_form");
						} // end if ($check == 0)
						else{ // type field are ok
							if (!isset($insert_duplication) || $insert_duplication != '1'){
								// check for duplicated insert in the database
								$sql = build_select_duplicated_query($table_name, $fields_labels_ar, $string1_similar_ar, $string2_similar_ar);

								if ($sql != ""){ // if there are some duplication
									$check = 0;
									txt_out("<h3>".$normal_messages_ar["duplication_possible"]."</h3>");
									if ($display_is_similar == 1){
										for ($i=0; $i<count($string1_similar_ar); $i++){
											txt_out("<br>");
											txt_out($normal_messages_ar["i_think_that"]);
											txt_out($string1_similar_ar[$i]);
											txt_out($normal_messages_ar["is_similar_to"]);
											txt_out($string2_similar_ar[$i]);
										} // end for
									} // end if ($display_is_similar == 1)
									
									display_sql($sql);
									// execute the select query
									$res_records = $db->send_query_limit($sql, $number_duplicated_records, 0);

									$results_type = "possible_duplication";
									$where_clause = ""; // I don't need it here, I've just a fixed number of results.

									$results_table = build_results_table($fields_labels_ar, $table_name, $res_records, $results_type, $action, $where_clause, "", "", "");

									txt_out ("<br>".$normal_messages_ar["similar_records"]);

									$insert_duplication_form = build_insert_duplication_form($fields_labels_ar, $table_name);

									echo $insert_duplication_form;
									echo $results_table;
								} // end if
							} // end if
							if ($check === 1){
								
								// insert a new record
								insert_record($fields_labels_ar, $table_name, $table_internal_name);

								if ($enable_insert_notice_email_sending === 1) {

									$unique_field_name = $db->get_primary_key($table_name);

									$unique_ID = $db->db_insert_id();
									if (empty($unique_ID)) {
										$sql = "SELECT $unique_field_name FROM $table_name ORDER BY timestamp DESC LIMIT 1";
										$db->send_query($sql);
										$unique_ID = $db->db_fetch_row();
										$db->free_result();
										$unique_ID = $unique_ID[0];
									}

									if (isset($unique_field_name) && $unique_field_name !== '' && isset($unique_ID) && $unique_ID !== false) {
										$sql = build_select_part($fields_labels_ar, $table_name);
										$sql .= " WHERE `".$table_name."`.`".$unique_field_name."` = '".$unique_ID."'";
										// execute the select query
										$res_details = $db->send_query($sql);
										
										// build the insert notice message
										$insert_notice_email = build_insert_update_notice_email_record_details($fields_labels_ar, $res_details);
										$to_addresses = '';
										$cc_addresses = '';
										$bcc_addresses = '';

										foreach ($insert_notice_email_to_recipients_ar as $insert_notice_email_to_recipient){
											$to_addresses .= $insert_notice_email_to_recipient.', ';
										} // end foreach

										$to_addresses = substr($to_addresses, 0, -2); // delete the last ', '

										foreach ($insert_notice_email_cc_recipients_ar as $insert_notice_email_cc_recipient){
											$cc_addresses .= $insert_notice_email_cc_recipient.', ';
										} // end foreach

										$cc_addresses = substr($cc_addresses, 0, -2); // delete the last ', '

										foreach ($insert_notice_email_bcc_recipients_ar as $insert_notice_email_bcc_recipient){
											$bcc_addresses .= $insert_notice_email_bcc_recipient.', ';
										} // end foreach

										$bcc_addresses = substr($bcc_addresses, 0, -2); // delete the last ', '

										$additional_headers = '';

										if ($cc_addresses != '') {
											$additional_headers .= "Cc:".$cc_addresses."\n";
										} // end if

										if ($bcc_addresses != '') {
											$additional_headers .= "Bcc:".$bcc_addresses."\n";
										} // end if
										$additional_headers .= "Content-Type: text/plain; charset=utf-8";
										$subject = DB_NAME.' - '.$table_name.' - '.$normal_messages_ar['new_insert_executed'];
										$subject = $mailer->mail_header_escape($subject);
										mail($to_addresses, $subject, $insert_notice_email, $additional_headers);
									} // end if
								} // end if

								

								if ($insert_again_after_insert == 1) {
									//txt_out("<p>".$normal_messages_ar["insert_result"]);
									txt_out($normal_messages_ar["record_inserted"]);

									txt_out("<h3>".$normal_messages_ar["insert_record"]."</h3>");

									$form_type = "insert";
									$res_details = "";

									// re-get the array containg label ant other information about the fields, could be changed in the insert (other choices......)
									$fields_labels_ar = build_fields_labels_array($table_internal_name, "1");

									$show_insert_form_after_error = 0;
									$show_edit_form_after_error = 0;

									// display the form
									$form = build_form($table_name, $action, $fields_labels_ar, $form_type, $res_details, "", "", $show_insert_form_after_error, $show_edit_form_after_error);
									echo $form;
								} // end if
								else{
									$unique_field_name = $db->get_primary_key($table_name);

									$location_url=$site_url.$dadabik_main_file.'?table_name='.urlencode($table_name).'&function=search&where_clause=&page=0&just_inserted=1';
									if ($unique_field_name != '') {
										$location_url .= '&order='.$unique_field_name.'&order_type=desc';
									} // end if

									header('Location: '.$location_url);

								} // end else
							} // end if
						} // end else
					} // end else
				} // end else
				if ($check === 0) {
					txt_out("<h3>".$normal_messages_ar["insert_record"]."</h3>");
					$form_type = "insert";
					$res_details = "";

					$show_insert_form_after_error = 1;
					$show_edit_form_after_error = 1;

					// display the form
					$form = build_form($table_name, $action, $fields_labels_ar, $form_type, $res_details, "", "", $show_insert_form_after_error, $show_edit_form_after_error);
					echo $form;
				} // end if
		} // end if
		break;
	case "search":

		// $page if index.php is called without parameters (the first time DaDaBIK is launched)
		if (!isset($page)) {
			$page = 0;
			$_SESSION['page_'.$table_name] = $page;
		} // end if

		// build the select query
		if (isset($execute_search) && $execute_search === '1'){ // it's a search result, the user has just filled the search form, so we have to build the select query
		//if (!isset($where_clause)){ // it's a search result, the user has just filled the search form, so we have to build the select query
			$where_clause = build_where_clause($fields_labels_ar, $table_name);
			$page = 0;
			$_SESSION['page_'.$table_name] = $page;
		} // end if
		elseif (!isset($where_clause)) { // when I call index for the first time
			$where_clause = '';
		} // end else

		// save the where_clause without the user part to pass 
		$where_clause_to_pass = $where_clause;

		$_SESSION['where_clause_'.$table_name] = $where_clause;

		if ($enable_authentication === 1 && $enable_browse_authorization === 1) { // $ID_user_field_name = '$current_user' where clause part in order to select only the records the current user owns
			$ID_user_field_name = get_ID_user_field_name($fields_labels_ar);

			if ($ID_user_field_name !== false) { // no ID_user fields available, don't use authorization
				if ($where_clause === '') {
					$where_clause = "`$table_name`.`$ID_user_field_name` = '".$db->escape_string($current_user)."'";
				} // end if
				else {
					$where_clause = "($where_clause) AND `$table_name`.`$ID_user_field_name` = '".$db->escape_string($current_user)."'";
				} // end else
			} // end if

		} // end if
		
		$sql = "SELECT COUNT(*) FROM `$table_name`";
		if ($where_clause != ""){
			$sql .= " WHERE $where_clause";
		} // end if
		$res_records_without_limit = $db->send_query($sql);
		while ($count_row = $db->db_fetch_row($res_records_without_limit)) {
			$results_number = $count_row[0];  // get the number of results
		} // end while
		$db->free_result($res_records_without_limit);
		
		if ($results_number > 0){ // at least one record found
			$pages_number = get_pages_number($results_number, $records_per_page); // get the total number of pages
			$sql = build_select_part($fields_labels_ar, $table_name);
			if ($where_clause != ""){
				$sql .= " WHERE $where_clause";
			} // end if
			if(isset($export_to_csv) && $export_to_csv == 1 && $export_to_csv_feature == 1) {
				if (isset($csv_creation_time_limt)) {
					set_time_limit($csv_creation_time_limt);
				} // end if
				// execute the select without limit query for csv
				$res_records_csv = $db->send_query($sql);
				$csv = build_csv($res_records_csv, $fields_labels_ar);
				ob_end_clean();
				$db->free_result($res_records_csv);
				header("Content-Type: text/x-csv");
				header('Content-Disposition: attachment; filename="'.$table_name.'.csv"');

				echo $csv;
				exit;
			} // end if

			
			if (!isset($order)){
				// get the first field present in the results form as order
				$count_temp = 0;
				$fields_labels_ar_count = count($fields_labels_ar);
				while (!isset($order) && $count_temp < $fields_labels_ar_count) {
					if ($fields_labels_ar[$count_temp]["present_results_search_field"] === '1') {
						$order = $fields_labels_ar[$count_temp]["name_field"];
					} // end if
					$count_temp++;
				} // end while
				if (!isset($order)) { // if no fields are present in the results form, just use the first field as order, the form wiil be empty, this is just to prevent error messages when composing the sql query
					$order = $fields_labels_ar[0]["name_field"];
				} // end if
			} // end if

			$_SESSION['order_'.$table_name] = $order;

			if (!isset($order_type)){
				$order_type = "ASC";
				$_SESSION['order_type_'.$table_name] = $order_type;
			} // end if

			if ($page > ($pages_number-1)) {
				$page = $pages_number-1;
			} // end if
			
			$sql .= " ORDER BY ";

			// get the index of $fields_labels_ar corresponding to a field
			$count_temp = 0;
			foreach ($fields_labels_ar as $field){
				if ($field['name_field'] === $order){
					$field_index = $count_temp;
					break;
				} // end if
				$count_temp++;
			} // end foreach
			
			if ($fields_labels_ar[$field_index]["primary_key_field_field"] !== '' && $fields_labels_ar[$field_index]["primary_key_field_field"] !== NULL){

				$linked_fields_ar = explode($fields_labels_ar[$field_index]['separator_field'], $fields_labels_ar[$field_index]['linked_fields_field']);
				$is_first = 1;
				foreach ($linked_fields_ar as $linked_field){
					$sql .= "`".$fields_labels_ar[$field_index]['primary_key_table_field'].$alias_prefix.$linked_field.$alias_prefix.$fields_labels_ar[$field_index]['alias_suffix_field']."`";
					

					if ($is_first === 1){ // add the order type just to the first field e.g. order by field_1 DESC, field_2, field_3
						$sql .= ' '.$order_type;
						$is_first = 0;
					} // end if
					$sql .= ', ';
				} //end foreach
				$sql = substr($sql, 0, -2); // deleter the last ', '
			} // end if
			else{
				$sql .= "`$table_name`.`".$fields_labels_ar[$field_index]["name_field"]."`";
				$sql .= " $order_type";
			} // end else
			
			// add limit clause
			$res_records = $db->send_query_limit($sql, $records_per_page, $page*$records_per_page);

			if (isset($just_inserted) && $just_inserted == "1") {
				//txt_out("<p>".$normal_messages_ar["insert_result"]);
				txt_out("<p class='error_message'>&nbsp;&nbsp;&nbsp;*** ".$normal_messages_ar["record_inserted"]." ***</p>\n");
			} // end if

			if (isset($just_delete_all_authorizated) && $just_delete_all_authorizated == "1" && $enable_browse_authorization === 0) {
				txt_out("<p class='error_message'>&nbsp;&nbsp;&nbsp;*** ".$error_messages_ar["deleted_only_authorizated_records"]." ***</p>\n");
			} // end if

			if (isset($just_delete_no_authorization) && $just_delete_no_authorization == "1") {
				txt_out("<p class='error_message'>&nbsp;&nbsp;&nbsp;*** ".$error_messages_ar["no_authorization_update_delete"]." ***</p>\n");
			} // end if

			display_sql($sql);
			
			txt_out("<br>$results_number ".$normal_messages_ar["records_found"], "n_results_found");

			// get the number of records in the current table
			$sql = "SELECT COUNT(*) FROM `$table_name`";

			if ($enable_authentication === 1 && $enable_browse_authorization === 1) { // $ID_user_field_name = '$session->username' where clause part in order to select only the records the current user owns
				$ID_user_field_name = get_ID_user_field_name($fields_labels_ar);

				if ($ID_user_field_name !== false) { // no ID_user fields available, don't use authorization
					$sql .= " WHERE `$table_name`.`$ID_user_field_name` = '".$db->escape_string($session->username)."'";
				} // end if

			} // end if

			// execute the select query
			$res_count = $db->send_query($sql);
			while ($count_row = $db->db_fetch_row($res_count)) {
				$records_number = $count_row[0];
			} // end while
			$db->free_result($res_count);

			txt_out('&nbsp;&nbsp;('.$normal_messages_ar["total_records"].':'.$records_number.')', 'total_records');

			if ($enable_delete == "1" && $enable_delete_all_feature === 1) {
				echo " <a class='onlyscreen' onclick=\"if (!confirm('".$normal_messages_ar['confirm_delete?']."')){ return false;}elseif (!confirm('".$normal_messages_ar['really?']."')){ return false;}\" href='".$action."?table_name=". urlencode($table_name)."&function=delete_all&where_clause=".urlencode($where_clause_to_pass)."&page=".$page."&order=".urlencode($order)."&order_type=".$order_type."'>".$normal_messages_ar['delete_all']."</a>";
			} // end if

			if ($results_number > $records_per_page){ // display the navigation bar

				txt_out ("<br><span class='page_n_of_m'>".$normal_messages_ar["page"].($page+1).$normal_messages_ar["of"].$pages_number."</span>"); // "Page n of x" statement

				// build the navigation tool
				$navigation_tool = build_navigation_tool($table_name, $where_clause_to_pass, $pages_number, $page, $action, $order, $order_type);

				// display the navigation tool
				echo "&nbsp;&nbsp;&nbsp;&nbsp;".$navigation_tool."<br><br>";
			} // end if ($results_number > $records_per_page)

			$change_table_form = build_change_table_form();

			$records_per_page_form = build_records_per_page_form($dadabik_main_file, $records_per_page, $table_name);

			if ($change_table_form != ""){ // if there is more than one table to manage
				txt_out('<table><tr><td>'.$change_table_form.'</td><td>'.$records_per_page_form.'</td></tr></table>');
			} // end if
			else {
				txt_out($records_per_page_form);
			} // end else

			$results_type = "search";

			// build the HTML results table
			$results_table = build_results_table($fields_labels_ar, $table_name, $res_records, $results_type, $action, $where_clause_to_pass, $page, $order, $order_type);

			echo $results_table;

			if ( $export_to_csv_feature == 1) {
				echo "<a href='".$action."?table_name=". urlencode($table_name)."&function=".$function."&where_clause=".urlencode($where_clause_to_pass)."&page=".$page."&order=".urlencode($order)."&order_type=".$order_type."&export_to_csv=1'>";

				txt_out ($normal_messages_ar["export_to_csv"], "export_to_csv");

				echo "</a>";
			}

		} // end if
		else{
			display_sql($sql);

			$change_table_form = build_change_table_form();

			if ($change_table_form != ""){ // if there is more than one table to manage
				txt_out('<table><tr><td>'.$change_table_form.'</td><td>'.$records_per_page_form.'</td></tr></table>');
			} // end if
			else {
				txt_out($records_per_page_form);
			} // end else

			txt_out($normal_messages_ar["no_records_found"]);
		} // end else
		break;
	case "details":
		if ($enable_details == "1" && ($enable_authentication === 0 || $enable_browse_authorization === 0 || current_user_is_owner($where_field, $where_value, $table_name, $fields_labels_ar))){
			// build the details select query
			$sql = build_select_part($fields_labels_ar, $table_name);

			$sql .= " WHERE `$table_name`.`$where_field` = '$where_value'";

			if (isset($just_next_record_on_last_one) && $just_next_record_on_last_one === 1) {
				txt_out("<p>".$error_messages_ar["this_record_is_the_last_one"]."</p>");
			} // end if

			if (isset($just_previous_record_on_first_one) && $just_previous_record_on_first_one === 1) {
				txt_out("<p>".$error_messages_ar["this_record_is_the_first_one"]."</p>");
			} // end if
			
			display_sql($sql);

			txt_out("<h3>".$normal_messages_ar["details_of_record"]."</h3>");

			txt_out('<p><a class="previous_next" href="'.$dadabik_main_file.'?function=choose_previous_record&table_name='.$table_name.'&where_field='.urlencode($where_field).'&where_value='.urlencode($where_value).'&from_function='.$function.'">&lt;&lt; '.$normal_messages_ar['previous'].'</a>&nbsp;&nbsp;&nbsp;<a class="previous_next" href="'.$dadabik_main_file.'?function=choose_next_record&table_name='.$table_name.'&where_field='.urlencode($where_field).'&where_value='.urlencode($where_value).'&from_function='.$function.'">'.$normal_messages_ar['next'].' &gt;&gt;</a></p>');

			// execute the select query
			$res_details = $db->send_query($sql);

			// build the HTML details table
			$details_table = build_details_table($fields_labels_ar, $res_details);
			
			// display the HTML details table
			echo $details_table;
		} // end if
		else {
			txt_out("<p class='error_message'>&nbsp;&nbsp;&nbsp;*** ".$error_messages_ar["no_authorization_view"]." ***</p>");
		} // end else
		break;
	case "edit":
		if ($enable_edit == "1" && ($enable_authentication === 0 || $enable_browse_authorization === 0 || current_user_is_owner($where_field, $where_value, $table_name, $fields_labels_ar))){

			if (isset($just_updated) && $just_updated == "1") {
				//txt_out("<h3>".$normal_messages_ar["update_result"]."</h3>");
				txt_out("<p class='error_message'>&nbsp;&nbsp;&nbsp;*** ".$normal_messages_ar["record_updated"]." ***</p>");
			}
			if (isset($just_updated_no_authorization) && $just_updated_no_authorization == "1") {
				txt_out("<p class='error_message'>&nbsp;&nbsp;&nbsp;*** ".$error_messages_ar["no_authorization_update_delete"]." ***</p>");
			}

			if (isset($just_next_record_on_last_one) && $just_next_record_on_last_one === 1) {
				txt_out("<p class='error_message'>&nbsp;&nbsp;&nbsp;*** ".$error_messages_ar["this_record_is_the_last_one"]." ***</p>");
			} // end if

			if (isset($just_previous_record_on_first_one) && $just_previous_record_on_first_one === 1) {
				txt_out("<p class='error_message'>&nbsp;&nbsp;&nbsp;*** ".$error_messages_ar["this_record_is_the_first_one"]." ***</p>");
			} // end if

			// build the details select query
			$sql = "SELECT * FROM `$table_name` WHERE `$table_name`.`$where_field` = '$where_value'";

			display_sql($sql);
			txt_out("<h3>".$normal_messages_ar["edit_record"]."</h3>");
			txt_out('<p><a class="previous_next" href="'.$dadabik_main_file.'?function=choose_previous_record&table_name='.$table_name.'&where_field='.urlencode($where_field).'&where_value='.urlencode($where_value).'&from_function='.$function.'">&lt;&lt; '.$normal_messages_ar['previous'].'</a>&nbsp;&nbsp;&nbsp;<a class="previous_next" href="'.$dadabik_main_file.'?function=choose_next_record&table_name='.$table_name.'&where_field='.urlencode($where_field).'&where_value='.urlencode($where_value).'&from_function='.$function.'">'.$normal_messages_ar['next'].' &gt;&gt;</a></p>');

			// execute the select query
			$res_details = $db->send_query($sql);
			
			$form_type = "update";

			$show_insert_form_after_error = 0;
			$show_edit_form_after_error = 0;

			// display the form
			$form = build_form($table_name, $action, $fields_labels_ar, $form_type, $res_details, $where_field, $where_value, $show_insert_form_after_error, $show_edit_form_after_error);
			
			echo $form;

			reset($fields_labels_ar);
		} // end if
		else {
			txt_out("<p class='error_message'>&nbsp;&nbsp;&nbsp;*** ".$error_messages_ar["no_authorization_view"]." ***</p>");
		} // end else
		break;
	case "update":
		if ($enable_edit == "1"){
			$check = 0;
			$check = check_required_fields($fields_labels_ar);
			if ($check == 0){
				txt_out($normal_messages_ar["required_fields_missed"], "error_messages_form");
			} // end if ($check == 0)
			else{ // required fields are ok
				// check field lengths
				$check = 0;
				$check = check_length_fields($fields_labels_ar);
				if ($check == 0){
					txt_out($normal_messages_ar["fields_max_length"], "error_messages_form");
				} // end if ($check == 0)
				else{ // fields length are ok
					$check = 0;
					$content_error_type = "";
					$check = check_fields_types($fields_labels_ar, $content_error_type);
					if ($check == 0){
						txt_out($normal_messages_ar["{$content_error_type}_not_valid"], "error_messages_form");
					} // end if ($check == 0)
					else{ // type field are ok
						if( $enable_authentication === 0 ||  $enable_update_authorization === 0 || current_user_is_owner($where_field, $where_value, $table_name, $fields_labels_ar)){

							// update the record
							update_record($fields_labels_ar, $table_name, $table_internal_name, $where_field, $where_value);

							if ($enable_update_notice_email_sending === 1) {

								$sql = build_select_part($fields_labels_ar, $table_name);

								$sql .= " WHERE `".$table_name."`.`".$where_field."` = '".$where_value."'";

								// execute the select query
								$res_details = $db->send_query($sql);
								
								// build the update notice message
								$update_notice_email = build_insert_update_notice_email_record_details($fields_labels_ar, $res_details);

								$to_addresses = '';
								$cc_addresses = '';
								$bcc_addresses = '';

								foreach ($update_notice_email_to_recipients_ar as $update_notice_email_to_recipient){
									$to_addresses .= $update_notice_email_to_recipient.', ';
								} // end foreach

								$to_addresses = substr($to_addresses, 0, -2); // delete the last ', '

								foreach ($update_notice_email_cc_recipients_ar as $update_notice_email_cc_recipient){
									$cc_addresses .= $update_notice_email_cc_recipient.', ';
								} // end foreach

								$cc_addresses = substr($cc_addresses, 0, -2); // delete the last ', '

								foreach ($update_notice_email_bcc_recipients_ar as $update_notice_email_bcc_recipient){
									$bcc_addresses .= $update_notice_email_bcc_recipient.', ';
								} // end foreach

								$bcc_addresses = substr($bcc_addresses, 0, -2); // delete the last ', '

								$additional_headers = '';

								if ($cc_addresses != '') {
									$additional_headers .= "Cc:".$cc_addresses."\n";
								} // end if

								if ($bcc_addresses != '') {
									$additional_headers .= "Bcc:".$bcc_addresses."\n";
								} // end if
								$additional_headers .= "Content-Type: text/plain; charset=utf-8";
								$subject = DB_NAME.' - '.$table_name.' - '.$normal_messages_ar['new_update_executed'];
								$subject = $mailer->mail_header_escape($subject);
								mail($to_addresses, $subject, $update_notice_email, $additional_headers);
							} // end if
							header('Location:'.$site_url.$dadabik_main_file.'?table_name='.urlencode($table_name).'&function=edit&where_field='.urlencode($where_field)."&where_value=".urlencode($where_value).'&just_updated=1');

							exit;
						} // end if
						else {
							header('Location:'.$site_url.$dadabik_main_file.'?table_name='.urlencode($table_name).'&function=edit&where_field='.urlencode($where_field)."&where_value=".urlencode($where_value).'&just_updated_no_authorization=1');
							exit;
						} // end else
					} // end else
				} // end else
			} // end else
			if ($check === 0) {

				txt_out("<h3>".$normal_messages_ar["edit_record"]."</h3>");
				txt_out('<p><a class="previous_next" href="'.$dadabik_main_file.'?function=choose_previous_record&table_name='.$table_name.'&where_field='.urlencode($where_field).'&where_value='.urlencode($where_value).'&from_function='.$function.'">&lt;&lt; '.$normal_messages_ar['previous'].'</a>&nbsp;&nbsp;&nbsp;<a class="previous_next" href="'.$dadabik_main_file.'?function=choose_next_record&table_name='.$table_name.'&where_field='.urlencode($where_field).'&where_value='.urlencode($where_value).'&from_function='.$function.'">'.$normal_messages_ar['next'].' &gt;&gt;</a></p>');

				$form_type = "update";

				$res_details = '';

				$show_insert_form_after_error = 1;
				$show_edit_form_after_error = 1;


				// display the form
				$form = build_form($table_name, $action, $fields_labels_ar, $form_type, $res_details, $where_field, $where_value, $show_insert_form_after_error, $show_edit_form_after_error);

				echo $form;

			} // end if
		} // end if
		break;
	case "delete":
		if ($enable_delete == "1") {

			// deletion of a record from a table, after the deletion we need to redirect to the show results mode
			$location_url = $site_url.$dadabik_main_file.'?table_name='.urlencode($table_name).'&function=search&where_clause='.urlencode($where_clause);

			if( $enable_authentication === 0 || $enable_delete_authorization === 0 || current_user_is_owner($where_field, $where_value, $table_name, $fields_labels_ar)){
				delete_record ($table_name, $where_field, $where_value);
			} // end if
			else {
				$location_url .= '&just_delete_no_authorization=1';
			} // end else

			header('Location: '.$location_url);

			exit;
		} // end if
		break;
	case "delete_all":
		if ($enable_delete == "1" && $enable_delete_all_feature === 1) {

			$ID_user_field_name = get_ID_user_field_name($fields_labels_ar);

			delete_multiple_records ($table_name, $where_clause, $ID_user_field_name);

			$location_url = $site_url.$dadabik_main_file.'?table_name='.urlencode($table_name)."&function=search&where_clause=&page=0";

			if ($enable_browse_authorization === 0 && $ID_user_field_name !== false) { // if the user see just his owns records the message doesn't make sense
				$location_url .= '&just_delete_all_authorizated=1';
			} // end if

			header('Location: '.$location_url);

			exit;
		} // end if
		break;
	case "show_insert_form":
		if ($enable_insert == "1") {
			txt_out("<h3>".$normal_messages_ar["insert_record"]."</h3>");
			$form_type = "insert";
			$res_details = "";

			$show_insert_form_after_error = 0;
			$show_edit_form_after_error = 0;

			// display the form
			$form = build_form($table_name, $action, $fields_labels_ar, $form_type, $res_details, "", "", $show_insert_form_after_error, $show_edit_form_after_error);
			echo $form;
		} // end if
		break;
	case "show_search_form":
		txt_out("<h3>".$normal_messages_ar["search_records"]."</h3>");

		$form_type = "search";
		$res_details = "";

		$show_insert_form_after_error = 0;
		$show_edit_form_after_error = 0;

		// display the form
		$form = build_form($table_name, $action, $fields_labels_ar, $form_type, $res_details, "", "", $show_insert_form_after_error, $show_edit_form_after_error);
		echo $form;
		break;
	case "choose_next_record";
		if (isset($where_clause) && isset($order) && isset($order_type)) { // could be not set if the session has expired

			// rebuild the query for the current results

			$sql = build_select_part($fields_labels_ar, $table_name);

			if ($where_clause !== '') {
				$sql .= " WHERE ".$where_clause;
			} // end if
			$sql .= " ORDER BY `$table_name`.`$order` $order_type";

			// execute the query

			$res = $db->send_query($sql);

			// loop through the recordset, when find the current record, read another one and save its where_value

			$record_found = 0;

			while ($row = $db->db_fetch_assoc($res)) {
				if ($row[$where_field] == $where_value) {
					$record_found = 1;

					if ($row = $db->db_fetch_assoc($res)) { // could be false if the current is the last record
						$new_where_value = $row[$where_field];
					} // end if
					
				} // end if
			} // end while

			if ($record_found === 0) { // the record has not been found
				txt_out('<p>'.$error_messages_ar['record_from_which_you_come_no_longer_exists'].'</p>');
			} // end if
			elseif (!isset($new_where_value)) { // the current record is the last one
				header("Content-Type: text/html;charset=utf-8"); 
				header('Location: '.$site_url.$dadabik_main_file.'?table_name='.$table_name.'&function='.$from_function.'&where_field='.urlencode($where_field).'&where_value='.urlencode($where_value).'&just_next_record_on_last_one=1');
				exit;
			} // end elseif
			else {
				// redirect to the function from which the user comes (edit or detail) using the where_value just discovered

				header("Content-Type: text/html;charset=utf-8"); 
				header('Location: '.$site_url.$dadabik_main_file.'?table_name='.$table_name.'&function='.$from_function.'&where_field='.urlencode($where_field).'&where_value='.urlencode($new_where_value));
				exit;
			} // end else

		} // end if
		break;
	case "choose_previous_record";
		if (isset($where_clause) && isset($order) && isset($order_type)) { // could be not set if the session has expired

			// rebuild the query for the current results

			$sql = build_select_part($fields_labels_ar, $table_name);

			if ($where_clause !== '') {
				$sql .= " WHERE $where_clause";
			} // end if
			$sql .= " ORDER BY `$table_name`.`$order` $order_type";

			// execute the query

			$res = $db->send_query($sql);

			// loop through the recordset, when find the current record, read another one and save its where_value

			$record_found = 0;

			while ($row = $db->db_fetch_assoc($res)) {
				if ($row[$where_field] == $where_value) {
					$record_found = 1;
				} // end if
				if ($record_found === 0) {
					$new_where_value = $row[$where_field];
				} // end if
			} // end while

			if ($record_found === 0) { // the record has not been found
				txt_out('<p>'.$error_messages_ar['record_from_which_you_come_no_longer_exists'].'</p>');
			} // end if
			elseif (!isset($new_where_value)) { // the current record is the first one
				header("Content-Type: text/html;charset=utf-8"); 
				header('Location: '.$site_url.$dadabik_main_file.'?table_name='.$table_name.'&function='.$from_function.'&where_field='.urlencode($where_field).'&where_value='.urlencode($where_value).'&just_previous_record_on_first_one=1');
				exit;
			} // end elseif
			else {
				// redirect to the function from which the user comes (edit or detail) using the where_value just discovered

				header("Content-Type: text/html;charset=utf-8"); 
				header('Location: '.$site_url.$dadabik_main_file.'?table_name='.$table_name.'&function='.$from_function.'&where_field='.urlencode($where_field).'&where_value='.urlencode($new_where_value));
				exit;
			} // end else

		} // end if
		break;
} // end swtich ($function)

// include footer
include ("skins/$skin/footer_datadmin.php");
?>
