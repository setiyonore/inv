var baseurl = $('#url').val();
var token = $('#token').val();
$(document).ready(function(){
    getData();
    $('[data-toggle="tooltip"]').tooltip();
    $('#filter')
    //Date picker
    $('#filterDate').daterangepicker({
        locale: 'id',
        dateFormat: 'yyyy-MM-dd',
    });
    $('#filterDate').on('cancel.daterangepicker', function() {
        $('#filterDate').val('')
    });
});

function create(){
    // $('#form').trigger('reset');
    $('#customer').val('');
    $('#customer').text('');
    $('#id').val('');
    $('#date').val('');
    $('#amount').val('');
    $('#package').val('');
    $('#top').val('');
    $('#tot').val('');
    $('#norek').val('');
    $('#afiliasi').val('');
    getPackage();
    getTypeOfPayment();
    getTypeTransaction();
    getNoRekening();
    $('#modalCreate').modal('show');
}
//edit
$('body').on('click','#my-btn-edit',function (){
    var id = $(this).data("id");
    var paket = $(this).data("package-id");
    var top = $(this).data("top-id");
    var tot = $(this).data("tot-id");
    var norek = $(this).data("norek-id");
    var cust = $(this).data("cust-id");
    $.get(baseurl+'/transaction/getCustomerId/'+cust,function (data){
        var html = "";
        for (i=0;i<data.length;i++){
            var id = data[i].id;
            var name = data[i].name;
            html += "<option value='"+id+"'>"+name+"</option>"
        }
        document.getElementById('customer').innerHTML = "";
        document.getElementById('customer').innerHTML = html;
    });
    getPackageId(paket);
    getTopId(top);
    getTotId(tot);
    getNorekId(norek);
    $('#modalCreate').modal('show');
    $.get(baseurl+'/transaction/edit/'+id,function (data){
        $('#id').val(data.id);
        $('#date').val(data.date);
        $('#customer').val(data.id_customer);
        $('#package').val(data.id_package);
        var rp = (data.amount/1000).toFixed(3);
        $('#amount').val("Rp."+rp+",00");
        $('#top').val(data.id_reference_type_of_payment);
        $('#tot').val(data.id_reference_type_transaction);
        $('#norek').val(data.id_no_rekening);
        $('#afiliasi').val(data.affiliation);
        $('#modalCreate').modal('show');
    });
});
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
                    filter();
                }else {
                    toastr.warning("Data Gagagal Di Simpan");
                }
            }
        }
    });
});

//detil
$('body').on('click','#my-btn-detil',function(){
    var id = $(this).data("id");
    $('#id').val(id);
    $.get(baseurl+'/transaction/detail/'+id,function (data){
        var norek = data[0]['data'][0].transaction.no_rek;
        var bank = data[0]['data'][0].transaction.bank;
        var nameNorek = data[0]['data'][0].transaction.name;
        $('#dateTransaction').text(': '+data[0]['data'][0].date);
        $('#invoiceTransaction').text(': '+data[0]['data'][0].transaction.no_invoice+'('+data[0]['data'][0].transaction.status+')');
        $('#customerTransaction').text(': '+data[0]['data'][0].transaction.customer);
        $('#totTransaction').text(': '+data[0]['data'][0].transaction.jenis_transaksi);
        $('#topTransaction').text(': '+data[0]['data'][0].transaction.jenis_pembayaran);
        $('#pacakageTransaction').text(': '+data[0]['data'][0].transaction.package);
        $('#amountTransaction').text(': '+data[0]['data'][0].nominal);
        $('#norekTransaction').text(': '+nameNorek+' ('+bank+'-'+norek+')');
        $('#modalDetil').modal('show');
    });
});
//detil invoice
function detilInvoice(){
    var id = $('#id').val();
    $('#modalDetilInvoice').modal('show');
    $.ajax({
        url: baseurl+'/transaction/getDetilInvoice/'+id,
        method: 'GET',
        data: {
            _token: token,
        },
        success: function (data) {
            // console.log(data[0].customer);
            var top = data[0].payment.top;
            var bank = data[0].payment.bank;
            var nama = data[0].payment.name;
            var norek = data[0].payment.no_rek;
            $('#noInvoice').text(data[0].invoice.no);
            $('#dueInvoice').text(data[0].due);
            $('#tglTransaction').text('Date: '+data[0].dateTransaction);
            $('#custName').text(data[0].customer.name);
            $('#phoneCustomer').text('Phone: '+data[0].customer.phone);
            $('#mailCust').text('Email: '+data[0].customer.email);
            $('#totalTransaction').text('Rp.'+data[0].transactionItem[0].amount);
            $('#payment').text(top+": "+bank+" - "+nama+" - "+norek);
            var html = "";
            for (let i =0;i<data[0].transactionItem.length;i++){
                html += "<tr>";
                html += "<td>"+(i+1)+"</td>";
                html += "<td>"+data[0].transactionItem[i].package+"</td>";
                html += "<td>"+data[0].transactionItem[i].type_package+"</td>";
                html += "<td>"+data[0].transactionItem[i].description+"</td>";
                html += "<td class='text-right'>"+"Rp."+data[0].transactionItem[i].amount+"</td>";
                html += "</tr>";
            }
            document.getElementById('itemTransaction').innerHTML = "";
            document.getElementById('itemTransaction').innerHTML = html;
        }
    });
}
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
function getPackageId(idpackage){
    $('#package').val(idpackage);
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
                if (id == idpackage){
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
function getTopId(idtop){
    $('#top').val(idtop);
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
                if (id == idtop){
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
function getTotId(idtot){
    $('#tot').val(idtot);
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
                if (id == idtot){
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
function getNorekId(idnorek){
    $('#norek').val(idnorek);
    $.ajax({
        url: baseurl+'/transaction/getNorek',
        method: 'GET',
        data: {
            _token: token,
        },
        success: function (data){
            var html = "";
            var titleSelect = "Pilih No Rekening";
            html += "<option value=''>"+titleSelect+"</option>";
            for (i=0;i<data.length;i++){
                var id = data[i].id;
                var description = data[i].description;
                var name = data[i].name;
                var norek = data[i].no_rek;
                if (id == idnorek){
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
            var titleSelect = "Pilih No Rekening";
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
//delete
$('body').on('click','#my-btn-delele',function (){
    var recId = $(this).data('id');
    $('#modalDelete').modal('show');
    $('#id').val(recId);
});
//submit delete
$('body').on('click','#submit-delete',function (e){
   e.preventDefault();
    var recId = $('#id').val();
    e.preventDefault();
    $.ajax({
        url: baseurl+'/transaction/destroy/'+recId,
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

//function filter
function filter(){
    var date = $('#filterDate').val();
    var norek = $('#filterNorek').val();
    var paket = $('#filterPaket').val();
    $('#data-table').DataTable({
        paging      : true,
        searching   : false,
        info        : true,
        ordering    : true,
        bDestroy    : true,
        ajax: {
            url: baseurl+'/transaction/search',
            type: "POST",
            data: {
                _token: token,
                date: date,
                norek: norek,
                paket: paket,
            },
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
