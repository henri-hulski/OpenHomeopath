<?php

/**
 * forms/saved_reps.php
 *
 * This file provides a form for managing your saved repertorizations from your user account.
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
 * @category  Homeopathy
 * @package   SaveReps
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

/**
 * build_saved_reps_table displays the users saved repertorizations in a nicely formatted html table.
 *
 * @param string  $order_by   row by which the table will be ordered
 * @param string  $order_type order direction: ASC | DESC
 * @param string  $username   user which repertorizations will be shown
 * @param string  $user_url   the url of the userinfo page
 * @param integer &$num_reps  number of saved repertorizations to be shown
 * @param boolean $self       true if the user is watching his own account, false otherwise
 *
 * @return string html table with the saved repertorizations
 * @access public
 */
function build_saved_reps_table($order_by, $order_type, $username, $user_url, &$num_reps, $self = true){
	global $db;
	// build the table heading
	$row_headers_ar = array (
		'rep_timestamp' => _("Date"),
		'rep_id' => _("RepNo"),
		'patient_id' => _("Patient"),
		'rep_public' => _("public")
	);
	$saved_reps_table = "<div class='scrollableContainer'>\n";
	$saved_reps_table .= "  <div  class='scrollingArea'>\n";
	$saved_reps_table .= "    <table class='saved_reps'>\n";

	// build the table heading
	$saved_reps_table .= "      <thead>\n";
	$saved_reps_table .= "        <tr>\n";
	$saved_reps_table .= "          <th class='radio'><div>&nbsp;</div></th>\n"; // skip the first column for radiobutton
	foreach ($row_headers_ar as $row => $header) {
		$saved_reps_table .= "          <th class='$row'><div>";
		$field_is_current_order_by = 0;
		if ($order_by != $row){ // the results are not ordered by this field at the moment
			$link_class="order_link_2";
			if ($row == "rep_timestamp") {
				$new_order_type = "DESC";
			} else {
				$new_order_type = "ASC";
			}
		} else {
			$field_is_current_order_by = 1;
			$link_class="order_link_2_selected";
			if ( $order_type == "DESC") {
				$new_order_type = "ASC";
			} else {
				$new_order_type = "DESC";
			}
		}
			
		$saved_reps_table .= "<a class='$link_class' href='" . $user_url . "order_by=$row&order_type=$new_order_type'";
		if ($self) {
			$saved_reps_table .= " onclick=\"return reloadSavedRepsTable('$row', '$new_order_type')\"";
		}
		$saved_reps_table .= ">";

		if ($field_is_current_order_by === 1) {
			if ($order_type === 'ASC') {
				$saved_reps_table .= '&uarr; ';
			} else {
				$saved_reps_table .= '&darr; ';
			}
		}
			
		$saved_reps_table .= "$header</a></div></th>\n";
	}
	$saved_reps_table .= "        </tr>\n";
	$saved_reps_table .= "      </thead>\n";

	// build the table body
	$i = 0;
	$class = "unchecked_1";
	$query = "SELECT rep_id, patient_id, rep_prescription, rep_note, UNIX_TIMESTAMP(rep_timestamp), rep_public FROM repertorizations WHERE username = '$username' ";
	if (!$self) {
		$query .= "AND rep_public = 1 ";
	}
	if ($order_by == 'rep_timestamp') {
		$query .= "ORDER BY $order_by $order_type, rep_id $order_type";
	} else {
		$query .= "ORDER BY $order_by $order_type";
	}
	$db->send_query($query);
	$saved_reps_table .= "      <tbody>\n";
	while (list($rep_id, $patient_id, $rep_prescription, $rep_note, $rep_timestamp, $rep_public) = $db->db_fetch_row()) {
		$i++;
		$date = date ("d.m.Y", $rep_timestamp);
		if ($rep_public == 1) {
			$public = _("yes");
		} else {
			$public = _("no");
		}
		if (empty($patient_id)) {
			$patient_id = "&nbsp;";
		}
		$saved_reps_table .= "        <tr class='$class' id='row_$i'>\n";
		$saved_reps_table .= "          <td class='radio'><div><input type='radio' id='radio_$i' value='$rep_id%$patient_id%$rep_prescription%$rep_note%$rep_timestamp%$rep_public' name='saved_rep' onclick=\"return colorizeRadioRow('radio','row','saved_reps_form');\" onkeyup=\"return colorizeRadioRow('radio','row','saved_reps_form');\"></div></td>\n";
		$saved_reps_table .= "          <td class='rep_timestamp'><div><label for='radio_$i'>$date</label></td>\n";
		$saved_reps_table .= "          <td class='rep_id'><div><label for='radio_$i'>$rep_id</label></div></td>\n";
		$saved_reps_table .= "          <td class='patient_id'><div><label for='radio_$i'>$patient_id</label></div></td>\n";
		$saved_reps_table .= "          <td class='rep_public'><div><label for='radio_$i'>$public</label></div></td>\n";
		$saved_reps_table .= "        </tr>\n";
		$class = ($class == "unchecked_1") ? "unchecked_2" : "unchecked_1";
	}
	$num_reps = $db->db_num_rows();
	$db->free_result();
	$saved_reps_table .= "      </tbody>\n";

	$saved_reps_table .= "    </table>\n";
	$saved_reps_table .= "  </div>\n";
	$saved_reps_table .= "</div>\n";

	return $saved_reps_table;
}

