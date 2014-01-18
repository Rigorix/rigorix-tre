<?php
require_once('inc/Engine.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<head>
	<title><?php echo $App->ConfigObj['title']?></title>
	<script type="text/javascript" src="js/libs/prototype.js"></script>
	<script type="text/javascript" src="js/Dashboard.js"></script>
	<script>dashboard.docroot = '<?=$_SERVER['DOCUMENT_ROOT'];?>';</script>
</head>
<frameset name="app_main_win" id="FF_UI_AppWindow" rows="60,*" border="0">
    <frame id="FF_UI_HeaderWindow" name="header" src="header.php" marginwidth="*" marginheight="0" scrolling="no" frameborder="0" noresize>
    <frameset name="app_content" id="FF_UI_ContentWindow" cols="200,*" border="0" bordercolor="">
        <frame name="menu" id="FF_UI_MenuWindow" src="menu.php" marginwidth="2" marginheight="*" scrolling="auto">
        <frame name="content" src="intro.php" marginwidth="2" marginheight="*" scrolling="auto">
    </frameset>
</frameset>
<noframes></noframes>