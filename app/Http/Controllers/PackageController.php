<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
/*models*/
use App\Models\MasterPackage;

class PackageController extends Controller
{
    public function index(Request $request){
        $data = MasterPackage::query()
            ->select('id','name','description','price')
            ->get();
        if ($request->ajax()){
            return DataTables::make($data)
                ->addColumn('name',function ($row){
                    return $row->name;
                })
                ->addColumn('description',function ($row){
                    return $row->description;
                })
                ->addColumn('price',function ($row){
                    $rupiah = number_format($row->price,2, ',', '.');
                    return "Rp.".$rupiah;
                })
                ->addColumn('action',function ($row){
                    return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-info btn-sm" id="my-btn-detil" data-id="'.$row->id.'"><i class="fa fa-file"></i></a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="my-btn-delele" data-id="'.$row->id.'" ><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['name','description','price','action'])
                ->make(true);
        }
        return view('package.index');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'nama' => 'required',
            'harga' => 'required|numeric',
        ]);
        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $data = MasterPackage::query()
            ->firstOrNew(array('id'=>$request->id));
        $data->name = $request->nama;
        $data->description = $request->deskripsi;
        if ($request->harga == 0){
            $data->price = $request->hargaOld;
        }else {
            $data->price = $request->harga;
        }
        $data->save();
        if ($data){
            return response()->json(['success'=>1]);
        } else {
            return response()->json(['success'=>0]);
        }
    }

    public function edit($id){
        $data = MasterPackage::query()
            ->where('id',$id)
            ->first();
        return response()->json($data);
    }

    public function destroy($id){
        $data = MasterPackage::query()->findOrFail($id);
        $data->delete();
        return response()->json(['success'=>1]);
    }

    public function search(Request $request){
        $clause = [
            'name' => $request->filterName
        ];
        $data = $this->doSearch($clause);
        return DataTables::make($data)
            ->addColumn('name',function ($row){
                return $row->name;
            })
            ->addColumn('description',function ($row){
                return $row->description;
            })
            ->addColumn('price',function ($row){
                $rupiah = number_format($row->price,2, ',', '.');
                return "Rp.".$rupiah;
            })
            ->addColumn('action',function ($row){
                return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="my-btn-delele" data-id="'.$row->id.'" ><i class="fa fa-trash"></i></a>';
            })
            ->rawColumns(['name','description','price','action'])
            ->make(true);

    }
    private function doSearch($clauses){
        $data = MasterPackage::query()
            ->select('id','name','description','price');
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
}
