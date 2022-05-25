<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HelperMasterTraits;
/*models*/
use App\Models\MasterNoRekening;
use App\Models\MasterPackage;

class TransactionController extends Controller
{
    use HelperMasterTraits;
    public function index(Request $request){
        $norek = MasterNoRekening::query()
            ->leftJoin('references as r','r.id','mst_no_rekening.id_reference_bank')
            ->where('r.id_type_reference',config('config.IdTypeReference.Bank'))
            ->select('mst_no_rekening.id','mst_no_rekening.name','mst_no_rekening.no_rek','r.description')
            ->get();
        $paket = MasterPackage::query()
            ->select('id','name')
            ->get();
        return view('transactions.index',compact('norek','paket'));
    }
}
