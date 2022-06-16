<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
/*models*/
use App\Models\Transaction;
use App\Models\MasterPackage;

class ReportController extends Controller
{
    public function index(Request $request){
        $start = Carbon::now()->startOfMonth()->toDateString();
        $end = Carbon::now()->endOfMonth()->toDateString();
        $data = Transaction::query()
            ->leftJoin('mst_package as p','p.id','=','transactions.id_package')
            ->whereBetween('date', [$start, $end])
            ->select('p.name as package','transactions.amount')
            ->selectRaw('DATE_FORMAT(transactions.date,"%d/%m/%Y") as date')
            ->get();
        $package = MasterPackage::query()
            ->select('id','name')
            ->get();
        if ($request->ajax()){
            return DataTables::of($data)
                ->addColumn('date',function ($row){
                    return $row->date;
                })
                ->addColumn('package',function ($row){
                    return $row->package;
                })
                ->addColumn('amount',function ($row){
                    $rupiah = number_format($row->amount,2, ',', '.');
                    return "Rp.".$rupiah;
                })
                ->rawColumns(['date','package','amount'])
                ->make(true);
        }
       return view('report.index',compact('package'));
    }

    public function getDataReport(){
        $start = Carbon::now()->startOfMonth()->toDateString();
        $end = Carbon::now()->endOfMonth()->toDateString();
        $transaction = Transaction::query()
            ->leftJoin('mst_package as p','p.id','=','transactions.id_package')
            ->whereBetween('date', [$start, $end])
            ->select('transactions.date','p.name as package')
            ->selectRaw('FORMAT(transactions.amount,"C") as amount')
            ->selectRaw('DATE_FORMAT(transactions.date,"%d/%m/%Y") as date')
            ->get();
        $amount = Transaction::query()
            ->whereBetween('date', [$start, $end])
            ->selectRaw('FORMAT(SUM(amount),"C") as amount')
            ->get();
        $data = [
            'transaction' => $transaction,
            'amount' => $amount
        ];
        return $data;
    }
    public function getAmount(){
        $start = Carbon::now()->startOfMonth()->toDateString();
        $end = Carbon::now()->endOfMonth()->toDateString();
        $amount = Transaction::query()
            ->whereBetween('date', [$start, $end])
            ->selectRaw('FORMAT(SUM(amount),"C") as amount')
            ->get();
        return response()->json($amount);

    }
}
