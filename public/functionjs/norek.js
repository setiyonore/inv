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

function create(){
    // $('#form').trigger("reset");
    $('#name').val('');
    $('#norek').val('');
    $('#bank').val('');
    $('#modalCreate').modal('show');
}

//store
$('#simpan').click(function (e){
    e.preventDefault();
    var id = $('#id').val();
    var bank = $('#bank').val();
    var no_rek = $('#norek').val();
    var name = $('#name').val();
    $.ajax({
        url: baseurl+'/norek/store',
        method: 'POST',
        data: {
            _token: token,
            id: id,
            bank: bank,
            noRekening: no_rek,
            nama: name
        },
        success: function (data) {
            if (data.errors){
                $.each(data.errors,function (key,value){
                    toastr.error('<strong><li>'+value+'</li></strong>')
                });
            }else{
                if (data.success === 1){
                    filter();
                    $('#modalCreate').modal('hide');
                    $('#form').trigger("reset");
                    toastr.success('Data Berhasil Di Simpan');
                }else {
                    toastr.warning("Data Gagagal Di Simpan");
                }
            }
        }
    });
});
//edit
$('body').on('click','#my-btn-edit',function (){
    var id = $(this).data("id");
    $.get(baseurl+'/norek/edit/'+id,function (data) {
        $('#id').val(data.id);
        $('#name').val(data.name);
        $('#bank').val(data.id_reference_bank);
        $('#norek').val(data.no_rek);
        getBank();
        $('#modalCreate').modal('show');
    });
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
        url: baseurl+'/norek/destroy/'+recId,
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
//filter
function filter(){
    var name = $('#filterName').val();
    var bank = $('#filterBank').val();
    $('#data-table').DataTable({
        paging      : true,
        searching   : false,
        info        : true,
        ordering    : true,
        bDestroy    : true,
        ajax: {
            url: baseurl+'/norek/search',
            type: "POST",
            data: {
                _token: token,
                name: name,
                bank: bank,
            },
        },
        columns:[
            { data: 'bank'},
            { data: 'no_rek'},
            { data: 'name'},
            { data: 'action'},
        ]
    });
}
function getBank(){
    var fk_bank = $('#bank').val();
    $.ajax({
       url: baseurl+'/norek/getBankReference',
       method: 'GET',
       data: {
           _token: token,
       },
       success: function (data){
           var html = "";
           var titleSelect = "Pilih Bank";
           html += "<option value=''>"+titleSelect+"</option>";
           for (i=0;i<data.length;i++){
               var id = data[i].id;
               var description = data[i].description;
               if (id == fk_bank){
                   html += "<option selected='true' value='"+id+"'>"+description+"</option>"
               } else {
                   html += "<option value='"+id+"'>"+description+"</option>"
               }
           }
           document.getElementById('bank').innerHTML = "";
           document.getElementById('bank').innerHTML = html;
       }
    });
}
