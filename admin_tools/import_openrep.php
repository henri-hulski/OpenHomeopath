<?php
chdir("..");
include_once ("include/classes/login/session.php");
if (!$session->isAdmin()) {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = "login.php?url=admin%import_openrep.php";
	header("Content-Type: text/html;charset=utf-8");
	header("Location: http://$host$uri/$extra");
	die();
} else {
	$skin = $session->skin;
	include("skins/$skin/header.php");
?>
<h1>
   <?php echo _("Import OpenRep-Repertories"); ?>
</h1>
<?php
	if (empty($_REQUEST['upload']) && empty($_REQUEST['import']) && empty($_REQUEST['request']) && empty($_REQUEST['insert'])) {
?>
  <p>
    <strong><?php echo _("Warning!"); ?></strong> <?php echo _("Main rubrics are recognized to the first comma. The main rubric itself may not contain a comma."); ?><br>
    <?php printf(_("If a main rubric contains a comma (e.g. 'Fever, pathological types' in bogboen) you have to change it in the source files %s and %s or %s."), '<em>*_sym</em>', '<em>*_tree</em>', '<em>*_mainsym</em>'); ?>
  </p>
  <p>
    <?php printf(_("If %s exists, %s will not be used."), '<em>*_tree</em>', '<em>*_mainsym</em>'); ?>
  </p>
  <form enctype="multipart/form-data" method="POST" name="upload_files" action="import_openrep.php">
    <table border="0" summary="layout">
      <tr>
        <td colspan='2' width="60%">
        </td>
        <td width="5%">
        </td>
        <td width="30%" align="center">
          <label for="sources"><span class="label"><?php echo _("Select source"); ?></span></label>
        </td>
        <td width="5%">
        </td>
      </tr>
      <tr>
        <td colspan='2' align="center">
          <?php echo "<a href='./datadmin.php?function=show_insert_form&amp;table_name=sources'>" . _("Add the source-entry to database</a> and select the source:"); ?><br><br>
        </td>
        <td></td>
        <td align="center">
          <select class="drop-down3" name="sources" id="sources" size="1" onchange='javascript:document.upload_files.submit()'>
<?php
		if (!empty($_REQUEST['sources'])) {
			$current_src_id = $_REQUEST['sources'];
			$query = "SELECT src_title FROM sources WHERE src_id = '$current_src_id'";
			$db->send_query($query);
			list($current_src_title) = $db->db_fetch_row();
			$db->free_result();
			echo ("          <option value='$current_src_id' selected='selected'>$current_src_title ($current_src_id)</option>\n");
		} else {
			echo ("          <option value=''></option>\n");
		}
		$query = "SELECT src_id, src_title FROM sources WHERE primary_src = 1 ORDER BY src_title";
		$db->send_query($query);
		while(list($src_id, $src_title) = $db->db_fetch_row()) {
			echo ("          <option value='$src_id'>$src_title ($src_id)</option>\n");
		}
		$db->free_result();
?>
          </select><br><br>
        </td>
        <td></td>
      </tr>
      <tr>
        <td colspan='2' align="center">
          <?php echo _("Choose files to import:"); ?>
        </td>
        <td colspan='3'></td>
      </tr>
      <tr>
        <td width="20%"></td>
        <td>
          <?php echo _("Repertory description"); ?> (<em>*.rd</em>):
        </td>
        <td></td>
        <td>
          <input name="importfile[]" type="file">
        </td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td>
          <?php echo _("Symptoms"); ?> (<em>*_sym</em>):
        </td>
        <td></td>
        <td>
          <input name="importfile[]" type="file">
        </td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td>
          <?php echo _("Remedy"); ?> (<em>*_rem</em>):
        </td>
        <td></td>
        <td>
          <input name="importfile[]" type="file">
        </td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td>
          <?php echo _("Symptom-remedy-relations"); ?> (<em>*_remsym</em>):
        </td>
        <td></td>
        <td>
          <input name="importfile[]" type="file">
        </td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td>
          <?php echo _("Treestructure"); ?> (<em>*_tree</em>):
        </td>
        <td></td>
        <td>
          <input name="importfile[]" type="file">
        </td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td>
          <?php echo _("Sources"); ?> (<em>*_src</em>):
        </td>
        <td></td>
        <td>
          <input name="importfile[]" type="file">
        </td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td>
          <?php echo _("Symptomreferences"); ?> (<em>*_ref</em>):
        </td>
        <td></td>
        <td>
          <input name="importfile[]" type="file">
        </td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td>
          <?php echo _("Main rubrics"); ?> (<em>*_mainsym</em>) - <span style="font-size:8pt;"><strong><?php echo _("Only if <em>*_tree</em> doesn't exist!"); ?></strong></span>:
        </td>
        <td></td>
        <td>
          <input name="importfile[]" type="file">
        </td>
        <td></td>
      </tr>
    </table>
    <br>
    <input type='hidden' name='upload' id='upload' value='0'>
    <div style="text-align: center;">
      <input type='submit' onclick='javascript:document.getElementById("upload").value=1' value=' <?php echo _("Upload files"); ?> '>
    </div>
  </form>
<?php
		include ("include/functions/import.php");
		$imported_reps_ar = get_imported_reps();
		if (!empty($imported_reps_ar)) {
?>
  <br>
  <h2>
    <?php echo _("Insert imported repertories in the darabase"); ?>
  </h2>
  <p>
    <strong><?php echo _("Warning!"); ?></strong> <?php echo _("Insert only repertories, that wasn't inserted before in the OpenHomeopath-database."); ?>
  </p>
  <p>
    <?php echo _("These repertories were imported and can be inserted in the database:"); ?>
  </p>
  <ul>
<?php
			foreach ($imported_reps_ar as $imported_rep_id) {
				$query = "SELECT src_title FROM sources WHERE src_id = '$imported_rep_id'";
				$db->send_query($query);
				list($imported_rep_title) = $db->db_fetch_row();
				$db->free_result();
				echo "    <li>$imported_rep_title - <a href='import_openrep.php?insert=1&rep=$imported_rep_id'>$imported_rep_id " . _("insert") . "</a></li>\n";
			}
?>
  </ul>
<?php
		}
	} elseif (!empty($_REQUEST['upload']) && $_REQUEST['upload'] == 1) {
		if (empty($_REQUEST['sources'])) {
			echo _("No source was selected!");
		} else {
			$uploaddir = UPLOAD_DIR;
			for ($i = 0; $i < 8; $i++) {
				if ($_FILES['importfile']['error'][$i] == 0) {
					$uploadfile = $uploaddir. basename($_FILES['importfile']['name'][$i]);
					if (!move_uploaded_file($_FILES['importfile']['tmp_name'][$i], $uploadfile)) {
						echo _("Problem with the file upload!")."\n";
					}
					if (substr(basename($_FILES['importfile']['name'][$i]), -3) == ".rd") {
						if (file_exists($uploadfile)) {
							$rd = array();
							$data = "<rd>" . implode("", file($uploadfile, FILE_IGNORE_NEW_LINES)) . "</rd>";
							$parser = xml_parser_create();
							xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
							xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
							xml_parse_into_struct($parser, $data, $values);
							xml_parser_free($parser);
							foreach ($values as $value) {
								if ($value['level'] == 2) {
									$tag = $value['tag'];
									if (substr($tag, 0, 10) == "repertory_") {
										$rd['version'] = 3;
										$tag = substr($tag, 10);
									} else {
										$rd['version'] = 1;
									}
									$rd[$tag] = $value['value'];
								}
							}
						} else {
							printf(_("Couldn't open %s."), $uploadfile);
							exit();
						}
					}
				}
			}
			$current_src_id = $_REQUEST['sources'];
			$query = "SELECT src_title FROM sources WHERE src_id = '$current_src_id'";
			$db->send_query($query);
			list($current_src_title) = $db->db_fetch_row();
			$db->free_result();
			$version = $rd['version'];
			$success_icon = "<img src='./skins/original/img/success.png' width='16' height='16'> ";
			$no_success_icon = "<img src='./skins/original/img/delete.png' width='16' height='16'> ";
			$sym_icon = $no_success_icon;
			$symptom_file = $rd['symptom_file'];
			if (is_readable($uploaddir.$symptom_file)) {
				$sym_icon = $success_icon;
			}
			$rem_icon = $no_success_icon;
			$remedy_file = $rd['remedy_file'];
			if (is_readable($uploaddir.$remedy_file)) {
				$rem_icon = $success_icon;
			}
			$remsym_icon = $no_success_icon;
			$remsymptom_file = $rd['remsymptom_file'];
			if (is_readable($uploaddir.$remsymptom_file)) {
				$remsym_icon = $success_icon;
			}
			$tree_icon = $no_success_icon;
			$symptomtree_file = "";
			if (isset($rd['symptomtree_file'])) {
				$symptomtree_file = $rd['symptomtree_file'];
				if (is_readable($uploaddir.$symptomtree_file)) {
					$tree_icon = $success_icon;
				}
			}
			$src_icon = $no_success_icon;
			$sources_file = "";
			if (isset($rd['sources_file'])) {
				$sources_file = $rd['sources_file'];
				if (is_readable($uploaddir.$sources_file)) {
					$src_icon = $success_icon;
				}
			}
			$ref_icon = $no_success_icon;
			$references_file = "";
			if (isset($rd['references_file'])) {
				$references_file = $rd['references_file'];
				if (is_readable($uploaddir.$references_file)) {
					$ref_icon = $success_icon;
				}
			}
			$mainsym_icon = $no_success_icon;
			$mainsymptoms_file = "";
			if (isset($rd['mainsymptoms_file'])) {
				$mainsymptoms_file = $rd['mainsymptoms_file'];
				if (is_readable($uploaddir.$mainsymptoms_file)) {
					$mainsym_icon = $success_icon;
				}
			}
?>
<p><?php echo _("Please check the repertory before importing:"); ?></p>
<ul style='list-style-type:none'>
  <li><?php echo _("Source:") . " <em>$current_src_title ($current_src_id)</em>";?></li>
  <li><?php echo _("OpenRep-Version:") . " <em>$version.0</em>";?></li>
  <li><?php echo _("Autor:") . " <em>" . $rd['author'] . "</em>";?></li>
  <li><?php echo _("Name:") . " <em>" . $rd['name'] . "</em>";?></li>
  <li><?php echo _("Remedy shortcut:") . " <em>" . $rd['name_short_cut'] . "</em>";?></li>
  <li><?php echo _("max. grade:") . " <em>" . $rd['maximum_grade'] . "</em>";?></li>
  <li><?php echo $sym_icon . _("Symptomfile:"). " <em>$symptom_file</em>";?></li>
  <li><?php echo $rem_icon . _("Remedyfile:") . " <em>$remedy_file</em>";?></li>
  <li><?php echo $remsym_icon . _("symptom-remedy-relations-file") . " <em>$remsymptom_file</em>";?></li>
  <li><?php echo $tree_icon . _("Symptomtreefile:") . " <em>$symptomtree_file</em>";?></li>
  <li><?php echo $src_icon . _("Sourcesfile:") . " <em>$sources_file</em>";?></li>
  <li><?php echo $ref_icon . _("Symptomreferencesfile:") . " <em>$references_file</em>";?></li>
  <li><?php echo $mainsym_icon . _("Main-rubrics-file:") . " <em>$mainsymptoms_file</em>";?></li>
</ul>
<form method="POST" name="import" action="import_openrep.php">
  <input type='hidden' name='rep' value='<?php echo strtolower($current_src_id);?>'>
<?php
			if ($version == 3) {
?>
    <input type='hidden' name='version' value='<?php echo $version;?>'>
<?php
			}
?>
  <input type='hidden' name='sym' value='<?php echo $symptom_file;?>'>
  <input type='hidden' name='ref' value='<?php echo $references_file;?>'>
  <input type='hidden' name='rem' value='<?php echo $remedy_file;?>'>
  <input type='hidden' name='remsym' value='<?php echo $remsymptom_file;?>'>
  <input type='hidden' name='src' value='<?php echo $sources_file;?>'>
  <input type='hidden' name='tree' value='<?php echo $symptomtree_file;?>'>
  <input type='hidden' name='mainsym' value='<?php echo $mainsymptoms_file;?>'>
  <input type='hidden' name='import' value='1'>
  <div style="text-align: center;">
    <input type='submit' value=' <?php echo _("Import OpenRep source"); ?> '>
  </div>
</form>
<?php
		}
	} elseif (!empty($_REQUEST['import']) || !empty($_REQUEST['request'])) {
		include ("include/functions/import.php");
		$rep = $_REQUEST['rep'];
		if (!empty($_REQUEST['import'])) {
			create_import_tables();
			$mainsym_imported = openrep_to_mysql();
			$rem_missed = get_rem_ids();
			$src_missed = get_src_ids();
			$mainsym_missed = extract_mainsym($mainsym_imported);
			if ($src_missed != 0 || $rem_missed != 0 || $mainsym_missed != 0) {
?>
<form method="POST" name="import" action="import_openrep.php">
  <input type='hidden' name='rep' value='<?php echo $rep;?>'>
  <input type='hidden' name='request' value='1'>
   <input type='hidden' name='mainsym_imported' value='<?php echo $mainsym_imported;?>'>
 <div style="text-align: center;">
<?php
				if ($src_missed != 0) {
?>
  <p><?php printf(ngettext("One reference source couldn't be identified. Please select the corresponding  reference source. If necessary add a new one.", "%d reference sources couldn't be identified. Please select the corresponding  reference sources. If necessary add new ones.", $src_missed), $src_missed); ?></p>
  <input type='hidden' name='src_missed' value='<?php echo $src_missed;?>'>
  <ul style='list-style-type:none'>
<?php
					$i = 1;
					$query = "SELECT src_id, src_title, src_author FROM `import_".$rep."__src` WHERE src_id = 0 ORDER BY src_author, src_title";
					$result = $db->send_query($query);
					while (list($src_id, $src_title, $src_author) = $db->db_fetch_row($result)) {
?>
    <li>
      <input type='hidden' name='src_<?php echo $i;?>' value='<?php echo $src_id;?>'>
      <?php echo "<strong>$src_author</strong>: <em>$src_title</em>";?>:
          <select name="source_<?php echo $i;?>" size="1">
            <option value=''></option>
<?php
						$i++;
						$src_author = $db->escape_string(trim (preg_replace('/\s\s+/u', ' ', $src_author))); // entferne überzähligen whitespace
						$src_author_ar = explode(' ', $src_author);
						unset($author_ar);
						foreach ($src_author_ar as $author) {
							if (strlen($author) > 2) {
								$author_ar[] = $author;
							}
						}
						$author_str = implode ("%' OR src_author LIKE '%", $author_ar);
						$src_title = $db->escape_string(trim (preg_replace('/\s\s+/u', ' ', $src_title))); // entferne überzähligen whitespace
						$src_title_ar = explode(' ', $src_title);
						unset($title_ar);
						foreach ($src_title_ar as $title) {
							if (strlen($title) > 3) {
								$title_ar[] = $title;
							}
						}
						$title_str = implode ("%' OR src_title LIKE '%", $title_ar);
						$where = "(src_author LIKE '%$author_str%') AND (src_title LIKE '%$title_str%')";
						$query = "SELECT src_id, src_title, src_author FROM sources WHERE $where ORDER BY src_author, src_title";
						$db->send_query($query);
						if ($db->db_num_rows() == 0) {
							$db->free_result();
							$where = "src_author LIKE '%$author_str%' OR src_title LIKE '%$title_str%'";
							$query = "SELECT src_id, src_title, src_author FROM sources WHERE $where ORDER BY src_author, src_title";
							$db->send_query($query);
						}
						if ($db->db_num_rows() == 0) {
							$db->free_result();
							$query = "SELECT src_id, src_title, src_author FROM sources ORDER BY src_author, src_title";
							$db->send_query($query);
						}
						while(list($src_id, $src_title, $src_author) = $db->db_fetch_row()) {
?>
            <option value='<?php echo $src_id;?>'><?php echo "$src_author: $src_title ($src_id)";?></option>
<?php
						}
						$db->free_result();
?>
          </select>
    </li>
<?php
					}
					$db->free_result($result);
?>
  </ul>
<?php
				}
				if ($rem_missed != 0) {
?>
  <p><?php printf(ngettext("The remedy couldn't be identified. Please select the corresponding remedy. If necessary add a new one.", "%d remedies couldn't be identified. Please select the corresponding remedies. If necessary add new ones.", $rem_missed), $rem_missed); ?></p>
  <input type='hidden' name='rem_missed' value='<?php echo $rem_missed;?>'>
  <ul style='list-style-type:none'>
<?php
					$i = 1;
					$query = "SELECT rem_id, rem_short, rem_long FROM `import_".$rep."__rem` WHERE remedy_id = 0 ORDER BY rem_long";
					$result = $db->send_query($query);
					while (list($imported_rem_id, $imported_rem_short, $imported_rem_long) = $db->db_fetch_row($result)) {
?>
    <li>
      <input type='hidden' name='rem_<?php echo $i;?>' value='<?php echo $imported_rem_id;?>'>
      <strong><?php echo "$imported_rem_long ($imported_rem_short)";?></strong>:
          <select name="remedy_<?php echo $i;?>" size="1">
            <option value=''></option>
<?php
						$i++;
						$query = "SELECT rem_id, rem_short, rem_name FROM remedies ORDER BY rem_name";
						$db->send_query($query);
						while(list($rem_id, $rem_short, $rem_name) = $db->db_fetch_row()) {
?>
            <option value='<?php echo $rem_id;?>'><?php echo "$rem_name ($rem_short)";?></option>
<?php
						}
						$db->free_result();
?>
          </select>
    </li>
<?php
					}
					$db->free_result($result);
?>
  </ul>
<?php
				}
				if ($mainsym_missed != 0) {
?>
  <p><?php printf(ngettext("The main rubric couldn't be identified. Please select the corresponding main rubric. If necessary add a new one.", "%d main rubrics couldn't be identified. Please select the corresponding main rubrics. If necessary add new ones.", $mainsym_missed), $mainsym_missed); ?></p>
  <input type='hidden' name='mainsym_missed' value='<?php echo $mainsym_missed;?>'>
  <ul style='list-style-type:none'>
<?php
					$i = 1;
					$query = "SELECT mainsym_name FROM `import_".$rep."__mainsym` WHERE rubric_id = 0";
					$result = $db->send_query($query);
					while (list($mainsym_name) = $db->db_fetch_row($result)) {
?>
    <li>
      <input type='hidden' name='mainsym_<?php echo $i;?>' value='<?php echo $mainsym_name;?>'>
      <strong><?php echo $mainsym_name;?></strong>:
          <select name="rubric_<?php echo $i;?>" size="1">
            <option value=''></option>
<?php
						$i++;
						$query = "SELECT lang_id FROM sources WHERE src_id LIKE '$rep'";
						$db->send_query($query);
						list($lang) = $db->db_fetch_row();
						$db->free_result();
						$query = "SELECT rubric_id, rubric_$lang FROM main_rubrics ORDER BY rubric_$lang";
						$db->send_query($query);
						while(list($rubric_id, $rubric_name) = $db->db_fetch_row()) {
?>
            <option value='<?php echo $rubric_id;?>'><?php echo $rubric_name;?></option>
<?php
						}
						$db->free_result();
?>
          </select>
    </li>
<?php
					}
					$db->free_result($result);
?>
  </ul>
<?php
				}
?>
    <br>
    <input type='submit' value=' <?php echo _("Send"); ?> '>
  </div>
</form>
<?php
				include("skins/$skin/footer.php");
				exit;
			}
		} else {
			if (!empty($_REQUEST['src_missed'])) {
				update_src();
			}
			if (!empty($_REQUEST['rem_missed'])) {
				update_rem();
			}
			if (!empty($_REQUEST['mainsym_missed'])) {
				update_mainsym();
			}
			$mainsym_imported = $_REQUEST['mainsym_imported'];
		}
		import_mainsym();
		if ($mainsym_imported == 0) {
			build_symptom_tree();
		}
		get_sym_ids();
		printf("<p>" . _("The import of  <strong>%s</strong> in MySQL-import-tables is finalized.") . "<br>\n", $rep);
		echo _("Please check the tables:") . " <strong>import_" . $rep . "__*</strong></p>\n";
		echo "<p>" . _("Now you can insert the new repertory in the OpenHomeopath-database.") . "</p>\n";
?>
<form method="POST" action="import_openrep.php">
  <input type='hidden' name='insert' value='1'>
  <input type='hidden' name='rep' value='<?php echo $rep;?>'>
  <div style="text-align: center;">
    <input type='submit' value=' <?php echo "$rep " . _("Insert in OpenHomeopath"); ?> '>
  </div>
</form>
<?php
	} elseif (!empty($_REQUEST['insert'])) {
		include ("include/functions/import.php");
		$rep = $_REQUEST['rep'];
		insert_ref();
		insert_sym();
		insert_remsym();
		printf("<p>" . _("<strong>Congratulations!</strong> It seems, that we got it. The import of <strong>%s</strong> in OpenHomeopath is finalized.") . "</p>\n", $rep);
	}
	include("skins/$skin/footer.php");
}
?>
