
var activity = {

	TIME: {
		SHORT: 		15,
		NORMAL: 	.1 * 60,
		LONG: 		30 * 60
	},

	onBeforeStart: [],
	onLoadComplete: [],
	timedEvents: {
		"SHORT": [
			/*
			 * Moved to service
			 function () {	// Check punteggio sempre aggiornato
				$.get('/classes/responder.php?action=get_user_points', function(n){
					$("#punteggioUtente").html(n);
				});
			}*/
		],
		"NORMAL": [],
		"LONG": []
	},

	run: function()
	{
		/*
		 * Set timed events
		 */
		this.event_trigger ( "onBeforeStart" );

		setInterval ( function() { activity.event_trigger ( "timedEvents", "SHORT" ); }, (activity.TIME.SHORT * 1000));
		setInterval ( function() { activity.event_trigger ( "timedEvents", "NORMAL" ); }, (activity.TIME.NORMAL * 1000));
		setInterval ( function() { activity.event_trigger ( "timedEvents", "LONG" ); }, (activity.TIME.LONG * 1000));

		this.set_action_queue ();

		/*
		 * 	Eseguo le attivita' in coda
		 */
		if ($(action_queue).size() > 0)
			$(action_queue).each(function() {
				($(this))();
			});

		this.run_internal ();

		/*
		 * 	Eseguo le attivita' in coda finale
		 */
		if ($(end_action_queue).size() > 0)
			$(end_action_queue).each(function(j, act) {
				(act)();
			});

		this.event_trigger ( "onLoadComplete" );
	},

	run_internal: function ()
	{

		/*
		 * 	Dialog add/edit picture
		 */
		$('[name=add-profile-picture]').click ( function() {
			Game.loadDialog ( {
				w: 500,
				h: 250,
				src: '/boxes/dialog_add_picture.php',
				title: 'Carica immagine profilo'
			});
		});
		$('[name=picture-uploader]').change(function() {
			if ($(this).val() != '') {
				var file_type = $(this).val().split(".")[1].toLowerCase();
				if (file_type != 'gif' && file_type != 'jpg' && file_type != 'png')
					Game.throwError("Il file da caricare dev'essere in formato jpg, gif o png!");
				else {
					$('[name=user-picture-thumb]').attr ("src", $(this).val());
					$('.load-action').removeClass("hidden");
				}
			}
		});
		$('[name=edit-profile-picture]').click ( function() {
			Game.loadDialog ( {
				w: 400,
				h: 250,
				src: '/boxes/dialog_edit_picture.php',
				title: 'Cambia immagine profilo'
			});
		});


		/*
		 * 	Start activity library
		 */
		activity.messages.init();
		activity.sfide.init();
		activity.ui.init();


		$(".rx-ui-search-user").autocomplete({
			source: "/classes/responder.php?action=getUserList",
			minLength: 2
		});


		/*
		 * 	Password recovery
		 */
		$('[name=password-recovery]').click (function() {
			if ( $('[name=username]').val () != '')
				$.get('/classes/responder.php?activity=password_recovery', $('[name=password_recovery_form]').serialize(), function(xhr){
					Game.throwGenericRequestResult (xhr);
				});
			else
				Parent.Game.throwError ( "Devi inserire il nickname prima di chiedere il recupero password" );
		});

		/*
		 * 	External link
		 */
		$(".external-link").click(function() {
			Game.loadStaticPageDialog($(this).attr("href"));
			return false;
		});



		/*
		 * 	Tab riepilogo
		 */
		function viewLastPlayoff(id_playoff)
		{
			$(win).html('<iframe width="645" height="430" src="responders/view_playoff.php?id_playoff=' + id_playoff + '"></iframe>');
			$(win).dialog( "open" );
		}

		function vediFotofinish(data)
		{
			$(win).html('<iframe width="480" height="460" src="responders/vedi_fotofinish.php?data=' + data + '"></iframe>');
			$(win).dialog( "open" );
		}

		function vediArchivioCampionati()
		{
			$(win).html('<iframe width="480" height="460" src="responders/archivioCampionati.php"></iframe>');
			$(win).dialog( "open" );
		}
	},

	set_action_queue: function ()
	{
		if ( $.urlParam('action_queue') ) {
			switch ($.urlParam('action_queue')) {

				case 'ERROR_ON_CONFIRM_SUBSCRIPTION':
					Game.throwError ( "C'&egrave; stato un errore durante la conferma della tua iscrizione.<br /><br />Riprova e, se l'errore persiste, contatta i nostri tecnici.<br /><br />Rigorix Staff");
					break;

				case 'CONFIRM_SUBSCRIPTION_OK':
					Game.throwSuccess ( "La tua registrazione &egrave; andata a buon fine.<br /><br /><!--Sei gi&agrave; stato autenticato al sito e sei pronto per<br />giocare con noi.<br /><br />-->Rigorix Staff");
					break;

				case 'CONFIRM_UNSUBSCRIPTION_OK':
					Game.throwSuccess ( "La tua disiscrizione &egrave; andata a buon fine.<br /><br />Ti ringraziamo per aver giocato con noi!<br /><br />Rigorix Staff");
					break;

			}
		}
	},

	event_trigger: function ( event_name, sub_event_name )
	{
		$( sub_event_name ? this[event_name][sub_event_name] : this[event_name]).each ( function(j) {
			this();
		});
	},

	register_event: function ( event_trigger, event_name, event_action )
	{
		if ( activity[event_trigger] )
			activity[event_trigger][event_name].push ( event_action );
	}

}
