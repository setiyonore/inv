var baseurl = $('#url').val();
var token = $('#token').val();
$(document).ready(function (){
    $('[data-toggle="tooltip"]').tooltip();
    getData();
})

//format currency
$('#price').on({
    keyup: function() {
        formatCurrency($(this));
    },
    blur: function() {
        formatCurrency($(this), "blur");
    }
});
function formatNumber(n) {
    // format number 1000000 to 1,234,567
    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}
function formatCurrency(input, blur) {
    // appends $ to value, validates decimal side
    // and puts cursor back in right position.

    // get input value
    var input_val = input.val();

    // don't validate empty input
    if (input_val === "") { return; }

    // original length
    var original_len = input_val.length;

    // initial caret position
    var caret_pos = input.prop("selectionStart");

    // check for decimal
    if (input_val.indexOf(".") >= 0) {

        // get position of first decimal
        // this prevents multiple decimals from
        // being entered
        var decimal_pos = input_val.indexOf(".");

        // split number by decimal point
        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);

        // add commas to left side of number
        left_side = formatNumber(left_side);

        // validate right side
        right_side = formatNumber(right_side);

        // On blur make sure 2 numbers after decimal
        if (blur === "blur") {
            right_side += "00";
        }

        // Limit decimal to only 2 digits
        right_side = right_side.substring(0, 2);

        // join number by .
        input_val = "Rp " + left_side + "." + right_side;

    } else {
        // no decimal entered
        // add commas to number
        // remove all non-digits
        input_val = formatNumber(input_val);
        input_val = "Rp " + input_val;

        // final formatting
        if (blur === "blur") {
            input_val += ".00";
        }
    }

    // send updated string to input
    input.val(input_val);

    // put caret back in the right position
    var updated_len = input_val.length;
    caret_pos = updated_len - original_len + caret_pos;
    input[0].setSelectionRange(caret_pos, caret_pos);
}

function getData(){
    $('#data-table').DataTable({
        paging      : true,
        searching   : false,
        info        : true,
        ordering    : true,
        bDestroy    : true,
        ajax:   {
            url: baseurl+'/package',
            type: 'GET',
        },
        columns:[
            { data: 'name'},
            { data: 'description'},
            { data: 'price'},
            { data: 'action'},
        ]
    })
}

function create(){
    $('#form').trigger("reset");
    $('#modalCreate').modal('show');
}

//store
$('#simpan').click(function (e){
  e.preventDefault();
  var id = $('#id').val();
  var name = $('#name').val();
  var description = $('#description').val();
  var price = $('#price').val();
  var removeRp = price.substring(3);
  var removeLast3 = removeRp.slice(0,-3);
  var finalPrice = parseFloat(removeLast3.replace(/,/g,''));
  var hargaOld = $('#hargaOld').val();
  $.ajax({
      url: baseurl+'/package/store',
      method: 'POST',
      data: {
          _token: token,
          id: id,
          nama: name,
          deskripsi: description,
          harga: finalPrice,
          hargaOld: hargaOld
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
})
//detil
$('body').on('click','#my-btn-detil',function (){
    var id = $(this).data("id");
    $.get(baseurl+'/package/detil/'+id,function (data){
        $('#modalDetil').modal('show');
    })
})
//edit
$('body').on('click','#my-btn-edit',function (){
    var id = $(this).data("id");
    $.get(baseurl+'/package/edit/'+id,function (data) {
        $('#id').val(data.id);
        $('#name').val(data.name);
        $('#description').val(data.description);
        $('#price').val(data.price);
        $('#hargaOld').val(data.price);
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
        url: baseurl+'/package/destroy/'+recId,
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
    var name = $('#filterName').val();
    $('#data-table').DataTable({
        paging      : true,
        searching   : false,
        info        : true,
        ordering    : true,
        bDestroy    : true,
        ajax: {
            url: baseurl+'/package/search',
            type: "GET",
            data: {
                filterName: name,
            },
        },
        columns:[
            { data: 'name'},
            { data: 'description'},
            { data: 'price'},
            { data: 'action'},
        ]
    });
}
