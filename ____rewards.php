<?php
require_once('classes/core.php');
require_once ('boxes/page_start.php');
?>

	<div class="rx-layout-col-large">

		<!-- Colonna sinistra * corpo pagina -->
		<div class="rx-layout-col-container">

			<div class="ui-box ui-box-content ui-corner-all">
				<div class="ui-box-title">
					Rewards
				</div>
				<div class="ui-box-content-html">

					<p>Lista dei rewards possibili:</p>

					<?php
					$myrewards = $dm_rewards->getPuntiRewards ();
					foreach ( $myrewards as $reward ): ?>

						<div class="reward-list-item">
							<h3><?php echo $reward->nome; ?></h3>
							<h5><?php echo $reward->score; ?></h5>
							<p><?php echo $reward->descrizione; ?></p>
						</div>

					<?php endforeach; ?>

				</div>
			</div>

		</div>
		<!-- Fine colonna sinistra * corpo pagina -->
		<div class="clr"></div>

	</div>

	<div class="rx-layout-col-right">
		<!-- Colonna destra * corpo pagina -->
		<div class="rx-layout-col-container">


		</div>
		<!-- Fine colonna destra * corpo pagina -->

	</div>
	<div class="rx-layout-col-extreme-right">

			<!-- Colonna destra banner * corpo pagina -->
			<div class="rx-layout-col-container">

				<?php $core->render_banner ("Middle"); ?>

			</div>
			<!-- Fine colonna destra banner * corpo pagina -->

	</div>
	<div class="clr"></div>

<?php
require_once ('boxes/page_end.php');
?>