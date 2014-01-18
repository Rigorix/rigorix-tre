<?php
if ( isset ($_REQUEST["awake"]) ) {
	chdir("../");
	require_once('classes/core.php');
	$user->update_data_activ ();
	header ("Location: /classes/service.php");
}
session_start();
if ( !isset ($_SESSION["rigorix"]["user"]) || $_SESSION["rigorix"]["user"] === false )
	exit();
$UserObject = $_SESSION["rigorix"]["user"];
$UserActivityDate = $UserObject->dta_activ;

if ( time() - strtotime ($UserActivityDate) > 600 ) {
	$UserObject->away = true; ?>

	<html>
	<head>
	</head>
	<body>
		<script>
			console.log ("sleep")
			top.window.Game.away();
			/*
			 * Set refresh time
			 */
			setTimeout ( function () {
				window.location.reload ();
			}, 60000);
		</script>
	</body>
	</html>

<?php exit; }

chdir("../");
require_once('classes/core.php');
?>
<html>
<head>
	<script src="/js/jquery-1.4.2.pack.js" type="text/javascript"></script>
	<script>var User = <? echo ( is_object($user->obj) ? $utility->print_json ( $user->obj ) : 'false' ); ?>;console.log ("awake")</script></head>
<body>
<?php
$UserScore = $user->get_points ( $user->obj->id_utente );
$UserOpenMatches = count ( $user->obj->sfide_da_giocare );
$UserMessages = count ( $dm_messaggi->getArrObjMessaggiUnread ( $user->obj->id_utente ) );
$SfideNonNotificate = $dm_sfide->getSfideNonNotificateByIdUtente ( $user->obj->id_utente );
$UsersOnline = $core->storage['NUMERO_UTENTI_ONLINE'];
$newBadges = $dm_rewards->getUnotifiedBadgeByIdUtente ( $user->obj->id_utente );
?>


<script>
var $ = top.window.$;
var Application = top.window;
var RefreshTime = 15; // Seconds
var UnotifiedMatches = <?php echo json_encode ($SfideNonNotificate); ?>;

/*
 * Update user score
 * console.log ("<service> Updating user score");
 */$("#punteggioUtente").html("<?php echo $UserScore; ?>");


/*
 * Update open matches
 * console.log ("<service> Updating user open matches");
 */
var tot = new Number(<?php echo $UserOpenMatches; ?> / 1);
if (tot > 0) {
	$("#totSfide").html(tot);
	$("#boxUtenteSfideAperteRow .sfide-row").removeClass( "hidden");
	$('.tornei_num_sfide').show().html(""+tot);
} else {
	$("#boxUtenteSfideAperteRow .sfide-row").addClass ("hidden");
	$('.tornei_num_sfide').hide();
}


/*
 * Check user unread messages
 * console.log ("<service> Updating user unread messages");
 */
var $UserMessagesTot = new Number(<?php echo $UserMessages; ?> / 1);
if ($UserMessagesTot > 0) {
	$('.count-unread-messages').html("" + $UserMessagesTot).parent().show();
	$('.tornei_num_messaggi').show().html("" + $UserMessagesTot);
} else {
	$('.count-unread-messages').parent().hide();
	$('.tornei_num_messaggi').hide();
}


/*
 * Updating messages table
 * console.log ("<service> Updating messages table");
 * console.log ("<service> Messages table present? " + ( $('.messages-table').size() > 0 ));
 */
if ( $('.messages-table').size() > 0 && Application.activity.ui.active_panel_name == "rx-tab-messaggi" ) {
	/*
	 * At the moment, there is the refresh button, so no need to refresh the table.
	 * Anyway, the feature is ready to be used
	 */
	//Application.activity.messages.get_filtered_messages ( Application.activity.messages.start_filter-1, Application.activity.messages.end_filter);
}


/*
 * Update user online
 */
$("#CountOnlineUsers").html (<?php echo $UsersOnline; ?>);


/*
 * Show unotified matches
 */
<?php if ( count ($SfideNonNotificate) > 0 ): ?>

	Application.Toaster.removeAll ();
	var Toaster = Application.Toaster.get ();

	<?php for ( $i=0; $i<count ($SfideNonNotificate); $i++ ): $dm_sfide->updateSfidaNotificata ($SfideNonNotificate[$i]->id_sfida); ?>

		var Match = UnotifiedMatches[<?php echo $i; ?>];
		var Notice = '<ul class="notifica-sfida">';
		Notice += '<?php echo $user->print_user_row ( $SfideNonNotificate[$i]->id_sfidato ); ?>';
		Notice += '<li>ha giocato la tua sfida! Hai ' + ( Match.id_vincitore == User.id_utente ? "vinto" : (Match.id_vincitore == 0) ? "pareggiato" : "perso" ) + ' per ' + Match.risultato.split (",").join (" a ") + '. <a href="#'+Match.id_sfida+'" class="vedi-sfida-button" id_sfida="'+Match.id_sfida+'"> Vedi sfida</a></li>';
		Notice += "</ul>";
		Toaster.add ( Notice );

		$(".vedi-sfida-button").click ( function () {
			var btn_scoper = this;
			Application.Game.loadDialog ({
                w: 635,
                h: 508,
                buttons: {
                    "CHIUDI": function() {
                        Application.Game.unloadDialog ();
                    }
                },
                dialogClass: 'game-view-dialog',
                src: '/boxes/dialog_sfida_vedi.php?id_sfida=' + $(btn_scoper).attr ("id_sfida"),
                title: "Vedi la sfida giocata"
            });
		});

	<?php endfor; ?>

	Toaster.show ();

<?php endif; ?>


/*
 * Show unotified badges
 */
<?php if ( count ($newBadges) > 0 ):
	_log ("NEW BADGE DIALOG", "CALL dialog");
	?>

	Application.Game.loadDialog ({
		w: 400,
        h: 440,
        buttons: {
            "CHIUDI": function() {
                Application.Game.unloadDialog ();
            }
        },
        dialogClass: 'game-view-dialog',
        src: '/boxes/dialog_new_badges.php',
        title: "Hurrraaaaaaaa!!!"
	});

<?php endif; ?>


/*
 * Set refresh time
 */
setTimeout ( function () {
	window.location.reload ();
}, RefreshTime * 1000);

</script>
</body>
</html>


