<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" ng-app="Rigorix">

<?php require_once("boxes/html_head.php"); ?>

<body ng-controller="Main" ng-class="modalClass">

<div class="rigorix-loading">
  <span>Loading...</span>
</div>

<div class="container">

<!--<div id="service_window">-->
<!--	<iframe name="service_window" src="classes/service.php"></iframe>-->
<!--</div>-->
<?php if ($core->settings['DEVELOPER']) require_once ("boxes/developer_prepage_tools.php"); ?>
<?php //if ($core->test && $user->is_logged) require_once ("boxes/test_box.php"); ?>
<div class="rx-loading-panel">
	<div class="rx-loading-panel-progress"></div>
</div>
<script>
	jQuery(".rx-loading-panel").dialog({
		title: "Caricamento in corso...",
		height: 80,
		resizable: false
	});
	jQuery(".rx-loading-panel-progress").progressbar({ value: 10 });
</script>

<div id="page" class="rx-loading">


	<div id="header">
		<a href="#home"><img src="i/logo.gif" alt="Rigorix - header" border="0" style="margin: 15px 0 3px 15px;" /></a>

		<?php $core->render_flat_box ( "user_public_stats.php" ); ?>
		<!--
		<?php if ( $core->settings["BETA"] == "true" ) { ?>
			<div class="beta-version">
				<p>BETA</p>
			</div>
		<?php } ?>
		-->

		<div class="bannerHeader">
			<?php $core->render_banner ("Top"); ?>
		</div>

		<?//php $core->render_flat_box ( "top_menu.php" ); ?>

	</div>
	<script>jQuery(".rx-loading-panel-progress").progressbar({ value: 30 });</script>

	<div id="body">

