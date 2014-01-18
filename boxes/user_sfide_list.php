<?php
global $sfide_list, $include_path_override;
if (!isset ($include_path_override) || $include_path_override != true)
	chdir("../");
require_once('classes/core.php');

if ( count ($sfide_list) == 0)
	echo "<div class='ui-box-content-html'>Nessuna sfida da mostrare</div>";
else { ?>


		<table class="form-table entry-table sfida-list-table border">
			<tr class="small-table-header">
				<th rowspan="2"></th>
				<th rowspan="2">&nbsp; Avversario</th>
				<th rowspan="2" align="center">Lanciata il</th>
				<th rowspan="2" align="center">Risposta il</th>
				<th rowspan="2">Risultato</th>
				<th colspan="2" align="center">Punti</th>
				<th rowspan="2" align="center">Stato</th>
			</tr>
			<tr class="small-table-header">
				<th align="center">Punti</th>
				<th align="center">Rewards</th>
			</tr>
			<?php
			for ( $i=0; $i < count ($sfide_list); $i++) {
				$sfida = $activity->create_sfida_obj ($sfide_list[$i]);
				$id_avversario = ($user->obj->id_utente == $sfida->id_sfidato) ? $sfida->id_sfidante : $sfida->id_sfidato;
				?>
				<tr sfida_action="<?php echo ($sfida->data_lancio_str == '') ? 'l' : 'r';?>" id_sfida="<?php echo $sfida->id_sfida; ?>" stato="<?=$sfida->stato?>" class="<?php if ($i%2==0) echo 'alt'; ?>">
					<td width="5" class="<?php if ($i%2==0) echo 'alt'; ?><?php if ($sfida->vinta) echo ' won';?><?php if ($sfida->pareggio) echo ' draw';?><?php if ($sfida->persa) echo ' lose';?>">&nbsp;</td>
					<td name="avversario">&nbsp; <?php $user->print_username ( $id_avversario ); ?></td>

					<?php if ( $user->is_user_active ( $id_avversario ) ): ?>

						<td align="center"><?php echo $utility->print_cal_date ( $utility->normalize_db_datetime ( $sfida->dta_sfida ) ); ?></td>
						<td align="center">
							<?php if ( $sfida->stato == 2 ): ?>
								<?php echo $utility->print_cal_date ( $utility->normalize_db_datetime ( $sfida->dta_conclusa ) ); ?>
							<?php endif; ?>
						</td>
						<td>
							<div class="result-screen">
								<?php
								if ( $sfida->risultato == '' || $sfida->risultato == null)
									$res[0] = $res[1] = "_none";
								else
									$res = explode(",", $sfida->risultato);
								?>
								<div class="team-screen home-team score<?=$res[0];?> <?=($sfida->id_sfidante == $sfida->id_vincitore ? 'win' : 'lose')?>"></div>
								<div class="team-screen away-team score<?=$res[1];?> <?=($sfida->id_sfidato == $sfida->id_vincitore  ? 'win' : 'lose')?>"></div>
							</div>
						</td>
						<td align="center">
                            <span class="badge">
							<?php
							if ( $sfida->vinta )
								echo 3;
							else if ( $sfida->pareggio)
								echo 1;
							else echo 0;
							?>
                                </span>
						</td>
						<td align="center">
                            <?php if ( count ( $sfida->rewardIds ) > 0 ): ?>
                                <span class="badge badge-warning cursor-pointer" data-toggle="popover" data-placement="top" data-trigger="click" data-html="true" data-content="<?php $user->print_rewards_popover($sfida->rewardIds); ?>">
                                    <?php echo $sfida->rewardPoints; ?>
                                </span>
                            <?php else: ?>
                                <span class="badge">
                                    <?php echo $sfida->rewardPoints; ?>
                                </span>
                            <?php endif; ?>
						</td>
						<td align="center">
						<?php if ($sfida->stato == 0) { ?>
							<button class="rx-ui-button button-small" name="lancia_sfida_torneo">Lancia sfida</button>
						<?php } else if ( $sfida->stato == 1 ) { ?>
							<?php if ( $user->obj->id_utente == $sfida->id_sfidante ) { ?>
                                <button class="btn btn-small" disabled="disabled" name="vedi_sfida_torneo">Lanciata ...</button>
							<?php } else { ?>
                                <button class="btn btn-small btn-warning" name="lancia_sfida_torneo">Rispondi</button>
							<?php } ?>
						<?php } else if ( $sfida->stato == 2 ) { ?>
                            <button class="btn btn-small btn-info" name="vedi_sfida_torneo">Vedi sfida</button>
<!--							<button class="rx-ui-button button-small" name="vedi_sfida_torneo">Vedi sfida</button>-->
						<?php } else if ( $sfida->stato > 3 ) { ?>
                            <button class="btn btn-small" disabled="disabled" name="vedi_sfida_torneo">A tavolino</button>
<!--							<button class="rx-ui-button button-small ui-state-disabled" disabled="true" name="vedi_sfida_torneo">A tavolino</button>-->
						<?php } ?>
						</td>

					<?php else: ?>

						<td colspan="6">
							<p style="padding: 9px 0;"><strong>ATTENZIONE!!</strong> Avversario disiscritto. Sfida non visibile!</p>
						</td>

					<?php endif; ?>

				</tr>
			<?php } ?>
		</table>
<? } ?>


<script>
activity.sfide.init_sfide_table ();
</script>