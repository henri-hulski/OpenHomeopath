<?php

/**
 * userinfo.php
 *
 * This file gives infos about the user account.
 * The user has access to saved repertorizations.
 * The user can personalize the reperory and the materia medica.
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
 * @package   UserAccount
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

if (!isset($tabbed) || !$tabbed) {
	include_once ("include/classes/login/session.php");
}
if (!$session->logged_in) {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = "login.php?url=userinfo.php";
	header("Content-Type: text/html;charset=utf-8"); 
	header("Location: http://$host$uri/$extra");
	die();
}
$username = $session->username;
if (!$tabbed && !isset($_REQUEST['tab'])) {
	$head_title = _("Account") . " :: OpenHomeopath";
	$current_page = 'userinfo';
	$skin = $session->skin;
	include("skins/$skin/header.php");
}
$lang = $session->lang;

/* Requested Username error checking */
if (empty($req_user)) {
	if (empty($_REQUEST['user'])) {
		$req_user = $username;
	} else {
		$req_user = trim($_REQUEST['user']);
		if(!$req_user || strlen($req_user) == 0 || !$db->usernameTaken($req_user)){
			var_dump ($req_user);
			die(_("Username not registered"));
		}
	}
}

/* Logged in user viewing own account */
if(strcmp($username,$req_user) == 0) {
?>
<h1><?php echo _("My account"); ?></h1>
<nav class="content">
  <h2>
    <?php echo _("Contents"); ?>
  </h2>
  <ul>
    <li><a href="#common"><?php echo _("General Info"); ?></a></li>
    <li><a href="#reps"><?php echo _("Saved repertorizations"); ?></a></li>
    <li><a href="#rep_custom"><?php echo _("Personalize the Repertory"); ?></a></li>
    <li><a href="#materia_custom"><?php echo _("Personalize the Materia Medica"); ?></a></li>
  </ul>
</nav>
<a id="common"><br></a>
<h2><?php echo _("General Info"); ?></h2>
<?php
}
/* Visitor not viewing own account */
else {
?>
<h1><?php echo _("User Info"); ?></h1>
<?php
}
/* Logged in user viewing own account */
if(strcmp($username,$req_user) == 0) {
	/* Donator */
	if ($magic_hat->is_donator) {
		echo "<strong>" . _("You've already donated for OpenHomeopath.") . "</strong><br>" . _("All functions of OpenHomeopath are available.") . "<br>" . _("Thanks a lot and keep it up!");
	} else {
		printf ("<strong>" . _("Up to now you didn't support OpenHomeopath.") . "</strong><br>" . _("Until the monthly donation goal is reached, the functionality of OpenHomeopath is restricted for non-donators.") . "<br>" . _("It would be nice, if you could help us %swith a donation%s."), "<strong><a href='donations.php' onclick=\"popup_url('donations.php',960,720)\">", "</a></strong>");
	}
	echo "<div class='center' style='width:50%'><p>";
	echo "<a href='donations.php' onclick=\"popup_url('donations.php',960,720); return false; \"><img src='img/donate_$lang.png' width='110' height='33' alt='" . _("Donations") . "' title='" . _("Every donation is very welcome and helps the development of OpenHomeopath.") . "'></a>";
	echo "</p></div>";
}

/* Display requested user information */
list($user_email, $user_real_name, $user_extra, $user_signatur, $userlevel, $hide_email, $user_skin, $user_lang_id, $user_sym_lang) = $db->getUserInfo($req_user, 'email, user_real_name, user_extra, user_signatur, userlevel, hide_email, skin_name, lang_id, sym_lang_id');

/* Username */
echo "<strong>" . _("Username:") . " ".$req_user."</strong><br>";

/* Email will be shown when user view his own account, the user didn't hide his email or administrator is viewing the page */
if(strcmp($username,$req_user) == 0 || $hide_email == 0 || $session->isAdmin()){
	echo "<strong>" . _("E-mail:") . "</strong> ".$user_email."<br>";
}

/* Real name */
if (!empty($user_real_name)) {
	echo "<strong>" . _("Real name:") . "</strong> ".$user_real_name."<br>";
}

/* More information */
if (!empty($user_extra)) {
	echo "<strong>" .  _("More information:") . "</strong> ".$user_extra."<br>";
}

/* Signature */
if (!empty($user_signatur)) {
	echo "<strong>" . _("Signature for the forum:") . "</strong> ".$user_signatur."<br>";
}