if (!empty($_REQUEST['ajax'])) {
	chdir("..");
	include_once ("include/classes/login/session.php");
	$username = $session->username;
}
if (!$tabbed && !isset($_REQUEST['tab'])) {
	$user_url = "userinfo.php?";
} else {
	$user_url = "index.php?tab=4&";
}

$loesch = 0;
$public = 0;
if (!empty($_REQUEST['loesch']) && !empty($_REQUEST['rep'])) {  // Repertorisierung löschen
	$rep_id = $_REQUEST['rep'];
	$query1 = "DELETE FROM rep_sym WHERE rep_id='$rep_id'";
	$query2 = "DELETE FROM repertorizations WHERE rep_id='$rep_id'";
	$loesch = 2;
	if ($db->send_query($query1) && $db->send_query($query2)) {
		$loesch = 1;
	}
} elseif (!empty($_REQUEST['public']) && !empty($_REQUEST['rep'])) {  // Öffentlich-Status ändern
	$rep_id = $_REQUEST['rep'];
	if ($_REQUEST['rep_public'] == "1"){
		$rep_public_new = 0;
	} else {
		$rep_public_new = 1;
	}
	$query = "UPDATE repertorizations SET rep_public = $rep_public_new WHERE rep_id = '$rep_id'";
	if ($db->send_query($query)) {
		$public = 1;
	}
}
if ($loesch != 0 || $public != 0) {
	echo ("    <div class='alert_box'>\n");
	if ($loesch == 1) {
		printf("      " . _("Repertorization No. %d was deleted.") . "\n", $rep_id);
	} elseif ($loesch == 2) {
		printf("      <span class='red'>" . _("Repertorization No. %d couldn't be deleted totally!") . "</span>\n", $rep_id);
	} elseif ($public == 1) {
		printf("      " . _("The public status of the repertorization no. %d changed.") . "\n", $rep_id);
	}
	echo ("        </div>\n");
}
if (!$tabbed && !isset($_REQUEST['tab'])) {
	$tab = -1;
} else {
	$tab = 1;
}
?>
    <form id="saved_reps_form" action="" accept-charset="utf-8">
      <div class = 'select'>
<?php
$order_by = "rep_timestamp";
$order_type = "DESC";
if (!empty($_REQUEST['order_by'])) {
	$order_by = $_REQUEST['order_by'];
}
if (!empty($_REQUEST['order_type'])) {
	$order_type = $_REQUEST['order_type'];
}
echo build_saved_reps_table($order_by, $order_type, $username, $user_url, $num_reps);
printf("      <p class='label'>" . ngettext("%d saved repertorization", "%d saved repertorizations", $num_reps) . "</p>\n", $num_reps);
?>
        <div class="button_area_2">  
          <input class="submit" type="button" onclick='repCall(<?php echo($tab);?>)' value=" <?php echo _("Show repertorization"); ?> ">
          <br>
          <br>
<?php
if ($tab == 1) {
	$tab = 0;
}
?>
          <input class="submit" type="button" onclick='repContinue(<?php echo($tab);?>)' value=" <?php echo _("Add more symptoms"); ?> ">
          <br>
          <br>
          <input class="submit" type="button" onclick='repDelete()' value=" <?php echo _("Delete repertorization"); ?> ">
          <br>
          <br>
          <input class="submit" type="button" onclick='repPublic()' value=" <?php echo _("Change public-state"); ?> ">
        </div>
      </div>
    </form>
