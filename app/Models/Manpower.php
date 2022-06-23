<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manpower extends Model
{
    use SoftDeletes;

    protected $table = 'manpower';
    protected $fillable = [
        'id_transaction',
        'id_employee',
        'id_reference_working_status',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
