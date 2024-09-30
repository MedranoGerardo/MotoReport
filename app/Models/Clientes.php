<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Definición del modelo Clientes que extiende la clase base Model de Eloquent
class Clientes extends Model
{
    // Uso del trait HasFactory para la generación de instancias con factories
    use HasFactory;

    // Atributos que se pueden asignar masivamente (usados al crear o actualizar registros)
    protected $fillable = [
        'nombre',
        'dui',
        'nit',
        'telefono',
        'direccion',
        'municipio',
        'departamento',
        'correo',
        'estado',
    ];

}
