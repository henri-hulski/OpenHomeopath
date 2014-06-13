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
<?php

function display_sql($sql)
// goal: display a sql query
// input: $sql
// output: nothing
// global: $display_sql
{
	global $display_sql;
	if ($display_sql == "1"){
		echo "<p><strong style='color:#ff0000;'>Your SQL query (for debugging purpose): </strong>".$sql."</p>";
	} // end if
} // end function display_sql

function txt_out($message, $class="")
// goal: display text
// input: $message, $font_size, $font_color, $bold (1 if bold)
// output: nothing
{
	if ( $class != "") {
		$message = "<span class='$class'>$message</span>";
	}
	echo $message;
} // end function txt_out

function get_pages_number($results_number, $records_per_page)
// goal: calculate the total number of pages necessary to display results
// input: $results_number, $records_per_page
// ouptut: $pages_number
{
	$pages_number = $results_number / $records_per_page;
	$pages_number = (int)($pages_number);
	if (($results_number % $records_per_page) != 0) $pages_number++; // if the reminder is greater than 0 I have to add a page because I have to round to excess

	return $pages_number;
} // end function get_pages_number

function build_date_select ($field_name, $day, $month, $year)
// goal: build three select to select a data (day, mont, year), if are set $day, $month and $year select them
// input: $field_name, the name of the date field, $day, $month, $year (or "", "", "" if not set)
// output: $date_select, the HTML date select
// global $start_year, $end_year
{
	global $start_year, $end_year, $year_field_suffix, $month_field_suffix, $day_field_suffix;

	$date_select = "";
	$day_select = "";
	$month_select = "";
	$year_select = "";
	
	$day_select .= "<select name=\"".$field_name.$day_field_suffix."\">";
	$month_select .= "<select name=\"".$field_name.$month_field_suffix."\">";
	$year_select .= "<select name=\"".$field_name.$year_field_suffix."\">";

	for ($i=1; $i<=31; $i++){
		$day_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($day != "" and $day == $i){
			$day_select .= " selected";
		} // end if
		$day_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for

	for ($i=1; $i<=12; $i++){
		$month_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($month != "" and $month == $i){
			$month_select .= " selected";
		} // end if
		$month_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for

	for ($i=$start_year; $i<=$end_year; $i++){
		$year_select .= "<option value=\"$i\"";
		if($year != "" and $year == $i){
			$year_select .= " selected";
		} // end if
		$year_select .= ">".$i."</option>";
	} // end for

	$day_select .= "</select>";
	$month_select .= "</select>";
	$year_select .= "</select>";

	$date_select = "<td valign=\"top\">".$day_select."</td><td valign=\"top\">".$month_select."</td><td valign=\"top\">".$year_select."</td>";

	return $date_select;

} // end function build_date_select

function contains_numerics($string)
// goal: verify if a string contains numbers
// input: $string
// output: true if the string contains numbers, false otherwise
{
	$count_temp = strlen($string);
	if(preg_match("/[0-9]+/", $string)) {
		return true;
		
	}
	return false;
} // end function contains_numerics

function is_valid_email($email)
// goal: chek if an email address is valid, according to its syntax
// input: $email
// output: true if it's valid, false otherwise
{
	return (preg_match( 
        '/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+'.   // the user name 
        '@'.                                     // the ubiquitous at-sign 
        '([-0-9A-Z]+\.)+' .                      // host, sub-, and domain names 
        '([0-9A-Z]){2,4}$/i',                    // top-level domain (TLD) 
        trim($email))); 
} // end function is_valid_email

function is_valid_url($url)
// goal: check if an url address is valid, according to its syntax, supports 4 letters domains (e.g. .info), http https ftp protcols and also port numbers
// input: $url
// output: true if it's valid, false otherwise
{
	return preg_match("/^((ht|f)tps*://)((([a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4}))|(([0-9]{1,3}\.){3}([0-9]{1,3})))(:[0-9]{1,4})*((/|\?)[a-z0-9~#%&'_\+=:\?\.-]*)*)$/i", $url); 
} // end function is_valid_url

?>