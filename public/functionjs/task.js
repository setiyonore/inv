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
//update status
$('body').on('click','#my-btn-edit',function (){
    var id = $(this).data("id");
    var status = $(this).data("status-id");
    $('#id').val(id);
    getStatus(status);
  $('#modalUpdateStatus').modal('show');
})

function getStatus(status){
    $('#status').val(status);
    $.ajax({
        url: baseurl+'/task/getStatus',
        method: 'GET',
        data: {
            _token: token,
        },
        success: function (data) {
            var html = "";
            for (i=0;i<data.length;i++){
                var id = data[i].id;
                var description = data[i].description;
                if (id == status){
                    html += "<option selected='true' value='"+id+"'>"+description+"</option>"
                } else {
                    html += "<option value='"+id+"'>"+description+"</option>"
                }
            }
            document.getElementById('status').innerHTML = "";
            document.getElementById('status').innerHTML = html;
        }
    });
}

//store update
$('#simpan').click(function (e) {
    e.preventDefault();
    var id = $('#id').val();
    var status = $('#status').val();
    $.ajax({
        url: baseurl+'/task/update',
        method: 'POST',
        data: {
            _token: token,
            id: id,
            status: status,
        },
        success: function (data) {
            if (data.success === 1){
                toastr.success('Data Berhasil Di Simpan');
                $('#modalUpdateStatus').modal('hide');
                getData();
            }else {
                toastr.warning("Data Gagagal Di Simpan");
            }
        }
    });
})

function filter(){
    var id_package = $('#filterPackage').val();
    $('#data-table').DataTable({
        paging      : true,
        searching   : false,
        info        : true,
        ordering    : true,
        bDestroy    : true,
        ajax: {
            url: baseurl+'/task/filter',
            type: "POST",
            data: {
                _token: token,
                id_package: id_package,
            },
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
