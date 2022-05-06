<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/*Models*/
use App\Models\Employee;

use Config;
use App\Traits\HelperMasterTraits;
class EmployeesController extends Controller
{
    use HelperMasterTraits;
    public function index(){
        $data = Employee::leftJoin('references as r','r.id','id_reference')
            ->select(
                'employees.id',
                'employees.name',
                'employees.nip',
                'employees.phone',
                'r.description as division')
            ->where('r.id_type_reference',config('config.IdTypeReference.Divisi'))
            ->get();
        $divisi = $this->getDivisi();
            dd($divisi);
    }
}
