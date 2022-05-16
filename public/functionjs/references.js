var baseurl = $('#url').val();
var token = $('#token').val();
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    // getData();
    Swal.fire(
        'Info',
        'Mohon Pilih Jenis Referensi Terlebih Dahulu',
        'info'
      );
    filter();
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
            url: baseurl+'/references/',
            type: "GET",
        },
        columns:[
            { data: 'description'},
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
    var id_type_reference = $('#tipe').val();
    var description = $('#description').val();

    $.ajax({
        url: baseurl+'/references/store',
        method: "POST",
        data: {
            _token: token,
            id: id,
            id_type_reference: id_type_reference,
            description: description,
        },
        success: function (data){
            if (data.errors) {
                $.each(data.errors, function(key, value) {
                    toastr.error('<strong><li>'+value+'</li></strong>');
                });
            } else {
                if (data.success===1){
                    filter();
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
        $('#tipe').val(data.id_type_reference);
        $('#description').val(data.description);
        // getTypeReference();
        var type = $('#tipe').val();
        console.log(type);
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
                filter();
                $('#modalDelete').modal('hide');
            }
        }
    });
});

function filter(){
    var idTypeReference = $('#typeReference').val();
    $('#data-table').DataTable({
        paging      : true,
        searching   : false,
        info        : true,
        ordering    : true,
        bDestroy    : true,
        ajax: {
            url: baseurl+'/references/filter',
            type: "POST",
            data: {
                id: idTypeReference,
                _token: token,
            },
        },
        columns:[
            { data: 'description'},
            { data: 'action'}
        ]
    });
}

function getTypeReference(){
    $.ajax({
        url: baseurl+'/references/getTypeReference',
        data: {_token:token},
        methods: 'GET',
        dataType: 'json',
        success: function (data) {
            var fk_type_reference = $('#type_references').val();
            var html = "";
            var titleselect = "Tipe Referensi";
            html += "<option value=''>"+titleselect+"</option>";
            for (i=0;i<data.length;i++){
                var id = data[i].id;
                var description = data[i].description;
                if (id == fk_type_reference){
                    html += "<option class='form-control' selected='true' value='"+id+"'>"+description+"</option>"
                } else {
                    html += "<option class='form-control' value='"+id+"'>"+description+"</option>"
                }
            }
            document.getElementById('type_references').innerHTML = "";
            document.getElementById('type_references').innerHTML = html;
        }
    });
}