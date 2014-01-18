<?php
global $user_list, $user_list_conf, $include_path_override, $user;
if (!isset ($include_path_override) || $include_path_override != true)
	chdir("../");
require_once('classes/core.php');

if ( !$user->is_logged ):
	echo "<big class='error message'>Devi essere loggato per poter lanciare o ricevere una sfida...<br />Tra 3 secondi sarai rediretto all'homepage</big>";
	echo '<script>setTimeout ( function () {top.window.location.href = "/index.php"; }, 3000);</script>';
	exit;
endif;

?>

<table class="form-table entry-table list">
	<tr>
		<th>Username</th>
		<th align="center">Ranking</th>
		<th align="center">Sesso</th>
	    <th></th>
	</tr>
	<?php
	for ( $i=0; $i < count ($user_list); $i++) {
		$utente = $user->createUserObject ( $user_list[$i] );
		$username = $user->get_smart_username ($utente->id_utente, true);

		echo '<tr '.(($i%2==0) ? 'class="alt"' : '').'>
			<td name="username"><a name="user-link" id_utente="'.$utente->id_utente.'">'.$username.'</a></td>
			<td align="center">'.$utente->ranking.'</td>
			<td align="center">'.$utente->sesso.'</td>
		</tr>';
	} ?>
</table>

<script>
activity.ui.init_user_list_interface ();
</script>