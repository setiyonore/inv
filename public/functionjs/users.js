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
    $('#oldRoles').val(0);
    getEmployee();
    getRoles();
}

//get data pegawai
function getEmployee() {
    const idEmployee = 0;
    $.ajax({
        url: baseurl + '/users/getEmployee',
        method: 'GET',
        data: {
            _token: token,
        },
        success: function (data) {
            var html = "";
            var titleSelect = "Pilih Pegawai";
            html += "<option value=''>" + titleSelect + "</option>";
            for (i = 0; i < data.length; i++) {
                var id = data[i].id;
                var name = data[i].name;
                if (id === idEmployee) {
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
    const id = $('#id').val();
    const name = $('#name').val();
    const email = $('#email').val();
    const pegawai = $('#employee').val();
    const role = $('#role').val();
    const oldRole = $('#oldRoles').val();
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
            oldRole: oldRole,
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
//tombol edit user di klik
$('body').on('click','#my-btn-edit',function (){
    const id = $(this).data("id");
    $.get(baseurl+'/users/edit/'+id,function (data){
        $('#id').val(id);
        $('#name').val(data.name);
        $('#email').val(data.email);
        $('#idEmployee').val(data.pegawai_id);
        $('#oldRoles').val(data['roles'][0]['id']);
        getRolesWithId(data['roles'][0]['id']);
        getEmployeeWithId(data.pegawai_id);
    });
    $('#modalCreate').modal('show');
})
function getEmployeeWithId(idEmployee){
    $.ajax({
        url: baseurl + '/users/getEmployee',
        method: 'GET',
        data: {
            _token: token,
        },
        success: function (data) {
            var html = "";
            var titleSelect = "Pilih Pegawai";
            html += "<option value=''>" + titleSelect + "</option>";
            for (i = 0; i < data.length; i++) {
                var id = data[i].id;
                var name = data[i].name;
                if (id === idEmployee) {
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

function getRolesWithId(idRoles){
    $.ajax({
        url: baseurl + '/users/getRoles',
        method: 'GET',
        data: {
            _token: token,
        },
        success: function (data) {
            var html = "";
            var titleSelect = "Pilih Roles";
            html += "<option value=''>" + titleSelect + "</option>";
            for (i = 0; i < data.length; i++) {
                var id = data[i].id;
                var name = data[i].name;
                if (id === idRoles) {
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
