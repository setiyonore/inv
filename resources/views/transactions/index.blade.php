@extends('layouts.app');
@section('title-page','Transaksi')
@section('includecss')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <script src="{{asset('dist/js/jquery.min.js')}}"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <style>
        .modal { overflow: auto !important; }
        /*.select2-dropdown--below {*/
        /*    top: -2.8rem; !*your input height*!*/
        /*}*/
    </style>
@endsection
@section('content-header')
    <input type="hidden" id="url">
    <input type="hidden" id="token" value="{{csrf_token()}}">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">@lang('transaction.title')</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                <li class="breadcrumb-item active">@lang('transaction.title')</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
@section('main-content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="col-xs-12">
                        <button class="btn btn-info" data-toggle="modal" onclick="create()"><i class="fa fa-plus"></i> @lang('global.addData')</button>
                        <div class="btn-group float-right">
                            <a href="#"  class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Print">
                                <i class="fa fa-print"></i>
                            </a>
                            <a href="#" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Export Excell">
                                <i class="fas fa-file-excel"></i>
                            </a>
                            <a href="#" class="btn btn-default" data-toggle="tooltip" title="Export PDF">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            <button class="btn btn-default" data-toggle="tooltip" title="Refresh">
                                <i class="fas fa-redo"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-xs-12">
                        <form action="" role="form" id="form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">@lang('transaction.date')</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                              </span>
                                            </div>
                                            <input type="text" class="form-control float-right" id="filterDate">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">@lang('transaction.norek')</label>
                                        <select id="filterNorek" class="form-control select2">
                                            <option value="">@lang('transaction.selectNorek')</option>
                                            @foreach($norek as $val)
                                                <option value="{{$val->id}}">{{$val->name}} ({{$val->description}}-{{$val->no_rek}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">@lang('transaction.package')</label>
                                        <select id="filterPaket" class="form-control select2">
                                            <option value="">@lang('transaction.selectPackage')</option>
                                            @foreach($paket as $val)
                                                <option value="{{$val->id}}">{{$val->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="box-footer">
                            <button class="btn btn-success" onclick="filter()"><i class="fas fa-filter"></i> @lang("global.filter")</button>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="data-table" class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>@lang('transaction.customer')</th>
                            <th>@lang('transaction.date')</th>
                            <th>@lang('transaction.package')</th>
                            <th>@lang('transaction.amount')</th>
                            <th>@lang('global.action')</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- modal create -->
    <div class="modal fade" id="modalCreate" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('transaction.add')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" role="form" id="form">
                        <input type="hidden" id="id">
                        <input type="hidden" id="iduser">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">@lang('transaction.date')</label>
                                    <input type="date" id="date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">@lang('transaction.customer')</label>
                                    <select class="form-control select2" id="customer">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">@lang('transaction.package')</label>
                                    <select name="package" id="package" class="form-control select2" onchange="getPricePackage()">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">@lang('transaction.amount')</label>
                                    <input type="text" class="form-control" id="amount" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">@lang('transaction.typeOfPayment')</label>
                                    <select name="top" id="top" class="form-control select2"></select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">@lang('transaction.typeOfTransaction')</label>
                                    <select name="tot" id="tot" class="form-control select2"></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">@lang('transaction.norek')</label>
                                    <select name="norek" id="norek" class="form-control select2"></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">@lang('transaction.affiliation')</label>
                                    <input type="text" class="form-control" id="afiliasi">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="simpan" class="btn btn-success">@lang('global.save')</button>
                            <button type="button" class="btn btn-warning" data-dismiss="modal">@lang('global.close')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- delete modal -->
    <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('global.confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('transaction.deleteTransaction')</p>
                    <input type="hidden" id="idFN">
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-delete" class="btn btn-danger">@lang('global.delete')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('global.cancel')</button>
                </div>
            </div>
        </div>
    </div>
    <!-- modal detail transaction-->
    <div class="modal fade" id="modalDetil" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @lang('transaction.detailTransaction')
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h5 class="text-left">@lang('transaction.date')</h5>
                        </div>
                        <div class="col-md-6">
                            <h5 id="dateTransaction"></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <h5 class="text-left">@lang('transaction.invoice')</h5>
                        </div>
                        <div class="col-md-8">
                            <h5 id="invoiceTransaction"></h5>
                        </div>
                        <div class="col-1">
                            <button class="btn btn-info text-right" onclick="detilInvoice()"><i class="fas fa-file-invoice"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <h5 class="text-left">
                                @lang('transaction.customer')
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <h5 id="customerTransaction">

                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <h5 class="text-left">@lang('transaction.typeOfTransaction')</h5>
                        </div>
                        <div class="col-md-6">
                            <h5 id="totTransaction"></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <h5 class="text-left">@lang('transaction.typeOfPayment')</h5>
                        </div>
                        <div class="col-md-6">
                            <h5 id="topTransaction"></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <h5 class="text-left">
                                @lang('transaction.package')
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <h5 id="pacakageTransaction"></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <h5 class="text-left">@lang('transaction.amount')</h5>
                        </div>
                        <div class="col-md-6">
                            <h5 id="amountTransaction"></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <h5 class="text-left">@lang('transaction.norek')</h5>
                        </div>
                        <div class="col-md-8">
                            <h5 id="norekTransaction"></h5>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">@lang('global.close')</button>
                </div>
            </div>
        </div>
    </div>
    <!-- modal invoice detil -->
    <div class="modal fade" id="modalDetilInvoice" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('transaction.detailInvoice')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="invoice p-3 mb-3">
                        <!-- title row -->
                        <div class="row">
                            <div class="col-12">
                                <h4>
{{--                                    <i class="fas fa-globe"></i> CV. Jakad Media Publishing--}}
                                    <img src="{{url('/img/jakadid.png')}}" alt="" style="width: 17%">
                                    <small class="float-right"><h5 id="tglTransaction"></h5></small>
                                </h4>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                From
                                <address>
                                    <strong>CV. Jakad Media Publishing</strong><br>
                                    Graha Indah E-11, Gayung Kebonsari<br>
                                    Gayungan Surabaya<br>
                                    Phone: 081230444797<br>
                                    Email: jakadmedia@gmail.com
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                To
                                <address>
                                    <strong id="custName"></strong><br>
                                    <h6 id="phoneCustomer"></h6>
                                    <h6 id="mailCust"></h6>
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                <b id="noInvoice"></b><br>
                                <br>
                                <b>Payment Due:</b> <h6 id="dueInvoice"></h6><br>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- Table row -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Package</th>
                                        <th>Type Of Package</th>
                                        <th>Description</th>
                                        <th class="text-right">@lang('transaction.subtotal')</th>
                                    </tr>
                                    </thead>
                                    <tbody id="itemTransaction">
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <div class="row">
                            <!-- accepted payments column -->
                            <div class="col-6">
                                <p class="lead">Payment Methods:</p>
                                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;" id="payment">
                                </p>
                            </div>
                            <!-- /.col -->
                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table text-right">
                                        <tr>
                                            <th>Total:</th>
                                            <td><h6 id="totalTransaction"></h6></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- this row will not appear when printing -->
                        <div class="row no-print">
                            <div class="col-12">
                                <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                                <button type="button" class="btn btn-success float-right" onclick="updateStatusInvoice()"><i class="far fa-credit-card"></i> Update Status Terbayar
                                </button>
                                <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;" onclick="exportPdf()">
                                    <i class="fas fa-download"></i> Generate PDF
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.invoice -->
                </div>
            </div>
        </div>
    </div>
    <!-- modal update status invoice -->
    <div class="modal fade" id="modalUpdateInvoice" role="dialog">
        <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('transaction.updateInvoice')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>@lang('transaction.updateInvoiceMessage')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">@lang('global.close')</button>
                        <button type="button" class="btn btn-success" id="my-btn-update-invoice">@lang('global.update')</button>
                    </div>
            </div>
        </div>
    </div>
    <!-- modal Manpower -->
     <div class="modal fade" id="modalManpower" role="dialog">
         <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title">@lang('transaction.manpower')</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
                 <div class="modal-body">
                     <div class="row">
                         <div class="col-6 text-left">
                             <h6 id="decriptionTr"></h6>
                         </div>
                         <div class="col-6 text-right">
                             <button class="btn btn-info" onclick="CreateManpower()">Tambah Manpower</button>
                         </div>
                     </div>
                     <br>
                     <div class="table table-responsive">
                         <table class="table table-striped">
                             <thead>
                             <tr>
                                 <th>@lang('global.no')</th>
                                 <th>@lang('global.name')</th>
                                 <th>@lang('employees.division')</th>
                                 <th>@lang('global.status')</th>
                                 <th>@lang('global.action')</th>
                             </tr>
                             </thead>
                             <tbody id="dataManpower"></tbody>
                         </table>
                     </div>
                 </div>
             </div>
         </div>
     </div>
    <!-- modal create manpower-->
    <div class="modal fade" id="modalCreateManpower" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('transaction.addManpower')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="formManpower" role="form">
                        <label>@lang('transaction.manpower')</label>
                        <select name="" id="idManpower" class="form-control select2">
                            <option value="">Pilih Pegawai</option>
                            @foreach($employee as $val)
                                <option value="{{$val->id}}">{{$val->name}} - {{$val->division}}</option>
                            @endforeach
                        </select>
                        <div class="modal-footer">
                            <button type="button" id="simpanManpower" class="btn btn-success">@lang('global.save')</button>
                            <button type="button" class="btn btn-warning" data-dismiss="modal">@lang('global.close')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- modal delete manpower -->
    <div class="modal fade" id="modalDeleteManpower" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('global.confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('transaction.deleteManpower')</p>
                    <input type="hidden" id="idMan">
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-delete-manpower" class="btn btn-danger">@lang('global.delete')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('global.cancel')</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- DataTables  & Plugins -->
    <script src="{{asset('plugins/datatables/datatables.min.js')}}"></script>
    <!-- Toastr -->
    <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('plugins/moment/locale/id.js')}}"></script>
    <script>
        var APP_URL = {!! json_encode(url('/')) !!}
        $('#url').val(APP_URL);
        $('.select2').select2();
    </script>
    <script src="{{asset('functionjs/transaction.js')}}"></script>
@endsection
