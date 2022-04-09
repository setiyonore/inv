@extends('layouts.app')
@section('title-page','Pelanggan')
@section('includecss')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<script src="{{asset('dist/jquery/jquery.min.js')}}"></script>
@endsection
@section('content-header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Pelanggan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                <li class="breadcrumb-item active">Pelanggan</li>
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
                        <button class="btn btn-info"><i class="fa fa-plus"></i> Tambah Data</button>
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
                        <form role="form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="filterName">@lang("global.name")</label>
                                        <input type="text" class="form-control" id="filterName" name="filterName">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="filterMail">@lang("global.email")</label>
                                        <input type="text" class="form-control" id="filterMail" name="filterMail">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="filterPhone">@lang("global.phone")</label>
                                        <input type="text" class="form-control" id="filterPhone" name="filterPhone">
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button class="btn btn-success"><i class="fas fa-filter"></i> @lang("global.filter")</button>
                            </div>
                        </form>
                    </div>
                    <hr>
                </div>
                <div class="card-body table-responsive">
                    <table id="data-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>@lang("global.name")</th>
                                <th>@lang("global.email")</th>
                                <th>@lang("global.phone")</th>
                                <th>@lang("global.action")</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- DataTables  & Plugins -->
    <script src="{{asset('plugins/datatables/datatables.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
            getData();
        });
        function getData(){
            $('#data-table').DataTable({
                paging      : true,
                searching   : false,
                info        : true,
                ordering    : false,
                bDestroy    : true,
                ajax: {
                    url: "{{ route('customers.index') }}",
                    type: "GET",
                },
                columns:[
                    { data: 'name'},
                    { data: 'phone'},
                    { data: 'email'},
                    { data: 'action'}
                ]
            });
        }
    </script>
@endsection
