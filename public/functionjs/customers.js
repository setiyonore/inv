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
            url: baseurl+'/customers',
            type: "GET",
        },
        columns:[
            { data: 'name'},
            { data: 'email'},
            { data: 'phone'},
            { data: 'action'}
        ]
    });
}

function create(){
   $('#form').trigger("reset");
   $('#modalCreate').modal('show');
}

$('#simpan').click(function (e) {
    e.preventDefault();
    var id = $('#id').val();
    var name = $('#name').val();
    var phone = $('#phone').val();
    var email = $('#email').val();

    $.ajax({
        url: baseurl+'/customers/store',
        method: "POST",
        data: {
            _token: token,
            id: id,
            name: name,
            phone: phone,
            email: email
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
    $.get(baseurl+'/customers/edit/'+id,function (data){
        $('#id').val(data.id);
        $('#name').val(data.name);
        $('#phone').val(data.phone);
        $('#email').val(data.email);
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
        url: baseurl+'/customers/destroy/'+recId,
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
    var name = $('#filterName').val();
    var email = $('#filterMail').val();
    var phone = $('#filterPhone').val();
    $('#data-table').DataTable({
        paging      : true,
        searching   : false,
        info        : true,
        ordering    : true,
        bDestroy    : true,
        ajax: {
            url: baseurl+'/customers/search',
            type: "GET",
            data: {
                name: name,
                email: email,
                phone: phone
            },
        },
        columns:[
            { data: 'name'},
            { data: 'email'},
            { data: 'phone'},
            { data: 'action'}
        ]
    });
}
