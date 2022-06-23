var baseurl = $('#url').val();
var token = $('#token').val();
$(document).ready(function (){
    getData();
});


function getData(){
    $('#data-table').DataTable({
        paging      : true,
        searching   : false,
        info        : true,
        ordering    : true,
        bDestroy    : true,
        ajax: {
            url: baseurl+'/task',
            type: "GET",
        },
        columns: [
            { data: 'date'},
            { data: 'customer'},
            { data: 'package'},
            { data: 'status'},
            { data: 'action'},
        ],
    });
}
