var baseurl = $('#url').val();
var token = $('#token').val();
$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    getData();
})

function getData() {
    $('#data-table').DataTable({
        paging: true,
        searching: false,
        info: true,
        ordering: true,
        bDestroy: true,
        ajax: {
            url: baseurl + '/roles',
            type: 'GET',
        },
        columns: [
            {data: 'name'},
            {data: 'action'},
        ]
    })
}

//create
function createRole() {
    $('#modalCreateRole').modal('show');
    $('#form').trigger('reset');
    $('#id').val('');
}

//save
$('#simpan').click(function (e) {
    e.preventDefault()
    var id = $('#id').val();
    var role = $('#role').val();
    $.ajax({
        url: baseurl + '/roles/store',
        method: 'POST',
        data: {
            _token: token,
            role: role,
        },
        success: function (data) {
            if (data.errors) {
                $.each(data.errors, function (key, value) {
                    toastr.error('<strong><li>' + value + '</li></strong>')
                })
            } else {
                if (data.success === 1) {
                    getData()
                    $('#modalCreateRole').modal('hide');
                    $('#form').trigger('reset');
                    toastr.success('Data Berhasil Disimpan');
                } else {
                    toastr.warning('Data Gagal Disimpan');
                }
            }
        }
    })
})
//detil
$('body').on('click','#btn-detil',function (){
    var id = $(this).data("id");
    getPermission(id);
    $('#modalDetil').modal('show');

})

function getPermission(id){
    $.ajax({
        url: baseurl+'/roles/getPermission/'+id,
        method: 'GET',
        data: {
            _token: token,
        },
        success:function (data) {
            console.log(data['permissions']);
            var html = "";
            for (let i = 0; i<data['permissions'].length;i++){
                var idPermission = data['permissions'][i]['id'];
                html += "<tr>";
                html += "<td>"+(i+1)+"</td>";
                html += "<td>"+data['permissions'][i]['name']+"</td>";
                html += "<td class='text-right'><a href='javascript:void(0)' class='btn btn-danger' id='delBenefit' data-id='"+idPermission+"'><i class='fa fa-trash'></i></a></td>";
                html += "</tr>";
            }
            document.getElementById('dataPermission').innerHTML = "";
            document.getElementById('dataPermission').innerHTML = html;
        }
    })
}
