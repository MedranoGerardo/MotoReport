<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelos extends Model
{
    use HasFactory;
    // Define la tabla asociada al modelo
    protected $table = 'modelos';

    // Define los atributos que se pueden asignar masivamente
    protected $fillable = ['nombre', 'marca_id', 'activo'];

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
