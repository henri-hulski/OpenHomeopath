<?php
/**
 * login.php
 *
 * This page allows the user to login and to reset his password
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
 * @package   Login
 * @author    Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

include_once ("include/classes/login/session.php");
$head_title = _("Login") . " :: OpenHomeopath";
$skin = $session->skin;
include("skins/$skin/header.php");
?>

<h1><?php echo _("Login"); ?></h1>

<?php
/**
 * User has already logged in, so display relavent links, including
 * a link to the admin center if the user is an administrator.
 */
if($session->logged_in){
	echo "<h2>" . _("Logged in") . "</h2>";
	echo "" . _("Welcome") . " <b>".$session->username."</b>, " . _("you're logged in.") . " <br><br>"
		."[<a href='userinfo.php?user=".$session->username."'>" . _("My account") . "</a>] &nbsp;&nbsp;"
		."[<a href='useredit.php'>" . _("Edit Account") . "</a>] &nbsp;&nbsp;";
	if($session->isAdmin()){
		echo "[<a href='useradmin.php'>" . _("User administration") . "</a>] &nbsp;&nbsp;";
	}
	echo "[<a href='include/classes/login/process.php'>" . _("Logout") . "</a>]";
} else {
?>

<?php
/**
 * User not logged in, display the login form.
 * If user has already tried to login, but errors were
 * found, display the total number of errors.
 * If errors occurred, they will be displayed.
 */
?>
<div class="center">
  <form action="include/classes/login/process.php" method="post" accept-charset="utf-8" style="display: inline">
    <div class="StdBlockHeader" style="text-align: left;"><?php echo _("Please enter your username and password"); ?></div>
    <div class="StdBlock center">
<?php
if($form->num_errors > 0){
   echo "<p class='error_message'>&nbsp;&nbsp;&nbsp;*** ".$form->num_errors." " . ngettext("error found", "errors found", $form->num_errors) . " ***</p>\n";
}
?>
      <br>
      <table class="center" style="border-spacing:0; border-collapse:collapse;">
        <tr>
          <td><label for="user"><?php echo _("Username:"); ?> </label></td>
          <td><input type="text" name="user" id="user" required size="30" maxlength="30" value="<?php echo $form->value("user"); ?>"/></td>
      	  <td><?php echo $form->error("user"); ?></td>
        </tr>
        <tr>
          <td><br><label for="pass"><?php echo _("Password:"); ?> </label></td>
          <td><input type="password" name="pass" id="pass" required size="30" maxlength="30" value="<?php echo $form->value("pass"); ?>" /></td>
          <td><?php echo $form->error("pass"); ?></td>
        </tr>
        <tr>
          <td colspan="2" style="text-align: left">
            <br>
            <input type="checkbox" id="remember_me" name="remember" <?php if($form->value("remember") != ""){ echo "checked"; } ?>>
            <label for="remember_me"><span style="font-size: 13px;"><?php echo _("Remember me next time"); ?> &nbsp;&nbsp;&nbsp;&nbsp;</span></label>
            <input type="hidden" name="sublogin" value="1">
<?php
if(isset($_GET['url'])) {
	echo ("            <input type='hidden' name='url' value='" . $_GET['url'] . "'>\n");
}
?>
            <input type="submit" value=" Login ">
          </td>
          <td></td>
        </tr>
      </table>
      <div class="FloatingText"><a href="register.php"><strong><?php echo _("Not registered? &ndash; Sign-Up!"); ?></strong></a></div>
    </div>
  </form>
</div>
<?php
/**
 * Forgot Password form has been submitted and no errors
 * were found with the form (the username is in the database)
 */
if(isset($_SESSION['forgotpass'])){
   /**
    * New password was generated for user and sent to user's
    * email address.
    */
   if($_SESSION['forgotpass']){
      echo "<h2>" . _("New password generated") . "</h2>\n";
      echo "<p>" . _("Your new password has been generated and sent to the e-mail associated with your account.") . "\n";
      echo "<br>" . _("So your old password will not work anymore.") . "</p>\n";
   }
   /**
    * Email could not be sent, therefore password was not
    * edited in the database.
    */
   else{
      echo "<h2>" . _("New Password Failure") . "</h2>\n";
      echo "<p>" . _("There was an error sending you the e-mail with the new password.") . "\n";
      echo "<br>" . _("So your password has not been changed.") . "</p>\n";
   }

   unset($_SESSION['forgotpass']);
}
else{

/**
 * Forgot password form is displayed, if error found
 * it is displayed.
 */
?>

  <div class="center" style="margin-top: 30px;">
    <form action="include/classes/login/process.php" method="post" accept-charset="utf-8" style="display: inline">
      <div class="StdBlockHeader" style="text-align: left;"><?php echo _("Forgot Password?"); ?>
      </div>
      <div class="StdBlock">
        <div class="FloatingText"><?php echo _("A new password will be generated for you and sent to the e-mail address associated with your account."); ?>
          <div class="FloatingText">
            <label for="lostpass"><?php echo _("Username:"); ?> </label>
            <input type="text" name="lostpass" id="lostpass" required size="30" maxlength="30" value="<?php echo $form->value("lostpass"); ?>"> <?php echo $form->error("lostpass"); ?>
            <input type="hidden" name="subforgot" value="1">
          </div>
          &nbsp;&nbsp;<input type="submit" class="submit" value=" <?php echo _("Send"); ?> ">
        </div>
      </div>
    </form>
  </div>

<?php
}
}
include("skins/$skin/footer.php");
?>
