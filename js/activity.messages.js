
/* Messages */
activity.messages = {

	count_per_page: 10,
	act_page: 1,
	message_open: false,

	init: function()
	{
		this.set_status ();
		this.set_interaction ();
	},

	set_status: function()
	{
		this.total = $('.paginator-total').text();
		this.total_pages =  Math.ceil(this.total / this.count_per_page);
		this.start_filter = ((this.act_page-1) * this.count_per_page + 1);
		this.end_filter = (this.act_page * this.count_per_page);
		if (this.end_filter > this.total)
			this.end_filter = this.total;
		$('.go-next-message').hide();
		$('.go-prev-message').hide();
		if (this.total > this.count_per_page && this.act_page < this.total_pages) {
			$('.go-next-message').show().unbind("click").bind("click", function() {
				activity.messages.act_page++;
				activity.messages.set_status ();
				activity.messages.get_filtered_messages ( activity.messages.start_filter-1, activity.messages.end_filter);
			});
		}
		if ( this.act_page > 1 )
			$('.go-prev-message').show().unbind("click").bind("click", function() {
				activity.messages.act_page--;
				activity.messages.set_status ();
				activity.messages.get_filtered_messages ( activity.messages.start_filter-1, activity.messages.end_filter);
			});
		$('.paginator-page').html ( this.start_filter + ' - ' + this.end_filter  );
	},

	set_interaction: function()
	{
		/*
		 * 	User messages / write
		 */
		$('.message-opener').unbind("click").bind("click", function() {
			var message_row = $(this).parents("tr");
			if ( $(message_row).hasClass("unread")) {
				$(message_row).removeClass("unread");
				var tot_m = new Number($('.count-unread-messages').text()) - 1;
				if (tot_m > 0) {
					$('.count-unread-messages').html( tot_m );
					$('.tornei_num_messaggi').html( tot_m );
				} else {
					$('.count-unread-messages').parent().hide();
					$('.tornei_num_messaggi').hide();
				}

			}
			if ( $(this).parent().find (".message-container").size() == 0) {
				activity.messages.message_open = true;
				$(this).parent().append ( $('<div class="message-container"><br />Caricamento messaggio...<br /><br /></div>'));
			} else {
				activity.messages.message_open = false;
				$(this).parent().find (".message-container").remove();
			}

			var messContainer = $(this).parent().find (".message-container");
			messContainer.data ("id_mess", $(this).attr ("id")).load ( 'classes/responder.php?action=readMessage&id_mess=' + $(this).attr ("id").split("msg_")[1], function () {
				messContainer.append ( '<div class="message-container-actions">' + ($(message_row).attr ("staff") == "false" ? '<button name="message-reply" class="button-small">Rispondi</button> <button name="message-ignore" class="button-small">Ignora utente</button>':'') + ' <button name="message-delete" class="button-small">Cancella</button></div>');
				$(messContainer).find("[name=message-reply]").unbind("click").bind("click", function() {
					Game.loadDialog ( {
						w: 450,
						h: 200,
						mode: "static",
						src: '/boxes/message_write.php?reply=true&id_mess=' + $(messContainer).data ("id_mess").split ("msg_")[1],
						title: 'Rispondi al messaggio'
					});
				});
				$(messContainer).find("[name=message-ignore]").unbind("click").bind("click", function() {
					Game.confirm ( {
						title: "Conferma richiesta",
						txt: 'Sicuro di voler ignorare questo utente?<br />Cos� facendo non riceverai pi� suoi messaggi.<br /><br />Ti sar� comunque possibile riabilitarlo dalla pagina impostazioni.',
						buttons: {
							"OK": function() { activity.messages.ignore_user ( messContainer.data ("id_mess")); },
							"Annulla": function() { $(this).dialog ("close"); }
						}
					});
				});
				$(messContainer).find("[name=message-delete]").unbind("click").bind("click", function() {
					id_mess = $(messContainer).data ("id_mess").split ("msg_")[1];
					row = $(this).parents("tr");
					Game.confirm ( {
						title: "Conferma richiesta",
						txt: 'Sicuro di voler cancellare il messaggio?',
						buttons: {
							"OK": function() {
								$.post( 'classes/responder.php?activity=delete_message&id_mess=' + id_mess, function(xhr) {
									eval ("var r = " + xhr);
									if (r.status == "OK") {
										$(row).remove();
									}
									Game.throwGenericRequestResult (xhr);
								});
							},
							"Annulla": function() {
								$(this).dialog ("close");
							}
						}
					});
				});
				$('.message-container-actions button').button ();
			} );
		});

		$('[name=open-write-message-dialog]').unbind("click").bind("click", function() {
			Game.loadDialog ( {
				w: 450, h: 200,
				mode: "static",
				src: '/boxes/message_write.php' + ( $(this).attr ("id_destinatario") != null ? "?id_destinatario=" + $(this).attr ("id_destinatario") : ""),
				title: 'Scrivi nuovo messaggio'
			});
		});

		$('[name=write-message-send]').unbind("click").bind("click", function() {
			if ($('[name=oggetto]').val() != '' && $('[name=destinatario]').val() != '') {
				$.post('/classes/responder.php?activity=write_message', $('[name=write_message_form]').serialize(), function(xhr){
					Game.throwGenericRequestResult(xhr);
				});
			}
			else {
				Game.throwError("Devi inserire sia il destinatario sia l'oggetto del messaggio");
			}
		});

		$('[name=messages-selector]').unbind("click").bind("click", function() {
			activity.messages.select_all_messages ( $(this).attr("checked") );
		});

		$('[name=markread-selected-messages]').unbind("click").bind("click", function() {
			var read_ids = '';
			$('.messages-table tr input:checked').each (function() { read_ids += "," + ( $(this).parents("tr").find (".message-opener").attr ("id").split ("msg_")[1])});
			read_ids = read_ids.substr (1, read_ids.length-1);
			$.post('/classes/responder.php?activity=multimark_as_read&ids=' + read_ids);
		});

		$('[name=reload-message-list]').click ( function( ){
			activity.messages.refresh_view();
		});

		$('[name=delete-selected-messages]').unbind("click").bind("click",function() {
			if ($('.messages-table input[type=checkbox]:checked').size() > 0) {
				var id_mess = '';
				$('.messages-table input[type=checkbox]:checked').each ( function() {
					id_mess += $(this).parents("tr").attr("id_mess") + ',';
				});
				Game.confirm({
					title: "Conferma richiesta",
					txt: 'Sicuro di voler cancellare il/i messaggio/i?',
					buttons: {
						"OK": function(){
							$.post('classes/responder.php?activity=delete_message&id_mess=' + id_mess, function(xhr){
								Game.throwGenericRequestResult(xhr);
								if ( xhr.status == 'OK') {
									$('.messages-table input[type=checkbox]:checked').each ( function() {
										$(this).parents("tr").remove();
									});
									activity.messages.refresh_view();
								}
							});
						},
						"Annulla": function(){
							$(this).dialog("close");
						}
					}
				});
			}
		});
	},

	get_filtered_messages: function ( start, end )
	{
		$(".messages-table-body").html("").load ( "/classes/responder.php?action=get_filtered_messages&start=" + start + "&end=" + end, function() {
			activity.messages.init ();
		} );
	},

	ignore_user: function ( id_mess )
	{
		var id_mess = id_mess.split( "msg_" )[1];
		$.post('classes/responder.php?activity=ignore_user_messages&id_mess=' + id_mess, function(xhr) {
			Game.throwGenericRequestResult(xhr);
		});
	},

	select_all_messages: function ( status )
	{
		$('.messages-table input[type=checkbox]').attr("checked", status);
	},

	send_pm: function ( id_utente )
	{
	    Game.loadDialog ( {
            w: 450, h: 200,
            src: '/boxes/message_write.php?id_destinatario=' + id_utente,
            title: 'Scrivi nuovo messaggio'
        });
	},

	refresh_view: function ()
	{
    	$('.area_personale_tab').tabs ("load", activity.ui.active_panel_index );
	}

}
