<?php
/**
 * archive.php
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
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 * @see       datadmin.php
 */

include ("include/datadmin/config.php");
include ("include/datadmin/languages/".$language.".php");
include_once ("include/classes/login/session.php");
include ("include/datadmin/functions.php");
$url = "archive.php";
include ("include/datadmin/check_login.php");
include ("include/functions/archive.php");


$action = $url;
$details = "0";

$sql = "SELECT name_table, alias_table FROM $table_list_name";
$i=0;
$db->send_query($sql);
while ($row = $db->db_fetch_row()) {
	$table_infos_ar[$i]['table_name'] = $row[0];
	$table_infos_ar[$i]['table_alias'] = $row[1];
	$i++;
}
$db->free_result();
if (empty($_GET['table_name'])) {
	$table_name = $table_infos_ar[0]['table_name'];
} else {
	$table_name = $_GET['table_name'];
}
foreach ($table_infos_ar as $table_infos) {
	if ($table_infos['table_name'] == $table_name) {
		$table_alias = $table_infos['table_alias'];
		break;
	}
}

if (isset($_GET["where_clause"])){
	$where_clause = stripslashes($_GET["where_clause"]);
	$_SESSION['where_clause_archive__'.$table_name] = $where_clause;
} // end if
elseif (isset($_SESSION['where_clause_archive__'.$table_name])){
	$where_clause = $_SESSION['where_clause_archive__'.$table_name];
} // end if

// the current page in records results (0......n)
// navigation bar, order, delete and delete all links, export to csv, show_all link
if (isset($_GET["page"])){
	$page = $_GET["page"];
	$_SESSION['page_archive__'.$table_name] = $page;
} // end if
elseif (isset($_SESSION['page_archive__'.$table_name])){
	$page = $_SESSION['page_archive__'.$table_name];
} // end if

// the field used to order the results
// navigation bar, order, delete and delete all links, export to csv
// why strepslashes? The first time $order is calculated in the code, so when I pass it through links new slashes are added if the field name contains quotes and I have to strip them
if (isset($_GET["order"])){
	$order = stripslashes($_GET["order"]);
	$_SESSION['order_archive__'.$table_name] = $order;
} // end
elseif (isset($_SESSION['order_archive__'.$table_name])){
	$order = $_SESSION['order_archive__'.$table_name];
} // end if

// the order type ('ASC'|'DESC')
// navigation bar, order, delete and delete all links, export to csv
if (isset($_GET["order_type"])){
	$order_type = $_GET["order_type"];
	$_SESSION['order_type_archive__'.$table_name] = $order_type;
} // end
elseif (isset($_SESSION['order_type_archive__'.$table_name])){
	$order_type = $_SESSION['order_type_archive__'.$table_name];
} // end if

