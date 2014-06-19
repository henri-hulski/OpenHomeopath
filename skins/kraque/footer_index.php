
        <!-- Begin Footer -->
			<div id="footer">
			<span style="color:black;">
			<?php
echo "\n          <strong>" . _("Members Total:") . "</strong> ".$db->getNumMembers()."<br>\n";
printf("          " . ngettext("There are %d registered member", "There are %d registered members", $db->num_active_users), $db->num_active_users);
printf("          " . ngettext("and %d guest viewing the site.", "and %d guests viewing the site.", $db->num_active_guests), $db->num_active_guests);
if (!empty($db->connection)) {
	$db->close_db();
}
?>
				</span><br>
				<?php echo "© 2009 OpenHomeo.org | <a href='index.php?page=impressum&lang=$lang'>Impressum</a>"; ?>
			</div>
			<!-- End Footer -->
      </div>
    <!-- End Wrapper -->
    <!-- Piwik -->
    <script>
var pkBaseURL = (("https:" == document.location.protocol) ? "https://openhomeo.org/piwik/" : "http://openhomeo.org/piwik/");
document.write(decodeURI("%3Cscript src='" + pkBaseURL + "piwik.js'%3E%3C/script%3E"));
</script><script>
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://openhomeo.org/piwik/piwik.php?idsite=1" style="border:0" alt=""></p></noscript>
<!-- End Piwik Tag -->
  </body>
</html>
