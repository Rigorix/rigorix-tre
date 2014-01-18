<?php
require_once("classes/core.php");
require_once("static/html_start.php");
?>

    <div class="row">
        <div class="col-12">
            <?php require_once("sections/navigation.php"); ?>
        </div>
    </div>

	<?php
	if ( isset( $_REQUEST["section"]) )
		register_section ($_REQUEST["section"]);
	
	if ( isset ( $_SESSION["rigorix"]["section"] ) ): ?>
		<!-- div class="row">
			<div class="col-6">
				<h3 class="man"><?php echo ucfirst ($_SESSION["rigorix"]["section"]); ?></h3>
			</div>
			<div class="col-6 text-right">
				<button class="btn btn-info"><span class="glyphicon glyphicon-plus-sign"></span>Add new item</button>
			</div>
		</div -->
		<div class="row">
			<div class="col-12">
                <!-- start section php include -->
				<?php require_once ("sections/" . $_SESSION["rigorix"]["section_page"]); ?>
                <!-- end section php include -->
			</div>
		</div>
	<?php endif; 

require_once("static/html_stop.php"); ?>