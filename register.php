<?php
/**
 * register.php
 *
 * Displays the registration form if the user needs to sign-up,
 * or lets the user know, if he's already logged in, that he
 * can't register another name.
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
 * @package   Register
 * @author    Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

include_once ("include/classes/login/session.php");
$head_title = _("Registration") . " :: OpenHomeopath";
$skin = $session->skin;
include("./skins/$skin/header.php");
?>
<h1><?php echo _("Registration to OpenHomeopath"); ?></h1>
<br>
<?php
/**
 * The user is already logged in, not allowed to register.
 */
if($session->logged_in){
	echo "<h2>" . _("Registered") . "</h2>\n";
	echo "<p>" . _("Thank you") . " <b>$session->username</b>, " . _("you're already registered.") . " <a href='login.php'>" . _("Please log in.") . "</a></p>\n";
}
/**
 * The user has submitted the registration form and the
 * results have been processed.
 */
elseif(isset($_SESSION['regsuccess'])) {
	/* Registration was successful */
	if($_SESSION['regsuccess']) {
		echo "<h2>" . _("Registered!") . "</h2>";
		if(REGISTER_VERIFY_EMAIL) {
			printf ("<p>" . _("Welcome") . " <strong>%s</strong>,<br>" . _("you was registered with the username %s  and the e-mail %s.") . "<br>\n", $_SESSION['reguname'], '<strong>"' . $_SESSION['reguname'] . '"</strong>', '<strong>"' . $_SESSION['regemail'] . '"</strong>');
			echo _("Your <strong>password</strong> was sent to your <strong>e-mail</strong>.") . "<br>\n";
			echo _("With this password and your username you can <a href='login.php'>login</a>.") . "</p>\n";
		} else {
			echo "<p>" . _("Welcome") . " <strong>".$_SESSION['reguname'] . "</strong>, " . _("your information has been added to the database, you may now <a href='login.php'>log in</a>.") . "</p>\n";
		}
	}
	/* Registration failed */
	else {
		echo "<h2>" . _("Registration not possible") . "</h2>\n";
		printf("<p>" . _("We're sorry, but an error has occurred and your registration for the username <b>%s</b>, could not be completed.<br>Please try again at a later time.") . "</p>\n", $_SESSION['reguname']);
	}
	unset($_SESSION['regsuccess']);
	unset($_SESSION['reguname']);
}
/**
 * The user has not filled out the registration form yet.
 * Below is the page with the sign-up form, the names
 * of the input fields are important and should not
 * be changed.
 */
else{
?>
<div align="center">
  <form action="include/classes/login/process.php" method="post" name="registrierung" accept-charset="utf-8" style="display: inline">
    <div class="StdBlockHeader" style="text-align: left">
<?php
echo _("Please enter a username");
if(!REGISTER_VERIFY_EMAIL){
	echo _(", password");
}
echo _(" and your e-mail.");
?>
    </div>
    <div align="center" class="StdBlock">
      <br>
      <table cellspacing="0" align="center">
<?php
	if($form->num_errors > 0) {
		echo "<tr><td colspan='3'><font size='2' color='#ff0000'>" . $form->num_errors . " " . ngettext("error found", "errors found", $form->num_errors) . "</font></td></tr>\n";
	}
?>
        <tr>
          <td><label for="user"><?php echo _("Username:"); ?> </label></td>
          <td><input type="text" name="user" id="user" size="30" maxlength="30" value="<?php echo $form->value("user"); ?>"></td>
          <td><?php echo $form->error("user"); ?></td>
        </tr>
<?php
	if(!REGISTER_VERIFY_EMAIL) {
?>
        <tr>
          <td><label for="pass"><?php echo _("Password:"); ?> </label></td>
          <td><input type="password" name="pass" id="pass" size="30" maxlength="30" value="<?php echo $form->value("pass"); ?>"></td>
          <td><?php echo $form->error("pass"); ?></td>
        </tr>
        <tr>
          <td><label for="pass2"><?php echo _("Re-enter Password:"); ?> </label></td>
          <td><input type="password" name="pass2" id="pass2" size="30" maxlength="30" value="<?php echo $form->value("pass2"); ?>"></td>
          <td><?php echo $form->error("pass2"); ?></td>
        </tr>
<?php
	}
?>
        <tr>
          <td><label for="email"><?php echo _("E-mail:"); ?> </label></td>
          <td><input type="text" name="email" id="email" maxlength="50" value="<? echo $form->value("email"); ?>"></td>
          <td><? echo $form->error("email"); ?></td>
        </tr>
<?php
	if(REGISTER_VERIFY_EMAIL) {
?>
        <tr>
          <td colspan="2"><br><?php echo _("Your <strong>password</strong> will be sent to your <strong>e-mail</strong>."); ?></td>
          <td></td>
        </tr>
<?php
	}
?>
        <tr>
          <td colspan="2" align="left">
            <input type="hidden" name="subjoin" value="1">
            <br>
            <input type="submit" value=" <?php echo _("Send!"); ?> ">
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
