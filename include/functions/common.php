<?php

/**
 * functions/common.php
 *
 * Some common internet functions.
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
 * @category  Internet
 * @package   Common
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

function url_exists($url) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_NOBODY, true); // set to HEAD request
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // don't output the response
	curl_exec($ch);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	return ($code >= 200 && $code < 400) ? true : false;
}

function bot_detected() {
	if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
		return true;
	}
	else {
		return false;
	}
}

// retrieve the browser language
function get_browser_language($allowed_languages, $default_language, $lang_variable = null, $strict_mode = true) {
        // Use $_SERVER['HTTP_ACCEPT_LANGUAGE'] when no $lang_variable was parsed
        if ($lang_variable === null) {
                $lang_variable = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }

        // Some information send with $_SERVER['HTTP_ACCEPT_LANGUAGE']?
        if (empty($lang_variable)) {
                // No? => use default language
                return $default_language;
        }

        // split the header
        $accepted_languages = preg_split('/,\s*/', $lang_variable);

        // set the default language
        $current_lang = $default_language;
        $current_q = 0;

        // parse all sent languages
        foreach ($accepted_languages as $accepted_language) {
                // get all info about this language
                $res = preg_match ('/^([a-z]{1,8}(?:-[a-z]{1,8})*)'.
                                   '(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i', $accepted_language, $matches);

                // is the syntax valid?
                if (!$res) {
                        // No? 0 => ignore
                        continue;
                }

                // split the language code in parts
                $lang_code = explode ('-', $matches[1]);

                // was a quality given?
                if (isset($matches[2])) {
                        // use the quality
                        $lang_quality = (float)$matches[2];
                } else {
                        // compatibility mode: use quality 1
                        $lang_quality = 1.0;
                }

                // until the language code is empty...
                while (count ($lang_code)) {
                        // check if the language is allowed
                        if (in_array (strtolower (join ('-', $lang_code)), $allowed_languages)) {
                                // compare quality
                                if ($lang_quality > $current_q) {
                                        // use this language
                                        $current_lang = strtolower (join ('-', $lang_code));
                                        $current_q = $lang_quality;
                                        break;
                                }
                        }
                        // in strict mode we're not minimalizing the language code
                        if ($strict_mode) {
                                break;
                        }
                        // delete the rightest part of the language code
                        array_pop ($lang_code);
                }
        }

        // return the found language
        return $current_lang;
}
