<?php
global $App;
global $User;
global $_REQUEST;
?>

<h1>Admin configurator <span class="description"><?=$print['adm_configurator_description']?></span></h1>

<div id="tabContainer">
	
	<div id="general_preferences" class="tabStyle">
		<a href="#" onclick="FF.Configurator.ShowTab(this, 'conf.general_preferences.php')">General preferences</a>
	</div>
	
	<div id="users_manager" class="tabStyle">
		<a href="#" onclick="FF.Configurator.ShowTab(this, 'conf.users_manager.php')">User manager</a>
	</div>
	
	<div id="db_tables" class="tabStyle">
		<a href="#" onclick="FF.Configurator.ShowTab(this, 'conf.db_tables.php')">DB Tables</a>
	</div>
	
	<div id="modules" class="tabStyle">
		<a href="#" onclick="FF.Configurator.ShowTab(this, 'conf.modules.php')">Modules</a>
	</div>
	
	<div id="timemachine" class="tabStyle">
		<a href="#" onclick="FF.Configurator.ShowTab(this, 'conf.timemachine.php')">Time machine</a>
	</div>
	
	<?
	if($_SESSION['FF']['pageLogger'] != "" && $_SESSION['FF']['pageLogger'] != null) {
		echo '<div class="tabStyle pageLogger"><a href="#" onclick="this.parentNode.style.display=\'none\';">X</a> '.$_SESSION['FF']['pageLogger'].'</div>';
		$_SESSION['FF']['pageLogger'] = "";
	}
	?>
	<div class="clear" id="tabContainerClearer"><br /></div>
</div><div class="clear" id="tabContainerClearer"><br /></div>