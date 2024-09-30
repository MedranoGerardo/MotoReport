<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marcas extends Model
{
    use HasFactory;
    // Define la tabla explícitamente en caso de que el nombre de la clase no siga la convención de Eloquent
    protected $table = 'marcas';
    // Define los campos que se pueden asignar masivamente
    protected $fillable = ['nombre', 'activo'];
      // Agrega el atributo 'modelos' como una propiedad accesible desde el modelo
    protected $appends = ['modelos'];

     // Relación one-to-many (uno a muchos) con el modelo Modelos
    public function modelos()
    {
        return $this->hasMany('App\Models\Modelos', 'marca_id', 'id');
    }
    // Accesor para obtener los modelos relacionados como una propiedad 'modelos'
    public function getModelosAttribute()
    {
        return $this->modelos()->get();
    }
    // Definición de un query scope para filtrar marcas activas
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
