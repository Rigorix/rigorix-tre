var admin = {

    init: function () {
        $(".btn-group a[action]").on ("click", function () {

            if ( $(this).attr ("modal") != null )
                alert ("modal");
            else
                $.getJSON("/services/" + $(this).attr("action"), function(data) {
                    admin.showAjaxResponseMessage ( data );
                });

        });

        $("[behaviour=calendar]").each(function () {
            $(this).datetimepicker({
                language: 'en'
            });
        });

    },

    showAjaxResponseMessage: function (data) {
        $.tmpl ( $("#modal_confirmation_dialog").text(), data).appendTo ( "body" );
        $(".modal:last").modal({ show: true });
    }

}
admin.init();