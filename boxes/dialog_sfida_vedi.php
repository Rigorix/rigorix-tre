<?php 
chdir("../");
require_once('classes/core.php');
require_once('boxes/dialog_start.php');

$include_path_override = true;
$sfida = $activity->create_sfida_obj ( $_REQUEST['id_sfida'] );
$_REQUEST["backType"] = ( !isset ($_REQUEST["backType"]) ) ? '' : $_REQUEST["backType"];
?>
<script language="javascript" type="text/javascript">
document.write ( activity.ui.get_sfida_swf_code ( { 'id_sfida': '<?php echo $sfida->id_sfida; ?>', 'back_type': '<?php echo $_REQUEST["backType"]; ?>' } ) );
</script>

<?php
require_once('boxes/dialog_end.php');
?>
