<?php
global $core;
?>
<div class="user-public-stats">
	Utenti registrati: <strong id="CountRegisteredUsers"><? $core->print_session_var ( "storage", "NUMERO_UTENTI_REGISTRATI" ); ?></strong> &nbsp;
	Utenti online: <strong id="CountOnlineUsers"><? $core->print_session_var ( "storage", "NUMERO_UTENTI_ONLINE" ); ?></strong>
</div>