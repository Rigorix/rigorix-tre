<?php
chdir("../");
require_once('classes/core.php');
?>
<div class="ui-box-content-html main-pane pbl">

	<p>Da questa pagina puoi richiedere la cancellazione da Rigorix.</p>

    <div class="callout callout-danger">
        <h3 class="text-error">ATTENZIONE:</h3>
        <p class="text-error">Una volta richiesta e confermata la cancellazione, non sarà possibile tornare indietro e verranno persi tutti i propri dati.</p>
    </div>

	<div class="text-center">
        <button class="btn btn-danger" name="cancellazione"><i class="icon-trash"></i> Cancellazione utente</button>
    </div>

</div>

<script>
activity.settings.init_unsubscribe_form ();
</script>