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
