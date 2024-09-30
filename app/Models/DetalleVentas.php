<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Definición del modelo DetalleVentas que extiende la clase base Model de Eloquent
class DetalleVentas extends Model
{
    // Usa el trait HasFactory para generar factories (datos falsos para pruebas)
    use HasFactory;

    // Atributos que pueden ser asignados masivamente
    protected $fillable = [
        'venta_id',
        'marca',
        'modelo',
        'tipo',
        'color',
        'poliza',
        'motor',
        'chasis',
        'precio',
    ];
    // Definición de la relación belongsTo con el modelo Ventas
    public function venta()
    {
        return $this->belongsTo(Ventas::class, 'venta_id');
    }
}
