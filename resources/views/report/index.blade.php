@extends('layouts.app')
@section('title-page', 'Report')
@section('includecss')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <script src="{{asset('dist/js/jquery.min.js')}}"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <style>
        .modal { overflow: auto !important; }
    </style>
@endsection
@section('content-header')
    <input type="hidden" id="url">
    <input type="hidden" id="token" value="{{csrf_token()}}">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">@lang('report.title')</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                <li class="breadcrumb-item active">@lang('report.title')</li>
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
                        <div class="btn-group float-right">
                            <a href="#"  class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Print">
                                <i class="fa fa-print"></i>
                            </a>
                            <a href="#" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Export Excell" onclick="exportExcel()">
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
                        <form action="" role="form">
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
                                        <label for="">@lang('transaction.package')</label>
                                        <select name="" id="filterPackage" class="form-control select2">
                                            <option value="">Pilih Paket</option>
                                            @foreach($package as $val)
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
                            <th>@lang('transaction.date')</th>
                            <th>@lang('transaction.package')</th>
                            <th class="text-right">@lang('transaction.amount')</th>
                        </tr>
                        </thead>
                    </table>
                    <hr>
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6 text-right">
                            <h6 id="amount"></h6>
                        </div>
                    </div>
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
            <script src="{{asset('functionjs/report.js')}}"></script>
@endsection
