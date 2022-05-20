<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Reference;
use App\Models\TypeReference;
use App\Traits\HelperMasterTraits;
use Auth;

class ReferencesController extends Controller
{
    use HelperMasterTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $typeReference = TypeReference::query()
                ->select('id','description','short')
                ->get();
        return view('references.index',compact('typeReference'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(),[
            'id_type_reference' => 'required',
            'description' => 'required',
        ]);
         if ($validator->fails()){
             return response()->json(['errors'=>$validator->errors()->all()]);
         }
         $data = Reference::query()
             ->firstOrNew(array('id'=>$request->id));
         $data->id_type_reference = $request->id_type_reference;
         $data->description = $request->description;
         $data->save();
         if ($data){
             return response()->json(['success'=>1]);
         } else {
             return response()->json(['success'=>0]);
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Reference::query()
        ->leftJoin('type_references as tr','tr.id','references.id_type_reference')
        ->select('tr.id as id_type_reference','references.id','references.description')
        ->where('references.id',$id)
        ->first();
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Reference::query()->findOrFail($id);
        $data->delete();
        return response()->json(['success'=>1]);
    }

    public function filter(Request $request){
       $data = Reference::query()
       ->where('id_type_reference',$request->id)
       ->get();
       if($request->ajax()){
           return DataTables::of($data)
           ->addColumn('description',function($row){
               return $row->description;
           })
           ->addColumn('action',function ($row){
            return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="my-btn-delele" data-id="'.$row->id.'" ><i class="fa fa-trash"></i></a>';
            })
            ->rawColumns(['description','action'])
            ->make(true);

       }
    }

    public function getTypeReference(){
        $data = $this->getTypeReferensi();
        return $data;
    }
}
