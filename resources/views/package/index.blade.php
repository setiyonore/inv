@extends('layouts.app')
@section('title-page','Paket')
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
        .modal { overflow: auto !important; }
    </style>
@endsection
@section('content-header')
    <input type="hidden" id="url">
    <input type="hidden" id="token" value="{{csrf_token()}}">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">@lang('package.title')</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                <li class="breadcrumb-item active">@lang('package.title')</li>
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
                        <button class="btn btn-info" data-toggle="modal" onclick="createPackage()"><i class="fa fa-plus"></i> @lang('global.addData')</button>
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
                        <form action="" role="form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">@lang('global.name')</label>
                                        <input type="text" class="form-control" id="filterName">
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
                                <th>@lang('global.name')</th>
                                <th>@lang('package.description')</th>
                                <th>@lang('package.price')</th>
                                <th>@lang('global.action')</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--Modal create -->
    <div class="modal fade" id="modalCreate" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('package.add')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" role="form" id="form">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="">@lang('package.typePackage')</label>
                            <select name="typePackage" id="typePackage" class="form-control select2"></select>
                        </div>
                        <div class="form-group">
                            <label for="">@lang('global.name')</label>
                            <input type="text" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label for="">@lang('package.description')</label>
                            <textarea class="form-control" rows="5" id="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">@lang('package.price')</label>
                            <input type="hidden" id="hargaOld">
                            <input type="text" class="form-control" id="price" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" datatype="currency">
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
                    <p>@lang('package.deleteConfirmation')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-delete" class="btn btn-danger">@lang('global.delete')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('global.cancel')</button>
                </div>
            </div>
        </div>
    </div>
    <!--modal detil-->
    <div class="modal fade" id="modalDetil" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @lang('package.detil')
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2"><h5 class="text-left">@lang('package.namePackage')</h5></div>
                        <div class="col-md-6"><h5 id="titleDetil"></h5></div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><h5 class="text-left">@lang('package.typePackage')</h5></div>
                        <div class="col-md-6"><h5 id="typeDetail"></h5></div>
                    </div>
                    <p id="decriptionDetil"></p>
                    <div class="row">
                        <div class="col-6 text-left"><b>@lang('package.benefit')</b></div>
                        <div class="col-6 text-right"><button class="btn btn-info" onclick="createBenefit()">@lang('package.addBenefit')</button></div>
                    </div>
                    <br>
                    <!--table benefit-->
                    <div class="table table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('package.no')</th>
                                    <th>@lang('package.item')</th>
                                    <th class="text-right">@lang('global.action')</th>
                                </tr>
                            </thead>
                            <tbody id="dataBenefit">
                            </tbody>
                        </table>
                    </div>
                    <!--table format naskah-->
                    <div class="row">
                        <div class="col-6 text-left"><b>@lang('package.formatNaskah')</b></div>
                        <div class="col-6 text-right"><button class="btn btn-info" onclick="createFN()">@lang('package.addFormatNaskah')</button></div>
                    </div>
                    <br>
                    <div class="table table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('package.no')</th>
                                    <th>@lang('package.item')</th>
                                    <th class="text-right">@lang('global.action')</th>
                                </tr>
                            </thead>
                            <tbody id="dataFormatNaskah"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal create benefit-->
    <div class="modal fade" id="modalCreateBenefit" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('package.addBenefit')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" role="form" id="formBenefit">
                        <label for="">@lang('package.benefit')</label>
                        <select class="form-control select2" id="benefit">
                            <option value="">@lang('package.selectBenefit')</option>
                            @foreach($benefit as $val)
                                <option value="{{$val->id}}">{{$val->description}}</option>
                            @endforeach
                        </select>
                        <div class="modal-footer">
                            <button type="button" id="simpanBenefit" class="btn btn-success">@lang('global.save')</button>
                            <button type="button" class="btn btn-warning" data-dismiss="modal">@lang('global.close')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- modal create format naskah -->
    <div class="modal fade" id="modalCreateFN" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('package.addFormatNaskah')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" role="form" id="formFN">
                        <div class="form-group">
                            <label for="">@lang('package.formatNaskah')</label>
                            <select class="form-control select2" name="formatNaskah" id="formatNaskah">
                                <option value="">@lang('package.selectFormatNaskah')</option>
                                @foreach($format as $val)
                                    <option value="{{$val->id}}">{{$val->description}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="simpanFN" class="btn btn-success">@lang('global.save')</button>
                            <button type="button" class="btn btn-warning" data-dismiss="modal">@lang('global.close')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--modal delete format naskah-->
    <div class="modal fade" id="modalDeleteFN" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('global.confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('package.deleteFormatNaskah')</p>
                    <input type="hidden" id="idFN">
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-delete-format" class="btn btn-danger">@lang('global.delete')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('global.cancel')</button>
                </div>
            </div>
        </div>
    </div>
    <!--modal delete benefit-->
    <div class="modal fade" id="modalDeleteBenefit" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('global.confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('package.deleteBenefit')</p>
                    <input type="hidden" id="idBenefit">
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-delete-benefit" class="btn btn-danger">@lang('global.delete')</button>
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
    <script src="{{asset('functionjs/package_master.js')}}"></script>
@endsection
