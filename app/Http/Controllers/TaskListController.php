<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

//models
use App\Models\MasterPackage;
use App\Models\Manpower;
class TaskListController extends Controller
{
    public function index(Request $request){
        $package = MasterPackage::query()
            ->select('id','name')
            ->get();
        $idpegawai = Auth::user()->pegawai_id;
        $data = Manpower::query()
            ->leftJoin('transactions as t','t.id','manpower.id_transaction')
            ->leftJoin('mst_package as p','p.id','t.id_package')
            ->leftJoin('customers as c','c.id','t.id_customer')
            ->leftJoin('references as r','r.id','manpower.id_reference_working_status')
            ->select('manpower.id','t.id as id_transaction','t.date',
                'p.name as package','c.name as customer','r.description as status')
            ->where('manpower.id_employee',$idpegawai)
            ->where('manpower.id_reference_working_status',config('config.idStatusBelumDikerjakan'))
            ->where('r.id_type_reference',config('config.IdTypeReference.statusPengerjaan'))
            ->get();
        if ($request->ajax()){
            return DataTables::of($data)
                ->addColumn('date',function ($row){
                    return Carbon::parse($row->date)->format('d/m/Y');
                })
                ->addColumn('customer',function ($row){
                    return $row->customer;
                })
                ->addColumn('package',function ($row){
                    return $row->package;
                })
                ->addColumn('status',function ($row){
                    return $row->status;
                })
                ->addColumn('action',function ($row){
                    return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-info btn-sm" id="my-btn-detil" data-id="'.$row->id.'"><i class="fa fa-file"></i></a>';
                })
                ->rawColumns(['date','customer','package','status','action'])
                ->make(true);

        }
        return view('task_list.index',compact('package'));
    }
}
