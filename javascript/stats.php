<?php

/**
 * stats.js
 *
 * A javascript to include for comunication with Piwik.
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
 * @category  Piwik
 * @package   Stats
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

$url = "http://openhomeo.org";
if (url_exists($url)) {
?>
<!-- Stats -->
    <script type="text/javascript">
      var _paq = _paq || [];
<?php
	$lang = $session->lang;
	$skin = $session->skin;
	if ($session->logged_in) {
		$user_name = $session->username;
		$user_level = $session->userlevel;
		if ($user_level == ADMIN_LEVEL) {
			$level = "Admin";
		} elseif ($user_level == EDITOR_LEVEL) {
			$level = "Editor";
		} elseif ($user_level == SHOW_LEVEL) {
			$level = "ShowActive";
		} else {
			$level = "User";
		}
	// you can set up to 5 custom variables for each visitor
		echo "      _paq.push(['setCustomVariable', 1, 'UserStatus', 'LoggedIn', 'visit']);\n";
		echo "      _paq.push(['setCustomVariable', 2, 'UserLevel', '$level', 'visit']);\n";
		echo "      _paq.push(['setCustomVariable', 3, 'UserName', '$user_name', 'visit']);\n";
		$_cvar = "{\"1\":[\"UserStatus\",\"LoggedIn\"],\"2\":[\"UserLevel\",\"$level\"],\"3\":[\"UserName\",\"$user_name\"],\"4\":[\"Language\",\"$lang\"],\"5\":[\"Skin\",\"$skin\"]}";
	} else {
		echo "      _paq.push(['setCustomVariable', 1, 'UserStatus', 'LoggedOut', 'visit']);\n";
		$_cvar = "{\"1\":[\"UserStatus\",\"LoggedOut\"],\"4\":[\"Language\",\"$lang\"],\"5\":[\"Skin\",\"$skin\"]}";
	}
	echo "      _paq.push(['setCustomVariable', 4, 'Language', '$lang', 'visit']);\n";
	echo "      _paq.push(['setCustomVariable', 5, 'Skin', '$skin', 'visit']);\n";
?>
      _paq.push(["setCookieDomain", "*.openhomeo.org"]);
      _paq.push(["trackPageView"]);
      _paq.push(["enableLinkTracking"]);

      (function() {
        var u="<?php echo $url; ?>/stats/";
        _paq.push(["setTrackerUrl", u+"js/"]);
        _paq.push(["setSiteId", "2"]);
        var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
        g.defer=true; g.async=true; g.src=u+"js/"; s.parentNode.insertBefore(g,s);
      })();
    </script>
<!-- End Stats Code -->
<?php
}
?>
