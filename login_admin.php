<?php
/**
 * login_admin.php
 *
 * This is the Admin Center page. Only administrators
 * are allowed to view this page. This page displays the
 * database table of users and banned users. Admins can
 * choose to delete specific users, delete inactive users,
 * ban users, update user levels, etc.
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
 * @category  Login
 * @package   Admin
 * @author    Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

include_once ("include/classes/login/session.php");

/**
 * displayUsers - Displays the users database table in
 * a nicely formatted html table.
 *
 * @param string $order_by users table row by which the table should be ordered
 * @param string $order_type order direction ('DESC'|'ASC')
 * @return string users html table
 * @access public
 */
function displayUsers($order_by = "last_activity", $order_type = "DESC") {
	global $db;
	$user_rows_ar = array(
			"username" => _("Username"),
			"userlevel" => _("Level"),
			"email" => _("E-mail"),
			"last_activity" => _("last active"),
			"registration" => _("registered since")
	);
	$order = ($order_by === "userlevel") ? "$order_by $order_type, username" : "$order_by $order_type";
	$select = implode(", ", array_keys($user_rows_ar));
	$query = "SELECT $select FROM " . TBL_USERS . " WHERE username != 'pacha' ORDER BY $order";
	$db->send_query($query);
	$num_rows = $db->db_num_rows();
	if ($num_rows == 0) {
		$user_table = _("no users");
		return $user_table;
	}

	/* Display table contents */
	$user_table = "<div class='scrollableUserContainer'>\n";
	$user_table .= "  <div  class='scrollingUserArea'>\n";
	$user_table .= "    <table class='user_table'>\n";

	/* Build the table heading */
	$user_table .= "      <thead>\n";
	$user_table .= "        <tr>\n";
	foreach ($user_rows_ar as $row => $val) {
		$user_table .= "          <th class='$row'><div>";
		$field_is_current_order_by = 0;
		if ($order_by != $row) {  // the results are not ordered by this field at the moment
			$link_class="order_link_2";
			if ($row == "username" || $row == "email") {
				$new_order_type = "ASC";
			} else {
				$new_order_type = "DESC";
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
			
		$user_table .= "<a class='$link_class' href='login_admin.php?order_by=$row&amp;order_type=$new_order_type#benutzertabelle'>";

		if ($field_is_current_order_by === 1) {
			if ($order_type === 'ASC') {
				$user_table .= '&uarr; ';
			} else {
				$user_table .= '&darr; ';
			}
		}
			
		$user_table .= "$val</a></div></th>\n";
	}
	$user_table .= "        </tr>\n";
	$user_table .= "      </thead>\n";

	/* Build the table body */
	$tr_results_class = 'tr_results_1';
	$td_controls_class = 'controls_1';
	$user_table .= "      <tbody>\n";
	while (list($username, $userlevel, $email, $last_activity, $registration) = $db->db_fetch_row()) {
		if ($userlevel < 6) {
			$level = _("normal user");
		} elseif ($userlevel == 6) {
			$level = _("Editor");
		} elseif ($userlevel == 9) {
			$level = _("Administrator");
		}
		if ($tr_results_class === 'tr_results_1') {
			$td_controls_class = 'controls_2';
			$tr_results_class = 'tr_results_2';
		} else {
			$td_controls_class = 'controls_1';
			$tr_results_class = 'tr_results_1';
		}
		$user_table .= "  <tr class='$tr_results_class'>\n";
		$user_table .= "    <td class='$td_controls_class username'><div><a href='userinfo.php?user=$username' target='_blank'>$username</a></div></td>\n";
		$user_table .= "    <td class='$td_controls_class userlevel'><div>$level</div></td>\n";
		$user_table .= "    <td class='$td_controls_class email'><div>$email</div></td>\n";
		$user_table .= "    <td class='$td_controls_class last_activity'><div>$last_activity</div></td>\n";
		$user_table .= "    <td class='$td_controls_class registration'><div>$registration</div></td>\n";
		$user_table .= "  </tr>\n";
	}
	$user_table .= "      </tbody>\n";
	$user_table .= "    </table><br>\n";
	$user_table .= "  </div>\n";
	$user_table .= "</div>\n";
	$db->free_result();
	return $user_table;
}

/**
 * displayBannedUsers - Displays the banned users
 * database table in a nicely formatted html table.
 *
 * @return string banned users html table
 * @access public
 */
function displayBannedUsers() {
	global $db;
	$query = "SELECT username, timestamp FROM " . TBL_BANNED_USERS . " ORDER BY username";
	$db->send_query($query);
	/* Error occurred, return given name by default */
	$num_rows = $db->db_num_rows();
	if ($num_rows == 0) {
		$banned_user_table = _("no banned users");
	} else {
		/* Display table contents */
		$banned_user_table = "<table align='left' border='1' cellspacing='0' cellpadding='3'>\n";
		$banned_user_table .= "<tr><td><b>" . _("Username") . "</b></td><td><b>" . _("Time Banned") . "</b></td></tr>\n";
		while(list($username, $timestamp) = $db->db_fetch_row()) {
			$banned_user_table .= "<tr><td>$username</td><td>$timestamp</td></tr>\n";
		}
		$banned_user_table .= "</table><br>\n";
		$db->free_result();
	}
	return $banned_user_table;
}


/**
 * User not an administrator, redirect to main page
 * automatically.
 */
if (!$session->isAdmin()) {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = "login.php";
	header("Content-Type: text/html;charset=utf-8"); 
	header("Location: http://$host$uri/$extra");
	die();
} else {
/**
 * Administrator is viewing page, so display all
 * forms.
 */
	$order_by = "last_activity";
	$order_type = "DESC";
	if (!empty($_REQUEST['order_by'])) {
		$order_by = $_REQUEST['order_by'];
	}
	if (!empty($_REQUEST['order_type'])) {
		$order_type = $_REQUEST['order_type'];
	}
	$user_table = displayUsers($order_by, $order_type);
	$head_title = _("User administration") . " :: OpenHomeopath";
	$skin = $session->skin;
	include("skins/$skin/header.php");
?>
<h1>
  <?php echo _("User administration"); ?>
</h1>
<br>
<?php
	if ($form->num_errors > 0) {
		echo "<p class='error_message'>!*** " . _("Error in the request, please correct") . "</p><br>\n";
	}
	if (isset($_GET["count"])) {
		$count = $_GET["count"];
		printf("<p class='error_message'>!*** " . ngettext("%d record was deleted!", "%d records were deleted!", $count) . "</p><br>\n", $count);
	}
?>
<div class="content">
  <h2>
    <?php echo _("Contents"); ?>
  </h2>
  <ul>
    <li><a href="#benutzertabelle"><?php echo _("Users Table Contents"); ?></a></li>
    <li><a href="#benutzerstatus"><?php echo _("Change the userlevel"); ?></a></li>
    <li><a href="#benutzer_loeschen"><?php echo _("Delete User"); ?></a></li>
    <li><a href="#inaktive_loeschen"><?php echo _("Delete Inactive Users"); ?></a></li>
    <li><a href="#benutzer_bannen"><?php echo _("Ban User"); ?></a></li>
    <li><a href="#gebannte_benutzer"><?php echo _("Banned Users Table"); ?></a></li>
    <li><a href="#bann_aufheben"><?php echo _("Repeal the ban of a username"); ?></a></li>
    <li><a href="#datenbankeintraege_loeschen"><?php echo _("Delete records of a user"); ?></a></li>
  </ul>
</div>
<table align="left" border="0" cellspacing="5" cellpadding="5">
  <tr>
    <td>
<?php
/**
 * Display Users Table
 */
?>
      <a name="benutzertabelle" id="benutzertabelle"><br></a>
      <h3 style="text-align: center;"><?php echo _("Users Table Contents:"); ?></h3>
    </td>
  </tr>
  <tr>
    <td>
<?php
	echo $user_table;
?>
    </td>
  </tr>
  <tr>
    <td>
      <span class="rightFlow"><a href="#oben" title="<?php echo _("To the top of the page"); ?>"><img src="<?php echo(ARROW_UP_ICON);?>" alt="<?php echo _("To the top of the page"); ?>" border="0"></a></span>
    </td>
  </tr>
  <tr>
    <td><hr>
    </td>
  </tr>
  <tr>
    <td>
<?php
/**
 * Update User Level
 */
?>
      <a name="benutzerstatus" id="benutzerstatus"></a>
      <h3><?php echo _("Change the userlevel"); ?></h3>
      <?php echo $form->error("upduser"); ?>
      <form action="include/classes/login/adminprocess.php" method="POST">
        <table>
          <tr>
            <td>
              <?php echo _("Username:"); ?><br>
              <input type="text" name="upduser" maxlength="30" value="<?php echo $form->value("upduser"); ?>">
            </td>
            <td>
              <?php echo _("State:"); ?><br>
              <select name="updlevel">
                <option value="1"><?php echo _("Normal User"); ?></option>
                <option value="6"><?php echo _("Editor"); ?></option>
                <option value="9"><?php echo _("Administrator"); ?></option>
              </select>
            </td>
            <td>
              <br>
              <input type="hidden" name="subupdlevel" value="1">
              <input type="submit" value=" <?php echo _("Change userlevel"); ?> ">
            </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
  <tr>
    <td>
      <span class="rightFlow"><a href="#oben" title="<?php echo _("To the top of the page"); ?>"><img src="<?php echo(ARROW_UP_ICON);?>" alt="<?php echo _("To the top of the page"); ?>" border="0"></a></span>
    </td>
  </tr>
  <tr>
    <td><hr>
    </td>
  </tr>
  <tr>
    <td>
<?php
/**
 * Delete User
 */
?>
      <a name="benutzer_loeschen" id="benutzer_loeschen"></a>
      <h3><?php echo _("Delete User"); ?></h3>
      <?php echo $form->error("deluser"); ?>
      <form action="include/classes/login/adminprocess.php" method="POST">
        <?php echo _("Username:"); ?><br>
        <input type="text" name="deluser" maxlength="30" value="<?php echo $form->value("deluser"); ?>">
        <input type="hidden" name="subdeluser" value="1">
        <input type="submit" value=" Benutzer löschen ">
      </form>
    </td>
  </tr>
  <tr>
    <td>
      <span class='error_message'><?php echo _("Warning!"); ?></span> &nbsp;<?php echo _("It will also delete all repertorizations from the deleted user."); ?><br>
      <?php echo _("If the user has made changes in the database, maintained them and the user will be banned, so that no one can register with the same username and change data."); ?><br>
      <?php echo _("The ban may be lifted by administrators."); ?>
    </td>
  </tr>
  <tr>
    <td>
      <span class="rightFlow"><a href="#oben" title="<?php echo _("To the top of the page"); ?>"><img src="<?php echo(ARROW_UP_ICON);?>" alt="<?php echo _("To the top of the page"); ?>" border="0"></a></span>
    </td>
  </tr>
  <tr>
    <td><hr>
    </td>
  </tr>
  <tr>
    <td>
<?php
/**
 * Delete Inactive Users
 */
?>
      <a name="inaktive_loeschen" id="inaktive_loeschen"></a>
      <h3><?php echo _("Delete Inactive Users"); ?></h3>
      <p><?php echo _("This will delete all users (not administrators), who have not logged in to the site within a certain time period. You specify the days spent inactive."); ?></p>
      <form action="include/classes/login/adminprocess.php" method="POST">
        <table>
          <tr>
            <td>
              <?php echo _("Days:"); ?><br>
              <select name="inactdays">
                <option value="30">30</option>
                <option value="60">60</option>
                <option value="90">90</option>
                <option value="180">180</option>
                <option value="365" selected="selected">365</option>
                <option value="730">730</option>
              </select>
            </td>
            <td>
              <br>
              <input type="hidden" name="subdelinact" value="1">
              <input type="submit" value=" <?php echo _("Delete all inactive"); ?> ">
            </td>
          </table>
        </form>
    </td>
  </tr>
  <tr>
    <td>
      <span class='error_message'><?php echo _("Warning!"); ?></span> &nbsp;<?php echo _("It will also delete all repertorizations of the deleted users."); ?><br>
      <?php echo _("If the users hade made changes in the database, maintained them and the user will be banned, so that no one can register with the same username and change data."); ?><br>
      <?php echo _("The ban may be lifted by administrators."); ?>
    </td>
  </tr>
  <tr>
    <td>
      <span class="rightFlow"><a href="#oben" title="<?php echo _("To the top of the page"); ?>"><img src="<?php echo(ARROW_UP_ICON);?>" alt="<?php echo _("To the top of the page"); ?>" border="0"></a></span>
    </td>
  </tr>
  <tr>
    <td><hr>
    </td>
  </tr>
  <tr>
    <td>
<?php
/**
 * Ban User
 */
?>
      <a name="benutzer_bannen" id="benutzer_bannen"></a>
      <h3><?php echo _("Ban User"); ?></h3>
      <?php echo $form->error("banuser"); ?>
      <form action="include/classes/login/adminprocess.php" method="POST">
        <?php echo _("Username:"); ?><br>
        <input type="text" name="banuser" maxlength="30" value="<?php echo $form->value("banuser"); ?>">
        <input type="hidden" name="subbanuser" value="1">
        <input type="submit" value=" <?php echo _("Ban User"); ?> ">
      </form>
    </td>
  </tr>
  <tr>
    <td>
      <span class='error_message'><?php echo _("Warning!"); ?></span> &nbsp;<?php echo _("It will also delete all repertorizations of the deleted user."); ?><br>
    </td>
  </tr>
  <tr>
    <td>
      <span class="rightFlow"><a href="#oben" title="<?php echo _("To the top of the page"); ?>"><img src="<?php echo(ARROW_UP_ICON);?>" alt="<?php echo _("To the top of the page"); ?>" border="0"></a></span>
    </td>
  </tr>
  <tr>
    <td><hr>
    </td>
  </tr>
  <tr>
    <td>
<?php
/**
 * Display Banned Users Table
 */
?>
      <a name="gebannte_benutzer" id="gebannte_benutzer"></a>
      <h3><?php echo _("Banned Users Table Contents:"); ?></h3>
    </td>
  </tr>
  <tr>
    <td>
<?php
	echo displayBannedUsers();
?>
    </td>
  </tr>
  <tr>
    <td>
      <span class="rightFlow"><a href="#oben" title="<?php echo _("To the top of the page"); ?>"><img src="<?php echo(ARROW_UP_ICON);?>" alt="<?php echo _("To the top of the page"); ?>" border="0"></a></span>
    </td>
  </tr>
  <tr>
    <td><hr>
    </td>
  </tr>
  <tr>
    <td>
<?php
/**
 * Delete Banned User
 */
?>
      <a name="bann_aufheben" id="bann_aufheben"></a>
      <h3><?php echo _("Repeal the ban of a username"); ?></h3>
      <?php echo $form->error("delbanuser"); ?>
      <form action="include/classes/login/adminprocess.php" method="POST">
        Benutzername:<br>
        <input type="text" name="delbanuser" maxlength="30" value="<?php echo $form->value("delbanuser"); ?>">
        <input type="hidden" name="subdelbanned" value="1">
        <input type="submit" value=" <?php echo _("Repeal ban"); ?> ">
      </form>
    </td>
  </tr>
  <tr>
    <td>
      <span class="rightFlow"><a href="#oben" title="<?php echo _("To the top of the page"); ?>"><img src="<?php echo(ARROW_UP_ICON);?>" alt="<?php echo _("To the top of the page"); ?>" border="0"></a></span>
    </td>
  </tr>
  <tr>
    <td><hr>
    </td>
  </tr>
  <tr>
    <td>
<?php
/**
 * Delete User Data
 */
?>
      <a name="datenbankeintraege_loeschen" id="datenbankeintraege_loeschen"></a>
      <h3><?php echo _("Delete records of a user"); ?></h3>
      <p><?php echo _("Here you can, for example with vandalism, delete the database entries for a user. In the tables <strong> Materia Medica </strong> and <strong> symptom-remedy-relations </strong> will delete all messages while the user in the tables <strong>symptoms</strong>, <strong>main rubrics</strong>, <strong>remedies</strong>, <strong>source</strong> and <strong>languages</strong> Only the entries to which no records of other users reference."); ?></p>
      <?php echo $form->error("deluserdata"); ?>
      <form action="include/classes/login/adminprocess.php" method="POST">
        <?php echo _("User, whose records should be deleted:"); ?><br>
        <input type="text" name="deluserdata" maxlength="30" value="<?php echo $form->value("deluserdata"); ?>">
        <input type="hidden" name="subdeluserdata" value="1">
        <input type="submit" value=" <?php echo _("Deleting Data"); ?> ">
      </form>
    </td>
  </tr>
  <tr>
    <td>
      <span class='error_message'><?php echo _("Warning!"); ?></span> &nbsp;<?php echo _("You cannot undo changes."); ?><br>
    </td>
  </tr>
  <tr>
    <td>
      <span class="rightFlow"><a href="#oben" title="<?php echo _("To the top of the page"); ?>"><img src="<?php echo(ARROW_UP_ICON);?>" alt="<?php echo _("To the top of the page"); ?>" border="0"></a></span>
    </td>
  </tr>
</table>
<br clear="all">
<?php
	include("skins/$skin/footer.php");
}
?>
