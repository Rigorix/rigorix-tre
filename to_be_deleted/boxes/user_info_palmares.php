<?php
chdir("../");
require_once ( "classes/core.php");
$badgeRewards = $dm_rewards->getBadgeRewards ();
?>
<div class="ui-box-content-html main-pane">

	<div class="ui-box ui-box-content ui-corner-all ui-box-unpadded">
		<div class="ui-box-title">Le mie coppe</div>
		<div class="ui-box-content-html">

			<div class="badge-list">
				<p>Hai <strong><?php echo count ( $user->get_badges ( $user->obj->id_utente ) ); ?></strong> delle <?php echo count ($badgeRewards); ?> coppe da vincere.<br />
					Se vuoi sapere come vincere le coppe, vai alla pagina dei <a href="riconoscimenti.php" class="link">RICONOSCIMENTI</a>!
				</p>
				<?php
				foreach ( $badgeRewards as $reward ): ?>

					<div class="badge-container <?php if ( $user->has_badge_by_key ( $user->obj->id_utente, $reward->key_id) ) echo 'I-have-it'; ?>">
						<img src="<?php echo ( $user->has_badge_by_key ( $user->obj->id_utente, $reward->key_id) ? $user->get_reward_picture ( $reward->key_id, "small" ) : $user->get_reward_picture ( $reward->key_id, "small", "_disabled" ) ); ?>" />
						<!-- div class="badge-content">
							<table>
								<tr valign="middle">
									<td height="100" width="100" align="center">
										<?php echo $reward->nome; ?>
									</td>
								</tr>
							</table>
						</div -->
					</div>

				<?php endforeach; ?>
				<div class="clr"></div>
			</div>

		</div>

	</div>

</div>