<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Ventas;
use Illuminate\Support\Facades\DB;
/**
 * Clase `VentasController` encargada de la gestión y visualización de las ventas.
 * 
 * Funcionalidades principales:
 * - Búsqueda y filtrado de ventas basadas en varios campos como código de venta, fecha, total, tipo, estado y nombre del cliente.
 * - Paginación dinámica para mostrar las ventas.
 * - Anulación de ventas mediante una transacción segura para cambiar el estado a "anulada".
 * 
 * Atributos principales:
 * - `$search`: Cadena utilizada para filtrar las ventas según los campos relevantes.
 * - `$paginate`: Define cuántas ventas se muestran por página.
 * 
 * Métodos principales:
 * - `paginationView()`: Define la vista de paginación personalizada.
 * - `render()`: Renderiza la vista de ventas con o sin filtros, y aplica paginación.
 * - `anularVenta()`: Anula una venta específica cambiando su estado a "anulada" dentro de una transacción de base de datos.
 */

class VentasController extends Component
{
    public $search, $paginate = 10;

    use WithPagination;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        if ($this->search != null && $this->search != "") {
            $ventas = Ventas::where('codigo_venta', 'like', '%' . $this->search . '%')
                ->orWhere('fecha_compra', 'like', '%' . $this->search . '%')
                ->orWhere('total', 'like', '%' . $this->search . '%')
                ->orWhere('tipo_venta', 'like', '%' . $this->search . '%')
                ->orWhere('estado', 'like', '%' . $this->search . '%')
                ->orWhereHas('cliente', function ($query) {
                    $query->where('nombre', 'like', '%' . $this->search . '%');
                })
                ->paginate($this->paginate);
        } else {
            $ventas = Ventas::orderBy('id', 'desc')->paginate($this->paginate);
        }
        return view(
            'livewire.ventas',
            compact('ventas')
        )->extends('layouts.app')->section('content');
    }

    #[On("anular")]
    public function anularVenta($id)
    {
        try {
            DB::beginTransaction();
            $venta = Ventas::find($id);
            $venta->estado = 'anulada';
            $venta->save();
            DB::commit();
            $this->dispatch('message-success', 'Venta anulada correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('message-error', 'Error al anular la venta');
        }
    }
}
