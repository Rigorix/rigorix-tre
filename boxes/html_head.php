<?php global $context; ?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Rigorix - Gioco online a premi</title>
	<meta name="description" content="Gioca gratis e vinci bellissimi premi ai rigori. Rigorix e' uno dei piu' divertenti giochi online gratuiti in flash in cui si vincono veri premi. Gioca online!">
	<meta name="keywords" content="giochi on line gratuiti a premi">
	<style type="text/css">
		@import "/app/assets/dist/dist.common.css";
		@import "/css/common.css";
		@import "/css/ui/jquery-ui-1.8.1.custom.css";
		@import "/css/colorPicker.css";
		<?php if ( isset ($context) && $context == 'dialog' ) { ?>
		.dialog-default-container { padding: 0 !important; }
		<?php } ?>
		/*@import "/css/developing.css";*/
	</style>
	<script>
		var root = '';
		var dev = <?php echo ( $_SESSION['rigorix']['settings']['DEVELOPER'] == true ) ? "true" : "false"; ?>;
		<?php if ( isset ($context) && $context == 'dialog' ) { ?>
		var DialogWindow = this;
		<?php } ?>

		var User = <? echo ( is_object($user->obj) ? $utility->print_json ( $user->obj ) : 'false' ); ?>;
    var RigorixEnv = <?php echo json_encode($env); ?>;
	</script>

	<!-- script src="/app/assets/vendor/jquery-1.10.2.min.js" type="text/javascript"></script>

	<script src="/app/assets/vendor/angular.min.js" type="text/javascript"></script>
	<script src="/app/assets/vendor/angular-route.min.js" type="text/javascript"></script>
  <script src="/app/assets/vendor/angular-resource.min.js" type="text/javascript"></script>
  <script src="/app/config.js" type="text/javascript"></script>
  <script src="/app/app.js" type="text/javascript"></script>
  <script src="/app/services/services.js" type="text/javascript"></script>
  <script src="/app/services/auth.js" type="text/javascript"></script>
  <script src="/app/controllers/Main.js" type="text/javascript"></script>
  <script src="/app/controllers/Sidebar.js" type="text/javascript"></script>
  <script src="/app/controllers/Header.js" type="text/javascript"></script>
  <script src="/app/controllers/Home.js" type="text/javascript"></script -->

  <script src="/app/assets/dist/dist.js" type="text/javascript"></script>

  <script src="/js/jquery-ui-1.8.1.custom.min.js" type="text/javascript"></script>
	<script src="/js/jquery-ui-1.8.1.custom.min.js" type="text/javascript"></script>
	<script src="/js/j_core.js" type="text/javascript"></script>
	<script src="/js/j_activity.js" type="text/javascript"></script>
	<script src="/js/activity.settings.js" type="text/javascript"></script>
	<script src="/js/activity.messages.js" type="text/javascript"></script>
	<script src="/js/activity.sfide.js" type="text/javascript"></script>
	<script src="/js/activity.ui.js" type="text/javascript"></script>
	<script src="/js/activity.fb.js" type="text/javascript"></script>
	<script src="/js/colorPicker/colorPicker.js" type="text/javascript"></script>
	<script>
	<? if( isset ($msg) && strlen($msg) > 0) { ?>
		action_queue.push(function() {
			win.html('<? echo $msg; ?>');
			win.dialog("open");
		});
	<? } ?>
	<? if( isset ($_REQUEST["show_tab"]) && $_REQUEST["show_tab"] != '' ) { ?>
		end_action_queue.push(function() { activity.ui.show_tab ('<?=$_REQUEST["show_tab"]?>'); });
	<? } ?>
	</script>
	<!-- OAS SETUP begin -->
	<SCRIPT type="text/javascript">
	<!--
	//configuration
	OAS_url ='http://oas.advit.it/RealMedia/ads/';
	OAS_listpos = 'Bottom,Middle,Top,Right1';
	OAS_query = '?';
	OAS_sitepage = 'www.rigorix.com/ap';
	//end of configuration
	OAS_version = 10;
	OAS_rn = '001234567890'; OAS_rns = '1234567890';
	OAS_rn = new String (Math.random()); OAS_rns = OAS_rn.substring (2, 11);
	function OAS_NORMAL(pos) {
	document.write('<A HREF="' + OAS_url + 'click_nx.ads/' + OAS_sitepage + '/1' + OAS_rns + '@' + OAS_listpos + '!' + pos + OAS_query + '" TARGET=_top>');
	document.write('<IMG SRC="' + OAS_url + 'adstream_nx.ads/' + OAS_sitepage + '/1' + OAS_rns + '@' + OAS_listpos + '!' + pos + OAS_query + '" BORDER=0 ALT="Click!"></A>');
	}
	//-->
	</SCRIPT>
	<SCRIPT type="text/javascript">
	<!--
	OAS_version = 11;
	if (navigator.userAgent.indexOf('Mozilla/3') != -1)
	OAS_version = 10;
	if (OAS_version >= 11)
	document.write('<SC'+'RIPT LANGUAGE=JavaScript1.1 SRC="' + OAS_url + 'adstream_mjx.ads/' + OAS_sitepage + '/1' + OAS_rns + '@' + OAS_listpos + OAS_query + '"><\/SCRIPT>');
	//-->
	</SCRIPT><SCRIPT type="text/javascript">
	 <!--
	 document.write('');
	function OAS_AD(pos) {
	if (OAS_version >= 11 && typeof(OAS_RICH)!='undefined')
	  OAS_RICH(pos);
	else
	  OAS_NORMAL(pos);
	}
	//-->
	</SCRIPT>
	<!-- OAS SETUP end -->

</head>