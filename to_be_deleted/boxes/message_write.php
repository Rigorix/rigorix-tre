<?php
chdir("../");
require_once('classes/core.php');

$destinatario = '';
$oggetto = '';

if ( isset ($_REQUEST['reply']) && $_REQUEST['reply'] == 'true' ) {
	$destinatario = $dm_messaggi->getUsernameSenderByIdMess ($_REQUEST['id_mess']);
	$oggetto = "RE: " . $dm_messaggi->getSubjectByIdMess ($_REQUEST['id_mess']);
}
if ( isset ($_REQUEST['id_destinatario']) && $_REQUEST['id_destinatario'] != '' ) {
	$destinatario = $dm_utente->getUsernameById ($_REQUEST['id_destinatario']);
}

?>
	<form name="write_message_form">
	<table width="400">
	<tr><td>Destinatario: </td><td width="100%"><input type="text" name="destinatario" id="ToUser" style="width: 350px" class="rx-ui-search-user" value="<?=$destinatario?>" /><div id="ToUserList" class="autocompleterUL"></div></td></tr>
	<tr><td>Oggetto: </td><td><input type="text" name="oggetto" id="subj" style="width: 350px" maxlength="255" value="<?=$oggetto;?>" /></td></tr>
	<tr valign="top"><td>Testo: </td><td><textarea name="testo" style="width: 350px; height:70px"></textarea></td></tr>
	</table>
	</form>
	<div align="center">
		<a class="rx-ui-button" name="write-message-send">Invia</a>
	</div>

