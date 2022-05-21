var baseurl = $('#url').val();
var token = $('#token').val();
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
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
            url: baseurl+'/norek',
            type: "GET",
        },
        columns:[
            { data: 'bank'},
            { data: 'no_rek'},
            { data: 'name'},
            { data: 'action'},
        ]
    });
}
