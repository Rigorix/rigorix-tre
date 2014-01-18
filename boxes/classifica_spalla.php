<?php
global $user;
$rank = $user->get_ranking_utenti ( 30 );
?>

<div class="ui-box-content-section-header">
    Primi 30 utenti
</div>

<?php $user->print_user_list ( $rank ); ?>

<!-- div align="center">
    <button class="rx-ui-button" name="show-user-search-panel">Vedi tutti</button>
</div -->

<script>
	activity.ui.init_ui_buttons ();
</script>