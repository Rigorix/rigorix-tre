/* Tornei */

activity.settings = {

	force_reload: false,

	init_update_data_form: function()
	{
		$('[name=indb_data_nascita]').datepicker({
			changeYear: true,
			changeMonth: true,
			dateFormat: "dd/mm/yy",
			defaultDate: '21/07/1980'
		});

		$('[name=aggiornamento-dati-utente]').unbind("click").bind ("click", function() {
			activity.ui.set_button_status ( $(this), "LOADING" );
			if ( Utils.form.validate ($('[name=aggiorna_utente]')) != false ) {
				$.post( 'classes/responder.php?activity=update_user_data', $('[name=aggiorna_utente]').serialize(), function(xhr) {
					Game.throwGenericRequestResult (xhr);
					activity.ui.set_button_status ( $('[name=aggiornamento-dati-utente]'), "LOADING_DONE" );
					if ( activity.settings.force_reload == true && $.parseJSON (xhr).status == "OK")
						window.location.reload ();
				});
			} else
				activity.ui.set_button_status ( $('[name=aggiornamento-dati-utente]'), "LOADING_DONE" );
		});
	},

	on_load_profile_picture: function()
	{
		$(win).html ('<img src="/i/ajax-loader.gif" />');
		var intID = setInterval(function() {
			if (ServiceWindow.document.body.innerHTML != '') {
				clearInterval(intID)
				eval ("var xhr = " + ServiceWindow.document.body.innerHTML);
				if (xhr.status == 'OK') {
					GameWindow.document.getElementById ('profile_picture').src = "i/profile_picture/" + xhr.filename;
					GameWindow.document.getElementById ('profile_picture_big').src = "i/profile_picture/" + xhr.filename;
				}
				Game.throwGenericRequestResult(ServiceWindow.document.body.innerHTML);
			}
		}, 500);
		$('[name=torneo-picture-form]').submit();
	},

	init_mascotte_form: function()
	{
		$('#setup_cloth_color').change (function() {
			$('#setup_maglia').css ("background-color", $(this).val())
		});
		var color_picker = $.farbtastic('#picker');
		color_picker.linkTo ( $('#setup_cloth_color') );
		var prev_coloring = $('#cloth_selector .ui-state-focus').attr("id");

//		$('#cloth_selector').buttonset();
//		$('#shirt_selector').buttonset();

		$('#cloth_selector button').click (function () {
		  $("#cloth_selector button").removeClass ("active");
		  $(this).addClass ( "active");
		});

		$('#shirt_selector button').click ( function() {
            $("#shirt_selector button").removeClass ("active");
            $(this).addClass ( "active");
			$('#setup_maglietta img').attr("src", "i/maschera_maglia_" + $(this).attr("id").split("type")[1] + ".png");
			$('[name=indb_tipo_maglietta]').val ($(this).attr("id").split("type")[1]);
		});

		var prev_num = $('[name=indb_numero_maglietta]').val();
		$('[name=indb_numero_maglietta]').change ( function() {
			if ( !isNaN ($(this).val()) && $(this).val() != '' ) {
				$('#setup_numero').html($(this).val());
				$('#setup_numero_shadow').html($(this).val());
				prev_num = $(this).val();
			}
			else {
				Game.throwError("Il numero della maglietta <br />deve, ovviamente, essere numerico");
				$('[name=indb_numero_maglietta]').val( prev_num );
			}
		});

		setInterval( function() {
			if ( $('#cloth_selector .active').size() > 0) {
				if ( prev_coloring != $('#cloth_selector .active').attr("id") ) {
					color_picker.setColor ( $('[name=indb_colore_' + $('#cloth_selector .active').attr("id") + ']' ).val() );
					prev_coloring = $('#cloth_selector .active').attr("id");
				}
				$('#setup_' + $('#cloth_selector .active').attr("id")).css ("background-color", $('#setup_cloth_color').val())
				$('[name=indb_colore_' + $('#cloth_selector .active').attr("id") + ']' ).val ( $('#setup_cloth_color').val());
			}
		}, 200);

		$('[name=aggiornamento-mascotte]').click ( function() {
			activity.ui.set_button_status ( $('[name=aggiornamento-mascotte]'), "LOADING" );
			$.post( 'classes/responder.php?activity=update_mascotte', $('[name=aggiorna_mascotte]').serialize(), function(xhr) {
				Game.throwGenericRequestResult (xhr);
				activity.ui.set_button_status ( $('[name=aggiornamento-mascotte]'), "LOADING_DONE" );
			});
		});
	},

	init_unsubscribe_form: function()
	{
		$('[name=cancellazione]').click ( function() {
			Game.confirm ( {
				txt: "Sei sicuro di voler cancellare il tuo utente?",
				buttons: {
					"CANCELLAMI": function() {
						activity.ui.set_button_status ( $('[name=cancellazione]'), "LOADING" );
						$.post( 'classes/responder.php?activity=unsubscribe_user', function(xhr) {
							Game.throwGenericRequestResult (xhr);
							activity.ui.set_button_status ( $('[name=cancellazione]'), "LOADING_DONE" );
						});
					},
					"ANNULLA": function() {
						$(error_win).dialog ("close");
					}
				},
				title: "Cancellazione utente"
			});
		});
	}
}
