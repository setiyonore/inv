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
            url: baseurl + '/users',
            type: 'GET',
        },
        columns: [
            {data: 'name'},
            {data: 'email'},
            {data: 'employee'},
            {data: 'role'},
            {data: 'action'},
        ]
    })
}

function create() {
    $('#modalCreate').modal('show');
    $('#form').trigger('reset');
    $('#id').val('');
    getEmployee();
    getRoles();
}

//get data pegawai
function getEmployee() {
    $.ajax({
        url: baseurl + '/users/getEmployee',
        method: 'GET',
        data: {
            _token: token,
        },
        success: function (data) {
            var idtipe = 0
            var html = "";
            var titleSelect = "Pilih Pegawai";
            html += "<option value=''>" + titleSelect + "</option>";
            for (i = 0; i < data.length; i++) {
                var id = data[i].id;
                var name = data[i].name;
                if (id === idtipe) {
                    html += "<option selected='true' value='" + id + "'>" + name + "</option>"
                } else {
                    html += "<option value='" + id + "'>" + name + "</option>"
                }
            }
            document.getElementById('employee').innerHTML = "";
            document.getElementById('employee').innerHTML = html;
        }
    })
}

//get data roles
function getRoles() {
    $.ajax({
        url: baseurl + '/users/getRoles',
        method: 'GET',
        data: {
            _token: token,
        },
        success: function (data) {
            var idtipe = 0
            var html = "";
            var titleSelect = "Pilih Roles";
            html += "<option value=''>" + titleSelect + "</option>";
            for (i = 0; i < data.length; i++) {
                var id = data[i].id;
                var name = data[i].name;
                if (id === idtipe) {
                    html += "<option selected='true' value='" + id + "'>" + name + "</option>"
                } else {
                    html += "<option value='" + id + "'>" + name + "</option>"
                }
            }
            document.getElementById('role').innerHTML = "";
            document.getElementById('role').innerHTML = html;
        }
    })
}

//store data
$('#simpan').on('click', function (e) {
    e.preventDefault();
    var id = $('#id').val();
    var name = $('#name').val();
    var email = $('#email').val();
    var pegawai = $('#employee').val();
    var role = $('#role').val();
    $.ajax({
        url: baseurl + '/users/store',
        method: 'POST',
        data: {
            _token: token,
            id: id,
            name: name,
            email: email,
            pegawai: pegawai,
            role: role,
        },
        success: function (data) {
            if (data.errors) {
                $.each(data.errors, function (key, value) {
                    toastr.error('<strong><li>' + value + '</li></strong>')
                });
            } else {
                if (data.success === 1) {
                    getData();
                    $('#modalCreate').modal('hide');
                    toastr.success('Data Berhasil Di Simpan');
                } else {
                    toastr.warning("Data Gagagal Di Simpan");
                }
            }
        }
    })
})
