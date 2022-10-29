@extends('layouts.app')
@section('title-page','Roles')
@section('includecss')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <script src="{{asset('dist/js/jquery.min.js')}}"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <style>
        .modal {overflow: auto !important;}
    </style>
@endsection
@section('content-header')
    <input type="hidden" id="url">
    <input type="hidden" id="token" value="{{csrf_token()}}">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">@lang('roles.title')</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                <li class="breadcrumb-item active">@lang('roles.title')</li>
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
                        <button class="btn btn-info" data-toggle="modal" onclick="createRole()" ><i class="fa fa-plus"></i> @lang('global.addData')</button>
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
                <div class="card-body table-responsive">
                    <table id="data-table" class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>@lang('global.name')</th>
                            <th>@lang('global.action')</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- modal create role-->
    <div class="modal fade" id="modalCreateRole" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('roles.addRole')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form" name="form">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="short">Role</label>
                            <input type="text" id="role" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="simpan" class="btn btn-success">@lang('global.save')</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">@lang('global.cancel')</button>
                </div>
            </div>
        </div>
    </div>
    {{--  Modal detil permission  --}}
    <div class="modal fade" id="modalDetil" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @lang('roles.detilPermission')
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <button class="btn btn-info" onclick="addPermission()">@lang('roles.addPermission')</button>
                    <br>
                    <br>
                    {{--  Table permission    --}}
                    <input type="hidden" id="idRoles">
                    <input type="hidden" id="idPermission">
                    <div class="table table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>@lang('roles.permission')</th>
                                    <th class="text-right">@lang('global.action')</th>
                                </tr>
                            </thead>
                            <tbody id="dataPermission">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal add permission   --}}
    <div class="modal fade" id="modalAddPermission" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('roles.addPermission')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" role="form" id="formBenefit">
                        <label for="">@lang('roles.addPermission')</label>
                        <select class="form-control select2" id="permission">
                            <option value="">@lang('roles.selectPermission')</option>
                            @foreach($dataRole as $val)
                                <option value="{{$val->id}}">{{$val->name}}</option>
                            @endforeach
                        </select>
                        <div class="modal-footer">
                            <button type="button" id="savePermission" class="btn btn-success">@lang('global.save')</button>
                            <button type="button" class="btn btn-warning" data-dismiss="modal">@lang('global.close')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--   Modal permission delete  --}}
    <div class="modal fade" id="modalDeletePermission" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('global.confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('roles.deletePermission')</p>
                    <input type="hidden" id="idBenefit">
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-delete-permission" class="btn btn-danger">@lang('global.delete')</button>
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
    <script>
        var APP_URL = {!! json_encode(url('/')) !!}
        $('#url').val(APP_URL);
        $('.select2').select2();
    </script>
    <script src="{{asset('functionjs/roles.js')}}"></script>
@endsection
