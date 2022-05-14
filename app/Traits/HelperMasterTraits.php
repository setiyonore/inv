<?php
namespace App\Traits;
/*Models*/
use App\Models\Reference;
use App\Models\TypeReference;

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

    public function getDataReferensi($id){
        $data = Reference::select('id','description as divisi')
                ->where('id_type_reference',$id)
                ->get();
        return $data;
    }

    public function getTypeReferensi(){
        $data = TypeReference::select('id','description')->get();
        return $data;
    }

}
