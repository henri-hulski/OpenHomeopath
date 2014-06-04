<?php
/**
 * useredit.php
 *
 * This page is for users to edit their account information
 * such as their password, email address, language, etc. Their
 * usernames can not be edited. When changing their
 * password, they must first confirm their current password.
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
 * @package   UserEdit
 * @author    Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

include_once ("include/classes/login/session.php");
if (!$session->logged_in) {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = "login.php?url=useredit.php";
	header("Content-Type: text/html;charset=utf-8"); 
	header("Location: http://$host$uri/$extra");
	die();
}
$head_title = _("Edit Account") . " :: OpenHomeopath";
$meta_description = _("Here you can edit your account");
$skin = $session->skin;
$lang = $session->lang;
include("./skins/$skin/header.php");
?>

<h1><?php echo _("User Account Edit"); ?></h1>

<?php
/**
 * User has submitted form without errors and user's
 * account has been edited successfully.
 */
if(isset($_SESSION['useredit'])) {
	unset($_SESSION['useredit']);
	echo "<h2>" . _("User Account Edit Success!") . "</h2>";
	echo "<p><b>$session->username</b>, " . _("your account has been successfully updated.") . " ";
} else {
	/**
	 * If user is not logged in, then do not display anything.
	 * If user is logged in, then display the form to edit
	 * account information, with the current email address
	 * already in the field.
	 */
?>

<h2><?php echo _("User:"); ?> <?php echo $session->username; ?></h2>
<div align="center">
  <form action="include/classes/login/process.php" method="post" accept-charset="utf-8" style="display: inline;">
    <div class="StdBlockHeader" style="text-align: left;"><?php echo _("Here you can edit your account"); ?>
    </div>
    <div align="center" class="StdBlock">
<?php
	List($user_email, $user_real_name, $user_extra, $hide_email, $user_skin, $user_lang, $user_sym_lang) = $db->getUserInfo($_SESSION['username'], 'email, user_real_name, user_extra, hide_email, skin_name, lang_id, sym_lang_id');
	if($form->num_errors > 0){
		echo "<p class='error_message'>&nbsp;&nbsp;&nbsp;*** ".$form->num_errors." " . ngettext("error found", "errors found", $form->num_errors) . " ***</p>\n";
	}
?>
      <fieldset><legend><strong>&nbsp;<?php echo _("Program settings"); ?>&nbsp;</strong></legend>
      <table cellspacing="0" align="center">
        <tr>
          <td><label for="skin"><?php echo _("Select skin:"); ?></label></td>
          <td><select name="skin" id="skin" size="1">
<?php
	if (!empty($user_skin)) {
		echo ("      <option selected='selected' value=''>$user_skin</option>\n");
	} else {
		echo ("      <option value=''>&nbsp;</option>\n");
	}
	$query = "SELECT skin_name FROM skins ORDER BY skin_id";
	$db->send_query($query);
	while(list($skin_name) = $db->db_fetch_row()) {
		if ($skin_name != $user_skin) {
			echo ("      <option value='$skin_name'>$skin_name</option>\n");
		}
	}
	$db->free_result();
?>
          </select></td>
          <td></td>
        </tr>
        <tr>
          <td><label for="lang"><?php echo _("Select language:"); ?></label></td>
          <td><select name="lang" id="lang" size="1">
<?php
	$query = "SELECT lang_id, lang_$lang FROM languages WHERE sys_lang != 0";
	$db->send_query($query);
	while (list($user_lang_id, $user_lang_name) = $db->db_fetch_row()) {
		$languages_ar[$user_lang_id] = $user_lang_name;
	}
	$db->free_result();
	if (!empty($user_lang)) {
		echo ("      <option selected='selected' value=''>$languages_ar[$user_lang]</option>\n");
	} else {
		echo ("      <option value=''>&nbsp;</option>\n");
	}
	foreach ($languages_ar as $lang_id => $lang_name) {
		if ($lang_id != $user_lang) {
			echo ("      <option value='$lang_id'>$lang_name</option>\n");
		}
	}
?>
          </select></td>
          <td></td>
       </tr>
<?php
	if ($db->exist_symptom_translation() === true) {
?>
        <tr>
          <td><label for="sym_lang"><?php echo _("Select your preferred symptom-language:"); ?></label></td>
          <td><select name="sym_lang" id="sym_lang" size="1">
<?php
		$query = "SELECT lang_id, lang_$lang FROM languages WHERE sym_lang != 0";
		$db->send_query($query);
		while (list($sym_lang_id, $sym_lang_name) = $db->db_fetch_row()) {
			$sym_lang_ar[$sym_lang_id] = $sym_lang_name;
		}
		$db->free_result();
		if (!empty($user_sym_lang)) {
			echo ("      <option selected='selected' value=''>$sym_lang_ar[$user_sym_lang]</option>\n");
			echo ("      <option value='wo'>" . _("as above") . "</option>\n");
		} else {
			echo ("      <option value=''>" . _("as above") . "</option>\n");
		}
		foreach ($sym_lang_ar as $sym_lang_id => $sym_lang_name) {
			if ($sym_lang_id != $user_sym_lang) {
				echo ("      <option value='$sym_lang_id'>$sym_lang_name</option>\n");
			}
		}
?>
          </select></td>
          <td></td>
       </tr>
<?php
	}
	if (!$session->isAdmin()) {
		if ($session->showActive()) {
?>
        <tr>
          <td><input type="checkbox" name="show_active" value="true" checked="checked"> <?php echo _("Show active users"); ?></td>
          <td></td>
        </tr>
<?php
		} else {
?>
        <tr>
          <td><input type="checkbox" name="show_active" value="true"> <?php echo _("Show active users"); ?></td>
          <td></td>
        </tr>
<?php
		}
	}
?>
      </table>
      </fieldset>
      <fieldset><legend><strong>&nbsp;<?php echo _("Change e-mail"); ?>&nbsp;</strong></legend>
      <table cellspacing="0" align="center">
       <tr>
          <td><label for="email"><?php echo _("E-mail:"); ?> </label></td>
          <td><input type="text" name="email" id="email" size="30" maxlength="50"
<?php
	if ($form->value("email")) {
		echo (" value='" . $form->value("email") . "'");
	} else {
		echo (" value='" . $user_email . "'");
	}
?>
></td>
          <td><?php echo $form->error("email"); ?></td>
        </tr>
<?php
	if (!empty($hide_email)) {
?>
        <tr>
          <td></td>
          <td><input type="checkbox" name="hide_email" value="true" checked> <?php echo _("Hide e-mail from other users"); ?></td>
        </tr>
<?php
	} else {
?>
        <tr>
          <td></td>
          <td><input type="checkbox" name="hide_email" value="true"> <?php echo _("Hide e-mail from other users"); ?></td>
        </tr>
<?php
	}
?>
      </table>
      </fieldset>
      <fieldset><legend><strong>&nbsp;<?php echo _("Public profile"); ?>&nbsp;</strong></legend>
      <table cellspacing="0" align="center">
        <tr>
          <td><label for="real_name"><?php echo _("Real name:"); ?> </label></td>
          <td><input type="text" name="real_name" id="real_name" size="30" maxlength="200"
<?php
	if ($form->value("real_name")) {
		echo (" value='" . $form->value("real_name") . "'");
	} else {
		echo (" value='" . $user_real_name . "'");
	}
?>
></td>
        </tr>
        <tr>
          <td><label for="extra"><?php echo _("More information:"); ?> </label></td>
          <td><textarea name="extra" id="extra" cols="40" rows="7">
<?php
	if ($form->value("extra")) {
		echo $form->value("extra");
	} else {
		echo $user_extra;
	}
?>
</textarea></td>
        </tr>
      </table>
      </fieldset>
      <fieldset><legend><strong>&nbsp;<?php echo _("Change password"); ?>&nbsp;</strong></legend>
      <table cellspacing="0" align="center">
        <tr>
          <td><label for="curpass"><?php echo _("current Password:"); ?> </label></td>
          <td><input type="password" name="curpass" id="curpass" size="30" maxlength="30" value="<?php echo $form->value("curpass"); ?>"/></td>
      	  <td><?php echo $form->error("curpass"); ?></td>
        </tr>
        <tr>
          <td><label for="newpass"> <?php echo _("new Password:"); ?> </label></td>
          <td><input type="password" name="newpass" id="newpass" size="30" maxlength="30" value="<?php echo $form->value("newpass"); ?>" /></td>
          <td><?php echo $form->error("newpass"); ?></td>
        </tr>
        <tr>
          <td><label for="newpass2"><?php echo _("Re-enter Password:"); ?> </label></td>
          <td><input type="password" name="newpass2" id="newpass2" size="30" maxlength="30" value="<?php echo $form->value("newpass2"); ?>"></td>
          <td><?php echo $form->error("newpass2"); ?></td>
        </tr>
      </table>
      </fieldset>
      <table cellspacing="0" align="center">
        <tr>
          <td colspan="3" align="left">
            <input type="hidden" name="subedit" value="1">
            <input type="submit" value=" <?php echo _("Save changes"); ?> ">
          </td>
        </tr>
      </table>
      <br>
    </div>
  </form>
</div>
<br>

<?php
}
include("./skins/$skin/footer.php");
?>
