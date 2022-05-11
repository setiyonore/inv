var baseurl = $('#url').val();
var token = $('#token').val();
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    getData();
});
// fetch data
function getData(){
    $('#data-table').DataTable({
        paging      : true,
        searching   : false,
        info        : true,
        ordering    : true,
        bDestroy    : true,
        ajax: {
            url: baseurl+'/typereferences',
            type: "GET",
        },
        columns:[
            { data: 'description'},
            { data: 'short'},
            { data: 'action'}
        ]
    });
}

// add modal
function create(){
   $('#form').trigger("reset");
   $('#modalCreate').modal('show');
}

// store
$('#simpan').click(function (e) {
    e.preventDefault();
    var id = $('#id').val();
    var description = $('#description').val();
    var short = $('#short').val();

    $.ajax({
        url: baseurl+'/references/store',
        method: "POST",
        data: {
            _token: token,
            id: id,
            short: short,
            description: description,
        },
        success: function (data){
            if (data.errors) {
                $.each(data.errors, function(key, value) {
                    toastr.error('<strong><li>'+value+'</li></strong>');
                });
            } else {
                if (data.success===1){
                    getData();
                    $('#modalCreate').modal('hide');
                    $('#form').trigger("reset");
                    toastr.success('Data Berhasil Di Simpan');
                } else {
                    toastr.warning('Data Gagal Disimpan')
                }
            }
        }
    });
})

//edit
$('body').on('click','#my-btn-edit',function (){
    var id = $(this).data("id");
    $.get(baseurl+'/references/edit/'+id,function (data){
        $('#id').val(data.id);
        $('#short').val(data.short);
        $('#description').val(data.description);
        $('#modalCreate').modal('show');
    })
});

//delete
$('body').on('click','#my-btn-delele',function (){
    var recId = $(this).data('id');
    $('#modalDelete').modal('show');
    $('#id').val(recId);
});

//submit delete
$('body').on('click','#submit-delete',function (e){
    var recId = $('#id').val();
    e.preventDefault();
    $.ajax({
        url: baseurl+'/references/destroy/'+recId,
        method: 'GET',
        data: {
            _token: token,
        },
        success: function (data){
            if (data.success===1){
                toastr.success('Data berhasil dihapus!');
                getData();
                $('#modalDelete').modal('hide');
            }
        }
    });
});

function filter(){
    var description = $('#filterdescription').val();
    var short = $('#filtershort').val();
    $('#data-table').DataTable({
        paging      : true,
        searching   : false,
        info        : true,
        ordering    : true,
        bDestroy    : true,
        ajax: {
            url: baseurl+'/references/search',
            type: "GET",
            data: {
                description: description,
                short: short
            },
        },
        columns:[
            { data: 'description'},
            { data: 'short'},
            { data: 'action'}
        ]
    });
}