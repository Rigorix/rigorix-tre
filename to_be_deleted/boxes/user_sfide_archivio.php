<?php
chdir("../");
require_once('classes/core.php');

$include_path_override = true;
if ( isset ($_REQUEST['start_date']) && isset ($_REQUEST['end_date'])):
	$is_search = true;
	$sfide_list = $user->get_sfide_chiuse_by_date_range ( $_REQUEST['start_date'], $_REQUEST['end_date']);
else:
	$is_search = false;
	$sfide_list = $user->get_last_ten_sfide_chiuse ( );
	$_REQUEST['start_date'] = $_REQUEST['end_date'] = '';
endif;
?>

<div class="ui-box-content-html main-pane">
	<p>Seleziona un arco di tempo nel quale cercare le sfide che hai giocato</p>

	<div class="ui-box ui-box-content ui-corner-all ui-box-unpadded">
		<div class="ui-box-content-html ui-box-system">
			<form name="cerca-sfide-chiuse-form" class="form-inline" onsubmit="return false;">
			<input validate_as="date" class="span3" type="text" name="start_date" placeholder="Data inizio" value="<?php echo $_REQUEST['start_date']; ?>" />
			<input validate_as="date" type="text" class="span3" name="end_date" placeholder="Data fine" value="<?php echo $_REQUEST['end_date']; ?>" />
			<button class="btn" name="cerca-sfide-chiuse">Cerca</button>
			</form>
		</div>
	</div>
	<?php if ( $is_search === false ): ?>
		<div class="ui-box ui-box-content ui-corner-all ui-box-unpadded" style="overflow: hidden;">
			<div class="ui-box-title">Ultime 10 sfide</div>
			<?php require ('boxes/user_sfide_list.php'); ?>
		</div>
		<br />
	<?php else: ?>
		<div class="ui-box ui-box-content ui-corner-all ui-box-unpadded" style="overflow: hidden;">
			<div class="ui-box-title">Sfide</div>
			<?php require ('boxes/user_sfide_list.php'); ?>
		</div>
		<br />
	<?php endif; ?>
</div>

<script>
activity.sfide.init_sfide_archivio ();
</script>