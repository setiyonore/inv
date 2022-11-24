<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HelperMasterTraits;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use PDF;
/*models*/
use App\Models\MasterNoRekening;
use App\Models\MasterPackage;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Reference;
use App\Models\Invoice;
use App\Models\Employee;
use App\Models\Manpower;

class TransactionController extends Controller
{
    use HelperMasterTraits;
    public function index(Request $request){
        $now = Carbon::now()->toDateString();
        $data = Transaction::query()
            ->leftJoin('customers as c','c.id','transactions.id_customer')
            ->leftJoin('mst_package as p','p.id','transactions.id_package')
            ->leftJoin('invoices as i','i.id_transaction','transactions.id')
            ->leftJoin('references as r','r.id','i.id_reference_status_invoice')
            ->select('c.name as customer','p.name as package',
                'transactions.date','transactions.amount','transactions.id',
            'transactions.id_package','transactions.id_reference_type_of_payment as top',
            'transactions.id_reference_type_transaction as tot','transactions.id_no_rekening as norek',
            'transactions.id_customer as cust','r.description as status','i.id_reference_status_invoice as id_status')
            ->where('transactions.date','=',$now)
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
                ->addColumn('payment',function ($row){
                    if($row->id_status == config('config.idStatusInvoiceUnpaid')){
                        return '<span class="badge badge-danger">'.$row->status.'</span>';
                    }else {
                        return '<span class="badge badge-success">'.$row->status.'</span>';
                    }
                })
                ->addColumn('action',function ($row){
                    return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="'.$row->id.'" data-package-id="'.$row->id_package.'" data-top-id="'.$row->top.'" data-tot-id="'.$row->tot.'" data-norek-id="'.$row->norek.'" data-cust-id="'.$row->cust.'" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-secondary btn-sm" id="my-btn-manpower" data-id="'.$row->id.'"><i class="fa fa-user"></i></a>
                                <a href="javascript:void(0)" class="btn btn-info btn-sm" id="my-btn-detil" data-id="'.$row->id.'"><i class="fa fa-file"></i></a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="my-btn-delele" data-id="'.$row->id.'" ><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['customer','date','package','amount','payment','action'])
                ->make(true);
        }
        $norek = $this->getNomerRekening();
        $paket = $this->getPackage();
        $employee = Employee::query()
            ->leftJoin('references as r','r.id','employees.id_reference_division')
            ->select('employees.id','employees.name','r.description as division')
            ->where('r.id_type_reference',config('config.IdTypeReference.Divisi'))
            ->get();
        return view('transactions.index',compact('norek','paket','employee'));
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
        $invoiceExist = Invoice::query()
            ->where('id_transaction',$data->id)
            ->count();
        if ($invoiceExist == 0){
            //inv-order-paket-tanggal
            //inv-BK-PJ-2206012139008
            $package =  MasterPackage::query()
                ->where('id',$request->paket)
                ->select('id_reference_type_package as idJenisPaket')
                ->first();
            $order = Reference::query()
                ->select('short')
                ->where('id',$package->idJenisPaket)
                ->where('id_type_reference',config('config.IdTypeReference.TypeOfPackage'))
                ->first();
            $shortPakcage = MasterPackage::query()
                ->where('id',$request->paket)
                ->select('short as shortPackage')
                ->first();
            $now = Carbon::now();
            $year = $now->year;
            $year = substr($year,-2,2);
            $month = $now->month;
            $day = $now->format('d');
            $time = $now->timestamp;
            $noInvoice = "INV"."-".$order->short."-".$shortPakcage->shortPackage."-".$year.$month.$day.$time;
            $status = config('config.idStatusInvoiceUnpaid');
            $duedate = Carbon::parse($request['tanggal']);
            $duedate = $duedate->addDays(7)->toDateString();
            $invoice = Invoice::query()
                ->firstOrNew(array('id'=>$request->id));
            $invoice->id_transaction = $data->id;
            $invoice->due = $duedate;
            $invoice->no = $noInvoice;
            $invoice->id_reference_status_invoice = $status;
            $invoice->save();
        }
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
                ->addColumn('payment',function ($row){
                    if($row->id_status == config('config.idStatusInvoiceUnpaid')){
                        return '<span class="badge badge-danger">'.$row->status.'</span>';
                    }else {
                        return '<span class="badge badge-success">'.$row->status.'</span>';
                    }
                })
                ->addColumn('action',function ($row){
                    return '<a href="javascript:void(0)"  class="btn btn-success btn-sm"  id="my-btn-edit" data-id="'.$row->id.'" data-package-id="'.$row->id_package.'" data-top-id="'.$row->top.'" data-tot-id="'.$row->tot.'" data-norek-id="'.$row->norek.'" data-cust-id="'.$row->cust.'" data-toggle="tooltip" data-placement="top" title="Edit this record"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-secondary btn-sm" id="my-btn-manpower" data-id="'.$row->id.'"><i class="fa fa-user"></i></a>
                                <a href="javascript:void(0)" class="btn btn-info btn-sm" id="my-btn-detil" data-id="'.$row->id.'"><i class="fa fa-file"></i></a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="my-btn-delele" data-id="'.$row->id.'" ><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['customer','date','package','amount','payment','action'])
                ->make(true);

    }

    private function doSearch($clauses,$tgl_awal,$tgl_akhir){
        $data = Transaction::query()
        // ->leftJoin('customers as c','c.id','transactions.id_customer')
        // ->leftJoin('mst_package as p','p.id','transactions.id_package')
        // ->select('c.name as customer','p.name as package',
        //     'transactions.date','transactions.amount','transactions.id',
        // 'transactions.id_package','transactions.id_reference_type_of_payment as top',
        // 'transactions.id_reference_type_transaction as tot','transactions.id_no_rekening as norek',
        // 'transactions.id_customer as cust');
        ->leftJoin('customers as c','c.id','transactions.id_customer')
        ->leftJoin('mst_package as p','p.id','transactions.id_package')
        ->leftJoin('invoices as i','i.id_transaction','transactions.id')
        ->leftJoin('references as r','r.id','i.id_reference_status_invoice')
        ->select('c.name as customer','p.name as package',
            'transactions.date','transactions.amount','transactions.id',
        'transactions.id_package','transactions.id_reference_type_of_payment as top',
        'transactions.id_reference_type_transaction as tot','transactions.id_no_rekening as norek',
        'transactions.id_customer as cust','r.description as status','i.id_reference_status_invoice as id_status');
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
            ->rightJoin('invoices as inv','inv.id_transaction','transactions.id')
            ->leftJoin('references as inv_status','inv_status.id','inv.id_reference_status_invoice')
            ->select('c.name as customer','p.name as package','date','amount',
                'tot.description as jenis_transaksi','top.description as jenis_pembayaran',
                'affiliation','nr.name','nr.no_rek','transactions.id','bnk.description as bank',
                'inv_status.description as status','inv.no as no_invoice')
            ->where('transactions.id',$id)
            ->where('tot.id_type_reference',
                config('config.IdTypeReference.TypeOfTransaction'))
            ->where('top.id_type_reference',
                config('config.IdTypeReference.TypeOfPayment'))
            ->where('bnk.id_type_reference',
                config('config.IdTypeReference.Bank'))
            ->where('inv_status.id_type_reference',
                config('config.IdTypeReference.StatusInvoice'))
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
    public function exportInvoice($id){
        $transaction = Transaction::query()
            ->where('id',$id)
            ->first();
        $dateCreated = Carbon::parse($transaction->date)->format('d/F/Y');
        $customer = Customer::query()
            ->where('id',$transaction->id_customer)
            ->select('name','phone','email')
            ->first();
        $invoice = Invoice::query()
            ->where('id_transaction',$id)
            ->select('due','no','id_reference_status_invoice','r.description as status')
            ->leftJoin('references as r','r.id','invoices.id_reference_status_invoice')
            ->first();
        $transactionItem = Transaction::query()
            ->leftJoin('mst_package as p','p.id','transactions.id_package')
            ->leftJoin('references as tp','tp.id','p.id_reference_type_package')
            ->select('p.description','p.name as package',
                'tp.description as type_package')
            ->selectRaw('FORMAT(transactions.amount,"C") as amount')
            ->where('transactions.id',$id)
            ->get();
        $paymentMethod = Transaction::query()
            ->leftJoin('references as r','r.id','transactions.id_reference_type_of_payment')
            ->leftJoin('mst_no_rekening as nr','nr.id','transactions.id_no_rekening')
            ->leftJoin('references as bk','bk.id','nr.id_reference_bank')
            ->where('transactions.id',$id)
            ->where('bk.id_type_reference',config('config.IdTypeReference.Bank'))
            ->where('r.id_type_reference',config('config.IdTypeReference.TypeOfPayment'))
            ->select('r.description as top','nr.name','nr.no_rek','bk.description as bank')
            ->first();
//        dd($paymentMethod);
        $data = array([
            'customer' => $customer,
            'transactionItem' => $transactionItem,
            'paymentMethod' => $paymentMethod,
            'invoice' => $invoice,
            'due' => Carbon::parse($invoice->due)->format('d/m/Y'),
            'dateTransaction' => Carbon::parse($transaction->date)->format('d/m/Y'),
            'dateCreate' => $dateCreated,
        ]);
        $filename = $invoice->no.".pdf";
        $pdf = PDF::loadView('transactions.invoicePdf', compact('data'));
        return $pdf->download($filename);
    }
    public function detilInvoice($id){
        $transaction = Transaction::query()
            ->where('id',$id)
            ->first();
        $customer = Customer::query()
            ->where('id',$transaction->id_customer)
            ->select('name','phone','email')
            ->first();
        $invoice = Invoice::query()
            ->where('id_transaction',$id)
            ->select('due','no','id_reference_status_invoice')
            ->first();
        $transactionItem = Transaction::query()
            ->leftJoin('mst_package as p','p.id','transactions.id_package')
            ->leftJoin('references as tp','tp.id','p.id_reference_type_package')
            ->select('p.description','p.name as package',
            'tp.description as type_package')
            ->selectRaw('FORMAT(transactions.amount,"C") as amount')
            ->where('transactions.id',$id)
            ->get();
        $paymentMethod = Transaction::query()
            ->leftJoin('references as r','r.id','transactions.id_reference_type_of_payment')
            ->leftJoin('mst_no_rekening as nr','nr.id','transactions.id_no_rekening')
            ->leftJoin('references as bk','bk.id','nr.id_reference_bank')
            ->where('transactions.id',$id)
            ->where('bk.id_type_reference',config('config.IdTypeReference.Bank'))
            ->where('r.id_type_reference',config('config.IdTypeReference.TypeOfPayment'))
            ->select('r.description as top','nr.name','nr.no_rek','bk.description as bank')
            ->first();
        $data = array([
            'transaction' => $transaction,
            'customer' => $customer,
            'invoice' => $invoice,
            'due' => Carbon::parse($invoice->due)->format('d/m/Y'),
            'dateTransaction' => Carbon::parse($transaction->date)->format('d/m/Y'),
            'transactionItem' => $transactionItem,
            'payment' => $paymentMethod,
        ]);
        return response()->json($data);
    }
    public function updateStatusInvoice($id){
        $invoice = Invoice::query()
            ->where('id_transaction',$id)
            ->first();
        $invoice->id_reference_status_invoice = config('config.idStatusInvoicePaid');
        $invoice->save();
        return response()->json(['success'=>1]);
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
    public function getPegawai(){
        $data = Employee::query()
            ->select('id','name')
            ->get();
        return $data;
    }
    public function getManpowerTransaction($id){
        $manpower = Manpower::query()
            ->leftJoin('employees as mp','manpower.id_employee','mp.id')
            ->leftJoin('references as r','r.id','manpower.id_reference_working_status')
            ->leftJoin('references as dv','mp.id_reference_division','dv.id')
            ->where('manpower.id_transaction',$id)
            ->where('dv.id_type_reference',config('config.IdTypeReference.Divisi'))
            ->select('manpower.id','mp.name','r.description as status','dv.description as division')
            ->get();
        $transaction = Transaction::query()
            ->leftJoin('mst_package as p','p.id','transactions.id_package')
            ->leftJoin('customers as c','c.id','id_customer')
            ->select('c.name','p.name as package','transactions.date')
            ->where('transactions.id',$id)
            ->first();
        $data = [
            'manpower'=>$manpower,
            'transaction' => $transaction,
            'dateTransaction' => Carbon::parse($transaction->date)->format('d/m/Y'),
        ];
        return response()->json($data);
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

    public function storeManpower(Request $request){
        $validator = Validator::make($request->all(),[
            'manpower' => 'required'
        ]);
        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $data = Manpower::query()
            ->create([
                'id_transaction'=>$request->id_transaksi,
                'id_employee' => $request->manpower,
                'id_reference_working_status' => config('config.idStatusBelumDikerjakan')
            ]);
        if ($data){
            return response()->json(['success'=>1]);
        } else {
            return response()->json(['success'=>0]);
        }
    }
    public function destroyManpower($id){
        $data = Manpower::query()->findOrFail($id);
        $data->delete();
        return response()->json(['success'=>1]);
    }
}
