/* User interface */

activity.ui = {

	managed_tabs: [],
	loading_elements: [],
	active_panel: false,
	active_panel_index: false,
	vedi_sfida_swf_path: '/swf/rigorixGame.swf',

	init: function()
	{

		/* Common elements */
		$(".rx-ui-button").button();
//		$(".list-element").lists ();

		/* Tabs */
		if ($('.area_personale_tab').attr ("loaded") != "true")
			$('.area_personale_tab').tabs({
				selected: $('.area_personale_tab').attr("default"),
				load: function(event, ui) {
					activity.ui.active_panel
					console.log  (ui.panel)
					$('.area_personale_tab').attr ("loaded", "true");
					activity.run_internal();
					activity.ui.init ();
					activity.ui.active_panel = ui.panel;
					activity.ui.active_panel_index = ui.index;
					activity.ui.active_panel_name = $('.area_personale_tab li:eq('+ui.index+')').attr ("name");
					$(".rx-ui-button").button();
				}
			});


		/* User interection */
		jQuery (".rx-username").bind ("contextmenu", function(el) {
			$(document).append ($('<div class="user-menu">asdads</div>'));
			$(".user-menu").position ();
			return false;
		});

		/* png */
		var badBrowser = (/MSIE ((5\.5)|6)/.test(navigator.userAgent) && navigator.platform == "Win32");
		if (badBrowser) {
			// get all pngs on page
			$('img[src$=.png]').each(function() {
				activity.ui.fix_png (this);
			});
		}

        this.set_custom_elements();
		this.reset_center_col ();
		this.init_ui_buttons ();

	},

    set_custom_elements: function ()
    {
        $("[text-fit-horizontal=true]").each ( function (index, element) {

            $(element).css ({
                "white-space": "nowrap",
                "display": "inline-block"
            });
            var startFontSize = 10;

            do {
                startFontSize++;
                $(element).css ("font-size", startFontSize + "px");
                if ( $(element).attr("text-max-size") != null && Number($(element).attr("text-max-size")) <= startFontSize)
                    break;

            }
            while ( $(element).outerWidth() < $(element).parent().outerWidth())

        });
    },

	init_settings: function( k )
	{
		$('.user-settings-tabs').tabs({
			selected: ($('.user-settings-tabs').attr("default") || 0),
			load: function(event, ui) {
				$('.user-settings-tabs').attr ("loaded", "true");
				activity.run_internal();
				activity.ui.init ();
				$(".rx-ui-button").button();
			}
		});
	},

	init_ui_buttons: function ()
	{
	   $('[name=show-user-search-panel]').unbind("click").bind ("click", function() {
	       Game.loadDialog ({
	           w: 600,
	           h: 400,
	           title: "Cerca utenti",
	           src: "/boxes/dialog_user_search.php"
	       });
	   });

	   $("[name=user-link]").unbind("click").bind ("click", function () {
			Game.loadDialog ({
				w: 800,
				h: 490,
				dialogClass: "dialog-no-padding",
				//mode: "static",
				src: '/boxes/dialog_sfida_gioca.php?id_avversario=' + $(this).attr ("id_utente"),
				title: "Lancia la tua sfida"
			});
		});
	},

	set_button_status: function ( button, status )
	{
		$(button).data ("status", status);

		if (status == "LOADING") {
			$(button).attr ("disabled", "true").addClass ("ui-state-disabled");
			$(button).find ("span").prepend ('<span class="ui-icon ui-icon-clock floating"></span>');
			this.loading_elements.push ( $(button) );
		}
		if (status == "LOADING_DONE") {
			$(button).removeAttr ("disabled").removeClass ("ui-state-disabled");
			$(button).find ("span").find (".ui-icon-clock").remove();
			this.loading_elements = $.grep(this.loading_elements, function(value) {
		        return value != $(button);
			});
		}
	},

	unlock_elements: function ()
	{
		$(this.loading_elements).each ( function() {
			$(this).removeAttr ("disabled").removeClass ("ui-state-disabled");
			$(this).find (".ui-icon-clock").remove();
			activity.ui.loading_elements = [];
		})
	},

	enlarge_center_col: function()
	{
		$('.rx-layout-col-right').animate({	width: 0 }, 600);
		$('.rx-layout-col-large').animate({	width: 815 }, 600);
	},

	reset_center_col: function()
	{
		$('.rx-layout-col-right').animate({	width: 205 }, 600);
		$('.rx-layout-col-large').animate({	width: 604 }, 600);
	},

	show_tab: function ( k )
	{
		k = k.split(",");
		$(k).each ( function(j, n) {
			if (activity.ui.managed_tabs.indexOf(n) == -1) {
				activity.ui.show_tab_action (n);
			}
		});
	},

	show_tab_action: function(n)
	{
		var tab_index = $('[name='+n+']').parent().find ("li").index ($('[name='+n+']'));
		if (tab_index > -1) {
			activity.ui.managed_tabs.push(n);
			$('[name=' + n + ']').parent().parent().tabs("select", tab_index);
		}
	},

	init_user_list_interface: function ()
	{
	   // $(".rx-ui-button").button();

	},
	init_gameset_panel: function ()
	{
		for(var i=0; i<5; i++) {
            var row = $('<div class="row-fluid"></div>');
            row.append ( $('<div class="span6 text-center"></div>').append ( this.create_shoot_box (i+1) ) );
            row.append ( $('<div class="span6 text-center"></div>').append ( this.create_keep_box (i+1) ) );

			$('#gameSetPanel').append ( row );
		}

		$('[name=submit-set-colpi-form]').unbind("click").bind ("click", function() {
			var sfida_action = $(this).attr ("sfida_action") == "l" ? 'lancia_sfida' : 'rispondi_sfida';
			activity.ui.set_button_status ( $(this), "LOADING" );
			var status = true;
			for ( var i=0; i < $('[name=set-game-form] input.game_setup_input').size(); i++ ) {
				var input = $('[name=set-game-form] input.game_setup_input')[i];
				if ( $(input).val() == '' )
					status = false;
			}
			if ( status == false )
				Game.throwError ( "Devi impostare tutti i tiri e le parate per proseguire" );
			else
				$.post('/classes/responder.php?activity=lancia_sfida&action=' + sfida_action, $('[name=set-game-form]').serialize(), function(xhr){
					var r = $.parseJSON( xhr );
					if (r.status == "OK") {
						Parent.activity.sfide.reload_sfide_generic ();


						if ( sfida_action == "lancia_sfida" ) {
							top.window.$(".dialog-no-padding").removeClass('dialog-no-padding');
							Game.throwGenericRequestResult(xhr);
						}
						else {
							// Prendo il risultato della sfida, con i punti vinti
							$.get('/classes/responder.php?action=get_sfida_reward&id_sfida=' + $("[name=id_sfida]").val(), function(xhr){
								parent.Game.throwSuccess(xhr);
							});
						}
					}
				})
		});

		$('[name=set-shuffle-sequence]').unbind("click").bind ("click", function() {
			$('.gameSetBox').each ( function(){
				var ind = Math.ceil (Math.random (0,1) * 3) - 1;
				$(this).find ("a:eq("+ind+")").click ();
			});
		});

		$('[name=reset-sequence]').unbind("click").bind ("click", function() {
			$('#gameSetPanel').html("");
			activity.ui.init_gameset_panel ();
		});

		$('.rx-button').button();
	},

	create_shoot_box: function (n)
	{
		var shoot_box = $('<div id="gameSetBox_tiro_'+n+'" class="gameSetBox"></div>');
		$(shoot_box).append ( $('<div title="tiro" class="label label-info">Tiro '+n+'</div>') )
		$(shoot_box).append ( $('<input type="hidden" class="game_setup_input" name="tiro'+n+'" value="" />') );
		$(shoot_box).append ( $('<a class="palloneSx_game" value="0"><img src="/i/lanciaSfide_pallone.png" width="21" height="20" class="png" /></a>') );
		$(shoot_box).append ( $('<a class="palloneCenter_game" value="1"><img src="/i/lanciaSfide_pallone.png" width="21" height="20" class="png" /></a>') );
		$(shoot_box).append ( $('<a class="palloneDx_game" value="2"><img src="/i/lanciaSfide_pallone.png" width="21" height="20" class="png" /></a>') );
		this.apply_shoot_box_actions ( $(shoot_box), true );
		return $(shoot_box);
	},

	apply_shoot_box_actions: function(shoot_box, status)
	{
		if (status == true) {
			$(shoot_box).find("a").fadeTo ( 0, .6).hover( function() {
				$(this).fadeTo( .2, 1).css("cursor", "pointer");
			}, function() {
				$(this).fadeTo( .2, 0.6);
			}).unbind("click").bind ("click", function() {
				$(this).fadeTo ( .2, 1);
				$(this).siblings("input:first").val($(this).attr("value"));
				activity.ui.apply_shoot_box_actions ( $(this).parent(".gameSetBox"), true );
				activity.ui.apply_shoot_box_actions ( $(this), false );
			});
		} else {
			$(shoot_box).unbind('mouseenter mouseleave click' ).fadeTo ( .2, 1);
		}
	},

	create_keep_box: function (n)
	{
		var keep_box = $('<div id="gameSetBox_parata_'+n+'" class="gameSetBox right"></div>');
		$(keep_box).append ( $('<div title="parata" class="label label-info">Parata '+n+'</div>') )
		$(keep_box).append ( $('<input type="hidden" class="game_setup_input" name="parata'+n+'" value="" />') );
		$(keep_box).append ( $('<a class="portiereSx_game" value="0"><img src="/i/lanciaSfide_portiereSx.png" width="50" height="39" class="png" /></a>') );
		$(keep_box).append ( $('<a class="portiereCenter_game" value="1"><img src="/i/lanciaSfide_portiere.png" width="27" height="47" class="png" /></a>') );
		$(keep_box).append ( $('<a class="portiereDx_game" value="2"><img src="/i/lanciaSfide_portiereDx.png" width="50" height="39" class="png" /></a>') );
		$(keep_box).find("img").imghover({suffix: "_on"}).css("cursor", "pointer");
		this.apply_keep_box_actions ( $(keep_box), true );
		return $(keep_box);
	},

	apply_keep_box_actions: function(keep_box, status)
	{
		if (status == true) {
            $(keep_box).find("img").mouseleave().removeAttr("disabled");
            $(keep_box).find("a").unbind("click").bind ("click", function() {
                $(this).siblings("input:first").val($(this).attr("value"));
                $(this).attr("hovered", "false");
                activity.ui.apply_keep_box_actions($(this).parent(".gameSetBox"), true);
                activity.ui.apply_keep_box_actions($(this), false);
                $(this).parent(".gameSetBox").find ("img").mouseleave();
            });
        } else {
//            $(this).attr("hovered", "true");
            $(keep_box).find ("img").mouseenter().attr ("disabled", "true").unbind ( "click" );
        }
	},

	fix_png: function ( png )
	{
		var src = png.src;
		if (!png.style.width) { png.style.width = $(png).width(); }
		if (!png.style.height) { png.style.height = $(png).height(); }
		png.src = new Image().src ("/i/blank.gif");
		png.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + src + "',sizingMethod='scale')";
	},

	get_sfida_swf_code: function ( params )
	{
		params.src = activity.ui.vedi_sfida_swf_path;
		params.w = 630;
		params.h = 500;

		var my_var = '<object id="flash_object" type="application/x-shockwave-flash" data="' + params.src + '" width="' + params.w + '" height="' + params.h + '">';
		my_var += '<param name="flashvars" value="gameXML=../swf/xml_sfida.php?id_sfida='+params.id_sfida+'&backType='+params.back_type+'" />';
		my_var += '<param name="movie" value="' + params.src + '" />';
		my_var += '<param name="quality" value="high" />';
		my_var += '<param name="wmode" value="transparent" />';
		my_var += '</object>';
		return my_var;
	}

}



