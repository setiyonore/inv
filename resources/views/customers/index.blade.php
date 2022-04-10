@extends('layouts.app')
@section('title-page','Pelanggan')
@section('includecss')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<script src="{{asset('dist/jquery/jquery.min.js')}}"></script>
@endsection
@section('content-header')
    <input type="hidden" id="url">
    <input type="hidden" id="token" value="{{csrf_token()}}">
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
                        <button class="btn btn-info" data-toggle="modal" onclick="create()"><i class="fa fa-plus"></i> Tambah Data</button>
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
                    <table id="data-table" class="table table-striped table-hover">
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
    <!--modal create-->
    <div class="modal fade" tabindex="-1" id="modalCreate" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang("customer.addCustomer")</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form" name="form">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="name">@lang('global.name')</label>
                            <input type="text" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label for="phone">@lang('global.phone')</label>
                            <input type="text" id="phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email">@lang('global.email')</label>
                            <input type="text" id="email" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="simpan" class="btn btn-success">@lang('global.save')</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">@lang('global.close')</button>
                </div>
            </div>
        </div>
    </div>
   <!--modal delete-->
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
                    <p>@lang('customer.deleteConfirmation')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-delete" class="btn btn-danger">@lang('global.delete')</button>
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
    <script>
        var APP_URL = {!! json_encode(url('/')) !!}
        $('#url').val(APP_URL);
    </script>
    <script src="{{asset('functionjs/customers.js')}}"></script>
@endsection
