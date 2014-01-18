<?php
require_once('inc/Engine.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Login</title>
<style>@import 'css/common.css';</style>
<script>
	/*
	 * Controllo che questa pagina sia chiamata fuori dall'iframe
	 */
	if (window.name != '') 
		parent.window.location = '<?php echo $_REQUEST['root']; ?>login.php';
</script>
</head>
<body>

<table width="100%" height="100%">
<tr><td align="center">
	<table>
	<tr valign="middle">	
	<td>
		<h1 class="login" align="center">Login</h1>
		<div class="LoginPanel round-corners">
			<form action="login.php?action=login" method="post">
			<br />
			<?php if($_REQUEST['action'] == 'loginError') echo '<strong>:( '.$print['password_unknown'].'!</strong><br />'; ?>
			<br />
			<?php if($User->Logged === true) { ?>
				Password <span class="PwdOk">OK</span>. <br /><a href="login.php?action=logout"><?php echo $print['change']; ?></a>
			<?php } else { ?>
				Password: <input type="password" class="round-input" name="pwd"><br /><br />
				<input type="submit" class="round-button" name="login" value="Login" align="center" />
			<?php } ?>
			</form>
		</div>
	</td>
	<? if($User->Logged === true && count ($User->getUserModules()) > 1) { ?>
	<td valign="middle" class="Size30">&raquo;</td>
	<td align="center">
		<h1 class="login"><?php echo $print['select_module']; ?></h1>
		<div class="LoginPanel round-corners">
			<form action="login.php?action=selectModule" method="post">
			<br /><br />
			<?php echo $print['modules']; ?>: <select name="module_path" class="round-input">
			<?php
			$Modules = $User->getUserModules();
			foreach($Modules as $Module) {
				echo '<option value="'.$Module['attributes']['path'].'">'.$Module['attributes']['name'].'</option>';
			}
			?>
			</select><br /><br />
			<input type="submit" name="login" class="round-button" value="<?php echo $print['select']; ?>" />
			</form>
		</div>
	</td>
	<? } ?>
	</tr>
	</table>
	<?php 
	if ( $User->Logged === true && count ($User->getUserModules()) == 1) { 
		$_REQUEST = array();
		$_REQUEST["action"] = "selectModule";
		$Module = $User->getUserModules();
		$_REQUEST["module_path"] = $Module[0]["attributes"]["path"];
		$User->doSelectModule ();
		?><script>window.location.href = "index.php";</script>
	<?php } ?>
</td></tr>
</table>

</body>
</html>
