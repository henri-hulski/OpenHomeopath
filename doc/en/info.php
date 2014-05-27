<?php

/**
 * doc/en/info.php
 *
 * Information details about OpenHomeopath in English.
 *
 * LICENSE: Permission is granted to copy, distribute and/or modify this document
 * under the terms of the GNU Free Documentation License, Version 1.3
 * or any later version published by the Free Software Foundation;
 * with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
 * A copy of the license is included in doc/en/fdl_1.3.php.
 *
 * @category  Documentation
 * @package   Info
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/fdl.html GNU Free Documentation License v1.3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

chdir("../..");
include_once ("include/classes/login/session.php");
$skin = $session->skin;
$head_title = _("Info") . " :: OpenHomeopath";
$meta_description = "OpenHomeopath Info";
include("help/layout/$skin/header.php");
?>
<h1>
  <?php echo _("Info"); ?>
</h1>
<div class="content">
  <h2>
    <?php echo _("Contents"); ?>
  </h2>
  <ul>
    <li><a href="#version">Program version</a></li>
    <li><a href="#license">License and Copyright</a></li>
    <li><a href="#credits">Credits to</a></li>
    <li><a href="#client">Client requirements</a></li>
    <li><a href="#server">Server requirements</a></li>
    <li><a href="#install">Installation and configuration</a></li>
    <li><a href="#download">Download</a></li>
  </ul>
</div>
<a name="version" id="version"><br></a>
<h2>
  Program version
</h2>
<p>
  This is <strong>OpenHomeopath Version 1.0</strong>.<br>
  After a <strong>fundamental revision of the entire code and database structure</strong> I'm glad to publish the first stable release of OpenHomeopath after more than 7 years work.<br>
  If you still find bugs or if you've a question please <a title="Contact to the author" href="mailto:henri.hulski@gazeta.pl?subject=OpenHomeopath">contact me</a>.
</p>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="license" id="license"><br></a> 
<h2>
 License and Copyright
</h2>
<p>
  Copyright &copy; 2007-2014 by Henri Schumacher.
</p>
<p>
  OpenHomeopath is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as
  published by the Free Software Foundation, either version 3 of the
  License, or (at your option) any later version.
</p>
<p>
  OpenHomeopath is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Affero General Public License for more details.
</p>
<p>
  <a href="../en/agpl3.php">Here</a> you find a copy of the GNU Affero General Public License.
</p>
<p>
  The manuals and documentation of OpenHomeopath is distributed under the <a href='../en/fdl_1.3.php'>GNU Free Documentation License Version 1.3 (FDLv1.3)</a>.
</p>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="credits" id="credits"><br></a>
<h2>
  Credits to
</h2>
<ul>
  <li><strong>Thomas Bochmann</strong>: Thomas has founded and maintains the homeopathy portal <a href='http://openhomeo.org'>OpenHomeo.org</a> on which OpenHomeopath runs.<br>
  Thomas also contributes much to OpenHomeopath by suggestions, proposals and constructive criticism.<br>
  He also provides on OpenHomeo.org some scripst, which assist OpenHomeopath.
  </li>
  <li><strong>Bernd Zille</strong>: His German homeopathic program for Windows <a href="http://www.zille-software.de">BZ-Homöopathie</a> inspired me to write OpenHomeopath.<br>
  Special credits for <strong>granting us the license</strong> to use his repertory with OpenHomeopath for uncommercially. See <a href="../../source.php?src=BZH">here</a> for details.</li>
  <li><strong>Eugenio Tacchini</strong>: His DataBase Interfaces Kreator <a href="http://www.dadabik.org">DaDaBIK</a> Version 4.2 provides the base of the data maintenance tool.<br>
  Copyright &copy; 2001-2007 by Eugenio Tacchini. The program is published under the <a href="../en/gpl3.php">GNU General Public License</a>.
  </li>
  <li><strong>Alexey Ozerov</strong>: The database installation is derivated freom his script <a href="http://www.ozerov.de/bigdump.php">BigDump ver. 0.34b</a> from 04.09.2011.<br>
  Copyright &copy; 2003-2011 by Alexey Ozerov. Published under <a href="../en/gpl3.php">GPL License</a>.
  </li>
  <li><strong>Jpmaster77</strong>: the login system is based on his free script <a href="http://www.evolt.org/PHP-Login-System-with-Admin-Features">Login System v.2.0</a> from 26.08.2004.
  </li>
  <li><strong>Phorum Development Team</strong>: The Homeophorum contains the software <a href="http://phorum.org/">Phorum</a> Version 5.1.25 from 14.03.2007 developed by the Phorum Development Team under the <a href='../../include/phorum/docs/license.txt'>Phorum License 2.0</a>.
  </li>
  <li><strong>Patrick Fitzgerald</strong>: The tabs are based on the javascript <a href="http://www.barelyfitz.com/projects/tabber/">Tabber</a> from Patrick Fitzgerald.<br>
  Copyright &copy; 2006 by Patrick Fitzgerald.<br>
  Distributed under the <a href='http://www.opensource.org/licenses/mit-license.php'>MIT license</a>, also called X11 license.
  </li>
</ul>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="client" id="client"><br></a>
<h2>
  Client requirements
</h2>
<p>For using OpenHomeopath <strong>Javascript and Cookies (at least from the same site)</strong> have to be enabled in the browser.</p>
<strong>OpenHomeopath</strong> is optimized for:
<ul>
<li><strong>Screen resolution:</strong> 1280x1024 or more</li>
<li><strong>Browser:</strong> Chromium/Chrome, Opera and Firefox</li>
<li><strong>Operating system:</strong> tested under Linux, but should work also on other systems.</li>
</ul>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="server" id="server"><br></a>
<h2>
  Server requirements
</h2>
<ul>
  <li><strong>MySQL</strong> from version 5.1</li>
  <li><strong>PHP</strong> from PHP 5.3</li>
  <li><strong>UTF-8</strong> support.</li>
</ul>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="install" id="install"><br></a>
<h2>
  Installation and configuration
</h2>
<ol>
  <li>Create a MySQL database and a user who has all rights for the database.</li>
  <li>Edit the file "openhomeopath/include/classes/db/config_db.php" by providing the name of the MySQL database, the username and his password. Also you can choose the database driver: "mysqli" or "mysql". Protect the file "config_db.php" on the server against unauthorized read and write access (e.g. with chmod 600), because the password is saved in plain text. Please note, that the server still needs read access.</li>
  <li>Upload the folder "openhomeopath" to your webserver.</li>
  <li>Call "<em>http://your.webaddress.com/</em>openhomeopath/install/<strong>install_db.php</strong>" on your server with the browser and import the data to the database.</li>
  <li>Now you can call "<em>http://your.webaddress.com/</em>openhomeopath/<strong>index.php</strong>" in your browser and here we are.</li>
  <li>The <strong>default user</strong> with administration rights is <strong><em>"admin"</em></strong>. The <strong>default password:
  <em>"admin"</em></strong>. You should change the password after.</li>
</ol>
<a name="download" id="download"><br></a>
<h2>
  Download
</h2>
<div>
  Here you can download <strong>OpenHomeopath</strong> as a compressed tarball for local installation:
  <ul>
    <li><a href="../../../openhomeopath_old/download/openhomeopath_0.9_beta.tar.gz">openhomeopath_0.9_beta.tar.gz</a> (Warning! This is still the unstable beta-version. Stable comes soon.)</li>
  </ul>
</div>

<?php
include("help/layout/$skin/footer.php");
?>
