/*
 * Jquery library - Rigorix CORE
 */

var action_queue = [];
var end_action_queue = [];
var win = false;
var parent_win = false;
var Parent = {}
var DialogWindowFrame = false;
var ServiceWindow = false;

$(document).ready(function(){
	/* Create Dialog */
	win = $("<div id='rx_dialog'></div>").dialog({
		autoOpen: false,
		modal: true,
		title: "Rigorix",
		maxWidth: '1000',
		resizable: false,
		buttons: {
			"Chiudi": function() {
				win.dialog("close");
			}
		}
	});
	$(win).dialog( "option", "height", "auto" );
	$(win).dialog( "option", "width", "auto" );

	/* Create Buttons */
	button_win = $("<div id='rx_dialog_button'></div>").dialog({
		autoOpen: false,
		modal: false,
		dialogClass: "ui-button-dialog",
		maxWidth: '600',
		resizable: false,
		buttons: {
			"Chiudi": function() {
				win.dialog("close");
			}
		}
	});


	window.win = $(win);
	ServiceWindow = top.service_window;
	GameWindow = window.top;

	/* Create ERROR Dialog */
	error_win = $("<div></div>").dialog({
		autoOpen: false,
		modal: true,
		title: "ERRORE",
		dialogClass: "ui-state-error",
		maxWidth: '1000',
		resizable: false,
		buttons: {
			"Chiudi": function() {
				error_win.dialog("close");
			}
		}
	});
	$(error_win).dialog( "option", "height", "auto" );
	$(error_win).dialog( "option", "width", "auto" );

	/*
	 * 	Buttons
	 */

	$(".rx-box-loader").each( function() {
		$(this).data ("href", $(this).attr("href")).data ("title", $(this).attr("title"))
	});
	$(".rx-box-loader").click( function() {
		var setup = { src: $(this).data("href"), title: $(this).data("title") };
		if ( $(this).hasClass ("mode-html"))
			setup.mode = 'static';
		Game.loadDialog ( setup );
	});
	$(".rx-box-loader").removeAttr ("href")

	//var selected = $tabs.tabs('option', 'selected'); // => 0

	$(".rx-page-loader").each(function() {
		$(this).attr("page-ref", $(this).attr("href"));
		$(this).removeAttr("href").css("cursor", "pointer");
		$(this).click(function() {
			Game.loadStaticPageDialog($(this).attr("page-ref"), function(xhr) {
				if ($(win).find('.static-page-dialog-content h1').size() > 0)
					$(win).dialog( "option", "title", $(win).find('.static-page-dialog-content h1').html());
				$(win).find('.static-page-dialog-content h1').remove();
			});
			return false;
		});
	});

	/* Game loaded */
	$(".rx-loading-panel-progress").progressbar({ value: 90 });
	setTimeout(function() {
		$(".rx-loading-panel").remove();
		$("#page").removeClass("rx-loading");
	}, 300);

	/*
	 * 	dialog container resize
	 */
	if (window.top.name != window.name && window.name == 'dialog_window') {
		// Pagina caricata in un dialog
		Parent.Game = window.top.Game;
		Parent.activity = window.top.activity;
		Parent.win = window.top.win;
		if ( $('.dialog-dimensions-rewriter').size() > 0) {
			var w = ($('.dialog-dimensions-rewriter').width() + 20) + "px";
			var h = ($('.dialog-dimensions-rewriter').height() + 30) + "px";
			window.top.document.getElementById ('rx_dialog').style.width = window.top.document.getElementById ('rx_dialog').getElementsByTagName("iframe")[0].style.width = w;
			window.top.document.getElementById ('rx_dialog').style.height = window.top.document.getElementById ('rx_dialog').getElementsByTagName("iframe")[0].style.height = h;
			Parent.win.dialog("option", "position", "center");
		}
	}

	$.urlParam = function(name) {
		var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
		if (!results)
			return false;
		return results[1] || false;
	}

	activity.run();
	if ( dev == true )
		Dev.init ();
});

