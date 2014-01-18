</div>

<script src="js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="bootstrap/js/bootstrap-datetimepicker.min.js"></script>
<script>
    (function($){
        $.fn.datetimepicker.dates['en'] = {
            days: ["Sunday", "Monday", "Tuesday", "Wensday", "Thursday", "Friday", "Saturday", "Sunday"],
            daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "SÃ¡b", "Dom"],
            daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
            months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            today: "Today"
        };
    }(jQuery));
</script>
<script type="text/template" id="modal_confirmation_dialog">
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">${title}</h4>
                </div>
                <div class="modal-body">
                    {{html body}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</script>
<script src="js/admin.js"></script>
</body>
</html>