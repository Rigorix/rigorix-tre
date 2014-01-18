<?php
require_once ("inc/Engine.php");
$App->Context = 'Menu';
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Menu</title>
	<style>@import 'css/common.css';</style>
	<?php echo $App->setGlobalJsVars(); ?>
	<script type="text/javascript" src="js/libs/prototype.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script>
		var jq = jQuery.noConflict();
	</script>
	<script type="text/javascript" src="js/FF.js"></script>
	<script type="text/javascript" src="js/FF.UI.js"></script>
	<script type="text/javascript" src="js/FF.MenuTree.js"></script>
</head>
<body id="menu">
<div id="menuContent">
	<div class="menuHeader">
		<h3><?php echo $print['wellcome'];?> <strong><?php echo $User->Name; ?></strong></h3>
		<p><?php 
		if($User->isGOD())
			echo $print['i_am_admin'] . " <strong style=color:red>GOD</strong>";
		else if($User->isAdmin()) 
			echo $print['i_am_admin'] . '!'; 
		else 
			echo $print['i_am_user'];
		?></p>
		<div class="menuRefreshButton">
			<a href="#" onClick="window.location.reload();"><img src="i/ico_refresh_small.gif" /></a>
		</div>
	</div>
	
	<div id="menuRightBar">
		<a class="cursorPointer" onclick="FF.UI.toggleMenuView();">< < < < < < < <</a>
	</div>
	
	<div id="MenuContainer_">
		<a class="MenuHome" href="intro.php" target="content"><img src="i/ico_home.gif" /></a>
		<br clear="all" />
		<ul id="MenuTree">
			
		<?
		$MENU = $App->getAdminMenu();
		$menuRow = 0;
		
		foreach ($MENU as $MenuHeader) {
			if($MenuHeader->getAttribute('defcon') < 3) {
				
				switch($MenuHeader->getAttribute('type')) {
					
					case '*DATABASE*': 
						
						echo '<li><a title="">'.$MenuHeader->getAttribute('name').'</a>';
						echo '<ul>';
						if($App->getTables()->length > 0 && ($App->getUserTables($User->Name)->length > 0 || $User->isAdmin())) {
							$userVisibleTables = 0;
							foreach($App->getTables() as $Table) {
								if (
									$User->hasTablePermission($Table->getAttribute('name')) && 
									($User->isAdmin() || $App->isTableVisible($Table->getAttribute('name')))
								) {
									$userVisibleTables++;
									echo '<li><a target="content" href="content.php?table='.$Table->getAttribute('name').'" target="content" title="">'.$Table->getAttribute('title').'</a>';
									$Plugins = $App->getTablePlugins($Table->getAttribute('name'));
									if($Plugins !== false) {
										echo '<ul>';
										foreach ($Plugins as $Plugin) {
											echo '<li name="plugin"><a href="pluginManager.php?action=runPlugin&table='.$Table->getAttribute('name').'&name='.$Plugin->getAttribute('name').'&file='.$Plugin->getAttribute('ref').'" target="content">'.$Plugin->getAttribute('name').'</a></li>';
										}
										echo '</ul>';
									}
									echo '</li>';
								}
							} 
							if($userVisibleTables == 0)
									echo '<li>'.$print['no_tables'].'</li>';
						} else
							echo '<li><a>'.$print['no_tables'].'</a></li>';
						echo '</ul></li>';
						break;
						
					case "*MAIL*": 
						echo '<li class="MenuMail"><a href="mailto:'.$MenuHeader->textContent.'">'.$MenuHeader->getAttribute('name')."</a></li>";
						break;
						
					case "*MENU*": 
						if($MenuHeader->getAttribute('defcon') <= $User->Type) {
							$dir = $MenuHeader->getAttribute('dir') | '';
							echo '<li><a title="">'.$MenuHeader->getAttribute('name').'</a>';
							echo '<ul>';
								foreach ($MenuHeader->getElementsByTagName('content') as $MenuItem) {
									if($MenuItem->getAttribute('page') != 'JAVASCRIPT')
										echo '<li><a href="'.$dir . "/" . $MenuItem->getAttribute('page').'" '.(($MenuItem->getAttribute('target') != '') ? 'target="'.$MenuItem->getAttribute('target').'"' : '').'>'.$MenuItem->getAttribute('name').'</a></li>';
									else
										echo '<li><a href="javascript:void(' . trim($MenuItem->textContent).');">'.$MenuItem->getAttribute('name').'</a></li>';
								}
							echo '</ul>';
							echo '</li>';
						}
						break;
					
				}
				
			}
			$menuRow++;
		}
		
		?>
		
		</ul>
		
	</div>
	<script language="javascript">
	var menu = FF.MenuTree.init("MenuTree");
	FF.UI.setSkin('Menu_minimizeBar');
	</script>
	
</div>
</body>
</html>
