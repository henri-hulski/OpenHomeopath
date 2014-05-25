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
// installed and allowed tables
$tables_names_ar = build_tables_names_array();

if (count($tables_names_ar) == 0){ // no tables installed and allowed
	echo "<p><b>[04] Error:</b> it is impossible to run DaDaBIK, probably because you have decided to exclude all the tables from the DaDaBIK interface; go to the <a href='admin.php'>administration interface</a> and include some tables.";
	exit;
} // end
else{
	if (!isset($_GET["table_name"])){
		$table_name = $tables_names_ar[0];
	} // end if
	else{
		$table_name = $_GET["table_name"];
		if ( !in_array($table_name, $tables_names_ar) ) { // someone try to manage a not-allowed table by changing the url
			echo "<p><b>[05] Error:</b> you are attemping to manage a not allowed table.";
			exit;
		}
	} // end else
	$enabled_features_ar = build_enabled_features_ar($table_name);

	$enable_insert = $enabled_features_ar["insert"];
	$enable_edit = $enabled_features_ar["edit"];
	$enable_delete = $enabled_features_ar["delete"];
	$enable_details = $enabled_features_ar["details"];

	$table_internal_name = $prefix_internal_table.$table_name;
} // end else
?>