if(strcmp($username,$req_user) == 0 || $session->isAdmin()){  // Benutzer betrachtet sein eigenes Konto oder Admin
/* Skin */
	if (!empty($user_skin)) {
		echo "<strong>" . _("Skin:") . "</strong> ".$user_skin."<br>";
	} else {
		echo "<strong>" . _("Skin:") . "</strong> " . _("not selected") . "<br>";
	}
/* Language */
	if (!empty($user_lang_id)) {
		$query = "SELECT lang_$lang FROM languages WHERE lang_id = '$user_lang_id'";
		$db->send_query($query);
		list($user_lang) = $db->db_fetch_row();
		$db->free_result();
		echo "<strong>" . _("Language:") . "</strong> $user_lang<br>";
	} else {
		echo "<strong>" . _("Language:") . "</strong> " . _("not selected") . "<br>";
	}
/* Symptom-language */
	if (!empty($user_sym_lang)) {
		$query = "SELECT lang_$lang FROM languages WHERE lang_id = '$user_sym_lang'";
		$db->send_query($query);
		list($sym_lang) = $db->db_fetch_row();
		$db->free_result();
		echo "<strong>" . _("Symptom language:") . "</strong> $sym_lang<br>";
	} else {
		echo "<strong>" . _("Symptom language:") . "</strong> " . _("same as system language") . "<br>";
	}
	echo "<br>";

/* userlevel */
	if ($userlevel == ADMIN_LEVEL) {
		echo _("You are administrator.");
	} elseif ($userlevel == EDITOR_LEVEL) {
		echo _("You are editor.");
	} elseif ($userlevel == SHOW_LEVEL) {
		echo _("Active users are shown.");
	} else {
		echo _("Active users are not shown.");
	}
	echo "<br>\n";
	if ($hide_email == 1) {
		echo _("Your e-mail is hidden from user users.");
	} else {
		echo _("Other users can see your e-mail.");
	}
}
if(strcmp($username,$req_user) == 0) {
	echo "<br><a href='useredit.php' target='_blank'><strong>" . _("Here you can edit your account.") . "</strong></a><br>\n";
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
?>
<br><span class="rightFlow"><a href="#up" title="<?php echo _("To the top of the page"); ?>"><img src="<?php echo(ARROW_UP_ICON);?>" alt="<?php echo _("To the top of the page"); ?>"></a></span>
<a id="reps"><br></a>
<h2><?php echo _("Saved repertorizations"); ?></h2>
<p><?php echo _("Here saved repertorizations can be opened,  deleted or taken as a basis for further repertorization."); ?><br>
<?php printf(_('Repertorizations can be made public so that other users will find them in userinfo (URL: <span class="nobr">"http://%s%s/<strong>userinfo.php?user=%s</strong>"</span>).'), $host, $uri, $req_user); ?></p>
<fieldset>
  <legend class='legend'>
<?php
	echo ("    " . _("Repertorizations from") . " $req_user\n");
?>
  </legend>
  <br>
  <div id='saved_reps'>
<?php
	include ("./forms/saved_reps.php");
?>
  </div>
</fieldset>
<br><span class="rightFlow"><a href="#up" title="<?php echo _("To the top of the page"); ?>"><img src="<?php echo(ARROW_UP_ICON);?>" alt="<?php echo _("To the top of the page"); ?>"></a></span>

<a id="rep_custom"><br></a>
<h2><?php echo _("Personalize the Repertory"); ?></h2>
<p><?php echo _("Here you can compose your <strong>personal Repertory profile</strong> by selecting <strong>which sources</strong> will be included. This profile will be used by the <strong>Repertorization</strong>, if you're <strong>logged in</strong> with your username."); ?>
<br>
<?php echo _("The <strong>reversed repertorization in the Materia Medica</strong> also uses the personalized repertory.") . " " . _("To personalize the actual <strong>Materia Medica</strong> see <a href='#materia_custom'>below</a>."); ?>
<br>
<?php echo _(" Accordingly, the <strong>Symptom-Info</strong> uses the personalized repertory in the remedy list."); ?>
</p>
<p>
<?php echo _("You can select your preferred symptom-language in your <a href='useredit.php'>user-account</a>."); ?>
</p>
<form accept-charset="utf-8" name="personal_rep_form">
  <fieldset>
    <legend class='legend'>
      <?php echo _("Compose your personal Repertory"); ?>
    </legend>
    <div class = 'select'>
      <table style="width:94%; border:0; text-align:left;">
        <tr>
          <td class="caption" colspan="2">
            <p id='personal_rep' class='center'>
<?php
	include ("./forms/personal_rep.php");
?>
            </p>
          </td>
        </tr>
        <tr>
          <td>
<?php
	echo("            <input type='radio' class='button' name='src' value='all'");
	if ($src_rep == 'all') {
		echo(" checked='checked'");
	}
	echo(">&nbsp;<span class='label'>" . _("Use all sources") . "</span>\n");
	$query = "SELECT lang_id, lang_$lang FROM languages WHERE sym_lang != 0 ORDER BY lang_id";
	$result = $db->send_query($query);
	while (list($sympt_lang, $sympt_lang_name) = $db->db_fetch_row($result)) {
		echo("<br>\n");
		echo("            <input type='radio' class='button' name='src' value='lang_$sympt_lang'");
		if ($src_rep == "lang_$sympt_lang") {
			echo(" checked='checked'");
		}
		printf(">&nbsp;<span class='label'>" . _("Symptoms in %s") . "</span>\n", $sympt_lang_name);
	}
?>
          </td>
          <td></td>
        </tr>
        <tr>
          <td>
            <label for='custom_src_rep'>
<?php
	echo("<input type='radio' class='button' name='src' id='custom_src_rep' value='custom'");
	if ($src_rep == 'custom') {
		echo(" checked='checked'");
	}
	echo(">&nbsp;<span class='label'>" . _("Select sources") . "</span></label>\n");
?>
          </td>
          <td></td>
        </tr>
        <tr>
          <td>
            <select class='selection4' name='src_sel[]' id='src_rep' size='7' multiple='multiple' onclick='document.getElementById("custom_src_rep").checked=true' onchange='document.getElementById("custom_src_rep").checked=true'>
<?php
	$query = "SELECT sources.src_no, sources.src_id, sources.src_title, languages.lang_$lang FROM sources, languages WHERE languages.lang_id = sources.lang_id ORDER BY sources.src_no";
	$result = $db->send_query($query);
	$i=0;
	while(list($src_no, $src_id, $src_title, $src_lang) = $db->db_fetch_row($result)) {
		$query = "SELECT src_id FROM sym_rem WHERE src_id = '" . $src_id . "' LIMIT 1";
		$db->send_query($query);
		$count = $db->db_num_rows();
		$db->free_result();
		if ($count > 0) {
			$query = "SELECT lang_$lang FROM languages JOIN sources ON languages.lang_id = sources.lang_id JOIN src_translations ON src_translations.src_translated = sources.src_id WHERE src_translations.src_native = '" . $src_id . "' ORDER BY lang_$lang";
			$db->send_query($query);
			while(list($src_translated_lang) = $db->db_fetch_row()) {
				$src_translated_lang_ar[] = $src_translated_lang;
			}
			$db->free_result();
			$src_translated_lang = "";
			if (!empty($src_translated_lang_ar)) {
				$src_translated_lang = ", " . implode(", ", $src_translated_lang_ar);
			}
			unset($src_translated_lang_ar);
			echo ("          <option value='$src_no'");
			if (isset($custom_rep_ar) && in_array($src_id, $custom_rep_ar)) {
				echo (" selected='selected'");
			}
			echo (">$src_id: $src_title ($src_lang" . $src_translated_lang . ")</option>\n");
			$i++;
		}
	}
	$count_sources = $i;
	$db->free_result($result);
?>
            </select>
          </td>
          <td>
            <input type="button" onclick='customTable("rep")' value=" <?php echo _("Compose Repertory"); ?> ">
          </td>
        </tr>
        <tr>
          <td>
<?php
	printf("      <p class='label2'>" . ngettext("one source", "%d sources", $count_sources) . "</p>\n", $count_sources);
?>
          </td>
          <td></td>
        </tr>
        <tr>
          <td class="caption" colspan="2">
            <?php echo _("For <strong>multiple choice</strong> press <strong><em>Ctrl</em></strong> and click simultaneously on the entries you want to select."); ?>
            <br><?php echo _("Only sources and users are shown, which are associated with records in the Repertory."); ?>
          </td>
        </tr>
      </table>
    </div>
    <br>
  </fieldset>
</form>
<br><span class="rightFlow"><a href="#up" title="<?php echo _("To the top of the page"); ?>"><img src="<?php echo(ARROW_UP_ICON);?>" alt="<?php echo _("To the top of the page"); ?>"></a></span>

<a id="materia_custom"><br></a>
<h2><?php echo _("Personalize the Materia Medica"); ?></h2>
<p><?php echo _("Here you can compose your <strong>personal Materia Medica</strong> by selecting <strong>which sources</strong> will be included. This profile will be used by the <strong>Remedy Descriptions</strong> in the Materia Medica if you're <strong>logged in</strong> with your username."); ?>
</p>

<form accept-charset="utf-8" name="personal_materia_form">
  <fieldset>
    <legend class='legend'>
      <?php echo _("Compose your personal Materia Medica"); ?>
    </legend>
    <div class='select'>
      <table style="width:94%; border:0; text-align:left">
        <tr>
          <td class="caption" colspan="2">
            <p id='personal_materia' class='center'>
<?php
	include ("./forms/personal_materia.php");
?>
            </p>
          </td>
        </tr>
        <tr>
          <td>
<?php
	echo("            <input type='radio' class='button' name='src' value='all'");
	if ($src_materia == 'all') {
		echo(" checked='checked'");
	}
	echo(">&nbsp;<span class='label'>" . _("Use all sources") . "</span>\n");
?>
          </td>
          <td></td>
        </tr>
        <tr>
          <td>
            <label for='custom_src_materia'>
<?php
	echo("<input type='radio' class='button' name='src' id='custom_src_materia' value='custom'");
	if ($src_materia == 'custom') {
		echo(" checked='checked'");
	}
	echo(">&nbsp;<span class='label'>" . _("Select sources") . "</span></label>\n");
?>
          </td>
          <td></td>
        </tr>
        <tr>
          <td>
            <select class='selection4' name='src_sel[]' id='src_materia' size='15' multiple="multiple" onclick='document.getElementById("custom_src_materia").checked=true' onchange='document.getElementById("custom_src_materia").checked=true'>
<?php
	$query = "SELECT sources.src_no, sources.src_id, sources.src_title, languages.lang_$lang FROM sources, languages WHERE languages.lang_id = sources.lang_id ORDER BY sources.src_no";
	$result = $db->send_query($query);
	$i=0;
	while (list($src_no, $src_id, $src_title, $src_lang) = $db->db_fetch_row($result)) {
		$query = "SELECT src_id FROM materia WHERE src_id = '" . $src_id . "' LIMIT 1";
		$db->send_query($query);
		$count = $db->db_num_rows();
		$db->free_result();
		if ($count > 0) {
			echo ("          <option value='$src_no'");
			if (isset($custom_materia_ar) && in_array($src_id, $custom_materia_ar)) {
				echo (" selected='selected'");
			}
			echo (">$src_id: $src_title ($src_lang)</option>\n");
			$i++;
		}
	}
	$count_sources = $i;
	$db->free_result($result);
?>
            </select>
          </td>
          <td>
            <input type="button" onclick='customTable("materia")' value=" <?php echo _("Compose Materia Medica"); ?> ">
          </td>
        </tr>
        <tr>
          <td>
<?php
	printf("      <p class='label2'>" . ngettext("one source", "%d sources", $count_sources) . "</p>\n", $count_sources);
?>
          </td>
          <td></td>
        </tr>
        <tr>
          <td class="caption" colspan="2">
            <?php echo _("For <strong>multiple choice</strong> press <strong><em>Ctrl</em></strong> and click simultaneously on the entries you want to select."); ?>
            <br><?php echo _("Only sources and users are shown, which are associated with records in the Materia Medica."); ?>
          </td>
        </tr>
      </table>
    </div>
    <br>
  </fieldset>
</form>

<?php
} else {
	$query = "SELECT rep_public FROM repertorizations WHERE username = '$req_user' AND rep_public = 1 LIMIT 1";
	$db->send_query($query);
	$count = $db->db_num_rows();
	$db->free_result();
	if ($count > 0) {
?>
<a id="reps"><br></a>
<h2><?php echo _("Public repertorizations from"); ?> <?php echo($req_user); ?></h2>
<p><?php printf(_("Here you can call saved repertorizations that was published by <strong><em>%s</em></strong>."), $req_user); ?></p>
<fieldset>
  <legend class='legend'>
<?php
		echo ("    " . _("Repertorizations from") . " $req_user\n");
?>
  </legend>
  <br>
  <div id='saved_reps'>
    <form id="saved_reps_form" accept-charset="utf-8">
      <div class = 'select'>
<?php
$order_by = "rep_timestamp";
$order_type = "DESC";
if (!empty($_REQUEST['order_by'])) {
	$order_by = $_REQUEST['order_by'];
}
if (!empty($_REQUEST['order_type'])) {
	$order_type = $_REQUEST['order_type'];
}
echo build_saved_reps_table($order_by, $order_type, $req_user, "userinfo.php?user=$req_user&", $num_rows, false);
printf("      <p class='label'>" . ngettext("%d public repertorization", "%d public repertorizations", $num_rows) . "</p>\n", $num_rows);
?>
        <div class="button_area_2">  
          <input class="submit" type="button" onclick='repCall(-1)' value=" <?php echo _("Show repertorization"); ?> ">
          <br>
          <br>
          <input class="submit" type="button" onclick='repContinue(-1)' value=" <?php echo _("Add more symptoms"); ?> ">
        </div>
      </div>
    </form>
  </div>
</fieldset>
<?php
	}
}
if (!$tabbed && !isset($_REQUEST['tab'])) {
	popup();
	include("skins/$skin/footer.php");
}
?>
