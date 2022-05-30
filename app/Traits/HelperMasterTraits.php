<?php
namespace App\Traits;
/*Models*/

use App\Models\MasterNoRekening;
use App\Models\Reference;
use App\Models\TypeReference;
use App\Models\Customer;

use Config;
/**
 * Trait HelperMasterTraits
 */
trait HelperMasterTraits
{
    public function getDivisi(){
        $data = Reference::select('id','description')
                ->where('id_type_reference',config('config.IdTypeReference.Divisi'))
                ->get();
        return $data;
    }
    public function getBenefit(){
        $data = Reference::query()
            ->select('id','description')
            ->where('id_type_reference',config('config.IdTypeReference.Benefit'))
            ->get();
        return $data;
    }
    public function getFormatNaskah(){
        $data = Reference::query()
            ->select('id','description')
            ->where('id_type_reference',config('config.IdTypeReference.FormatNaskah'))
            ->get();
        return $data;
    }
    public function getBank(){
        $data = Reference::query()
            ->select('id','description')
            ->where('id_type_reference',config('config.IdTypeReference.Bank'))
            ->get();
        return $data;
    }
    public function getDataReferensi($id){
        $data = Reference::select('id','description')
                ->where('id_type_reference',$id)
                ->get();
        return $data;
    }

    public function getTypeReferensi(){
        $data = TypeReference::select('id','description')->get();
        return $data;
    }

    public function getNomerRekening(){
        $data = MasterNoRekening::query()
            ->leftJoin('references as r','r.id','mst_no_rekening.id_reference_bank')
            ->where('r.id_type_reference',config('config.IdTypeReference.Bank'))
            ->select('mst_no_rekening.id','mst_no_rekening.name','mst_no_rekening.no_rek','r.description')
            ->get();
        return $data;
    }

    public function getCustomerById($id){
        $data = Customer::query()
            ->select('id','name')
            ->where('id',$id)
            ->get();
        return $data;
    }

}
