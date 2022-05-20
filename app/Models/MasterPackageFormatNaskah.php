<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterPackageFormatNaskah extends Model
{
    use SoftDeletes;

    protected $table = 'mst_package_format_naskah';
    protected $fillable = [
        'id_mst_package',
        'id_reference_format_naskah',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