// the number of result records to be displayed in a page
// records_per_page listbox
if (isset($_GET["records_per_page"])){ // the user set a new value from the listbox
	$records_per_page = (int)$_GET["records_per_page"];
	$_SESSION['records_per_page_archive__'.$table_name] = $records_per_page;
} // end
elseif (isset($_SESSION['records_per_page_archive__'.$table_name])){ // otherwise use the value saved for this table
	$records_per_page = $_SESSION['records_per_page_archive__'.$table_name];
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
// navigation bar, order, edit, detail, delete and delete all links, export to csv, bottom links, insert/edit/search form
if (isset($_GET["function"])){ // from the homepage
	$function = $_GET["function"];
} // end
else{
	$function = "search";
} // end else

// the function ('details') from which the user click on update buttons
if (isset($_GET["from_function"])){
	$from_function = $_GET["from_function"];
} // end
else{
	$from_function = "search";
} // end else

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

// set to 1 when you want to show the details table
if (isset($_GET["details"]) && (int)$_GET["details"] === 1) {
	if (isset($where_clause)) {
		unset($where_clause);
	} // end if
	$where_clause = "`archive__$table_name`.`$where_field` = '$where_value'";
	$details = "1";
}

// set to 1 when a research has been just executed
// from the search form
if (isset($_GET["execute_search"])){
	$execute_search = $_GET["execute_search"];
} // end if

// set to 1 after a delete with no authorization
// delete case
if (isset($_GET["just_delete_no_authorization"])){
	$just_delete_no_authorization = $_GET["just_delete_no_authorization"];
} // end if

// the timestamp
if (isset($_GET["timestamp"])){
	$timestamp = $_GET["timestamp"];
} // end if

$fields_labels_ar = build_fields_labels_array($prefix_internal_table.$table_name, 1);

if ($function == "restore") {
	// restore of a record from the archiv, after the restore we need to redirect to the show results or details mode
	if ($from_function  == "details") {
		$call = "&details=1&where_field=".urlencode($where_field)."&where_value=".urlencode($where_value);
	} else {
		$call = "&function=".urlencode($from_function);
	}
	$location_url = $site_url.$url.'?table_name='.urlencode($table_name).$call;
	if (!empty($where_clause)){
		$location_url .= "&where_clause=".urlencode($where_clause);
	}
	$sql_1 = "SELECT COUNT(*) FROM `$table_name` WHERE `$where_field` = '$where_value'";
	  // test, if the value to restore exist still in the main database
	$db->send_query($sql_1);
	$value_exist = $db->db_fetch_row();
	$db->free_result();
	if( $enable_authentication === 0 || $enable_update_authorization === 0 || $value_exist[0] == 0 || current_user_is_owner($where_field, $where_value, $table_name, $fields_labels_ar)){
		$where = "`$where_field` = '$where_value'";
		$db->restore_table_row($table_name, $where, $timestamp);
		$location_url .= '&just_restored=1';
	} // end if
	else {
		$location_url .= '&just_delete_no_authorization=1';
	} // end else
	header("Content-Type: text/html;charset=utf-8"); 
	header('Location: '.$location_url);
	exit;
}
$skin = $session->skin;
include("./skins/$skin/header_datadmin_top.php");
include("./skins/$skin/frame.php");
?>
<table class="main_table" cellpadding="10">
<tr>
<td valign="top">
<h1><?php echo _("Archive"); ?></h1>
<p><?php echo _("In the archive we are documenting all updates and deletions of records from the database.
<br>A previous version of a record can be restored."); ?></p>

<?php
	if (isset($_GET["just_restored"]) && $_GET["just_restored"] == 1) {
		echo "<p class='error_message'>&nbsp;&nbsp;&nbsp;*** " . _("One record was restored successfully!") . " ***</p>\n";
	}
echo "<h2 class='center'>" . _("Table:") . " " . $table_alias . "</h2><br>\n";
?>
<table width="100%" class="onlyscreen">
  <tr>
    <td align="left">
      <span class="NavBlock"><a class="NavLink" href="<?php echo $dadabik_main_file; ?>?table_name=<?php echo urlencode($table_name); ?>"><?php echo _("Data maintenance"); ?></a> &bull; <a class="NavLink" href="<?php echo $url; ?>?function=show_search_form&table_name=<?php echo urlencode($table_name); ?>"><?php echo $submit_buttons_ar["search_short"]; ?></a> &bull; <a class="NavLink" href="<?php echo $url; ?>?function=search&table_name=<?php echo urlencode($table_name); ?>"><?php echo $normal_messages_ar["last_search_results"]; ?></a> &bull; <a class="NavLink" href="<?php echo $url; ?>?function=search&empty_search_variables=1&table_name=<?php echo urlencode($table_name); ?>"><?php echo $normal_messages_ar["show_all"]; ?></a> &bull; <a class="NavLink" href="./express.php"><span class="nobr"><?php echo _("Express-Tool"); ?></span></a></span>
    </td>
  </tr>
</table>
<?php
if ((urlencode($table_name) == "remedies") && (empty($function) || $function === 'search' || $from_function === 'search')) {
?>
<br>
<strong><?php echo _("Select initial letter:"); ?> </strong>
<?php
	$abc_ar = array('A', 'B', 'C', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
	foreach ($abc_ar as $abc) {
		echo ("<a class='abc' href=\"$url?function=search&table_name=".urlencode($table_name)."&where_clause=rem_short LIKE '$abc%'&page=0\">&nbsp;$abc </a>\n");
	}
?>
<br>
<?php
}
if (urlencode($table_name) == "symptoms" && (empty($function) || $function === 'search' || $from_function === 'search')) {
?>
<br>
<form method="POST" action="<?php echo $url; ?>?table_name=<?php echo urlencode($table_name); ?>&page=0&function=search&execute_search=1" enctype="multipart/form-data">
<label for="mainrubrics"><strong><?php echo _("Select the main rubric:"); ?> &nbsp;&nbsp;</strong></label>
<input name="rubric_id__select_type" type="hidden" value="is_equal">
<select name="mainrubrics">
	<option value=""><?php echo _("all rubrics"); ?></option>
<?php
$lang = $session->lang;
$sql = "SELECT rubric_id, rubric_$lang FROM main_rubrics ORDER BY rubric_$lang";
$db->send_query($sql);
while(list($rubric_id, $rubric) = $db->db_fetch_row()) {
	echo ("          <option value='$rubric_id'>$rubric</option>\n");
}
$db->free_result();
?>
</select>
<input type="submit" class="submit" style="font-size:11px;" value="<?php echo _("Select"); ?>">
</form>
<?php
}
switch($function){
	case "search":
		if ($details == "1") {
			echo("<br><h3>".$normal_messages_ar["details_of_record"]."</h3>");
		}
		if (!isset($page)) {
			$page = 0;
			if ($details != "1") {
				$_SESSION['page_archive__'.$table_name] = $page;
			}
		} // end if
		
		// build the select query
		if (isset($execute_search) && $execute_search === '1'){ // it's a search result, the user has just filled the search form, so we have to build the select query
		//if (!isset($where_clause)){ // it's a search result, the user has just filled the search form, so we have to build the select query
			$where_clause = build_where_clause($fields_labels_ar, "archive__".$table_name);
			$page = 0;
			$_SESSION['page_archive__'.$table_name] = $page;
		} // end if
		elseif (!isset($where_clause)) { // when I call index for the first time
			$where_clause = '';
		} // end else
		
		// save the where_clause without the user part to pass 
		$where_clause_to_pass = $where_clause;
		
		if ($details != "1") {
			$_SESSION['where_clause_archive__'.$table_name] = $where_clause;
		}
		
		$sql = "SELECT COUNT(*) FROM archive__$table_name";
		if ($where_clause != ""){
			$sql .= " WHERE $where_clause";
		} // end if
		$res_records_without_limit = $db->send_query($sql);
		while ($count_row = $db->db_fetch_row($res_records_without_limit)) {
			$results_number = $count_row[0];  // get the number of results
		} // end while
		$db->free_result($res_records_without_limit);
		
		$change_table_form = build_change_table_form_archiv($table_infos_ar, $table_name);
		$records_per_page_form = build_records_per_page_form($action, $records_per_page, $table_name);
		
		if ($results_number > 0){ // at least one record found
			$pages_number = get_pages_number($results_number, $records_per_page); // get the total number of pages
			$sql = build_select_part($fields_labels_ar, "archive__".$table_name);
			if ($where_clause != ""){
				$sql .= " WHERE $where_clause";
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
				if (!isset($order)) { // if no fields are present in the results form, just use the first field as order, the form will be empty, this is just to prevent error messages when composing the sql query
					$order = $fields_labels_ar[0]["name_field"];
				} // end if
			} // end if
			if ($details != "1") {
				$_SESSION['order_archive__'.$table_name] = $order;
			}
		
			if (!isset($order_type)){
				$order_type = "ASC";
				if ($details != "1") {
					$_SESSION['order_type_archive__'.$table_name] = $order_type;
				}
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
			
			if (!empty($fields_labels_ar[$field_index]["primary_key_field_field"])){
				$linked_fields_ar = explode($fields_labels_ar[$field_index]['separator_field'], $fields_labels_ar[$field_index]['linked_fields_field']);
				$is_first = 1;
				foreach ($linked_fields_ar as $linked_field){
					$sql .= $fields_labels_ar[$field_index]['primary_key_table_field'].$alias_prefix.$linked_field.$alias_prefix.$fields_labels_ar[$field_index]['alias_suffix_field'];
					
		
					if ($is_first === 1){ // add the order type just to the first field e.g. order by field_1 DESC, field_2, field_3
						$sql .= ' '.$order_type;
						$is_first = 0;
					} // end if
					$sql .= ', ';
				} //end foreach
				$sql = substr($sql, 0, -2); // deleter the last ', '
			} // end if
			else{
				$sql .= 'archive__'.$table_name.'.'.$fields_labels_ar[$field_index]["name_field"];
				$sql .= ' '.$order_type;
			} // end else
			if ($fields_labels_ar[$field_index]["name_field"] != "timestamp") {
				$sql .= ', archive__'.$table_name.'.timestamp DESC';
			}
			$res_records = $db->send_query_limit($sql, $records_per_page, $page*$records_per_page);
			
			if (isset($just_delete_no_authorization) && $just_delete_no_authorization == "1") {
				echo("<p class='error_message'>&nbsp;&nbsp;&nbsp;*** ".$error_messages_ar["no_authorization_restore"]." ***</p>\n");
			} // end if
		
			display_sql($sql);
		
			echo("<span class='n_results_found'><br>$results_number ".$normal_messages_ar["records_found"]."</span>");
		
			// get the number of records in the current table
			$sql = "SELECT COUNT(*) FROM archive__".$table_name;
		
			if ($enable_authentication === 1 && $enable_browse_authorization === 1) { // $ID_user_field_name = '$session->username' where clause part in order to select only the records the current user owns
				$ID_user_field_name = get_ID_user_field_name($fields_labels_ar);
		
				if ($ID_user_field_name !== false) { // no ID_user fields available, don't use authorization
					$sql .= " WHERE archive__".$table_name.'.'.$ID_user_field_name." = '".$db->escape_string($session->username)."'";
				} // end if
		
			} // end if
		
			// execute the select query
			$res_count = $db->send_query($sql);
			while ($count_row = $db->db_fetch_row($res_count)) {
				$records_number = $count_row[0];
			}
			$db->free_result($res_count);
		
			echo("<span class='total_records'>&nbsp;&nbsp;(".$normal_messages_ar["total_records"].": $records_number)</span>");
		
			if ($results_number > $records_per_page){ // display the navigation bar
		
				echo ("<br><span class='page_n_of_m'>".$normal_messages_ar["page"].($page+1).$normal_messages_ar["of"].$pages_number."</span>"); // "Page n of x" statement
		
				// build the navigation tool
				$navigation_tool = build_navigation_tool($table_name, $where_clause_to_pass, $pages_number, $page, $action, $order, $order_type);
		
				// display the navigation tool
				echo "&nbsp;&nbsp;&nbsp;&nbsp;".$navigation_tool."<br><br>";
			} // end if ($results_number > $records_per_page)
		
			if ($change_table_form != ""){ // if there is more than one table to manage
				echo("<table>\n  <tr>\n   <td>\n".$change_table_form."    </td>\n    <td>".$records_per_page_form."    </td>\n  </tr>\n</table>\n");
			} // end if
			else {
				echo($records_per_page_form);
			} // end else
		
			// build the HTML results table
			$results_table = build_results_table_archiv($fields_labels_ar, $table_name, $res_records, $action, $where_clause_to_pass, $page, $order, $order_type, $table_infos_ar, $details);
			$db->free_result($res_records);
		
			echo $results_table;
		} // end if
		else{
			display_sql($sql);
		
			if ($change_table_form != ""){ // if there is more than one table to manage
				echo("<table>\n  <tr>\n    <td>\n".$change_table_form."    </td>\n    <td>".$records_per_page_form."    </td>\n  </tr>\n</table>\n");
			} // end if
			else {
				echo($records_per_page_form);
			} // end else
		
			echo($normal_messages_ar["no_records_found"]);
		} // end else
		break;
	case "show_search_form":
		echo("<h3>".$normal_messages_ar["search_records"]."</h3>");

		$form_type = "search";
		$res_details = "";

		$show_insert_form_after_error = 0;
		$show_edit_form_after_error = 0;

		// display the form
		$form = build_form($table_name, $action, $fields_labels_ar, $form_type, $res_details, "", "", $show_insert_form_after_error, $show_edit_form_after_error);
		echo $form;
		break;
} // end switch ($function)
?>
<hr class="onlyscreen">
<table width="100%" class="onlyscreen">
  <tr>
    <td align="left">
      <span class="NavBlock"><a class="NavLink" href="<?php echo $dadabik_main_file; ?>?table_name=<?php echo urlencode($table_name); ?>"><?php echo _("Data maintenance"); ?></a> &bull; <a class="NavLink" href="<?php echo $url; ?>?function=show_search_form&table_name=<?php echo urlencode($table_name); ?>"><?php echo $submit_buttons_ar["search_short"]; ?></a> &bull; <a class="NavLink" href="<?php echo $url; ?>?function=search&table_name=<?php echo urlencode($table_name); ?>"><?php echo $normal_messages_ar["last_search_results"]; ?></a> &bull; <a class="NavLink" href="<?php echo $url; ?>?function=search&empty_search_variables=1&table_name=<?php echo urlencode($table_name); ?>"><?php echo $normal_messages_ar["show_all"]; ?></a> &bull; <a class="NavLink" href="./express.php"><span class="nobr"><?php echo _("Express-Tool"); ?></span></a></span>
    </td>
  </tr>
</table>
</td>
</tr>
</table>
<?php
include("./skins/$skin/footer.php")
?>
