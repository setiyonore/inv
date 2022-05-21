<?php

namespace App\Http\Controllers;

use App\Traits\HelperMasterTraits;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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
}
