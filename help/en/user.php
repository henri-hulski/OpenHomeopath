<?php

/**
 * help/en/user.php
 *
 * The English user manual for OpenHomeopath.
 *
 * LICENSE: Permission is granted to copy, distribute and/or modify this document
 * under the terms of the GNU Free Documentation License, Version 1.3
 * or any later version published by the Free Software Foundation;
 * with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
 * A copy of the license is included in doc/en/fdl_1.3.php.
 *
 * @category  Manual
 * @package   User
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/fdl.html GNU Free Documentation License v1.3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

chdir("../..");
include_once ("include/classes/login/session.php");
$skin = $session->skin;
$head_title = "User account :: Help :: OpenHomeopath";
$meta_description = "OpenHomeopath Manual, User account";
include("help/layout/$skin/header.php");
?>
<h1>
  OpenHomeopath Manual
</h1>
<h2>
  User account
</h2>
<p>
  In the navigation bar of OpenHomeopath you find the <strong>user menue</strong> beneath <img src="../../<?php echo(USER_ICON);?>" width="16" height="16" alt="Usericon">.
</p>
<p>
  If you aren't logged in you see here only the item <strong><em>"Log in"</em></strong>. From here you get to the <strong>log in form</strong>. Here you can log in with username and password.<br>
  If you forgot the password send the <strong><em>"Forgot Password?"</em></strong> form with your username and we'll send a new password to your registered email.<br>
  For register click on <strong><em>"Not registered? – Sign-Up!"</em></strong> and you come to the registration form.<br>
  After filling in and sending the form you will get an email with your password.
</p>
<div>
  If you're logged in you've the following items in the user menue:
  <ul>
    <li><strong><em>"My account"</em></strong>,</li>
    <li><strong><em>"Settings"</em></strong>,</li>
    <li><strong><em>"Log out"</em></strong>.</li>
  </ul>
  If you've it activated in the setting you see beneath a list with the <strong>currently active users</strong>.<br>
  When clicking on a user you get more informations about him.
</div>
<br>
<div class="content">
  <h2>
    Contents
  </h2>
  <ul>
    <li><a href="#account">My account</a></li>
    <li><a href="#settings">Settings</a></li>
    <li><a href="#logout">Log out</a></li>
  </ul>
</div>
<a name="account" id="account"><br></a>
<h3>
  My account
</h3>
<p>
  In the user account you find under <strong>General Info</strong> details about the user, which you can change in the <a href="#settings">Settings</a>.
</p>
<div>
  In the sortable <strong><em>Saved repertorizations</em></strong> table you can choose a repertorization for:
  <ul>
    <li><strong><em>"Show repertorization"</em></strong> - return to the repertorization result,</li>
    <li><strong><em>"Add more symptoms"</em></strong> - continue with the selected repertorization,</li>
    <li><strong><em>"Delete repertorization"</em></strong> - delete the selected repertorization,</li>
    <li><strong><em>"Change public-state"</em></strong> - publish a repertorization, which allows other users to see it in <strong>your user-info</strong> under the URL: <strong>"openhomeopath/userinfo.php?user=</strong><em>your_username</em><strong>"</strong> (replace <em>your_username</em> with your username).
</li>
  </ul>
</div>
<p>
  Under <strong><em>Personalize the Repertory</em></strong> you can compose your <strong>personal Repertory profile</strong> by selecting <strong>which sources</strong> will be included. This profile is used by the <strong>Repertorization</strong>, if you're logged in.<br>
  The reversed repertorization in the <strong>Materia Medica</strong> also uses the personalized repertory.<br>
  Accordingly, the <strong>Symptom-Info</strong> uses the personalized repertory in the remedy list.
</p>
<p>
  Under <strong><em>Personalize the Materia Medica</em></strong> you can compose your <strong>personal Materia Medica</strong> by selecting <strong>which sources</strong> will be included. This profile will be used by the <strong>Remedy Descriptions</strong> in the Materia Medica if you're logged in.
</p>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="settings" id="settings"><br></a>
<h3>
  Settings
</h3>
<ul>
  <li><strong>Program settings:</strong>
  <ul>
    <li><strong>Select skin:</strong> Here you can select <strong>your default skin</strong>. At the moment we've 2 skins:  <strong><em>"original"</em></strong> from Henri Schumacher and <strong><em>"kraque"</em></strong> without sidebar from Thomas Bochmann.<br>
    <li><strong>Select language:</strong> Here you select the <strong>program language</strong>. At the moment OpenHomeopath is translated in English and German.</li>
    <li><strong>Select your preferred symptom-language:</strong> Your <strong>preferred symptom-language</strong> will be considered, if a symptom exists in different translations. At the moment we have symptom translations in German and English.</li>
    <li><strong>Show active users:</strong> If checked, you get a list with the <strong>currently active users</strong> beneath the user menue. By clicking on a user you get more informations about him.</li>
  </ul></li>
  <li><strong>Change e-mail:</strong> You can change your email and decide if it should be visible for other users. Use only a valid email address.</li>
  <li><strong>Public profile:</strong> Here you can insert <strong>your real name and further personal information</strong>, that will be visible to other users.</li>
  <li><strong>Change password:</strong> Here you can change your password.</li>
</ul>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="logout" id="logout"><br></a>
<h3>
  Log out
</h3>
<p>
  Here you can <strong>log out</strong>.
</p>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span><br>
<?php
include("help/layout/$skin/footer.php");
?>
