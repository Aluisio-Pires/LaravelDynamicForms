<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'size',
    ];

    public function forms(){
        return $this->belongsToMany(Form::class, 'form_field', 'field_id', 'form_id');
    }

    public function validations()
    {
        return $this->belongsToMany(Validation::class, 'field_validation', 'field_id', 'validation_id');
    }
}
