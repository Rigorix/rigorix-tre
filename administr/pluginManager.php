<?php
require_once ("inc/Engine.php");
$App->Context = 'PluginManager';
$Table = new Table($App->CurrentTable);
$Plugin = $Table->getPlugin($_REQUEST['file']);
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Content</title>
	<style>@import 'css/common.css';</style>
	<?php echo $App->setGlobalJsVars(); ?>
	<script type="text/javascript" src="js/libs/prototype.js"></script>
	<script type="text/javascript" src="js/libs/scriptaculous.js"></script>
	<script type="text/javascript" src="js/FF.js"></script>
	<script type="text/javascript" src="js/FF.Utils.js"></script>
	<script type="text/javascript" src="js/FF.UI.js"></script>
	<script type="text/javascript" src="js/FF.EventManager.js"></script>
	<script type="text/javascript" src="js/FF.Toolbar.js"></script>
	<script type="text/javascript" src="js/FF.Contents.js"></script>
	<script type="text/javascript" src="js/FF.Console.js"></script>
</head>
<body id="contents">
<div id="contentsContent">

	<div id="contentHeader">
		
		<?php require_once ($App->inc_dir . "/toolbar.php"); ?>
		<?php require_once ($App->inc_dir . "/tabs.php"); ?>
		
	</div>
	
	<div id="contentWrapper">
		
		<!--div id="tabContent" class="off"></div -->
		<?php require_once('plugins/'.$Plugin->getAttribute('ref')); ?>
			
	</div>
	
</div>
<?php $App->checkJsMessages(); ?>
</body>
</html>
