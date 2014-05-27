<?php
include_once("include/classes/login/session.php");
$skin = $session->skin;
include("./skins/$skin/header_datadmin_top.php");
include("./skins/$skin/frame.php");
?>
              <table class="main_table" cellpadding="10">
                <tr>
                  <td valign="top">
                  <h1 class="onlyscreen"><?php echo _("Data maintenance"); ?></h1>
<?php
if (empty($_get["table_name"])) {
?>
                  <p><?php echo _("Here, the database can be updated."); ?></p>
                  <p><?php echo _("Also is the <strong><a href='express.php'><span class='nobr'>Express Tool</span></a></strong> are available to insert quickly and easily <br><strong>symptoms</strong> and <strong>symptom-remedy-relations</strong> into the database."); ?></p>
<?php
}
$query = "SELECT name_table, alias_table_$lang FROM datadmin__tables";
$i=0;
$db->send_query($query);
while ($row = $db->db_fetch_row()) {
	$table_info_ar[$i]['table_name'] = $row[0];
	$table_info_ar[$i]['table_alias'] = $row[1];
	$i++;
}
$db->free_result();
foreach ($table_info_ar as $table_info) {
	if ($table_info['table_name'] == $table_name) {
		$table_alias = $table_info['table_alias'];
		break;
	}
}
echo "<h2 class='center'>" . _("Table:") . " " . $table_alias . "</h2><br>\n";
?>
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

                      &bull; <a class="NavLink" href="<?php $dadabik_main_file; ?>?function=show_search_form&table_name=<?php echo urlencode($table_name); ?>"><?php echo $submit_buttons_ar["search_short"]; ?></a> &bull; <a class="NavLink" href="<?php $dadabik_main_file; ?>?function=search&table_name=<?php echo urlencode($table_name); ?>"><?php echo $normal_messages_ar["last_search_results"]; ?></a> &bull; <a class="NavLink" href="<?php $dadabik_main_file; ?>?function=search&empty_search_variables=1&table_name=<?php echo urlencode($table_name); ?>"><?php echo $normal_messages_ar["show_all"]; ?></a> &bull; <a class="NavLink" href="./archive.php?table_name=<?php echo urlencode($table_name); ?>"><?php echo _("Archive"); ?></a> &bull; <a class="NavLink" href="./express.php"><span class="nobr"><?php echo _("Express-Tool"); ?></span></a></span>
                      </td>
                    </tr>
                  </table>

<?php
if ($table_name == "remedies" && (empty($function) || $function === 'search')) {
?>
<br>
<strong><?php echo _("Select initial letter:"); ?> </strong>
<?php
	$abc_ar = array('A', 'B', 'C', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
	foreach ($abc_ar as $abc) {
		echo ("<a class='abc' href=\"$dadabik_main_file?function=search&table_name=".urlencode($table_name)."&where_clause=rem_short LIKE '$abc%'&page=0\">&nbsp;$abc </a>\n");
	}
?>
<br>
<?php
}
if (urlencode($table_name) == "symptome" && (empty($function) || $function === 'search')) {
?>
<br>
<form method="POST" action="<? echo $dadabik_main_file; ?>?table_name=<?php echo urlencode($table_name); ?>&page=0&function=search&execute_search=1" enctype="multipart/form-data">
<label for="rubrics"><strong><?php echo _("Select the main rubric:"); ?> &nbsp;&nbsp;</strong></label>
<input name="rubric_id__select_type" type="hidden" value="is_equal">
<select name="rubric_id">
  <option value=""><?php echo _("all rubrics"); ?></option>
<?php
$lang = $session->lang;
$query = "SELECT rubric_id, rubric_$lang FROM rubrics ORDER BY rubric_$lang";
$db->send_query($query);
while($rubric = $db->db_fetch_row()) {
	echo ("          <option value='$rubric[0]'>$rubric[1]</option>\n");
}
$db->free_result();
?>
</select>
<input type="submit" class="submit" style="font-size:11px;" value="<?php echo _("Select"); ?>">
</form>
<?php
}
?>
