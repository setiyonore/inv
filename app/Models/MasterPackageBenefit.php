<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterPackageBenefit extends Model
{
    use SoftDeletes;

    protected $table = 'mst_package_benefit';
    protected $fillable = [
        'id_mst_package',
        'id_reference_benefit',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
