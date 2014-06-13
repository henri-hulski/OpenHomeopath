<?php

/**
 * help/en/introduction.php
 *
 * The English indroduction to OpenHomeopath.
 *
 * LICENSE: Permission is granted to copy, distribute and/or modify this document
 * under the terms of the GNU Free Documentation License, Version 1.3
 * or any later version published by the Free Software Foundation;
 * with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
 * A copy of the license is included in doc/en/fdl_1.3.php.
 *
 * @category  Manual
 * @package   Introduction
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/fdl.html GNU Free Documentation License v1.3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

chdir("../..");
include_once ("include/classes/login/session.php");
$skin = $session->skin;
$head_title = "Introduction :: Help :: OpenHomeopath";
$meta_description = "OpenHomeopath Manual, Introduction";
include("help/layout/$skin/header.php");
?>
<h1>
  OpenHomeopath Manual
</h1>
<h2>
  Introduction
</h2>
<p>
  <strong>Homeopathy</strong> is a healing method, that was foundet at the beginning of the 19th century by the physician <strong>Dr. Samuel Hahnemann</strong> and that is based on the principle <strong><em>"Similia similibus curentur"</em> - let like be cured by like</strong>, according to which a substance that causes the symptoms of a disease in healthy people will <strong>cure similar symptoms</strong> in sick people.
</p>
<p>
  The <strong>basis</strong> for the homeopathic treatment was created by proving thousands of remedies (from plant, animal and mineral origin) on healthy people and describing them in <strong><em>"Materia Medica's"</em></strong>.<br>
  In order to make it more easy to find the right remedy (<strong><em>"Similimum"</em></strong>), the symptoms with the related remedies were <strong>combined to rubrics</strong> and published in a structurized form as <strong><em>"Repertories"</em></strong>.<br>
  In a rubric each remedy got a <strong>grade</strong> from 1-3, 1-4 or 1-5 (according to the repertory), depending on how strongly the corresponding symptom is pronounced.
</p>
<p>
  The homeopath is collecting the <strong>symptoms</strong> including the modalities by a detailed interview of the patient (<strong><em>"Anamnesis"</em></strong>).<br>
  Based on the <strong>symptoms</strong> we can limit the choice of the remedies by <strong>repertorization</strong>.<br>
  We should pay special attention to symptoms, that are <strong>atypical</strong> for the disease course and <strong>important</strong> for the patient, which may be <strong>significant</strong> for the choice of the remedy.<br>
  To find the right remedy from the repertorized ones you should consult <strong>detailed descriptions of the remedies</strong> and select the one, which is the most relevant to the patient in <strong>constitution and symptoms</strong>.
</p>
<?php
include("help/layout/$skin/footer.php");
?>
