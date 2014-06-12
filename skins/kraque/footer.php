<?php

/**
 * frame.php
 *
 * The html footer to include.
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
 * @category  Skin
 * @package   KraqueFooter
 * @author    Thomas Bochmann <thomas.bochmann@gmail.com>
 * @copyright 2009 Thomas Bochmann
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

?>
        </section>
        <footer>
          <span class="leftFlow">
<?php
echo "\n          <b>" . _("Members Total:") . "</b> ".$db->getNumMembers()."<br>\n";
printf("          " . ngettext("There are %d registered member", "There are %d registered members", $db->num_active_users) . "<br>\n", $db->num_active_users);
printf("          " . ngettext("and %d guest viewing the site.", "and %d guests viewing the site.", $db->num_active_guests) . "<br>\n", $db->num_active_guests);
if (!empty($db->connection)) {
	$db->close_db();
}
?>
          </span><a href="doc/<?php echo $lang; ?>/info.php#license">Copyright</a> &copy; 2007-2014 by Henri Schumacher 
          <br>
           <?php echo _("OpenHomeopath is distributed under the terms of the <a href='doc/en/agpl3.php'>AGPL-License</a>"); ?>&nbsp;&nbsp; 
          <br>
          <a title="<?php echo _("Contact to the author"); ?>" href="mailto:henri.hulski@gazeta.pl?subject=OpenHomeopath"><?php echo _("Contact to the author"); ?></a>
        </footer>
    </div>
<?php
include ("javascript/stats.php");
?>
  </body>
</html>
