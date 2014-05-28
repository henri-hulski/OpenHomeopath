<?php

/**
 * support.php
 *
 * Here you find information, how you can support OpenHomeopath.
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
 * @package   Support
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

include_once ("include/classes/login/session.php");
$skin = $session->skin;
$head_title = _("Support") . " :: OpenHomeopath";
$meta_description = _("How can I support OpenHomeopath?");
include("./skins/$skin/header.php");
?>
<h1>
  <?php echo _("How can I support OpenHomeopath?"); ?>
</h1>
<p><?php echo _("If you like OpenHomeopath and you want to help us you've the following options:"); ?></p>
<ol>
  <li><strong><?php echo _("Feedback"); ?>:</strong> <?php echo _("We depend on your feedback. If you find bugs or have an idea how to improve the program please post in the <a href='homeophorum.php?index,2'>forum</a> or write me an <a href='mailto:henri.hulski@gazeta.pl?subject=OpenHomeopath'>e-mail</a>.") . " " . _("At the Moment I'm preparing the first stable release 1.0 so I need particularly your feedback."); ?></li>
  <li><strong><?php echo _("Advertise"); ?>:</strong> <?php echo _("To popularize OpenHomeopath and help the community to grow you can link to OpenHomeopath from homeopathy related websites as well as post in related forums and social networks like <em>facebook</em> about us. Also writing an article for a homeopathic magazine could help."); ?></li>
   <li>
    <strong><?php echo _("Donations"); ?>: </strong><?php echo _("<strong>OpenHomeopath is open-source</strong> and this will remain so. But that doesn't mean that we've no costs of development. We need much time for programming and testing. It's really a large project."); ?><br>
   <?php echo _("In addition we've fixed costs for server, Internet, electricity and computer service."); ?><br>
   <?php echo _("We consciously <strong>don't take money from advertising</strong> so we depend only <strong>on your help</strong>."); ?>
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
<?php
if ($session->lang == "de") {
?>
      <br><strong>Konto-Nr.:</strong> 600 380 51 00
      <br><strong>BLZ:</strong> 430 609 67
<?php
}
?>
    </p>
   <p><?php printf ("<strong>" . _("The financial concept of OpenHomeopath is based on reaching a monthly donation goal of %d €/$ by collective effort of all users.") . "</strong> " . _("Until the monthly donation goal is reached, the functionality of OpenHomeopath is restricted for non-donators.") . " " . _("When the monthly donating goal will be reached, <strong>OpenHomeopath will be fully usable for everybody</strong> until the 10th or when reaching 50% of the donating goal until the 20th of the next month."), DONATION_GOAL_MONTHLY); ?></p>
  </li>
  <li><strong><?php echo _("Participation in the server costs"); ?>:</strong> <?php echo _("If you want to contribute to the server costs, you can <a href='http://openhomeo.org/spenden.html'>transfer donations</a> to the non-profit association \"Verein zur Förderung der naturheilkundlichen Medizin e.V.\"."); ?></li>
   <li><strong><?php echo _("Complete the database "); ?>:</strong> <?php echo _("You can actively add repertories with the <a href='express.php'>express-tool</a>. If someone has a English or German materia medica in digital format please contact. This can be a textfile, a table, a PDF with text or a database. I will try to import it to OpenHomeopath."); ?></li>
  <li><strong><?php echo _("Translations"); ?>:</strong> <?php echo _("It would be nice to have OpenHomeopath translated in more languages. For the internationalization I'm using gettext. With the poeditor it's easy to make new translations and integrate them. We also need to translate the help files and parts of the database, specially the main rubrics. I also need someone for translating the German help into English. I you're interested please send me an <a href='mailto:henri.hulski@gazeta.pl?subject=OpenHomeopath'>e-mail</a>."); ?></li>
</ol>
<?php
include("./skins/$skin/footer.php");
?>
