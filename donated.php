<?php

/**
 * donated.php
 *
 * This file shows up after you've donated for OpenHomeopath.
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
 * @category  Donated
 * @package   Donations
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

include_once ("include/classes/login/session.php");
$skin = $session->skin;
include("./skins/$skin/header.php");
$first_name = "";
$hallo = "";
if (!empty($_REQUEST['first_name'])) {
	$hallo = sprintf(_("Hallo %s.") . " ", urldecode($_REQUEST['first_name']));
}
?>
<h1>
  <?php echo _("Thanks a lot!"); ?>
</h1>
<p><strong><?php echo $hallo . _("Thank you for the donation!"); ?></strong></p>
<p><?php echo _("All functions of OpenHomeopath are now unlocked."); ?><br>
<?php echo _("If there should be problems with activation, for example when your PayPal e-mail didn't comply with your e-mail in OpenHomeopath, please send me <a href='mailto:henri.hulski@gazeta.pl?subject=OpenHomeopath'>an e-mail</a>. I will activate you manually."); ?>
</p>
<p><em><strong>Henri</strong> - <?php echo _("author of OpenHomeopath"); ?></em></p>
<?php
include("./skins/$skin/footer.php");
?>
