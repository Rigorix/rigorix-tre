<div class="test-box ui-corner-all">
	<form name="bug_report_form" method="post" action="bug_report_form.php" target="dialog_window">
	<input type="hidden" name="bug_reporting" value="true" />
	<input type="hidden" name="id_utente" value="<?php echo $user->obj->id_utente; ?>" />
	<input type="hidden" name="reporting_from" value="<?php echo $_SERVER["PHP_SELF"]; ?>" />
	<button name="post_bug_report" class="rx-ui-button">BUG REPORT</button>
	</form>
</div>


<script>
$('[name=post_bug_report]').click ( function() {
	var dialog = Game.loadDialog ({
		w: 400,
		h: 400,
		title: "Bug reporting",
		src: '/bug_report_form.php?bug_reporting',
		onFinishLoad: function() {
			$('[name=bug_report_form]').submit();
		}
	});
	return false;
});
</script>
