@extends('layouts.app')
@section('title-page','Karyawan')
@section('includecss')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <script src="{{asset('dist/js/jquery.min.js')}}"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('content-header')
    <input type="hidden" id="url">
    <input type="hidden" id="token" value="{{csrf_token()}}">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">@lang('employees.title')</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                <li class="breadcrumb-item active">@lang('employees.title')</li>
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
                        <form role="form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="filterName">@lang('global.name')</label>
                                        <input type="text" class="form-control" id="filterName" name="filterName">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="filterNip">@lang('employees.nip')</label>
                                        <input type="text" class="form-control" id="filterNip" name="filterNip">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="filterDivisi">@lang('employees.division')</label>
                                        <select name="filterDivision" id="filterDivision" class="form-control select2" style="width: 100%;">
                                            <option value="">Pilih Divisi</option>
                                            @foreach($divisi as $val)
                                                <option value="{{$val->id}}">{{$val->description}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="data-table" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>@lang('global.name')</th>
                                <th>@lang('employees.nip')</th>
                                <th>@lang('employees.division')</th>
                                <th>@lang('global.phone')</th>
                                <th>@lang('global.action')</th>
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
                    <h5 class="modal-title">@lang("employees.add")</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form" name="form">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label>@lang("global.name")</label>
                            <input type="text" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label>@lang("employees.nip")</label>
                            <input type="text" class="form-control" id="nip" name="nip">
                        </div>
                        <div class="form-group">
                            <label>@lang("global.phone")</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label>@lang("employees.division")</label>
                            <select name="division" id="division" class="form-control select2" style="width: 100%;">

                            </select>
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
    <script>
        var APP_URL = {!! json_encode(url('/')) !!}
        $('#url').val(APP_URL);
        $('.select2').select2();
    </script>
    <script src="{{asset('functionjs/employees.js')}}"></script>
@endsection
