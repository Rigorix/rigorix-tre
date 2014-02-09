<?php
session_start();
$_SESSION["rigorix_auth_origin"] = @ $_GET["origin"];
require_once( "hybridauth/Hybrid/Auth.php" );

try{
  $hybridauth = new Hybrid_Auth( 'hybridauth/config.php' );
}
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
  <?php echo $message ; ?>
  <b>Exception</b>: <?php echo $e->getMessage() ; ?>
  <pre><?php echo $e->getTraceAsString() ; ?></pre>
  <?php
  die();
}

$provider  = @ $_GET["provider"];
$return_to = @ $_GET["return_to"];
$origin = @ $_GET["origin"];

if( !$return_to ):
  echo "ERROR: return_to must be specified!";

  die();
endif;

if( ! empty( $provider ) && $hybridauth->isConnectedWith( $provider ) ):
  $return_to .= "/?connected_with=" . $provider;
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
endif;

if( ! empty( $provider ) ):
  if( isset( $_REQUEST["redirect_to_idp"] ) ):
    $adapter = $hybridauth->authenticate( $provider, $params );
//    die();
    // here we display a "loading view" while tryin to redirect the user to the provider
  endif;
endif;
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
die();
?>