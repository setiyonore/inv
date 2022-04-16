<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\TypeReference;

class TypeReferencesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = TypeReference::all();
        // dd($data);
        if ($request->ajax()){
            return DataTables::of($data)
                ->addColumn('short',function ($row){
                    return $row->short;
                })
                ->addColumn('description',function ($row){
                    return $row->description;
                })
                ->addColumn('action',function ($row){
                    return 
                    ' <a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>
                    <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="my-btn-delele" data-id="'.$row->id.'" ><i class="fa fa-trash"></i></a> ';
                })
                ->rawColumns(['description','short','action'])
                ->make(true);
        }
        return view('references.index');
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
        $validator = Validator::make($request->all(),[
            'short' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $data = TypeReference::query()
            ->firstOrNew(array('id'=>$request->id));

        $data->short = $request->short;
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
        $data = TypeReference::findOrFail($id);
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
        $data = TypeReference::findOrFail($id);
        $data->delete();
        return response()->json(['success'=>1]);
    }
}
