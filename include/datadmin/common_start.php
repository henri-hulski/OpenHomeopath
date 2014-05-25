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
ob_start();

// tables present in the database
$table_names_ar = build_tables_names_array(0, 0);

if (count($table_names_ar) == 0){ // no table
	echo "<p><b>[02] Error:</b> your database ".DB_NAME." is empty. No tables found. Please create some tables to manage before using DaDaBIK.";
	exit;
} // end if

ini_set('session.cookie_path', $site_path);
// session_start();

if (!isset($_POST)){
	$_POST=$HTTP_POST_VARS;
}
if (!isset($_GET)){
	$_GET=$HTTP_GET_VARS;
}
if (!isset($_FILES)){
	$_FILES=$HTTP_POST_FILES;
}
if (!isset($_SESSION)){
	$_SESSION=$HTTP_SESSION_VARS;
}

// the var is set in check_login but check_login it's not included by e.g. admin, and it's useful for some functions (e.g. build_tables_names_array) to have it set
$current_user_is_administrator = 0;
$current_user_is_editor = 0;

?>