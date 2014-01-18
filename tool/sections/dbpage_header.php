<div class="row mbm" xmlns="http://www.w3.org/1999/html">
	<div class="col-6">
		<h3 class="man"><?php echo ucfirst ($_SESSION["rigorix"]["section"]); ?></h3>
	</div>
    <div class="col-6 text-right">
        <form class="form-inline" role="form" action="?<?php echo get_current_url(); ?>" method="get">
            <?php echo get_current_url() . "--"; ?>
            <div class="form-group col-lg-4 pull-right">
                <input placeholder="Search..." name="search-query" id="search-box" class="form-control" content-table="<?php echo $_SESSION["rigorix"]["section"]; ?>" />
            </div>
            <button class="btn">Search</button>
        </form>
    </div>
<!--    <div class="col-6 text-right">-->
<!--        <button class="btn btn-info"><span class="glyphicon glyphicon-plus-sign"></span>Add new item</button>-->
<!--    </div>-->
</div>