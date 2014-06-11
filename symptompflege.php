<?php
if (!isset($tabbed) || !$tabbed) {
	include_once ("include/classes/login/session.php");
}
if (!$session->logged_in) {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$zusatz = "login.php?url=symptompflege.php";
	header("Content-Type: text/html;charset=utf-8"); 
	header("Location: http://$host$uri/$zusatz");
	die();
}
$current_page = "symptompflege";
if (!$tabbed && !isset($_REQUEST['tab'])) {
	$head_title = _("Symptom maintenance") . " :: OpenHomeopath";
	$skin = $session->skin;
	include("skins/$skin/header.php");
}
?>
<h1>
   <?php echo _("Symptom maintenance"); ?>
</h1>
<?php
$symptoms_tbl = $db->get_custom_table("symptoms");
include ("forms/symptom_select_form.php");

if ((isset($_REQUEST['main_rubrics']) && $num_rows != 0) || !empty($_REQUEST['symsel'])) {
	$display = "block";
} else {
	$display = "none";
}
?>
<div id='selected_symptoms' style='display:<?php echo($display);?>;'>
<fieldset>
  <legend class="legend">
    <?php echo _("Selected symptoms"); ?>
  </legend>
  <form action="" accept-charset="utf-8">
    <div class = 'select'>
      <select class="selection" name="symsel[]" id="symSelect" size="16" onDblClick="doubleClick('symDeselect()',this)" onClick="doubleClick('symDeselect()',this)">
<?php
	if (isset($_REQUEST['symsel'])) {
		$symselect = explode("_", $_REQUEST["symsel"]);
		foreach ($symselect as $sym_id) {
			$query = "SELECT $symptoms_tbl.symptom, main_rubrics.rubric_$lang FROM $symptoms_tbl, main_rubrics WHERE $symptoms_tbl.sym_id = $sym_id AND main_rubrics.rubric_id = $symptoms_tbl.rubric_id";
			$db->send_query($query);
			list ($symptom, $main_rubric) = $db->db_fetch_row();
			$db->free_result();
			echo ("        <option value='$sym_id' title='$main_rubric >> $symptom'>$main_rubric >> $symptom</option>\n");
		}
	}
?>
      </select>
<?php
	if (!$tabbed && !isset($_REQUEST['tab'])) {
		$tab = -1;
	} else {
		$tab = 4;
	}
?>
      <div class="button_area_3">
        <input type="button" class="submit" value=" <?php echo _("Deselect symptom"); ?> " onclick="symDeselect()">
        <input type="button" class="submit" value=" <?php echo _("Edit symptom"); ?> " onclick="symptomEdit(<?php echo($tab);?>, 'symSelect')">
        <input type="button" class="submit" value=" <?php echo _("Link as synonyms"); ?> " onclick="synonym()">
        <input type="button" class="submit" value=" <?php echo _("Link as cross reference"); ?> " onclick="xref()">
      </div>
    </div>
  </form>
</fieldset>
</div>
<?php
if (!$tabbed && !isset($_REQUEST['tab'])) {
	popup();
	include("./skins/$skin/footer.php");
}
?>
