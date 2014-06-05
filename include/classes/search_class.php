<?php

/**
 * search_class.php
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
 * @category  Homeopathy
 * @package   Search
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

/**
 * The Search class is responsible for building the WHERE part of the symptoms-search query
 *
 * @category  Homeopathy
 * @package   Search
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 */
class Search {

	/**
	 *  Escape character for encoding quotes
	 */
	const esc_chr = '@';
	
	
	/**
	 * The requested search string after an array with the search words
	 * @var string|array
	 * @access public
	 */
	public $search;
	
	/**
	 * If true we're searching for whole words with boolean fulltext search.
	 * If false we're searching for parts of words with regular expressions.
	 * @var boolean
	 * @access private
	 */
	private $whole_word;
	
	/**
	 * 'AND'|'OR': If 'AND' the search result should contain all requested words/phrases, if 'OR' any requested words/phrases.
	 * @var string
	 * @access private
	 */
	private $and_or;
	
	/**
	 * Symptoms table
	 * @var string
	 * @access private
	 */
	private $symptoms_tbl;
	
	/**
	 * The phrases from the search request first as array later as string prepared for the SQL-query
	 * @var array|string
	 * @access private
	 */
	private $search_phrase;
	
	/**
	 * The words which the search result must not contain first as array later as string prepared for the SQL-query
	 * @var array|string
	 * @access private
	 */
	private $search_not;
	
	/**
	 * 'boolean'|'regexp': 'boolean' if we're using the boolean fulltext search 'regexp' for a search with regular expressions
	 * @var string
	 * @access private
	 */
	private $mode;
	
	/**
	 * Contains the dublicated requested words with ss or ß
	 * @var array
	 * @access private
	 */
	private $dublicated_ss = array();

	/**
	 * Class constructor
	 *
	 * @return void
	 * @access public
	 */
	public function __construct() {
		
		global $db;
		
		$this->search = $_REQUEST['search'];
		$this->whole_word = (empty($_REQUEST['whole_word']) || $_REQUEST['whole_word'] === 'false') ? false : true;
		$this->and_or = (empty($_REQUEST['and_or'])) ? "AND" : $_REQUEST['and_or'];
		$this->symptoms_tbl = $db->get_custom_table("symptoms");
	}
	
	
	/**
	 * build_search is the central function of the Search class that builds the search query
	 *
	 * @return void
	 * @access public
	 */
	public function build_search() {
		
		// searching for parts of words with regular expressions
		if ($this->whole_word === false) {
			$this->mode = 'regexp';
			$this->delete_punctuation();
			$this->encode_quotes();
			$this->clean_whitespace();
			$this->extract_phrases();
			$this->build_phrases_search_query();
			$this->extract_search_not();
			$this->build_search_not_query();
			if (!empty($this->search)) {
				$this->extract_search_words();
				$this->build_search_query($this->search);
				if (empty($this->search_phrase)) {
					$this->search = "({$this->search})";
				} else {
					$this->search = "({$this->search} {$this->and_or} {$this->search_phrase})";
				}
			} elseif (!empty($this->search_phrase)) {
				$this->search = "({$this->search_phrase})";
			}
			if (!empty($this->search_not)) {
				if (!empty($this->search)) {
					$this->search .= " AND ";
				} else {
					$this->search = "";
				}
				$this->search .= $this->search_not;
			}
			$this->decode_quotes();
			$this->clean_whitespace();
		}
		//  searching for whole words with boolean fulltext search
		else {
			$this->mode = 'boolean';
			$this->encode_quotes();
			$this->extract_phrases();
			$this->extract_search_not();
			$this->extract_search_words();
			$this->build_boolean_query('+', $this->search);
			$this->build_boolean_query('+', $this->search_phrase);
			$this->build_boolean_query('-', $this->search_not);
			$this->search .= $this->search_phrase . $this->search_not;
			$this->clean_whitespace();
			$this->decode_quotes();
			$this->search = "MATCH ({$this->symptoms_tbl}.symptom) AGAINST ('" . $this->search . "' IN BOOLEAN MODE)";
		}
	}
	
