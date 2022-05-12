<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
/*Models*/
use App\Models\Employee;

use Config;
use App\Traits\HelperMasterTraits;
class EmployeesController extends Controller
{
    use HelperMasterTraits;
    public function index(Request $request){
        $data = Employee::leftJoin('references as r','r.id','id_reference')
            ->select(
                'employees.id',
                'employees.name',
                'employees.nip',
                'employees.phone',
                'r.description as division')
            ->where('r.id_type_reference',config('config.IdTypeReference.Divisi'))
            ->get();
        if ($request->ajax()){
            return DataTables::of($data)
                ->addColumn('name',function ($row){
                    return $row->name;
                })
                ->addColumn('nip',function ($row){
                    return $row->nip;
                })
                ->addColumn('division',function ($row){
                    return $row->division;
                })
                ->addColumn('phone',function ($row){
                    return $row->phone;
                })
                ->addColumn('action',function ($row){
                    return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="my-btn-delele" data-id="'.$row->id.'" ><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['name','nip','division','phone','action'])
                ->make(true);
        }
        $divisi = $this->getDivisi();
        return view('employees.index',compact('divisi'));
    }

    public function store(Request $request){
       $validator = Validator::make($request->all(),[
           'nama' => 'required',
           'nip' => 'required',
           'telepon' => 'required',
           'divisi' => 'required',
       ]);
        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $data = Employee::query()
            ->firstOrNew(array('id'=>$request->id));
        $data->name = $request->nama;
        $data->phone = $request->telepon;
        $data->nip = $request->nip;
        $data->id_reference = $request->divisi;
        $data->save();
        if ($data){
            return response()->json(['success'=>1]);
        } else {
            return response()->json(['success'=>0]);
        }
    }
    public function getDivision(){
        return $this->getDivisi();
    }
}
