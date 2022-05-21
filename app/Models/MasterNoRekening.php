<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterNoRekening extends Model
{
    use SoftDeletes;

    protected $table = "mst_no_rekening";
    protected $fillable = [
        'id_reference_bank',
        'name',
        'no_rek',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
