<?php

/**
 * donations.php
 *
 * This file gives you the possibility to donate for OpenHomeopath.
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
 * @category  Donations
 * @package   Donations
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

include_once ("include/classes/login/session.php");
if (empty($_GET['popup'])) {
	$skin = $session->skin;
	include("skins/$skin/header.php");
} else {
	include_once ("include/functions/layout.php");
	echo " <div class='donations_popup'>";
}
?>

<h1><?php echo _("Donations"); ?></h1>

   <p><?php echo _("<strong>OpenHomeopath is open-source</strong> and this will remain so. But that doesn't mean that we've no costs of development. We need much time for programming and testing. It's really a large project."); ?><br>
   <?php echo _("In addition we've fixed costs for server, Internet, electricity and computer service."); ?><br>
   <?php echo _("We consciously <strong>don't take money from advertising</strong> so we depend only <strong>on your help</strong>."); ?></p>
   <p><strong><?php echo _("Here you can send your donation in Euro or US-Dollar with PayPal to us. You decide yourself how much you want to give."); ?></strong></p>
      <table class='center'>
        <tr>
          <td><strong><?php echo _("Euro"); ?>:</strong>&nbsp;&nbsp;</td>
          <td><?php add_donate_button(2); ?></td>
        </tr>
        <tr>
          <td><strong><?php echo _("US dollar"); ?>:</strong>&nbsp;&nbsp;</td>
          <td><?php add_donate_button(3); ?></td>
        </tr>
      </table>
      <div style='font-size: smaller;'><?php echo _("For US dollar you can check the option \"Make This Recurring (Monthly)\" on the PayPal site.") . "<br>" . _("Similar to a standing order we will receive the donation amount monthly until you cancel it."); ?></div>
    <p><strong><?php echo _("Alternatively you can transfer money directly to our bank account. A good idea would be a standing order. It allows us planning in longer terms."); ?></strong></p>
    <p>
      <strong><?php echo _("Our bank account"); ?>:</strong><br>
      <strong><?php echo _("Bank"); ?>:</strong> GLS Bank<br>
      <strong><?php echo _("Recipient"); ?>:</strong> Berenika Zapalowicz<br>
      <strong>IBAN:</strong> DE35430609676003805100<br>
      <strong>BIC:</strong> GENODEM1GLS<br>
      <strong><?php echo _("Description"); ?>:</strong> OpenHomeopath + <?php echo _("username or e-mail"); ?>
    </p>
   <p><?php printf ("<strong>" . _("The financial concept of OpenHomeopath is based on reaching a monthly donation goal of %d €/$ by collective effort of all users.") . "</strong> " . _("Until the monthly donation goal is reached, the functionality of OpenHomeopath is restricted for non-donators.") . " " . _("When the monthly donating goal will be reached, <strong>OpenHomeopath will be fully usable for everybody</strong> until the 10th or when reaching 50%% until the 20th of the next month."), DONATION_GOAL_MONTHLY); ?></p>

<?php
if (empty($_GET['popup'])) {
	include("skins/$skin/footer.php");
} else {
	echo " </div>";
}
?>
