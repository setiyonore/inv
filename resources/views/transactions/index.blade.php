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
                                            <input type="text" class="form-control float-right" id="reservation">
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
