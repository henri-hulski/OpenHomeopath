<?php

/**
 * help/en/preamble.php
 *
 * The preamble to OpenHomeopath in English.
 *
 * LICENSE: Permission is granted to copy, distribute and/or modify this document
 * under the terms of the GNU Free Documentation License, Version 1.3
 * or any later version published by the Free Software Foundation;
 * with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
 * A copy of the license is included in doc/en/fdl_1.3.php.
 *
 * @category  Manual
 * @package   Preamble
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/fdl.html GNU Free Documentation License v1.3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

chdir("../..");
include_once ("include/classes/login/session.php");
$skin = $session->skin;
$head_title = "Preambel :: Help :: OpenHomeopath";
$meta_description = "OpenHomeopath Manual, Preambel";
include("help/layout/$skin/header.php");
?>
<h1>
  OpenHomeopath Manual
</h1>
<h2>
  Preambel
</h2>
<p>The most important parts of OpenHomeopath are <strong>well organized in Tabs</strong> on the homepage.</p>
<p>The main purpose of <strong>OpenHomeopath</strong> is to find the corresponding homeopathic remedies for a selection of symptoms. This process we call <a href="manual.php#repertorization"><strong>Repertorization</strong></a>.<br>
OpenHomeopath contains at the moment the repertories of Kent, Bogner, Boenninghausen and the Repertorium publicum of Vladimir Polony in English and the repertories of Kent and from BZ-Homöopathie in German.<br>
If you are logged in, you can <strong>save the repertory results</strong> together with patient-ID, prescription, case note and date.<br>
The saved repertorizations can be managed from your user account. You can review them, continue repertorizing, publish them to other users or delete them.<br>
<strong>You can also save or print the repertorization result as PDF.</strong> The PDF-file contains the 20 most important remedies and is prepared for printing in A4-format.</p>
<p>In the <a href="<?php echo($materia_url);?>"><strong>Materia Medica</strong></a> you can retrieve all corresponding symptoms and rubrics for the given remedy together with the grades. In the Materia Medica you can find also details about the remedy like related and incompatible remedies, antidotes, notes about preparation, origin and synonyms, a general description and the leading symptoms.</p>
<p>The Repertory and the Materia Medica can be customized in the user account by choosing the sources to be included.</p>
<p>OpenHomeopath provides an extendable database which contains the symptoms, remedies and symptom-remedy-relations. You can use <a href="datadmin.php">Datadmin</a> and the <a href='expresstool.php'>Express-Tool</a> for editing and extending the database. With the <a href='expresstool.php'>Express-Tool</a> you have a straight forward tool for copying repertories from books to OpenHomeopath.</p>
<p><a href="../../doc/<?php echo $lang; ?>/info.php"><strong>OpenHomeopath is opensource</strong></a> and distributed under the terms of the <a href="../../doc/en/agpl3.php">GNU Affero General Public License (AGPLv3)</a>.</p>
<p><strong>The financial concept of OpenHomeopath</strong> is based on reaching a monthly donation goal of <?php echo(DONATION_GOAL_MONTHLY);?> €/$</strong> by collective effort of all users. Until the monthly donation goal is reached, the functionality of OpenHomeopath is restricted for non-donators.") . " " . _("When the monthly donating goal will be reached, <strong>OpenHomeopath will be fully usable for everybody</strong> until the 10th or when reaching 50% of the donating goal until the 20th of the next month.</p>
<p>I hope OpenHomeopath helps you to heal.</p>
<p><a href="mailto:henri.hulski@gazeta.pl?subject=OpenHomeopath">Proposals and improvements</a> are very welcome.</p>
<?php
include("help/layout/$skin/footer.php");
?>