var Game = {
	responder: '/classes/responder.php',

	error_win_default: {
		width: 'auto',
		height: 'auto',
		buttons: {
			"Chiudi": function () { $(this).dialog("close"); }
		}
	},
	confirm_win_default: {
		width: 'auto',
		height: 'auto',
		title: 'Conferma richiesta',
		buttons: {
			"OK": function () { },
			"Annulla": function () { $(this).dialog("close"); }
		}
	},

	loadStaticPageDialog: function(KEY, options, callback)
	{
		$.getJSON ( Game.responder + '?action=get_page&key=' + KEY, function( xhr ) {
			$(win).html( xhr.content );
			$(win).dialog( "option", "width", 670 );
			$(win).dialog( "option", "height", 500 );
			$(win).dialog( "option", "title", xhr.title );
			if (options != null && typeof options == 'object')
				$(win).dialog( "option", options );
			if (typeof options == 'function')
				callback = options;
			$(win).dialog( "open" );
			if (typeof callback == 'function')
				(callback)();
		});
	},

	throwError: function( msg )
	{
		if ( typeof msg == 'string' ) {
			$(error_win).dialog ( "option", Game.error_win_default)
			$(error_win).html(msg).dialog("open");
		}
		activity.ui.unlock_elements ();
	},

	throwSuccess: function( msg )
	{
		if (typeof msg == 'string') {
			$(error_win).dialog ("close");
			$(win).html(msg).dialog("open");

			if ( GameWindow.action_after_success ) {
				$(win).bind( "dialogclose", function(event, ui) {
					(GameWindow.action_after_success)();
				});
			}
		}
		activity.ui.unlock_elements ()
		Game.centerDialog ( $(win) );
	},

	dialog: function( msg )
	{
		if (typeof msg == 'string') {
			$(error_win).dialog ("close");
			$(win).html(msg).dialog("open");
		}
	},

	throwGenericRequestResult: function(xhr)
	{
		eval ("var xhr = " + xhr);
		if (xhr.status == 'KO' || xhr.status == 'KO-generic')
			Game.throwError (xhr.text);
		if (xhr.status == 'OK') {
			if (parent.Game)
				parent.Game.throwSuccess(xhr.text);
			else
				Game.throwSuccess(xhr.text);

		}
		activity.ui.unlock_elements ();
		Game.centerDialog ( $(win) );
	},

	confirm: function( conf )
	{
		var setup = Game.confirm_win_default;
		if ( typeof conf == 'object') {
			if (!conf.w)
				conf.w = 'auto';
			if (!conf.h)
				conf.h = 'auto';
			$(error_win).dialog( "option", "title", conf.title );
			if (conf.buttons)
				$(error_win).dialog("option", "buttons", conf.buttons);
			if (conf.txt)
				$(error_win).html ( conf.txt )
			$(error_win).dialog( "open" );
		}
	},

	closeConfirm: function ()
	{
	    $(error_win).dialog( "close" );
	},

	centerDialog: function ( win )
	{
		var popup = $(win).parents(".ui-dialog:first");
		$(popup).css ( "left", ($(document.body).width() - $(popup).width()) / 2 + "px");
		$(popup).css ( "top", ($(document.body).height() - $(popup).height()) / 2 + "px");
	},

	loadDialog: function( setup )
	{
		if (!setup.w)
			setup.w = 'auto';
		if (!setup.h)
			setup.h = 'auto';
		if (setup.buttons)
			$(win).dialog("option", "buttons", setup.buttons);
		else
			$(win).dialog("option", "buttons", {
				"Chiudi": function() {
					win.dialog("close");
				}
			});
		if ( setup.dialogClass )
		  $(win).dialog("option", "dialogClass", setup.dialogClass);
		if ( setup.mode == 'static')
			$(win).load( setup.src, function() {
				activity.run_internal();
				Game.centerDialog ( $(win) );
				if ( setup.onFinishLoad )
					(setup.onFinishLoad)();
			} );
		if (setup.title)
			$(win).dialog( "option", "title", setup.title );
		$(win).dialog ("open");
		if ( setup.mode != 'static')
			$(win).html('<iframe name="dialog_window" width="'+setup.w+'" height="'+setup.h+'" src="'+setup.src+'"></iframe>');
		setTimeout ( function () {
			Game.centerDialog ( $(win) );
		}, 5);
		return win;
	},

	unloadDialog: function()
	{
	    if (DialogWindowFrame != false)
            $(DialogWindowFrame).remove();
	    $(win).dialog( "close" );
	},

	loadButtonMenu: function( setup )
	{
		$(button_win).dialog( "option", "width", "auto" );
		$(button_win).dialog( "option", "height", "auto" );
		$(button_win).dialog("option", "buttons", setup.buttons);
		if ( setup.position && setup.position.left ) {
			$(button_win).dialog( "option", "position", [(setup.position.left + 50), (setup.position.top-8-$(document.body).scrollTop())] );
		}
		if (setup.title)
			$(button_win).dialog( "option", "title", setup.title );
		$(button_win).dialog( "open" );
	},

	away : function ()
	{
		if ( $(".imAway").size () == 0 && "dio" == "cane" )
			$("<div style='font-size: 20px; min-height: auto !important' align='center'>... ZZZzzzZZZZzzzZZZzz ...</div>").dialog ({
				modal: true,
				title: false,
				resizable: false,
				dialogClass: "imAway",
				buttons: {
					"Sono tornato": function () {
						$(this).dialog ("close");
					}
				},
				open: function( event, ui ) {
					$(".ui-widget-overlay").css ("opacity", ".8");
					$(".ui-dialog .ui-dialog-buttonpane").css ("text-align", "center");
					$(".ui-dialog .ui-dialog-buttonpane button").css ("float", "none").css ("margin", "5px 0")
					$(".imAway .ui-dialog-titlebar").remove ();
				},
				close: function (ev, ui) {
					top.service_window.location.href = "classes/service.php?awake";
					$(this).remove ();
				}
			});
	}

}

