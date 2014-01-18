<?php
require_once ("inc/Engine.php");
$App->Context = 'ImageViewer';
$Table = new Table($App->CurrentTable);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Image viewer</title>
	<script>
	function res() {
		console.log(window.innerHeight);
		console.log(window.outerHeight);
		console.log(document.body.offsetHeight);
		iWidth = document.body.clientWidth; 
       iHeight = document.body.clientHeight; 
       iWidth = document.images[0].width - iWidth; 
       iHeight = document.images[0].height - iHeight; 
       window.resizeBy(iWidth, iHeight); 
       self.focus(); 
	 //  resizeTo(document.getElementById('image').offsetWidth,document.getElementById('image').offsetHeight);
	}
	</script>
</head>
<body style="margin: 0; padding: 0" onload="res();">
<img src="<?=$App->ConfigObj['adminloadingpath'].$Table->name."/".$_REQUEST['file']?>" id="image" />
</body>
</html>
