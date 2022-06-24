<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use App\Traits\HelperMasterTraits;

//models
use App\Models\MasterPackage;
use App\Models\Manpower;
class TaskListController extends Controller
{
    use HelperMasterTraits;
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
                'p.name as package','c.name as customer','r.description as status',
            'manpower.id_reference_working_status as status_id')
            ->where('manpower.id_employee',$idpegawai)
            ->where('manpower.id_reference_working_status','!=',config('config.idStatusSudahDikerjakan'))
            ->where('r.id_type_reference',config('config.IdTypeReference.statusPengerjaan'))
            ->get();
        if ($request->ajax()){
            return $this->getMake($data);

        }
        return view('task_list.index',compact('package'));
    }

    public function getStatus(){
        return $this->getDataReferensi(config('config.IdTypeReference.statusPengerjaan'));
    }

    public function update(Request $request){
        $data = Manpower::query()
            ->findOrFail($request->id);
        $data->id_reference_working_status = $request->status;
        $data->save();
        if ($data){
            return response()->json(['success'=>1]);
        } else {
            return response()->json(['success'=>0]);
        }
    }
    public function search(Request $request){
        $clause = [
            't.id_package' => $request['id_package'],
        ];
        $data = $this->doSearch($clause);
        return $this->getMake($data);
    }

    private function doSearch($clauses){
        $idpegawai = Auth::user()->pegawai_id;
        $data = Manpower::query()
            ->leftJoin('transactions as t','t.id','manpower.id_transaction')
            ->leftJoin('mst_package as p','p.id','t.id_package')
            ->leftJoin('customers as c','c.id','t.id_customer')
            ->leftJoin('references as r','r.id','manpower.id_reference_working_status')
            ->select('manpower.id','t.id as id_transaction','t.date',
                'p.name as package','c.name as customer','r.description as status',
                'manpower.id_reference_working_status as status_id')
            ->where('manpower.id_employee',$idpegawai)
            ->where('manpower.id_reference_working_status','!=',config('config.idStatusSudahDikerjakan'))
            ->where('r.id_type_reference',config('config.IdTypeReference.statusPengerjaan'));
        $fields = array_keys($clauses);
        $index = 0;
        foreach ($clauses as $item) {
            if ($item != null) {
                $data = $data->where($fields[$index], 'LIKE', '%' . $item . '%');
            }
            $index++;
        }
        $data = $data->get();
        return $data;
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function getMake($data)
    {
        return DataTables::of($data)
            ->addColumn('date', function ($row) {
                return Carbon::parse($row->date)->format('d/m/Y');
            })
            ->addColumn('customer', function ($row) {
                return $row->customer;
            })
            ->addColumn('package', function ($row) {
                return $row->package;
            })
            ->addColumn('status', function ($row) {
                return $row->status;
            })
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="' . $row->id . '" data-status-id="' . $row->status_id . '" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>';
            })
            ->rawColumns(['date', 'customer', 'package', 'status', 'action'])
            ->make(true);
    }
}
