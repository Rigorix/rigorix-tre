<?php
require_once ("inc/Engine.php");
$_CONF = $App->getConfigObj();
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title><?php echo $_CONF['title'];?></title>
	<style>@import 'css/common.css';</style>
</head>
<body id="header">
<div id="headerContent">
	<a href="intro.php" target="content" title="<?php echo $print['go_to_intro']; ?>">
	<?php if ($_CONF['logo'] != '') { ?> 
		<img id="headerLogo" src="<?php echo $App->root . 'i/' .$_CONF['logo'];?>" align="left" height="50"> 
	<?php } ?>
	<h1><?php echo $_CONF['title'];?></h1>
	<h2><?php echo $_CONF['subtitle'];?></h2>
	</a>
	<div id="logout">
		<input type="button" class="round-button" value="<?php echo $print['logout']; ?>" onClick="window.parent.location = 'login.php?action=logout'">
	</div>
</div>
</body>
</html>