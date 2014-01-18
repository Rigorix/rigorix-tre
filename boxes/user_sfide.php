<?php
chdir("../");
require_once('classes/core.php');
?>

	<div class="ui-box-content-tab sub-level">
		<div class="rx-ui-tab borderless sfide-tabs">
		     <ul>
		         <li name="rx-tab-sfide-attive"><a href="boxes/user_sfide_generic.php"><span>Sfide da giocare</span></a></li>
		         <li name="rx-tab-sfide-attesa"><a href="boxes/user_sfide_attesa.php"><span>In attesa di risposta</span></a></li>
		         <li name="rx-tab-sfide-archivio"><a href="boxes/user_sfide_archivio.php"><span>Archivio</span></a></li>
		     </ul>
		</div>
	</div>

<script>
activity.sfide.init_sfide_list ();
</script>