</div>
	<div id="footer">
		<table cellpadding="0" cellspacing="0" width="100%" height="35">
		<tr valign="middle"><td class="footerCopyright" align="left">
			<p><strong><a title="gioco online gratis" href="http://www.rigorix.com/gioco_online_gratis.php">Rigorix</a></strong> &egrave; un
	          marchio di propriet&agrave; <a href="http://www.internetting.it">Internetting</a>
	          (P. IVA 03465230278) e <a href="http://www.consulenzasportiva.it">ConsulenzaSportiva</a></p>
		</td><td class="footerDisclaimer" align="right">
			<p><a href="#riconoscimenti">Riconoscimenti</a> - <a target="_blank" href="http://partners.sprintrade.com/index.html?super_affiliate_code=CD4337">Affiliazione</a> - <a href="#regolamento" title="Regolamento">Regolamento</a> - <a href="#partners" title="Siti partner">Siti partners</p>
		</td></tr>
		</table>
	</div>

	<? if($_SERVER['DOCUMENT_ROOT'] == '/home2/rigorix/public_html') { ?>
	<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	var pageTracker = _gat._getTracker("UA-784125-12");
	pageTracker._initData();
	pageTracker._trackPageview();
	</script>
	<?php render_banner ("Right1"); ?>
	<? } ?>
	<script>jQuery(".rx-loading-panel-progress").progressbar({ value: 70 });</script>
	<div class="clr"></div>
</div>

<map name="LogoMap" id="LogoMap">
	<area shape="poly" title="Vai alla homepage" coords="23,34,25,86,204,87,214,107,245,107,258,63,288,42,284,14,242,3,215,15,208,30" href="index.php" />
</map>
<map name="Map" id="Map">
  <area shape="rect" coords="362,205,582,243" href="registrazione.php" />
</map>
<script>jQuery(".rx-loading-panel-progress").progressbar({ value: 80 });</script>
<script type="text/javascript">
  var uvOptions = {};
  (function() {
    var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
    uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/nusrgtA8EpEMNMta4rfqg.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
  })();
</script>

<?php
if ( in_array ($user->obj->username, $core->settings["ADMINS"]) ) { ?>
<div id="foot-debug">
	<div class="debug-buttons">
		<button name="reset-session-log">CLEAR LOG</button>
		<button name="reset-session">SESSION RESET</button>
	</div>
	<div class="debug-log">
		<?php echo $_SESSION['rigorix']['log']; ?>
	</div>
</div>
<?php } ?>

<!-- script src="/bootstrap/js/bootstrap.min.js" type="text/javascript"></script -->

</div>
</body>
</html>