<?php
namespace App\Traits;
/*Models*/
use App\Models\Reference;

use Config;
/**
 * Trait HelperMasterTraits
 */
trait HelperMasterTraits
{
    public function getDivisi(){
        $data = Reference::select('id','description as divisi')
                ->where('id_type_reference',config('config.IdTypeReference.Divisi'))
                ->get();
        return $data;
    }
}
