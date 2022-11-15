<?php

namespace App\Http\Controllers;

use App\Models\Manpower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
//models
use App\Models\Transaction;
use App\Models\Customer;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $order = Transaction::query()
            ->count('id');
        $paketBuku = Transaction::query()
            ->leftJoin('mst_package as p','p.id','transactions.id_package')
            ->where('p.id_reference_type_package',config('config.IdTypeReference.PaketBuku'))
            ->count('transactions.id');
        $paketJurnal = Transaction::query()
            ->leftJoin('mst_package as p','p.id','transactions.id_package')
            ->where('p.id_reference_type_package',config('config.IdTypeReference.PaketJurnal'))
            ->count('transactions.id');
        $pelanggan = Customer::query()
            ->count('id');
        $tugasSaya = Manpower::query()
                ->leftJoin('transactions as t','t.id','manpower.id_transaction')
                ->leftJoin('mst_package as mp','mp.id','t.id_package')
                ->leftJoin('customers as c','c.id','t.id_customer')
                ->leftJoin('references as r','r.id','manpower.id_reference_working_status')
                ->where('manpower.id_employee',Auth::user()->pegawai_id)
                ->where('manpower.id_reference_working_status','!=',config('config.idStatusSudahDikerjakan'))
                ->select('t.date','mp.name as paket','c.name as customer','r.description as status',
                    'manpower.id_reference_working_status as id_status')
            ->get();
        return view('home.dasboard',compact('order','paketBuku','paketJurnal','pelanggan','tugasSaya'));
    }

    public function getOrderPerMonth(){
        //get order per month
        $orderPerMont = Transaction::query()
            ->select(DB::raw('LEFT (date ,7) as bulan,COUNT(*) as jml'))
            ->groupBy(DB::raw('LEFT(`date`, 7)'))
            ->get();
        $paketJurnal = Transaction::query()
            ->leftJoin('mst_package as p','p.id','transactions.id_package')
            ->where('p.id_reference_type_package',config('config.IdTypeReference.PaketJurnal'))
            ->select(DB::raw('LEFT (date ,7) as bulan,COUNT(*) as jml'))
            ->groupBy(DB::raw('LEFT(`date`, 7)'))
            ->get();
        $paketBuku = Transaction::query()
            ->leftJoin('mst_package as p','p.id','transactions.id_package')
            ->where('p.id_reference_type_package',config('config.IdTypeReference.PaketBuku'))
            ->select(DB::raw('LEFT (date ,7) as bulan,COUNT(*) as jml'))
            ->groupBy(DB::raw('LEFT(`date`, 7)'))
            ->get();
        $data = array();
        for ($i=0;$i<count($orderPerMont);$i++){
            $data[$i]['bulan'] = $orderPerMont[$i]->bulan;
            $data[$i]['order'] = $orderPerMont[$i]->jml;
        }
        $data = [
            'data' => $data,
            'paketJurnal' => $paketJurnal,
            'paketBuku' => $paketBuku,
        ];
        return response()->json($data);
    }
}
