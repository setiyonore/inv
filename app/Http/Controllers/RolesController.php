<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Role;
use function Sodium\add;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        $role = Role::findByName('admin');
        $role->permissions->pluck('name');
        $dataRole = Permission::all();
        $data = Role::query()->select('id', 'name')->get();
        if ($request->ajax()) {
            return DataTables::make($data)
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="btn-detil" data-id="' . $row->id . '" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-eye"></i></a>';
                })
                ->rawColumns(['name', 'action'])
                ->make(true);
        }
        return view('roles.index', compact('dataRole'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $role = Role::create(['name' => strtolower($request->role)]);
        if ($role) {
            return response()->json(['success' => 1]);
        } else {
            return response()->json(['success' => 0]);
        }
    }

    public function assignPermissionToRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idRoles' => 'required',
            'idPermission' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $role = Role::findById($request->idRoles);
        $permission = Permission::findById($request->idPermission);
        $role->givePermissionTo($permission);
        if ($role) {
            return response()->json(['success' => 1]);
        }
        return response()->json(['success' => 0]);
    }

    public function getPermission($id)
    {
        $data = Role::findById($id);
        $data->getPermissionNames();
        return response()->json($data);
    }

    public function deletePermission(Request $request)
    {
        $role = Role::findById($request->idRole);
        $permission = Permission::findById($request->idPermission);
        $role->revokePermissionTo($permission);
        if ($role) {
            return response()->json(['success' => 1]);
        }
        return response()->json(['success' => 0]);
    }
}
