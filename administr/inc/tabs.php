<?php
global $App;
global $Table;
global $User;
?>

<? if($Table) { ?>
	<h1>
		<?php if ( $App->Context == "Configuring") { ?><a href="../content.php?table=<?=$Table->name?>">&laquo;</a><?php } ?>
		<?php echo $Table->title; ?> 
		<?=(($_REQUEST['action'] == 'runPlugin') ? ' > <span class="PluginName">'.$Plugin->getAttribute('name') : '')?> 
		<span class="description"><?php echo $Table->hasDescription() != false ? $Table->getDescription() : ''; ?> &nbsp; 
		<?php if($_SESSION['FF'][$Table->name]['show'] == 'all') echo '(Table name: '.$Table->name.')'; ?>
		<br /></span>
	</h1>
<? } ?> 

<div id="tabContainer">
	
	<?php if(
		$App->Context != 'PluginManager' && 
		$App->Context != 'Intro'
	) { ?>
		
		<?php echo $Table->getPager(); ?>
		
		<?php if ( $App->Context != "Configuring") { ?>
			<div class="tabStyle">
				<a href="#" onClick="FF.UI.runFilter(this);">Filtra</a>
			</div>
		<?php } ?>
		
		<?php if (isset($_SESSION['FF']['FILTERS'][$Table->name]) && $App->Context != "Configuring") { ?>
			<div class="tabStyle eraseTab">
				<a href="content.php?table=<?=$Table->name?>&action=deleteFilter">Cancella Filtro</a>
			</div>
		<?php } ?>
		
		<div class="tabStyle special1" id="tabDuplicate">
			<a href="#" onClick="FF.Contents.duplicateRow(this);">Dupplica dato</a>
		</div>
		
		<div class="tabStyle special1" id="tabMultipleDelete">
			<a href="#" onClick="FF.Contents.multipleDelete(this);">Cancellazione multipla</a>
		</div>
		
		<?php if($User->Type > 0) { ?>
			<!--div class="tabStyle special2">
				<a href="#" onclick="FF.UI.runConfig(this);">Configura tabella</a>
			</div-->
		<?php } ?>
		
		<?php
		if($_SESSION['FF']['pageLogger'] != "" && $_SESSION['FF']['pageLogger'] != null) {
			echo '<div class="tabStyle pageLogger"><a href="#" onclick="this.parentNode.style.display=\'none\';">X</a> '.$_SESSION['FF']['pageLogger'].'</div>';
			$_SESSION['FF']['pageLogger'] = "";
		}
		?>
	<?php } ?>
	
</div><div class="clear" id="tabContainerClearer"><br /></div>