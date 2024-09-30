<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\DetalleVentas;
use App\Models\Ventas;
use App\Models\Clientes;
use App\Models\Marcas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Clase `DetalleVentaController` maneja la lógica para la gestión
 * de los detalles de ventas, permitiendo crear, editar y eliminar detalles, así como asociar un cliente a la venta.
 * 
 * Funcionalidades principales:
 * - Inicializar una nueva venta o cargar una existente, asignando un código único y manejando el cliente asociado.
 * - Gestionar la creación y edición de detalles de venta, validando los campos requeridos y actualizando el total de la venta.
 * - Permitir la búsqueda y paginación de los detalles de venta de una venta específica.
 * - Cambiar el cliente asociado a la venta y asegurar la consistencia de los datos mediante transacciones.
 * - Eliminar detalles de venta, recalculando el total de la venta.
 * - Finalizar la venta, verificando que tenga un cliente asociado y detalles de venta registrados.
 * 
 * Métodos principales:
 * - `mount()`: Inicializa o carga una venta existente, asociando un código único y preparando los datos de la venta.
 * - `changeCliente()`: Actualiza el cliente de una venta asegurando la integridad de los datos mediante transacciones.
 * - `render()`: Renderiza la vista de detalles de venta, mostrando los detalles de acuerdo a los criterios de búsqueda y paginación.
 * - `save()`: Crea un nuevo detalle de venta, validando los campos requeridos y actualizando el total de la venta.
 * - `edit()`: Carga un detalle de venta para su edición.
 * - `update()`: Actualiza un detalle de venta existente y recalcula el total de la venta.
 * - `destroy()`: Elimina un detalle de venta y recalcula el total.
 * - `saveVenta()`: Finaliza el proceso de venta, asegurando que la venta tenga detalles y un cliente asignado.
 */

class DetalleVentaController extends Component
{
    // Definición de propiedades públicas
    public $idSelect = null, $isEdit = false, $idVenta = null, $paginate = 10, $search, $marca, $modelo, $tipo, $color, $poliza, $motor, $chasis, $precio, $total, $idCliente, $currentVenta;

    // Uso del trait WithPagination para manejar paginación
    use WithPagination;

    // Método para especificar la vista personalizada de paginación
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    // Método para inicializar la venta o cargar una existente
    public function mount($id)
    {
        if ($id == null || $id == 0) {
            $this->idVenta = Ventas::create([
                'vendedor_id' => Auth::id(),
                'fecha_compra' => date('Y-m-d H:i:s'),
                'total' => 0,
                'tipo_venta' => 'contado',
            ])->id;
            $code = 'V' . str_pad($this->idVenta, 8, '0', STR_PAD_LEFT);
            Ventas::where('id', $this->idVenta)->update(['codigo_venta' => $code]);
            $this->currentVenta = Ventas::find($this->idVenta);
            $this->dispatch("url-update", route('venta', $this->idVenta));
        } else {
            $this->idVenta = $id;
            $this->isEdit = true;
            $this->currentVenta = Ventas::find($id);
            $this->idCliente = $this->currentVenta->cliente_id;
        }
    }

    // Método para cambiar el cliente de la venta
    public function changeCliente()
    {
        try {
            DB::beginTransaction();
            Ventas::where('id', $this->idVenta)->update(['cliente_id' => $this->idCliente]);
            DB::commit();
            $this->dispatch("message-success", "Cliente actualizado correctamente");
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch("message-error", "Error al actualizar el cliente");
        }
    }

