<?php
global $core, $user;

// Imposto il TAB di default

$default_tab = 0;
?>

	<div class="ui-box-content-tab sub-level">
		<div class="borderless user-settings-tabs" default="<?=$default_tab?>">
			<ul>
				<li><a href="boxes/user_settings_datiutente.php"><span>Dati utente</span></a></li>
				<?php if ( !in_array("UPDATE_USER_FIELDS", $user->actions) ) { ?>
				<li><a href="boxes/user_settings_mascotte.php"><span>Rigorix mascotte</span></a></li>
				<!-- li><a href="boxes/user_settings_alerts.php"><span>Alert</span></a></li -->
				<li><a href="boxes/user_settings_cancellazione.php"><span>Cancellazione utente</span></a></li>
				<? } ?>
			</ul>
		</div>
	</div>

<script>
activity.ui.init_settings ();
<?php if ( in_array("UPDATE_USER_FIELDS", $user->actions) ) { ?>
activity.settings.force_reload = true;
<? } ?>
</script>