var baseurl = $('#url').val();
var token = $('#token').val();
$(document).ready(function(){
    getData();
    $('[data-toggle="tooltip"]').tooltip();
    $('#filter')
    //Date picker
    $('#reservation').daterangepicker({
        locale: 'id',
    });
    $('#reservation').on('cancel.daterangepicker', function() {
        $('#reservation').val('')
    });
});

function create(){
    $('#form').trigger('reset');
    $('#customer').val('');
    $('#customer').text('Ketik min 3 huruf');
    $('#amount').val('');
    getPackage();
    getTypeOfPayment();
    getTypeTransaction();
    getNoRekening();
    $('#modalCreate').modal('show');
}

//store
$('#simpan').click(function (e){
    e.preventDefault();
    var id = $('#id').val();
    var tanggal = $('#date').val();
    var pelanggan = $('#customer').val();
    var paket = $('#package').val();
    var amount = $('#amount').val();
    var removeRp = amount.substring(3);
    var removeLast3 = removeRp.slice(0,-3);
    var finalPrice = removeLast3.replace('.', "");
    var intPrice = parseInt(finalPrice.replace('.', ""));
    var jenisPembayaran = $('#top').val();
    var tipeTransaksi = $('#tot').val();
    var noRekening = $('#norek').val();
    var afiliasi = $('#afiliasi').val();
    var iduser = $('#iduser').val();
    $.ajax({
        url: baseurl+'/transaction/store',
        method: 'POST',
        data: {
            _token: token,
            id: id,
            tanggal: tanggal,
            pelanggan: pelanggan,
            paket: paket,
            nominal: intPrice,
            jenisPembayaran: jenisPembayaran,
            jenisTransaksi: tipeTransaksi,
            noRekening: noRekening,
            afiliasi: afiliasi,
            iduser: iduser,
        },
        success: function (data){
            if (data.errors){
                $.each(data.errors,function (key,value){
                    toastr.error('<strong><li>'+value+'</li></strong>')
                });
            } else {
                if (data.success === 1){
                    toastr.success('Data Berhasil Di Simpan');
                    $('#modalCreate').modal('hide');
                    getData();
                }else {
                    toastr.warning("Data Gagagal Di Simpan");
                }
            }
        }
    });
});

//key up search
$(document).on('keyup','.select2-search__field',function (e){
    var search = e.target.value;
   if (search.length >= 3){
       $.ajax({
           url: baseurl+'/transaction/searchCustomer/'+search,
           method: 'GET',
           data: {
               _token: token,
           },
           success: function (data){
               var html = "";
               for (i=0;i<data.length;i++){
                   var id = data[i].id;
                   var name = data[i].name;
                   html += "<option value='"+id+"'>"+name+"</option>"
               }
               document.getElementById('customer').innerHTML = "";
               document.getElementById('customer').innerHTML = html;
           }
       });
   }
});
function getPackage(){
    var fk_package = $('#package').val();
    $.ajax({
        url: baseurl+'/transaction/getPackage',
        method: 'GET',
        data: {
            _token: token,
        },
        success: function (data){
            var html = "";
            var titleSelect = "Pilih Paket";
            html += "<option value=''>"+titleSelect+"</option>";
            for (i=0;i<data.length;i++){
                var id = data[i].id;
                var name = data[i].name;
                if (id == fk_package){
                    html += "<option selected='true' value='"+id+"'>"+name+"</option>"
                } else {
                    html += "<option value='"+id+"'>"+name+"</option>"
                }
            }
            document.getElementById('package').innerHTML = "";
            document.getElementById('package').innerHTML = html;
        }
    });
}
function getPricePackage(){
    var id = $('#package').val();
    $.ajax({
        url: baseurl+'/transaction/getPricePackage/'+id,
        method: 'GET',
        data: {
            _token: token,
        },
        success: function (data){
            $('#amount').val(data);
        }
    });
}
function getTypeOfPayment(){
    var fk_top = $('#top').val();
    $.ajax({
        url: baseurl+'/transaction/getTypeOfPayment',
        method: 'GET',
        data: {
            _token: token,
        },
        success: function (data){
            var html = "";
            var titleSelect = "Pilih Jenis Pembayaran";
            html += "<option value=''>"+titleSelect+"</option>";
            for (i=0;i<data.length;i++){
                var id = data[i].id;
                var description = data[i].description;
                if (id == fk_top){
                    html += "<option selected='true' value='"+id+"'>"+description+"</option>"
                } else {
                    html += "<option value='"+id+"'>"+description+"</option>"
                }
            }
            document.getElementById('top').innerHTML = "";
            document.getElementById('top').innerHTML = html;
        }
    });
}
function getTypeTransaction(){
    var fk_tot = $('#tot').val();
    $.ajax({
        url: baseurl+'/transaction/getTypeTransaction',
        method: 'GET',
        data: {
            _token: token,
        },
        success: function (data){
            var html = "";
            var titleSelect = "Pilih Jenis Transaksi";
            html += "<option value=''>"+titleSelect+"</option>";
            for (i=0;i<data.length;i++){
                var id = data[i].id;
                var description = data[i].description;
                if (id == fk_tot){
                    html += "<option selected='true' value='"+id+"'>"+description+"</option>"
                } else {
                    html += "<option value='"+id+"'>"+description+"</option>"
                }
            }
            document.getElementById('tot').innerHTML = "";
            document.getElementById('tot').innerHTML = html;
        }
    });

}
function getNoRekening(){
    var fk_norek = $('#norek').val();
    $.ajax({
        url: baseurl+'/transaction/getNorek',
        method: 'GET',
        data: {
            _token: token,
        },
        success: function (data){
            var html = "";
            var titleSelect = "Pilih Jenis Transaksi";
            html += "<option value=''>"+titleSelect+"</option>";
            for (i=0;i<data.length;i++){
                var id = data[i].id;
                var description = data[i].description;
                var name = data[i].name;
                var norek = data[i].no_rek;
                if (id == fk_norek){
                    html += "<option selected='true' value='"+id+"'>"+name+"("+description+"-"+norek+")"+"</option>"
                } else {
                    html += "<option value='"+id+"'>"+name+"("+description+"-"+norek+")"+"</option>"
                }
            }
            document.getElementById('norek').innerHTML = "";
            document.getElementById('norek').innerHTML = html;
        }
    });
}
function getData(){
    $('#data-table').DataTable({
        paging      : true,
        searching   : false,
        info        : true,
        ordering    : true,
        bDestroy    : true,
        ajax: {
            url: baseurl+'/transaction',
            type: "GET",
        },
        columns: [
            { data: 'customer'},
            { data: 'date'},
            { data: 'package'},
            { data: 'amount'},
            { data: 'action'},
        ],
    });
}
