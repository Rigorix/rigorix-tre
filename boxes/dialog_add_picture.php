<?php
chdir("../");
require_once('classes/core.php');
require_once('boxes/dialog_start.php');
?>
	<table>
	<tr valign="top">
		<td>
			<img name="user-picture-thumb" width="120" src="<?php echo $user->get_user_picture_uri ($user->obj); ?>" /></td>
		<td>
		<form name="profile-picture-form" action="/classes/responder.php?activity=upload_profile_picture" target="service_window" method="post" enctype="multipart/form-data">
		<p>Utilizza questo form per aggiungere/modificare un'immagine che pi&ugrave; ti rappresenti come Rigorix Player!!<br /><br />
		Seleziona un'immagine dal tuo PC cliccando su "Sfoglia".<br /></p>
		<br />
		<input type="file" value="Sfoglia" name="picture-uploader" />
		<br /><br />
		<p><strong>NB:</strong> <em>Le immagini dovranno essere in formato jpg, gif o png con un limite di peso di 1MB.</em></p>
		<br />
		<div class="hidden load-action">
			<p><strong>L'immagine &egrave; stata selezionata.</strong><br />
			Ora premi "CARICA" e attendi il caricamento.</p>
			<br />
			<input type="submit" class="rx-ui-button" name="add-profile-picture-action" onclick="activity.settings.on_load_profile_picture();" value="CARICA" />
		</div>
		</form>
		</td>
	</tr>
	</table>

<?php require_once('boxes/dialog_end.php'); ?>
