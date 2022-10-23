<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Matrix\Builder;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
//models
use App\Models\Employee;
use App\User;
class UserController extends Controller
{
    public function index(Request $request){
        $data = User::query()
            ->with('roles')
            ->leftJoin('employees as e','e.id','users.pegawai_id')
            ->select(
                'users.name',
                'users.email',
                'users.id',
                'e.name as pegawai'
            )
            ->get();
//        dd($data->toJson());
        if ($request->ajax()){
            return DataTables::make($data)
                ->addColumn('name',function ($row){
                    return $row->name;
                })
                ->addColumn('email',function ($row){
                    return $row->email;
                })
                ->addColumn('employee',function ($row){
                    return $row->pegawai;
                })
                ->addColumn('role',function ($row){
                    foreach ($row['roles'] as $data){
                        return $data['name'];
                    };
                })
                ->addColumn('action',function ($row){
                    return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="my-btn-delele" data-id="'.$row->id.'" ><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['name','email','employee','role','action'])
                ->make(true);
        }
        return view('users.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'pegawai' => 'required',
            'role' => 'required'
        ]);
        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $role = Role::findById($request->role);
        $user = User::query()->create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt(12345678),
            'pegawai_id'=>$request->pegawai
        ]);
        $user->assignRole($role);
        if ($user){
            return response()->json(['success'=>1]);
        } else {
            return response()->json(['success'=>0]);
        }
    }
    public function getRoles(){
        $data = Role::query()
            ->select('id','name')
            ->get();
        return response()->json($data);
    }
    public function getEmployee()
    {
        $data = Employee::query()
            ->select('id','name')->get();
        return response()->json($data);
    }
}
