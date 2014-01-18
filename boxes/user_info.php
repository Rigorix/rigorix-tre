<?php
global $core, $user;
$default_tab = 0;
?>

	<div class="ui-box-content-tab sub-level">
		<div class="borderless user-settings-tabs" default="<?=$default_tab?>">
			<ul>
				<li class="rx-tab-info-palmares"><a href="boxes/user_info_palmares.php"><span>Palmares</span></a></li>
<!--				<li class="rx-tab-info-statistiche"><a href="boxes/user_info_statistiche.php"><span>Statistiche</span></a></li>-->
			</ul>
		</div>
	</div>

<script>
$('.user-settings-tabs').tabs({
	load: function(event, ui) {
		activity.run_internal();
		activity.ui.init ();
		$(".rx-ui-button").button();
	}
});
</script>