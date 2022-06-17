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
            'transactions.id_package' => $request['paket']
        ];
        $data = $this->doSearch($clause,$tgl_awal,$tgl_akhir);
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
    private function doSearch($clauses,$tgl_awal,$tgl_akhir){
        $data = Transaction::query()
            ->leftJoin('mst_package as p','p.id','=','transactions.id_package')
            ->select('p.name as package','transactions.amount')
            ->selectRaw('DATE_FORMAT(transactions.date,"%d/%m/%Y") as date');
        $fields = array_keys($clauses);
        $index = 0;
        foreach ($clauses as $item) {
            if ($item != null) {
                $data = $data->where($fields[$index], 'LIKE', '%' . $item . '%');
            }
            $index++;
        }
        if ($tgl_awal != null && $tgl_akhir != null){
            $data->whereBetween('date', [$tgl_awal, $tgl_akhir]);
        }
        $result = $data->get();
        return $result;
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
    public function getAmountFilter(Request $request){
        $tgl = $request['date'];
        $tgl_awal = strtok($tgl,'-');
        $tgl_awal = str_replace(' ','',$tgl_awal);
        $tgl_awal = str_replace('/','-',$tgl_awal);
        $tgl_awal = Carbon::parse($tgl_awal)->format('Y-m-d');
        $tgl_akhir = substr($tgl,strpos($tgl,"-")+2);
        $tgl_akhir = str_replace('/','-',$tgl_akhir);
        $tgl_akhir = Carbon::parse($tgl_akhir)->format('Y-m-d');
        $amount = Transaction::query()
            ->selectRaw('FORMAT(SUM(amount),"C") as amount')
            ->whereBetween('date', [$tgl_awal, $tgl_akhir]);
        if ($request['paket'] != null){
            $amount->where('id_package',$request['paket']);
        }
//        $amount->get();
        return response()->json($amount->get());

    }
}
