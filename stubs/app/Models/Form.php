<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function fields(){
        return $this->BelongsToMany(Field::class, 'form_field', 'form_id', 'field_id');
    }

    public function modelos(){
        return $this->hasMany(Modelo::class);
    }
}
