<?php

namespace App\Http\Controllers;

use Cassandra\Custom;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
class CustomerController extends Controller
{
    public function index(Request $request){
        $data = Customer::query()
            ->select('id','name','phone','email')
            ->get();
        if ($request->ajax()){
            return DataTables::of($data)
                ->addColumn('name',function ($row){
                    return $row->name;
                })
                ->addColumn('email',function ($row){
                    return $row->email;
                })
                ->addColumn('phone',function ($row){
                    return $row->phone;
                })
                ->addColumn('action',function ($row){
                    return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="my-btn-delele" data-id="'.$row->id.'" ><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['name','phone','email','action'])
                ->make(true);
        }
        return view('customers.index');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'phone' => 'required',
            'email' => 'email:rfc,dns',
        ]);
        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $data = Customer::query()
            ->firstOrNew(array('id'=>$request->id));
        $data->name = $request->name;
        $data->phone = $request->phone;
        $data->email = $request->email;
        $data->save();
        if ($data){
            return response()->json(['success'=>1]);
        } else {
            return response()->json(['success'=>0]);
        }
    }

    public function edit($id){
        $data = Customer::query()
            ->where('id',$id)
            ->first();
        return response()->json($data);
    }

    public function destroy($id){
        $data = Customer::query()->findOrFail($id);
        $data->delete();
        return response()->json(['success'=>1]);
    }

    public function search(Request $request){
        dd($request);
        $clause = [
            'name' => $request['filterName'],
            'email' => $request['filterMail'],
            'phone' => $request['filterPhone']
        ];
        $data = $this->doSearch($clause);
        Return DataTables::of($data)
        ->addColumn('name',function ($row){
            return $row->name;
        })
        ->addColumn('email',function ($row){
            return $row->email;
        })
        ->addColumn('phone',function ($row){
            return $row->phone;
        })
        ->addColumn('action',function ($row){
            return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="my-btn-delele" data-id="'.$row->id.'" ><i class="fa fa-trash"></i></a>';
        })
        ->rawColumns(['name','phone','email','action'])
        ->make(true);
    }

    private function doSearch($clauses){
        $data = Customer::query()
            ->select('id','name','email','phone');

        $fields = array_keys($clauses);
        $index = 0;
        foreach ($clauses as $item) {
            if ($item != null) {
                $data = $data->where($fields[$index], 'LIKE', '%' . $item . '%');
            }
            $index++;
        }
        $data->get();
        return $data;
    }
}
