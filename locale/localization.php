<?php

/**
 * localization.php
 *
 * Responsible for setting the locale variables and the gettext language.
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
 * @category  Layout
 * @package   Layout
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

$lang = $session->lang;
switch ($lang) {
	case "en" :
		$locale = LOCALE_EN;
		break;
	default :
		$locale = LOCALE_DE;
}
putenv("LC_ALL=$locale"); //needed on some systems
#putenv("LANGUAGE=$locale"); //needed on some systems
#putenv("LANG=$locale");
setlocale(LC_ALL, $locale);
bindtextdomain("openhomeopath", "locale");
bind_textdomain_codeset("openhomeopath", "UTF-8");
textdomain("openhomeopath");
?>
