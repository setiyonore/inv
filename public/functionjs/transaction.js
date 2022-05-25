var baseurl = $('#url').val();
var token = $('#token').val();
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $('#filter')
    //Date picker
    $('#reservation').daterangepicker({
        locale: 'id',
    });
    $('#reservation').on('cancel.daterangepicker', function(ev, picker) {
        $('#reservation').val('')
    });
});
