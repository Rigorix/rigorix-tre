<?php
chdir("../");
require_once('classes/core.php');

$include_path_override = true;
$sfide_list = $user->obj->sfide_da_giocare;
?>
<div class="ui-box-content-html main-pane">
	<p>Qui trovi la lista di tutte le sfide che devi lanciare o a cui devi rispondere.</p>
	<br />

	<div class="ui-box ui-box-content ui-corner-all ui-box-unpadded" style="overflow: hidden;">
		<div class="ui-box-title">Sfide</div>
		<?php require ('boxes/user_sfide_list.php'); ?>
	</div>
	<br />
</div>

<script>
activity.sfide.init_sfide_list ();
</script>
