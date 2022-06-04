<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class MasterPackage extends Model
{
    use SoftDeletes;
    protected $table = 'mst_package';
    protected $fillable = [
        'id',
        'id_reference_type_package',
        'name',
        'short',
        'description',
        'price',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
