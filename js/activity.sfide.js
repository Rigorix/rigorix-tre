/* Sfide */

activity.sfide = {

	active_panel: false,
	active_panel_index: false,

	init: function ()
	{
		this.init_tabs ();
	},

	init_tabs: function()
	{
		$('.rx-ui-tab.sfide-tabs').tabs({
			load: function(event, ui) {
				activity.run_internal();
				activity.ui.init ();
				activity.sfide.active_panel = ui.panel;
				activity.sfide.active_panel_index = ui.index;
				$(".rx-ui-button").button();
			}
		});
	},

	init_sfide_list: function()
	{
		this.init_tabs ();
	},

    open_sfida_dialog: function ( id_sfida, sfida_action, nome_avversario )
    {
        Game.loadDialog ({
            w: 800,
            h: 490,
            dialogClass: "dialog-no-padding",
            src: '/boxes/dialog_sfida_gioca.php?id_sfida=' + id_sfida + '&sfida_action=' + sfida_action,
            title: "Lancia la tua sfida a &nbsp;<span class='su-username'>" + nome_avversario + "</span>"
        });
    },

	init_sfide_table: function()
	{
        var self = this;
        $("[data-toggle=popover]").popover();

		$('[name=lancia_sfida_torneo]').click ( function() {
			var row = $(this).parents ( "tr:first" );
            self.open_sfida_dialog ( $(row).attr ("id_sfida"), $(row).attr ('sfida_action'), $(row).find("[name=avversario]").text() );
		});

		$('[name=vedi_sfida_torneo]').click ( function() {
		    var row = $(this).parents ( "tr:first" );
		    Game.loadDialog ({
                w: 635,
                h: 508,
                buttons: {
                    "CHIUDI": function() {
                        $(win).html("")
                        Game.unloadDialog ();
                    }
                },
                dialogClass: 'game-view-dialog',
                src: '/boxes/dialog_sfida_vedi.php?id_sfida=' + $(row).attr ("id_sfida"),
                title: "Vedi la sfida giocata"
            });
		});

	},

	init_sfide_torneo_list: function()
	{
		this.init_tabs ();
	},

	init_sfide_archivio: function ()
	{
		var today = Utils.date.getTodayStringDate ();
		var weekAgo = Utils.date.getCustomDate ( -7 );
		$('[name=start_date]').datepicker({
			changeYear: true,
			changeMonth: true,
			dateFormat: "dd/mm/yy",
			defaultDate: weekAgo
		});
		$('[name=end_date]').datepicker({
			changeYear: true,
			changeMonth: true,
			dateFormat: "dd/mm/yy",
			defaultDate: today
		});
		$('[name=cerca-sfide-chiuse]').click ( function() {
			if (Utils.form.validate($('[cerca-sfide-chiuse-form]')))
				$(activity.sfide.active_panel).load("/boxes/user_sfide_archivio.php?start_date=" + $("[name=start_date]").val() + "&end_date=" + $("[name=end_date]").val());
		});
	},

	set_torneo_filter: function ( sel )
	{
		$(activity.sfide.active_panel).load( "/boxes/user_sfide_torneo.php?id_torneo=" + $(sel).val(), function () {
			activity.sfide.init_sfide_table ();
		} );
	},

	reload_sfide_generic: function ( )
	{
		//$(activity.sfide.active_panel).load( "/boxes/user_sfide_generic.php" );
		$('.rx-ui-tab.sfide-tabs').tabs ("load", activity.sfide.active_panel_index );
	}

}
