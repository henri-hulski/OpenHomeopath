<?php

/**
 * donate_cancel.php
 *
 * This file shows up when you cancel the donation for OpenHomeopath.
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
 * @category  DonateCancel
 * @package   Donations
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

include_once ("include/classes/login/session.php");
$skin = $session->skin;
include("./skins/$skin/header.php");
?>
<h1>
  <?php echo _("Donation"); ?>
</h1>
<p><?php echo _("It's a pity that you didn't decide donating."); ?></p>
<p><?php echo _("Maybe the next time."); ?></p>
<p><?php echo _("Henri Schumacher - author of OpenHomeopath."); ?></p>
<br>
<div class='center'>
<?php
add_donate_button();
?>
</div>
<?php
include("./skins/$skin/footer.php");
?>
