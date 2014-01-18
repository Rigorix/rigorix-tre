<?
require_once('classes/core.php');
require_once ('boxes/page_start.php');
?>

	<div class="rx-layout-col-large">
	
		<!-- Colonna sinistra * corpo pagina -->
		<div class="rx-layout-col-container">
			
			<?php echo $core->render_db_static_page ( $core->page_key ); ?>
			
		</div>
		<!-- Fine colonna sinistra * corpo pagina -->
		<div class="clr"></div>
		
	</div>	
		
	<div class="rx-layout-col-right">
		<!-- Colonna destra * corpo pagina -->
		<div class="rx-layout-col-container">
		
			<?php $core->render_box_highlight ( "user_box.php", "Box personale" ); ?>
			<?php $core->render_box_unpadded ( "classifica_spalla.php", "Ranking utenti" ); ?>
			
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