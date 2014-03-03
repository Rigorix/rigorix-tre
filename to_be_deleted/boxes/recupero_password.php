<?php 
chdir("../");
$include_path = "/";
require_once ('classes/core.php');
require_once ('boxes/dialog_start.php');
?>

<div class="dialog-dimensions-rewriter" style="width: 400px; height: 140px; ">

	<form onsubmit="return false;" action="recuperoPassword.php?recupero=Effettuato" name="password_recovery_form" method="POST">
	<input type="hidden" name="posted" value="1" />
	<p>
	Inserisci il tuo nickname e clicca "Recupera password".<br /><br />
	Ti arriver&agrave; una mail all'indirizzo di posta con cui ti sei registrato con la password.<br /><br />
	</p>
	<table cellspacing="0" width="100%">
	<tr>
		<td width="15%">Nickname </td><td><input type="text" name="username" /></td>
		<td><button class="rx-ui-button" name="password-recovery">Recupera password</button></td>
	</tr>
	</table>
	</form>

</div>

<?php require_once('boxes/dialog_end.php'); ?>