    // Método para renderizar la vista
    public function render()
    {
        if ($this->search != null && $this->search != "") {
            $detalleVentas = DetalleVentas::where('venta_id', $this->idVenta)
                ->where(function ($query) {
                    $query->where('marca', 'like', '%' . $this->search . '%')
                        ->orWhere('modelo', 'like', '%' . $this->search . '%')
                        ->orWhere('tipo', 'like', '%' . $this->search . '%')
                        ->orWhere('color', 'like', '%' . $this->search . '%')
                        ->orWhere('poliza', 'like', '%' . $this->search . '%')
                        ->orWhere('motor', 'like', '%' . $this->search . '%')
                        ->orWhere('chasis', 'like', '%' . $this->search . '%')
                        ->orWhere('precio', 'like', '%' . $this->search . '%');
                })
                ->paginate($this->paginate);
        } else {
            $detalleVentas = DetalleVentas::where('venta_id', $this->idVenta)->orderBy('id', 'desc')->paginate($this->paginate);
        }
        $this->total = DetalleVentas::where('venta_id', $this->idVenta)->sum('precio');
        $clientes = Clientes::where('estado', 'activo')->get();
        $marcas = Marcas::activos()->get();
        return view('livewire.detalle-venta', compact('detalleVentas', 'clientes', 'marcas'))->extends('layouts.app')->section('content');
    }

    // Método para abrir el modal de creación o edición
    public function abrir_modal($isReset = true)
    {
        if ($isReset) {
            $this->resetErrorBag();
            $this->resetValidation();
            $this->resetUI();
        }
        $this->dispatch("abrir-modal");
    }

    // Método para cerrar el modal
    public function cerrar_modal()
    {
        $this->dispatch("cerrar-modal");
    }

    // Método para guardar un detalle de venta
    public function save()
    {
        $rules = [
            'marca' => 'required',
            'modelo' => 'required',
            'tipo' => 'required',
            'color' => 'required',
            'poliza' => 'required',
            'motor' => 'required|unique:detalle_ventas,motor',
            'chasis' => 'required|unique:detalle_ventas,chasis',
            'precio' => 'required | numeric',
        ];

        $message = [
            'marca.required' => 'El campo marca es requerido',
            'modelo.required' => 'El campo modelo es requerido',
            'tipo.required' => 'El campo tipo es requerido',
            'color.required' => 'El campo color es requerido',
            'poliza.required' => 'El campo poliza es requerido',
            'motor.required' => 'El campo motor es requerido',
            'motor.unique' => 'El campo motor ya fue registrado',
            'chasis.required' => 'El campo chasis es requerido',
            'chasis.unique' => 'El campo chasis ya fue registrado',
            'precio.required' => 'El campo precio es requerido',
            'precio.numeric' => 'El campo precio debe ser un número',
        ];

        if ($this->modelo == null || $this->modelo == "") {
            $this->dispatch("is-invalid-modelo", true);
        } else {
            $this->dispatch("is-invalid-modelo", false);
        }

        $this->validate($rules, $message);

        try {
            DB::beginTransaction();
            DetalleVentas::create([
                'venta_id' => $this->idVenta,
                'marca' => $this->marca,
                'modelo' => $this->modelo,
                'tipo' => $this->tipo,
                'color' => $this->color,
                'poliza' => $this->poliza,
                'motor' => $this->motor,
                'chasis' => $this->chasis,
                'precio' => $this->precio,
            ]);
            $this->total = DetalleVentas::where('venta_id', $this->idVenta)->sum('precio');
            Ventas::where('id', $this->idVenta)->update(['total' => $this->total]);
            DB::commit();
            $this->cerrar_modal();
            $this->resetUI();
            $this->dispatch("message-success", "Detalle de venta guardado correctamente");
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch("message-error", "Error al guardar el detalle de venta");
        }
    }

    // Método para editar un detalle de venta existente
    public function edit($id)
    {
        $this->idSelect = $id;
        $detalleVenta = DetalleVentas::find($id);
        $this->marca = $detalleVenta->marca;
        $this->tipo = $detalleVenta->tipo;
        $this->color = $detalleVenta->color;
        $this->poliza = $detalleVenta->poliza;
        $this->motor = $detalleVenta->motor;
        $this->chasis = $detalleVenta->chasis;
        $this->precio = $detalleVenta->precio;
        $this->modelo = $detalleVenta->modelo;
        $this->change_marca($this->marca, $detalleVenta->modelo);
        $this->resetErrorBag();
        $this->resetValidation();
        $this->abrir_modal(false);
    }

