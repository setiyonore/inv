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
            ->select('c.name as customer','p.name as package',
                'transactions.date','transactions.amount','transactions.id',
            'transactions.id_package','transactions.id_reference_type_of_payment as top',
            'transactions.id_reference_type_transaction as tot','transactions.id_no_rekening as norek',
            'transactions.id_customer as cust')
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
                    return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="'.$row->id.'" data-package-id="'.$row->id_package.'" data-top-id="'.$row->top.'" data-tot-id="'.$row->tot.'" data-norek-id="'.$row->norek.'" data-cust-id="'.$row->cust.'" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>
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
    public function edit($id){
        $data = Transaction::query()
            ->where('id',$id)
            ->first();
        return response()->json($data);
    }
    public function destroy($id){
        $data = Transaction::query()
            ->findOrFail($id);
        $data->delete();
        return response()->json(['success'=>1]);
    }
    public function search(Request $request){
        $tgl = $request['date'];
        $tgl_awal = strtok($tgl,'-');
        $tgl_awal = str_replace(' ','',$tgl_awal);
        $tgl_awal = str_replace('/','-',$tgl_awal);
        $tgl_awal = Carbon::parse($tgl_awal)->format('Y-m-d');
        $tgl_akhir = substr($tgl,strpos($tgl,"-")+2);
        $tgl_akhir = str_replace('/','-',$tgl_akhir);
        $tgl_akhir = Carbon::parse($tgl_akhir)->format('Y-m-d');
        $clause = [
            'transactions.id_no_rekening' => $request['norek'],
            'transactions.id_package' => $request['paket'],
        ];
        $data = $this->doSearch($clause,$tgl_awal,$tgl_akhir);
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
                    return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="'.$row->id.'" data-package-id="'.$row->id_package.'" data-top-id="'.$row->top.'" data-tot-id="'.$row->tot.'" data-norek-id="'.$row->norek.'" data-cust-id="'.$row->cust.'" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-info btn-sm" id="my-btn-detil" data-id="'.$row->id.'"><i class="fa fa-file"></i></a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="my-btn-delele" data-id="'.$row->id.'" ><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['customer','date','package','amount','action'])
                ->make(true);

    }

    private function doSearch($clauses,$tgl_awal,$tgl_akhir){
        $data = Transaction::query()
        ->leftJoin('customers as c','c.id','transactions.id_customer')
        ->leftJoin('mst_package as p','p.id','transactions.id_package')
        ->select('c.name as customer','p.name as package',
            'transactions.date','transactions.amount','transactions.id',
        'transactions.id_package','transactions.id_reference_type_of_payment as top',
        'transactions.id_reference_type_transaction as tot','transactions.id_no_rekening as norek',
        'transactions.id_customer as cust');
        $fields = array_keys($clauses);
        $index = 0;
        foreach ($clauses as $item) {
            if ($item != null) {
                $data = $data->where($fields[$index], 'LIKE', '%' . $item . '%');
            }
            $index++;
        }
        $data = $data->whereBetween('transactions.date',[$tgl_awal,$tgl_akhir]);
        $result = $data->get();
        return $data;

    }
    public function searchCustomer($keyword){
        $data = Customer::query()
            ->where('name','like','%'.$keyword.'%')
            ->select('id','name')
            ->get();
        return $data;
    }
    public function detail($id){
        $data = Transaction::query()
            ->leftJoin('customers as c','c.id','transactions.id_customer')
            ->leftJoin('mst_package as p','p.id','transactions.id_package')
            ->leftJoin('references as tot','tot.id',
                'transactions.id_reference_type_transaction')
            ->leftJoin('references as top','top.id',
                'transactions.id_reference_type_of_payment')
            ->leftJoin('mst_no_rekening as nr','nr.id','transactions.id_no_rekening')
            ->leftJoin('references as bnk','bnk.id','nr.id_reference_bank')
            ->select('c.name as customer','p.name as package','date','amount',
                'tot.description as jenis_transaksi','top.description as jenis_pembayaran',
                'affiliation','nr.name','nr.no_rek','transactions.id','bnk.description as bank')
            ->where('transactions.id',$id)
            ->where('tot.id_type_reference',
                config('config.IdTypeReference.TypeOfTransaction'))
            ->where('top.id_type_reference',
                config('config.IdTypeReference.TypeOfPayment'))
            ->where('bnk.id_type_reference',
                config('config.IdTypeReference.Bank'))
            ->first();
        $tanggal = Carbon::parse($data->date)->format('d/m/Y');
        $nominal = number_format($data->amount,2, ',', '.');
        $data = array([
            'data' => array([
                'date' => $tanggal,
                'transaction' => $data,
                'nominal' => "Rp.".$nominal,
            ]),
        ]);
            return response()->json($data);
    }
    public function getCustomerId($id)
    {
        return $this->getCustomerById($id);
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
