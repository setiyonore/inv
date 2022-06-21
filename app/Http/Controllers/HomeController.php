<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return view('home.dasboard',compact('order','paketBuku','paketJurnal','pelanggan'));
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