    // Método para actualizar un detalle de venta existente
    public function update()
    {
        $rules = [
            'marca' => 'required',
            'modelo' => 'required',
            'tipo' => 'required',
            'color' => 'required',
            'poliza' => 'required',
            'motor' => 'required|unique:detalle_ventas,motor,' . $this->idSelect,
            'chasis' => 'required|unique:detalle_ventas,chasis,' . $this->idSelect,
            'precio' => 'required | numeric',
        ];

        $message = [
            'marca.required' => 'El campo marca es requerido',
            'modelo.required' => 'El campo modelo es requerido',
            'tipo.required' => 'El campo tipo es requerido',
            'color.required' => 'El campo color es requerido',
            'poliza.required' => 'El campo poliza es requerido',
            'motor.required' => 'El campo motor es requerido',
            'motor.unique' => 'El campo motor ya fue registrado',
            'chasis.required' => 'El campo chasis es requerido',
            'chasis.unique' => 'El campo chasis ya fue registrado',
            'precio.required' => 'El campo precio es requerido',
            'precio.numeric' => 'El campo precio debe ser un número',
        ];

        if ($this->modelo == null || $this->modelo == "") {
            $this->dispatch("is-invalid-modelo", true);
        } else {
            $this->dispatch("is-invalid-modelo", false);
        }

        $this->validate($rules, $message);

        try {
            DB::beginTransaction();
            DetalleVentas::where('id', $this->idSelect)->update([
                'marca' => $this->marca,
                'modelo' => $this->modelo,
                'tipo' => $this->tipo,
                'color' => $this->color,
                'poliza' => $this->poliza,
                'motor' => $this->motor,
                'chasis' => $this->chasis,
                'precio' => $this->precio,
            ]);
            $this->total = DetalleVentas::where('venta_id', $this->idVenta)->sum('precio');
            Ventas::where('id', $this->idVenta)->update(['total' => $this->total]);
            DB::commit();
            $this->cerrar_modal();
            $this->resetUI();
            $this->dispatch("message-success", "Detalle de venta actualizado correctamente");
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch("message-error", "Error al actualizar el detalle de venta");
        }
    }

    public function resetUI()
    {
        $this->idSelect = null;
        $this->marca = null;
        $this->modelo = null;
        $this->tipo = null;
        $this->color = null;
        $this->poliza = null;
        $this->motor = null;
        $this->chasis = null;
        $this->precio = null;
        $this->dispatch("set-modelo", "", []);
        $this->dispatch("is-invalid-modelo", false);
    }

    public function saveVenta($id)
    {
        if ($this->total == 0) {
            $this->dispatch("message-error", "No se puede guardar una venta sin detalles");
            return;
        }
        if ($id == null || $id == 0) {
            $this->dispatch("message-error", "Error al guardar la venta");
            return;
        }
        if ($this->currentVenta->cliente_id == null) {
            $this->dispatch("message-error", "Debe seleccionar un cliente para la venta");
            return;
        }
        try {
            DB::beginTransaction();
            Ventas::where('id', $id)->update(['estado' => 'finalizado']);
            DB::commit();
            $this->dispatch("abrir-confirm-sale");
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch("message-error", "Error al pagar la venta");
        }
    }

    #[On("delete")]
    // Método para eliminar un detalle de venta
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            DetalleVentas::find($id)->delete();
            $this->total = DetalleVentas::where('venta_id', $this->idVenta)->sum('precio');
            Ventas::where('id', $this->idVenta)->update(['total' => $this->total]);
            DB::commit();
            $this->dispatch("message-success", "Detalle de venta eliminado correctamente");
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch("message-error", "Error al eliminar el detalle de venta");
        }
    }

    #[On("change-marca")]
    public function change_marca($name, $modelo = "")
    {
        $listModelos = Marcas::where('nombre', $name)->first()->modelos;
        $this->modelo = $modelo;
        $this->dispatch("set-modelo", $modelo, $listModelos);
    }
}