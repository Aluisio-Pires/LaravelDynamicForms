<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'complement',
    ];

    public function fields()
    {
        return $this->belongsToMany(Field::class, 'field_validation', 'validation_id', 'field_id');
    }
}
