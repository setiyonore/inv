<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        $role = Role::findByName('admin');
        $role->permissions->pluck('name');

        $data = Role::query()->select('id','name')->get();
        if ($request->ajax()){
            return DataTables::make($data)
                ->addColumn('name',function ($row){
                    return $row->name;
                })
                ->addColumn('action',function ($row){
                    return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-eye"></i></a>';
                })
                ->rawColumns(['name','action'])
                ->make(true);
        }

        return view('roles.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'role' => 'required'
        ]);
        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $role = Role::create(['name' => strtolower($request->role)]);
        if ($role){
            return response()->json(['success'=>1]);
        } else {
            return response()->json(['success'=>0]);
        }
    }
}