Toaster = {

	collector: [],
	options:
	{
		html: 									'<div class="toast-message-container"><small>Clicca per chiudere</small></div>',
//			__container_class: 						"toast-message-container",
		__text_container_class:					"toast-message-text",
//			message_query: 							".toast-message-text",
		container_query: 						'body'
//			container_internal_query: 				".toast-message-container",
//			type: 									"inline"
	},

	get: function ( option )
	{
		var thisOption = $.extend ( {}, Toaster.options );
		if ( arguments.length > 0 )
			$.extend ( thisOption, arguments[1] );
		if ( this.collector.length == 0 )
			var newError = $( thisOption.html ).css ("z-idnex", Toaster.collector * 3 );
		else
			var newError = $( this.collector[this.collector.length-1].element )
		newError.config = thisOption;
		newError.click ( Toaster.remove );

		Toaster.collector.push ( {
			element: 	newError,
			options: 	thisOption,
			status: 	"hidden"
		});

		$(this.options.container_query).append ( newError );

		return {
			ref: Toaster.collector.length,
			options: thisOption,
			container: $(newError),

			show: function () {
				//this.container.css ("top", "-" + this.container.height() + "px");
				this.container.show ();
				//this.container.animate ({ top: 0 });
			},

			add: function ( message ) {
				$(this.container).prepend ( $('<span class="' + Toaster.options.__text_container_class + '"></span>').html(message) );
				return this;
			},

			remove: function () {
				this.container.animate ({ top: "-" + this.container.height() + "px" });

			}
		}
	},

	remove: function ()
	{
		$(this).remove ();
	},

	removeAll: function ()
	{
		for ( var i=0; i<this.collector.length; i++ ) {
			this.collector[i].element.remove ();
		}
		this.collector = [];
	}
/*














		add: function ( message, option )
		{
			var thisOption = $.extend ( {}, Toaster.options );
			if ( arguments.length > 0 )
				$.extend ( thisOption, arguments[1] );
			var newError = $( thisOption.html ).css ("z-idnex", Toaster.collector * 3 );
			newError.config = option;
			newError.click ( Toaster.remove );

			Toaster.collector.push ( {
				element: 	newError,
				options: 	thisOption,
				status: 	( message != undefined ) ? "shown" : "hidden",
				message: 	message
			});

			if ( $(this.options.container_query).find ( Toaster.container_internal_query ).size () > 0 )
				$(this.options.container_query).find ( Toaster.message_query ).append ( Toaster.collector[this.ref-1].message );
			else
				$(this.options.container_query).append ( Toaster.collector[this.ref-1].element );

			if ( message != undefined ) {
				newError.find ( thisOption.message_query ).html ( message );
				Toaster.show.call ( this );
			}

			return {
				ref: Toaster.collector.length,
				options: thisOption,
				container: newError.find ( thisOption.container_internal_query ),
				show: function () {
					Toaster.show.call ( this );
				},
				hide: function () {
					Toaster.collector[this.ref-1].status = "hide";
					$(this).hide ();
				},
				add: function ( message ) {
					$(this.options.container_internal_query).prepend ( $('<span class="' + Toaster.options.__text_container_class + '"></span>').html(message) );
					return this;
				}
			}
		},

		show: function () {
			if ( Toaster.collector[this.ref-1].status != "shown" ) {
				Toaster.collector[this.ref-1].status = "shown";

			} else
				$(this).show ();
			return this;
		},

		remove: function ()
		{
			$(this).remove ();
		}
*/
}

