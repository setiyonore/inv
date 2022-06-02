<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Traits\HelperMasterTraits;
/*models*/
use App\Models\MasterPackage;
use App\Models\MasterPackageBenefit;
use App\Models\MasterPackageFormatNaskah;
class PackageController extends Controller
{
    use HelperMasterTraits;
    public function index(Request $request){
        $data = MasterPackage::query()
            ->select('id','name','description','price','id_reference_type_package as tp')
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
                    return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="'.$row->id.'" data-tipe-id="'.$row->tp.'" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-info btn-sm" id="my-btn-detil" data-id="'.$row->id.'"><i class="fa fa-file"></i></a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="my-btn-delele" data-id="'.$row->id.'" ><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['name','description','price','action'])
                ->make(true);
        }
        $benefit = $this->getBenefit();
        $format = $this->getFormatNaskah();
        return view('package.index',compact('benefit','format'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'nama' => 'required',
            'tipePaket' => 'required',
            'harga' => 'required|numeric',
        ]);
        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $data = MasterPackage::query()
            ->firstOrNew(array('id'=>$request->id));
        $data->id_reference_type_package = $request->tipePaket;
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

    public function storeBenefit(Request $request){
        $validator = Validator::make($request->all(),[
            'benefit' => 'required'
        ]);
        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $data = MasterPackageBenefit::query()
            ->create([
                'id_mst_package' => $request->id_package,
                'id_reference_benefit' => $request->benefit
            ]);
        if ($data){
            return response()->json(['success'=>1]);
        } else {
            return response()->json(['success'=>0]);
        }
    }

    public function storeFormatNaskah(Request $request){
        $validator = Validator::make($request->all(),[
            'formatNaskah' => 'required'
        ]);
        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $data = MasterPackageFormatNaskah::query()
            ->create([
                'id_mst_package' => $request->id_package,
                'id_reference_format_naskah' => $request->formatNaskah,
            ]);
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
                                <a href="javascript:void(0)" class="btn btn-info btn-sm" id="my-btn-detil" data-id="'.$row->id.'"><i class="fa fa-file"></i></a>
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
    public function detil($id){
        $data = MasterPackage::query()
            ->leftJoin('references as r','r.id','mst_package.id_reference_type_package')
            ->select('mst_package.name','mst_package.description',
                'r.description as tipePaket')
            ->where('mst_package.id',$id)
            ->where('r.id_type_reference',config('config.IdTypeReference.TypeOfPackage'))
            ->get();
        $benefit = MasterPackageBenefit::query()
            ->leftJoin('references as r','r.id','mst_package_benefit.id_reference_benefit')
            ->where('r.id_type_reference',config('config.IdTypeReference.Benefit'))
            ->select('r.description',
                'mst_package_benefit.id')
            ->where('mst_package_benefit.id_mst_package',$id)
            ->get();
        $format = MasterPackageFormatNaskah::query()
            ->leftJoin('references as r','r.id','mst_package_format_naskah.id_reference_format_naskah')
            ->where('r.id_type_reference',config('config.IdTypeReference.FormatNaskah'))
            ->select('r.description','mst_package_format_naskah.id')
            ->where('mst_package_format_naskah.id_mst_package',$id)
            ->get();
        $data = array([
            'paket' => $data,
            'benefit' => $benefit,
            'format' => $format
        ]);
        return response()->json($data);
    }

    public function destroyFormatNaskah($id){
        $data = MasterPackageFormatNaskah::query()
            ->findOrFail($id);
        $data->delete();
        return response()->json(['success'=>1]);
    }

    public function destroyBenefit($id){
        $data = MasterPackageBenefit::query()
            ->findOrFail($id);
        $data->delete();
        return response()->json(['success'=>1]);
    }

    public function getTipePaket(){
        return $this->getTypePackage();
    }

}
