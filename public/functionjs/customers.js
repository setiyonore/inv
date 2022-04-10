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
        ordering    : false,
        bDestroy    : true,
        ajax: {
            url: baseurl+'/customers',
            type: "GET",
        },
        columns:[
            { data: 'name'},
            { data: 'phone'},
            { data: 'email'},
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
