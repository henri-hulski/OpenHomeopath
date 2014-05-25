<?php
if (!isset($tabbed) || !$tabbed) {
	include_once ("include/classes/login/session.php");
}
if (!$session->logged_in) {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$zusatz = "login.php?url=symptomeditor.php?sym=" . $_REQUEST['symptom'];
	header("Content-Type: text/html;charset=utf-8"); 
	header("Location: http://$host$uri/$zusatz");
	die();
}
$current_page = "symptomeditor";
if (!$tabbed && !isset($_GET['tab']) && empty($_GET['popup'])) {
	$head_title = _("Symptom-Editor") . " :: OpenHomeopath";
	$skin = $session->skin;
	include("./skins/$skin/header.php");
} elseif (empty($_GET['popup'])) {
?>
  <div style='float: right; margin: 25px;'>
      <a id='history_back_tab_3' style='padding: 7px;'><img alt=""  id='arrow_left_tab_3' height='24' width='28' src='./img/arrow_left_inactive.gif' border='0'></a><a id='history_forward_tab_3' style='padding: 7px;'><img alt=""  id='arrow_right_tab_3' height='24' width='28' src='./img/arrow_right_inactive.gif' border='0'></a>
  </div>
<?php
}
?>
<h1>
  <?php echo _("Symptom-Editor"); ?>
</h1>
<?php
if ($session->logged_in && !$magic_hat->restricted_mode) {
	if (!$tabbed && !isset($_REQUEST['tab'])) {
		$url = "userinfo.php?user=" . $session->username . "#rep_custom";
	} else {
		$url = 'javascript:userTabOpen("rep_custom")';
	}
	if ($db->is_custom_table("sym_rem") === false) {
		$display_personal_rep = "none";
	} else {
		$display_personal_rep = "block";
	}
	printf("<p class='center' id='personalized_rep_2' style='display:%s;'><span class='alert_box'>" . _("You are using a personalized Repertory. You can change the preferences in <a href='%s'>My account</a>.") . "</span></p>\n", $display_personal_rep, $url);
} elseif (!$session->logged_in) {
	echo ("<p class='center''><span class='alert_box'><strong>" . _("Important!") . "</strong> " . _("Guests are limited to the Homeopathic Repertory from Kent (kent.en). For activating more repertories an customizing OpenHomeopath you've to <a href='http://openhomeo.org/openhomeopath/register.php'>register for free</a> and <a href='http://openhomeo.org/openhomeopath/login.php'>log in</a>.") . "</span></p>\n");
} elseif ($magic_hat->restricted_mode) {
	echo ("<p class='center''><span class='alert_box'><strong>" . _("Important!") . "</strong> " . _("At the moment only the Homeopathic Repertory from Kent (kent.en) is enabled.") . "<br>" . _("As long as the donation goal for this month is not reached some functions of OpenHomeopath are only available for users who have already donated.") . "<br><a href=\"javascript:popup_url('donations.php',960,720)\"><strong>" . _("Please donate now!") . "</strong></a></span></p>\n");
}
?>
<form action="" accept-charset="utf-8">
  <fieldset>
<?php
$num_rows = 0;
$sym_id = $_REQUEST['symptom'];
$lang = $session->lang;
$query = "SELECT symptoms.symptom, symptoms.rubric_id, main_rubrics.rubric_$lang, languages.lang_$lang, sym_src.kuenzli FROM (symptoms, main_rubrics, languages) LEFT JOIN sym_src ON sym_src.sym_id = symptoms.sym_id WHERE main_rubrics.rubric_id = symptoms.rubric_id AND languages.lang_id = symptoms.lang_id AND symptoms.sym_id = $sym_id";
$db->send_query($query);
list($symptom, $rubric_id, $rubric_name, $lang_name, $translation_id, $native, $kuenzli) = $db->db_fetch_row();
$db->free_result();
$symptom_link = $symptom;
if (strpos($symptom, " > ") !== false) {
	$rubrics_ar = explode(" > ", $symptom);
	$rubrics_num = count($rubrics_ar);
	$rubric = array_pop($rubrics_ar);
	$rubrics_link_ar[$rubrics_num] = $rubric;
	while (count($rubrics_ar) > 0) {
		$rubrics_num = count($rubrics_ar);
		$rubrics = implode(" > ", $rubrics_ar);
		$query = "SELECT sym_id FROM symptoms WHERE rubric_id = $rubric_id AND symptom = '$rubrics'";
		$db->send_query($query);
		list($subrubric_id) = $db->db_fetch_row();
		$db->free_result();
		$rubric = array_pop($rubrics_ar);
		if (!empty($subrubric_id)) {
			if (!$tabbed && !isset($_GET['tab'])) {
				$rubric = "<a href='symptomeditor.php?sym=$subrubric_id'>$rubric</a>";
			} else {
				$rubric = "<a href='javascript:tabOpen(\"symptomeditor.php?sym=\", $subrubric_id, \"GET\", 3)'>$rubric</a>";
			}
		}
		$rubrics_link_ar[$rubrics_num] = $rubric;
	}
	ksort($rubrics_link_ar, SORT_NUMERIC);
	$symptom_link = implode(" > ", $rubrics_link_ar);
}
echo ("    <legend class='legend'>\n");
echo ("      $rubric_name >> $symptom_link\n");
echo ("    </legend>\n");
echo ("    <ul class='blue'>\n");
echo ("      <li><strong>" . _("Symptom:") . " </strong><span class='gray'>$symptom</span></li>\n");
echo ("      <li><strong>" . _("Symptom-No.:") . " </strong><span class='gray'>$sym_id</span></li>\n");
echo ("      <li><strong>" . _("Main rubric:") . " </strong><span class='gray'>$rubric_name</span></li>\n");
echo ("      <li><strong>" . _("Language:") . " </strong><span class='gray'>$lang_name");
if ($native != 0) {
	echo (" (" . _("native language") . ")");
}
echo ("</span></li>\n");
echo ("      <li><strong>" . _("Translations:") . "</strong>\n");
if (!empty($translation_id)) {
	$query = "SELECT DISTINCT symptoms.symptom, languages.lang_$lang, symptoms.native FROM symptoms, languages WHERE symptoms.translation_id = $translation_id AND symptoms.sym_id != $sym_id AND languages.lang_id = symptoms.lang_id";
	$db->send_query($query);
	$num_rows = $db->db_num_rows();
	if ($num_rows > 0) {
		echo ("        <ul>\n");
		while (list($trans_symptom, $trans_lang_name, $trans_native) = $db->db_fetch_row()) {
			echo ("          <li><strong>" . $trans_lang_name . "</strong>");
			if ($trans_native != 0) {
				echo (" (" . _("native language") . ")");
			}
			echo ("<strong>: </strong><span class='gray'>$trans_symptom</span></li>\n");
		}
		echo ("        </ul>\n");
	}
	$db->free_result();
} else {
	echo ("<span class='gray'>keine</span></li>\n");
}
echo ("      </li>\n");
if ($kuenzli == 1) {
	$image = "img/success.png";
} else {
	$image = "img/delete.png";
}
echo ("      <li><strong>" . _("Künzli-dot:") . " &nbsp;</strong><span class='gray'><img src='skins/original/$image' width='16' height='16'></span></li>\n");
echo ("    </ul>\n");
?>
  </fieldset>
</form>
<?php
if (!$tabbed && !isset($_GET['tab']) && empty($_GET['popup'])) {
	popup(1);
	include("./skins/$skin/footer.php");
}
?>
