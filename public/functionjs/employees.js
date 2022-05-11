var baseurl = $('#url').val();
var token = $('#token').val();
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    getdata();
});
function create(){
    $('#form').trigger("reset");
    $('#modalCreate').modal('show');
}
function getdata(){
    $('#data-table').DataTable({
        paging      : true,
        searching   : false,
        info        : true,
        ordering    : true,
        bDestroy    : true,
        ajax: {
            url: baseurl+'/employees',
            type: "GET",
        },
        columns:[
            { data: 'name'},
            { data: 'nip'},
            { data: 'division'},
            { data: 'phone'},
            { data: 'action'},
        ]

    })
}
