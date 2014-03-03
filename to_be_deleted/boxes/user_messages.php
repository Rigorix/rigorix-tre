<?php
chdir("../");
require_once('classes/core.php');
?>

<!--<div style="margin: 0; background: url(i/tit_messaggi.gif) no-repeat left top; width: 592px; height: 45px;">

</div>-->

	<div class="ui-box-content-html main-pane">

	<p>Da questa pagina puoi scambiare messaggi con tutti i rigoristi avversari!<br />
	Scrivi correttamente il nickname di chi vuoi contattare o il messaggio potrebbe non essere recapitato!</p>

	<br />
	<button name="open-write-message-dialog" class="rx-ui-button"><span class="ui-icon ui-icon-mail-closed"></span> Nuovo messaggio</button>
	<button name="reload-message-list" class="rx-ui-button"><span class="ui-icon ui-icon-refresh"></span> Ricarica</button>
	<br /><br />

	<div class="ui-box ui-box-content ui-corner-all ui-box-unpadded">
		<div class="ui-box-title" style="padding-left: 0; padding-right: 0; ">
			<table width="100%">
			<tr>
				<td width="5%"><input type="checkbox" name="messages-selector" /></td>
				<td width="15%">Data</td>
				<td width="13%">Mittente</td>
				<td width="67%">Oggetto</td>
			</tr>
			</table>
		</div>
		<div class="ui-box-content">
			<table class="messages-table" width="100%" cellspacing="0">
			<?php
			$messaggi = $user->get_filtered_messages (0, 10);
			if ( count ($messaggi) == 0)
				echo '<tr valign="top"><td colspan="4" align="center"><br /><br />Non ci sono messaggi da mostrare<br /><br /><br /></td></tr>';
			?><tbody class="messages-table-body"><?php
				$user->print_messages_row ($messaggi);
			?></tbody>
			<tfoot>
			<?php
			if ( count ($messaggi) > 0) { ?>
				<tr valign="top">
					<td colspan="4" style="background: #fff;">
						<table>
						<tr><td width="400" style="background: #fff;">
							<strong>&nbsp; Azione su selezionati: </strong><button name="delete-selected-messages" class="rx-ui-button button-small">Cancella</button> <button name="markread-selected-messages" class="rx-ui-button button-small">Marca come letti</button>
						</td><td style="padding: 2px 6px;" width="10%" nowrap="nowrap">
							<a class="go-prev-message"> &laquo; Pi&ugrave; recenti </a> &nbsp; <strong class="paginator-page">1 - 49</strong> di <strong class="paginator-total"><?php echo $user->get_count_unread_messages (); ?></strong> &nbsp; <a class="go-next-message"> Pi&ugrave; vecchi &raquo; </a>
						</td></tr>
						</table>
					</td>
				</tr>
			<?php } ?>
			</tfoot>
			</table>
		</div>
	</div>
</div>