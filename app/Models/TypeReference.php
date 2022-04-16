<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeReference extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'short', 'description'
    ];

    protected $hidden = [
        
    ];

    // public function references()
    // {
    //     return $this->hasMany(TypeReferences::class, 'type_references_id');
    // }
}
