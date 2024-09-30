<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    use HasFactory;
    // Atributos que se pueden asignar masivamente

    protected $fillable = [
        'fecha_compra',
        'cliente_id',
        'total',
        'vendedor_id',
        'observaciones',
        'tipo_venta',
        'estado',
    ];
    // Define atributos adicionales que no están en la base de datos
    protected $appends = ['cliente'];

    public function cliente()
    {
        return $this->belongsTo('App\Models\Clientes', 'cliente_id', 'id');
    }

    public function getClienteAttribute()
    {
        return $this->cliente()->first();
    }

    public function vendedor()
    {
        return $this->belongsTo('App\Models\User', 'vendedor_id', 'id');
    }

    public function getVendedorAttribute()
    {
        return $this->vendedor()->first();
    }

    public function detalleVentas()
    {
        return $this->hasMany('App\Models\DetalleVentas', 'venta_id', 'id');
    }

    public function getDetalleVentasAttribute()
    {
        return $this->detalleVentas()->get();
    }
}
