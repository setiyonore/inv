var baseurl = $('#url').val();
var token = $('#token').val();
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
    $('#filter')
    //Date picker
    $('#filterDate').daterangepicker({
        locale: 'id',
        dateFormat: 'yyyy-MM-dd',
    });
    $('#filterDate').on('cancel.daterangepicker', function() {
        $('#filterDate').val('');
    });
    getData();
    getAmount();
});

function getData(){
    $('#data-table').DataTable({
        paging      : true,
        searching   : false,
        info        : true,
        ordering    : true,
        bDestroy    : true,
        ajax: {
            url: baseurl+'/report',
            type: "GET",
        },
        columns: [
            { data: 'date'},
            { data: 'package'},
            { data: 'amount'},
        ],
        columnDefs: [
            {
                targets: 2,
                className: 'dt-body-right'
            }
        ]
    });
}

function getAmount(){
    $.ajax({
        url: baseurl+'/report/getAmount',
        method: 'GET',
        data: {_token:token},
        success: function (data){
            // console.log(data[0].amount);
            $('#amount').text('TOTAL: Rp.'+data[0].amount);
        }
    });
}
