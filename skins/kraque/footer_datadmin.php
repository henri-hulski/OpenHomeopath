<?php
/*
***********************************************************************************
DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) http://www.dadabik.org/
Copyright (C) 2001-2007  Eugenio Tacchini

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

If you want to contact me by e-mail, this is my address: eugenio.tacchini@unicatt.it
***********************************************************************************
*/
?>

    <hr class="onlyscreen">
    <table width="100%" class="onlyscreen">
      <tr>
        <td align="left"><span class="NavBlock"><a class="NavLink" href="<?php echo $dadabik_main_file; ?>?table_name=<?php echo urlencode($table_name); ?>"><?php echo $normal_messages_ar["home"]; ?></a>
<?php
if ($enable_insert == "1"){
?>
        &bull; <a class="NavLink" href="<?php $dadabik_main_file; ?>?function=show_insert_form&table_name=<?php echo urlencode($table_name); ?>"><?php echo $submit_buttons_ar["insert_short"]; ?></a>
<?php
}
?>
        &bull; <a class="NavLink" href="<?php $dadabik_main_file; ?>?function=show_search_form&table_name=<?php echo urlencode($table_name); ?>"><?php echo $submit_buttons_ar["search_short"]; ?></a> &bull; <a class="NavLink" href="<?php $dadabik_main_file; ?>?function=search&table_name=<?php echo urlencode($table_name); ?>"><?php echo $normal_messages_ar["last_search_results"]; ?></a> &bull; <a class="NavLink" href="<?php $dadabik_main_file; ?>?function=search&empty_search_variables=1&table_name=<?php echo urlencode($table_name); ?>"><?php echo $normal_messages_ar["show_all"]; ?></a>  &bull; <a class="NavLink" href="./archive.php?table_name=<?php echo urlencode($table_name); ?>"><?php echo _("Archive"); ?></a> &bull; <a class="NavLink" href="./express.php"><span class="nobr"><?php echo _("Express-Tool"); ?></span></a></span>
        </td>
      </tr>
    </table>
    </td>
  </tr>
</table>
<?php
include("./skins/$skin/footer.php")
?>
