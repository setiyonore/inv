@extends('layouts.app')
@section('title-page','Referensi')
@section('includecss')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('content-header')
    <input type="hidden" id="url">
    <input type="hidden" id="token" value="{{csrf_token()}}">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Referensi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                <li class="breadcrumb-item active">Referensi</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
@section('main-content')
<select class="form-control select2" id="position-option" name="position_id" onclick="getReferensi()">
    @foreach ($type_reference as $position)
       <option value="{{ $position->id }}">{{ $position->description }}</option>
    @endforeach
 </select>

<?php $i=1 ?>
@forelse ($items as $item)
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td>{{$item->description}}</td>
                                        <td>{{$item->description}}</td>
                                            <form action=" " method="post" class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                    ?>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center p-5">
                                                Data Tidak Tersedia
                                            </td>
                                        </tr>
                                    @endforelse
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
        $('.select2').select2()
    </script>
    <script src="{{asset('functionjs/references.js')}}"></script>
@endsection
