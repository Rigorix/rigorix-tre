<?php
require_once('classes/core.php');
require_once ('boxes/page_start.php');
?>

	<div class="rx-layout-col-large" ng-controller="Home">

		<!-- Colonna sinistra * corpo pagina -->
		<div ng-view class="rigorix-main-view"></div>
		<div class="clr"></div>

	</div>

	<div class="rx-layout-col-right" >
		<!-- Colonna destra * corpo pagina -->
		<div class="rx-layout-col-container ptl" ng-controller="Sidebar">
			<ng-include src="'app/templates/user-box.html'"></ng-include>
			<ng-include src="'app/templates/best-users.html'"></ng-include>
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

  <div set-loader="60"></div>
<?php
require_once ('boxes/page_end.php');
?>