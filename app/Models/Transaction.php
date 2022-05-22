<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id_customer',
        'id_reference_type_transaction',
        'id_reference_type_of_payment',
        'id_package',
        'amount',
        'id_reference_tax',
        'affiliation',
        'id_no_rekening',
        'id_user',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
