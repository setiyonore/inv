<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            return $this->getMake($data);
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
        $user = User::query()
            ->firstOrNew(array('id'=>$request->id));
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->id == null){
            $user->password = bcrypt(12345678);
        }
        $user->pegawai_id = $request->pegawai;
        $user->save();
        if ($request->oldRole ==0){
            $role = Role::findById($request->role);
            $user->assignRole($role);
        } else {
            $user->syncRoles([]);
            $role = Role::findById($request->role);
            $user->assignRole($role);

        }
        if ($user){
            return response()->json(['success'=>1]);
        } else {
            return response()->json(['success'=>0]);
        }
    }
    public function editUser($id){
        $data = User::query()->with('roles')->where('id',$id)
            ->select('id','pegawai_id','name','email')
            ->first();
        return response()->json($data);
    }
    public function destroy($id){
        $user = User::query()->findOrFail($id);
        $user->syncRoles([]);
        $user->delete();
        return response()->json(['success'=>1]);
    }
    public function search(Request $request){
        $clause = [
            'users.name' => $request->filterName,
            'email' => $request->filterEmail,
        ];
        $data = $this->doSearch($clause);
        return $this->getMake($data);
    }
    private function doSearch($clauses){
        $data = User::query()
            ->with('roles')
            ->leftJoin('employees as e','e.id','users.pegawai_id')
            ->select(
                'users.name',
                'users.email',
                'users.id',
                'e.name as pegawai'
            );
        $fields = array_keys($clauses);
        $index = 0;
        foreach ($clauses as $item) {
            if ($item != null) {
                $data = $data->where($fields[$index], 'LIKE', '%' . $item . '%');
            }
            $index++;
        }
        $result = $data->get();
        return $result;
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

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function getMake($data)
    {
        return DataTables::make($data)
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('email', function ($row) {
                return $row->email;
            })
            ->addColumn('employee', function ($row) {
                return $row->pegawai;
            })
            ->addColumn('role', function ($row) {
                foreach ($row['roles'] as $data) {
                    return $data['name'];
                };
            })
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="' . $row->id . '" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="my-btn-delele" data-id="' . $row->id . '" ><i class="fa fa-trash"></i></a>';
            })
            ->rawColumns(['name', 'email', 'employee', 'role', 'action'])
            ->make(true);
    }
    public function updatePassword(Request $request){
        $validator = Validator::make($request->all(),[
            'oldPassword' => 'required',
            'newPassword' => 'required',
        ]);
        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $user = User::query()->findOrFail(Auth::user()->id);
        if (Hash::check($request->oldPassword,$user->password)){
            $user->fill([
                'password' => Hash::make($request->newPassword)
            ])->save();
            return response()->json(['success'=>1]);
        }
        return response()->json(['success'=>0]);
    }
}