	/**
	 * clean_whitespace removes whitespace at the beginning and end of the search string and double whitespace inside the search string
	 *
	 * @return void
	 * @access private
	 */
	private function clean_whitespace() {
		$this->search = preg_replace('/\s\s+/u', ' ', $this->search);
		$this->search = trim ($this->search);
	}
	
	/**
	 * delete_punctuation replaces punctuation inside the search string with a space
	 *
	 * @return void
	 * @access private
	 */
	private function delete_punctuation() {
		$punctuation = array('.', ',', ';', '!', ':', '@', '/', '*', '$', '^', '#');
		$this->search = str_replace($punctuation, ' ', $this->search);
	}
	
	/**
	 * encode_quotes repaces quotes and escaped quotes in the search string with the escape character constant esc_chr
	 *
	 * @return void
	 * @access private
	 */
	private function encode_quotes() {
		$quotes = array("\\'", '\\"', "\'", '\"', "'", '"');
		$this->search = str_replace($quotes, self::esc_chr, $this->search);
		// delete "\"
		$this->search = str_replace('\\', '', $this->search);
	}
	
	/**
	 * decode_quotes replaces the escape character constant esc_chr in the search string with a double quote
	 *
	 * @return void
	 * @access private
	 */
	private function decode_quotes() {
		$quote = '"';
		if ($this->mode === 'regexp') {
			$quote = '';
		}
		$this->search = str_replace(self::esc_chr, $quote, $this->search);
	}
	
	/**
	 * extract_phrases pulls quoted phrases from the search string and stores them in an array ($this->search_phrase)
	 *
	 * @return void
	 * @access private
	 */
	private function extract_phrases() {
		// copy phrases in quotes to an array
		preg_match_all('/' . self::esc_chr . '[^' . self::esc_chr . ']+' . self::esc_chr . '/u', $this->search, $this->search_phrase);
		// delete phrases in quotes from search
		$this->search = preg_replace('/' . self::esc_chr . '[^' . self::esc_chr . ']+' . self::esc_chr . '/u', '', $this->search);
		$this->search_phrase = $this->search_phrase[0];
		// if we are in regexp-mode append after every word '[[:punct:][:space:]]*' so we ignore punctuation and whitespaces between words in a phrase
		if (!empty($this->search_phrase) && $this->mode === 'regexp') {
			$this->search_phrase = preg_replace('/([\s' . self::esc_chr . '][\wßäöüÄÖÜ]+)/u', '\1[[:punct:][:space:]]*', $this->search_phrase);
		}
		$this->dublicate_ss('search_phrase');
	}
	
	/**
	 * extract_search_not pulls not desired words with a preceded '-' from the search string and stores them in an array ($this->search_not)
	 *
	 * @return void
	 * @access private
	 */
	private function extract_search_not() {
		// copy words beginning with "-" to an array
		preg_match_all('/-([\wßäöüÄÖÜ]+)/u', $this->search, $this->search_not);
		// delete words beginning with "-"
		$this->search = preg_replace('/-[\wßäöüÄÖÜ]+/u', '', $this->search);
		$this->search_not = $this->search_not[1];
		$this->dublicate_ss('search_not');
	}
	
	/**
	 * extract_search_words extract the remaining search words from the search string and stores them in an array ($this->search)
	 *
	 * @return void
	 * @access private
	 */
	private function extract_search_words() {
		// words to array
		$this->search = preg_split("/[\s\\,]+/", $this->search, -1,  PREG_SPLIT_NO_EMPTY);
		$this->dublicate_ss('search');
	}
	
	/**
	 * build_phrases_search_query builds the SQL search query for the phrases search in regexp-mode
	 *
	 * @return void
	 * @access private
	 */
	private function build_phrases_search_query() {
		$this->build_search_query($this->search_phrase);
	}
	
	/**
	 * build_search_not_query builds the SQL search query for not desired words in regexp-mode
	 *
	 * @return void
	 * @access private
	 */
	private function build_search_not_query() {
		// array to string
		if (!empty($this->search_not)) {
			$this->search_not = implode("' AND {$this->symptoms_tbl}.symptom NOT REGEXP '", $this->search_not);
			$this->search_not = "{$this->symptoms_tbl}.symptom NOT REGEXP '{$this->search_not}'";
		}
		if (!empty($this->dublicated_ss)) {
			if (!empty($this->search_not)) {
				$this->search_not .= " AND ";
			} else {
				$this->search_not = "";
			}
			$this->search_not .= implode(" AND ", $this->dublicated_ss);
			unset($this->dublicated_ss);
		}
	}
	
