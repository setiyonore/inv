var baseurl = $('#url').val();
var token = $('#token').val();
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    getdata();
});
function create(){
    //getDivisi();
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
//store data employees
$('#simpan').click(function (e){
    e.preventDefault();
    var id = $('#id').val();
    var name = $('#name').val();
    var nip = $('#nip').val();
    var phone = $('#phone').val();
    var division = $('#division').val();
    $.ajax({
        url: baseurl+'/employees/store',
        method: 'POST',
        data: {
            _token: token,
            id: id,
            nama: name,
            nip: nip,
            telepon: phone,
            divisi: division
        },
        success: function (data) {
            if (data.errors){
                $.each(data.errors,function (key,value){
                    toastr.error('<strong><li>'+value+'</li></strong>')
                });
            }else{
                if (data.success === 1){
                    getdata();
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
   $.get(baseurl+'/employees/edit/'+id,function (data) {
       $('#id').val(data.id);
       $('#name').val(data.name);
       $('#nip').val(data.nip);
       $('#phone').val(data.phone);
       $('#division').val(data.id_reference_division);
       $('[name=division]').val(data.id_reference_division);
       getDivisi();
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
        url: baseurl+'/employees/destroy/'+recId,
        method: 'GET',
        data: {
            _token: token,
        },
        success: function (data){
            if (data.success===1){
                toastr.success('Data berhasil dihapus!');
                getdata();
                $('#modalDelete').modal('hide');
            }
        }
    });
});
function getDivisi(){
    $.ajax({
        url: baseurl+'/employees/getDivision',
        data: {_token:token},
        methods: 'GET',
        dataType: 'json',
        success: function (data) {
            var fk_divisi = $('#division').val();
            var html = "";
            var titleselect = "Pilih Divisi";
            html += "<option value=''>"+titleselect+"</option>";
            for (i=0;i<data.length;i++){
                var id = data[i].id;
                var description = data[i].description;
                if (id == fk_divisi){
                    html += "<option class='form-control select2 select2-hidden-accessible' selected='true' value='"+id+"'>"+description+"</option>"
                } else {
                    html += "<option class='form-control select2 select2-hidden-accessible' value='"+id+"'>"+description+"</option>"
                }
            }
            document.getElementById('division').innerHTML = "";
            document.getElementById('division').innerHTML = html;
        }
    });
}

function filter(){
    var name = $('#filterName').val();
    var nip = $('#filterNip').val();
    var division = $('#filterDivision').val();
    $('#data-table').DataTable({
        paging      : true,
        searching   : false,
        info        : true,
        ordering    : true,
        bDestroy    : true,
        ajax: {
            url: baseurl+'/employees/search',
            type: "GET",
            data: {
                name: name,
                nip: nip,
                division: division,
            },
        },
        columns:[
            { data: 'name'},
            { data: 'nip'},
            { data: 'division'},
            { data: 'phone'},
            { data: 'action'},
        ]
    });
}
