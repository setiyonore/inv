<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reference extends Model
{
    use SoftDeletes;

    protected $table = 'references';

    protected $fillable = [
        'id_type_reference',
        'description',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function typereferences()
    {
        return $this->belongsTo(TypeReference::class, 'products_id', 'id');
    }
}