	/**
	 * build_search_query builds the SQL search query in regexp-mode
	 *
	 * @param array|string  &$search receiving an array containing the search strings,
	 *                               returning a string with the SQL-query
	 *                               ($this->search|$this->search_phrases)
	 * @return void
	 * @access private
	 */
	private function build_search_query(&$search) {
		if (!empty($search)) {
			$search = implode("' {$this->and_or} {$this->symptoms_tbl}.symptom REGEXP '", $search);
			$search = "{$this->symptoms_tbl}.symptom REGEXP '$search'";
		}
		if (!empty($this->dublicated_ss)) {
			if (!empty($search)) {
				$search .= " {$this->and_or} ";
			} else {
				$search = "";
			}
			$search .= implode(" {$this->and_or} ", $this->dublicated_ss);
			unset($this->dublicated_ss);
		}
	}
	
	/**
	 * build_boolean_query builds the SQL search query in boolean-mode
	 *
	 * @param string  $operator '+'|'-': If '+' the result has to contain this string if '-' it must not
	 * @param array|string  &$search receiving an array containing the search strings,
	 *                               returning a string with the SQL-query
	 *                               ($this->search|$this->search_phrases|$this->search_not)
	 * @return void
	 * @access private
	 */
	private function build_boolean_query($operator, &$search) {
		if (empty($search)) {
			$search = '';
		} else {
			if ($operator === '+' && $this->and_or === 'OR') {
				$operator = '';
			}
			$search = implode(" $operator", $search);
			$search = " $operator" . $search;
		}
	}
	
	/**
	 * dublicate_ss search for a 'ss' or 'ß' in the search string and dublicate them with the counterpart
	 *
	 * This function closes a bug in the German repertories, where some words sometimes are written
	 * with ss and sometimes with ß.
	 *
	 * Every string containing 'ss' is dublicated and the 'ss' is replaced by 'ß'.
	 * Also every string containing 'ß' is dublicated and the 'ß' is replaced by 'ss'.
	 *
	 * @param string $search_ar_name The variable name of the array containing the search strings ('search'|'search_phrase'|'search_not').
	 * @return void
	 * @access private
	 */
	private function dublicate_ss($search_ar_name) {
		$search_ar = &$this->$search_ar_name;
		if (!empty($search_ar)) {
			$private_search_ar = $search_ar;
			foreach ($private_search_ar as $key => $search_string) {
				if (strpos($search_string, 'ss') !== false) {
					$duplicate = str_replace('ss', 'ß', $search_string);
					$this->merge_dublicate_ss($search_ar_name, $duplicate, $key);
				}
				if (strpos($search_string, 'ß') !== false) {
					$duplicate = str_replace('ß', 'ss', $search_string);
					$this->merge_dublicate_ss($search_ar_name, $duplicate, $key);
				}
			}
		}
	}
	
	/**
	 * merge_dublicate_ss creates the SQL query string for the dublicated search strings containing 'ss' or 'ß'.
	 *
	 * @param string  $search_ar_name The variable name of the array containing the search strings ('search'|'search_phrase'|'search_not').
	 * @param string  $duplicate      The dublicated search string with the replaced 'ss'|'ß'.
	 * @param integer $key            The array key of the dublicated search string in the search strings array.
	 * @return void
	 * @access private
	 */
	private function merge_dublicate_ss($search_ar_name, $duplicate, $key) {
		$search_ar = &$this->$search_ar_name;
		if ($this->mode === 'boolean') {
			$search_ar[$key] = "(" . $search_ar[$key] . " " . $duplicate . ")";
		} else {
			$regexp = 'REGEXP';
			$and_or = 'OR';
			if ($search_ar_name === 'search_not') {
				$regexp = 'NOT REGEXP';
				$and_or = 'AND';
			}
			$this->dublicated_ss[] = "({$this->symptoms_tbl}.symptom $regexp '{$search_ar[$key]}' $and_or {$this->symptoms_tbl}.symptom $regexp '$duplicate')";
			unset($search_ar[$key]);
		}
	}
	
}
?>
