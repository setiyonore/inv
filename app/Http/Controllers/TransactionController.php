<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HelperMasterTraits;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
/*models*/
use App\Models\MasterNoRekening;
use App\Models\MasterPackage;
use App\Models\Transaction;
use App\Models\Customer;

class TransactionController extends Controller
{
    use HelperMasterTraits;
    public function index(Request $request){
        $data = Transaction::query()
            ->leftJoin('customers as c','c.id','transactions.id_customer')
            ->leftJoin('mst_package as p','p.id','transactions.id_package')
            ->select('c.name as customer','p.name as package','transactions.date','transactions.amount','transactions.id')
            ->get();
        if ($request->ajax()){
            return DataTables::make($data)
                ->addColumn('customer',function ($row){
                    return $row->customer;
                })
                ->addColumn('date',function ($row){
                    return Carbon::parse($row->date)->format('d/m/Y');
                })
                ->addColumn('package',function ($row){
                    return $row->package;
                })
                ->addColumn('amount',function ($row){
                    $rupiah = number_format($row->amount,2, ',', '.');
                    return "Rp.".$rupiah;
                    return $row->amount;
                })
                ->addColumn('action',function ($row){
                    return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-info btn-sm" id="my-btn-detil" data-id="'.$row->id.'"><i class="fa fa-file"></i></a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="my-btn-delele" data-id="'.$row->id.'" ><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['customer','date','package','amount','action'])
                ->make(true);
        }
        $norek = $this->getNomerRekening();
        $paket = $this->getPackage();
        return view('transactions.index',compact('norek','paket'));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'tanggal' => 'required',
            'pelanggan' => 'required',
            'paket' => 'required',
            'nominal' => 'required',
            'jenisPembayaran' => 'required',
            'jenisTransaksi' => 'required',
            'noRekening' => 'required',
        ]);
        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }
        if ($request->iduser == null){
            $iduser = Auth::user()->id;
        } else {
            $iduser = $request->iduser;
        }
        dd($iduser);
        $data = Transaction::query()
            ->firstOrNew(array('id'=>$request->id));
        $data->date = $request->tanggal;
        $data->id_customer = $request->pelanggan;
        $data->id_reference_type_transaction = $request->jenisTransaksi;
        $data->id_reference_type_of_payment = $request->jenisPembayaran;
        $data->id_package = $request->paket;
        $data->amount = $request->nominal;
        $data->affiliation = $request->afiliasi;
        $data->id_no_rekening = $request->noRekening;
        $data->id_user = $iduser;
        $data->save();
        if ($data){
            return response()->json(['success'=>1]);
        } else {
            return response()->json(['success'=>0]);
        }
    }
    public function searchCustomer($keyword){
        $data = Customer::query()
            ->where('name','like','%'.$keyword.'%')
            ->select('id','name')
            ->get();
        return $data;
    }

    public function getPackage(){
        $data = MasterPackage::query()
            ->select('id','name')
            ->get();
        return $data;
    }
    public function getPricePackage($id){
        $data = MasterPackage::query()
            ->select('price')
            ->where('id',$id)
            ->first();
        $data = "Rp.".number_format($data->price,2, ',', '.');
        return $data;
    }
    public function getTypeOfPayment(){
        return $this->getDataReferensi(config('config.IdTypeReference.TypeOfPayment'));
    }
    public function getTypeTransaction(){
        return $this->getDataReferensi(config('config.IdTypeReference.TypeOfTransaction'));
    }

    public function getNoRekening(){
        return $this->getNomerRekening();
    }
}
