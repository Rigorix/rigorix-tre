<?php
	// start a new session (required for Hybridauth)
	session_start();
	$_SESSION["rigorix_auth_origin"] = @ $_GET["origin"];

	// change the following paths if necessary
	$config = 'hybridauth/config.php';
	require_once( "hybridauth/Hybrid/Auth.php" );

	try{
		$hybridauth = new Hybrid_Auth( $config );
	}
	// if sometin bad happen
	catch( Exception $e ){
		$message = "";

		switch( $e->getCode() ){
			case 0 : $message = "Unspecified error."; break;
			case 1 : $message = "Hybriauth configuration error."; break;
			case 2 : $message = "Provider not properly configured."; break;
			case 3 : $message = "Unknown or disabled provider."; break;
			case 4 : $message = "Missing provider application credentials."; break;
			case 5 : $message = "Authentication failed. The user has canceled the authentication or the provider refused the connection."; break;

			default: $message = "Unspecified error!";
		}
		?>
		<h3>Something bad happen!</h3>
		<hr />
		<?php echo $message ; ?>
		<b>Exception</b>: <?php echo $e->getMessage() ; ?>
		<pre><?php echo $e->getTraceAsString() ; ?></pre>
		<?php
		// diplay error and RIP
		die();
	}

	$provider  = @ $_GET["provider"];
	$return_to = @ $_GET["return_to"];

	if( !$return_to ) {
		echo "Invalid params!";
	}

	if( ! empty( $provider ) && $hybridauth->isConnectedWith( $provider ) )
	{
		$return_to = $return_to . ( strpos( $return_to, '?' ) ? '&' : '?' ) . "connected_with=" . $provider ;
?>
<script language="javascript">
	if(  window.opener ){
		try { window.opener.parent.$.colorbox.close(); } catch(err) {}
		window.opener.parent.location.href = "<?php echo $return_to; ?>";
	}

	window.self.close();
</script>
<?php
		die();
	}

	if( ! empty( $provider ) )
	{
		$params = array();

		if( $provider == "OpenID" ){
			$params["openid_identifier"] = @ $_REQUEST["openid_identifier"];
		}

		if( isset( $_REQUEST["redirect_to_idp"] ) ){
			$adapter = $hybridauth->authenticate( $provider, $params );
		}
		else{
			// here we display a "loading view" while tryin to redirect the user to the provider
?>
<table width="100%" height="100%" border="0">
  <tr>
    <td align="center" height="190px" valign="middle" style="font-family: Arial; font-size: 14px; color: #666; ">
    	<img src="/i/Rigorix-social-loading.gif" />
    	<br /><br /><br />
    Stiamo contattando <b><?php echo ucfirst( strtolower( strip_tags( $provider ) ) ) ; ?></b>...
  </tr>
</table>
<script>
	setTimeout ( function () { window.location.href = window.location.href + "&redirect_to_idp=1"; }, 1000 );
</script>
<?php
		}

		die();
	}
?>