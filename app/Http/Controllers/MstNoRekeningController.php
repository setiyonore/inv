<?php

namespace App\Http\Controllers;

use App\Traits\HelperMasterTraits;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

/*models*/
use App\Models\MasterNoRekening;

class MstNoRekeningController extends Controller
{
    use HelperMasterTraits;
    public function index(Request $request){
        $data = MasterNoRekening::query()
            ->leftJoin('references as r','r.id','mst_no_rekening.id_reference_bank')
            ->select('mst_no_rekening.id','r.description','mst_no_rekening.name','mst_no_rekening.no_rek')
            ->where('r.id_type_reference',config('config.IdTypeReference.Bank'))
            ->get();
        if ($request->ajax()){
            return  DataTables::of($data)
                ->addColumn('bank',function ($row){
                    return $row->description;
                })
                ->addColumn('no_rek',function ($row){
                    return $row->no_rek;
                })
                ->addColumn('name',function ($row){
                    return $row->name;
                })
                ->addColumn('action',function ($row){
                    return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="my-btn-delele" data-id="'.$row->id.'" ><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['bank','no_rek','name','action'])
                ->make(true);
        }
        $bank = $this->getBank();
        return view('no_rek.index',compact('bank'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'bank' => 'required',
            'noRekening' => 'required',
            'nama' => 'required',
        ]);
        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $data = MasterNoRekening::query()
            ->firstOrNew(array('id'=>$request->id));
        $data->id_reference_bank = $request->bank;
        $data->no_rek = $request->noRekening;
        $data->name = $request->nama;
        $data->save();
        if ($data){
            return response()->json(['success'=>1]);
        } else {
            return response()->json(['success'=>0]);
        }
    }

    public function edit($id){
        $data = MasterNoRekening::query()
            ->where('id',$id)
            ->first();
        return response()->json($data);
    }

    public function getBankReferensi(){
        return $this->getBank();
    }
}