var Utils = {

	isValidEmail: function( email )
	{
		var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
		return filter.test(email);
	}

}


Utils.date = {

	isValidStringDate: function( dta )
	{
		if (dta.indexOf("/") > -1) {
			var date = dta.split("/");
			if (date.length != 3)
				return false;
			else {
				if (date[0].length != 2 || isNaN(date[0]) || date[0] == '00')
					return false;
				if (date[1].length != 2 || isNaN(date[1]) || date[0] == '00')
					return false;
				if (date[2].length != 4 || isNaN(date[2]) || date[0] == '0000')
					return false;
			}
			return true;
		} else
			return false;
	},

	getTodayStringDate: function()
	{
		var date = new Date();
		return date.getDate() + "/" + (date.getMonth() + 1) + "/" + date.getFullYear();
	},

	getCustomDate: function( cdays )
	{
		var now = new Date().getTime();
		var d = new Date ( now + ( cdays * (24 * 60 * 60 * 1000)));
		return d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear();
	}

}

Utils.form = {

	validate: function ( form )
	{
		var elements = $(form).serializeArray ();
		for (var i=0; i<elements.length; i++) {
			ret = Utils.form.validate_field ( elements[i] );
			if (ret == false)
				return false;
		};
		return true;
	},

	validate_field: function ( elem )
	{
		elem.field = $("[name=" + elem.name + "]");
		if ( typeof $(elem.field).attr("validate_as") != "undefined" ) {
			var validate_as = $(elem.field).attr("validate_as");
			var validation_rules = validate_as.split (",");
			elem.error = $(elem.field).attr("validate_error_text");
			for (var i=0; i< validation_rules.length; i++) {
				var rule = validation_rules[i];
				if ( typeof elem.error == "undefined" )
					elem.error = Utils.form.validation_error[rule];
				switch ( rule ) {
					case 'date':
						if (!Utils.date.isValidStringDate(elem.value) && elem.value != '') {
							Game.throwError( elem.error );
							return false;
						}
						break;

					case 'email':
						if (!Utils.isValidEmail(elem.value)) {
							Game.throwError( elem.error );
							return false;
						}
						break;

					case 'mandatory':
						if ( elem.value == '') {
							Game.throwError( elem.error );
							return false;
						}
						break;

					case 'number':
						if ( isNaN (elem.value)) {
							Game.throwError( elem.error );
							return false;
						}
						break;

					case 'not':
						return false;
						break;

					case 'default':
						return true;
						break;
				}
			}
		}
		return true;
	},

	validation_error: {
		mandatory: 		"Tutti i campi con <sup>*</sup> devono essere compilati",
		email: 			"Il campo Email non e' in un formato valido",
		date: 			"Il campo data non e' in un formato valido",
		number: 		"I campi numerici devono avere solo numeri"
	}

}


var Dev = {

	init: function ()
	{
		$('[name=reset-session-log]').click ( function() {
			$.get ( "/classes/responder.php?action=reset-session-log" );
			$('.debug-log').html("");
		});
		$('[name=reset-session]').click ( function() {
			$.get ( "/classes/responder.php?action=reset-session" );
		});
	}

}
