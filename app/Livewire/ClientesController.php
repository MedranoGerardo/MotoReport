<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Clientes;
use Illuminate\Support\Facades\DB;

/**
 * Clase ClientesController
 * Controlador Livewire para la gestión de clientes, incluyendo búsqueda, creación, edición, eliminación y validación.
 */
class ClientesController extends Component
{
    use WithPagination;

    public $idSelect = null,
    $nombre,
    $dui,
    $nit,
    $telefono,
    $direccion,
    $municipio = "Ahuachapán",
    $departamento = "Ahuachapán",
    $correo,
    $estado = "activo",
    $paginate = 10,
    $search;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        if ($this->search != null && $this->search != "") {
            $clientes = Clientes::where('nombre', 'like', '%' . $this->search . '%')
                ->orWhere('dui', 'like', '%' . $this->search . '%')
                ->orWhere('nit', 'like', '%' . $this->search . '%')
                ->orWhere('telefono', 'like', '%' . $this->search . '%')
                ->orWhere('direccion', 'like', '%' . $this->search . '%')
                ->orWhere('municipio', 'like', '%' . $this->search . '%')
                ->orWhere('departamento', 'like', '%' . $this->search . '%')
                ->orWhere('correo', 'like', '%' . $this->search . '%')
                ->paginate($this->paginate);
        } else {
            $clientes = Clientes::orderBy('id', 'desc')->paginate($this->paginate);
        }

        return view('livewire.clientes', compact('clientes'))
            ->extends('layouts.app')
            ->section('content');
    }

    public function abrir_modal()
    {
        $this->resetUI();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch("abrir-modal");
    }

    public function store()
    {
        $rules = [
            // 'nombre' => 'required|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\'’`´\-.\s]+$/|max:255',
            'nombre' => 'required|regex:/^[A-ZÁÉÍÓÚÑÜ\'’`´\-.\\s]+$/|max:255',
            'dui' => 'required|regex:/^\d{8}-\d{1}$/|max:10|unique:clientes,dui',
            'nit' => 'required|regex:/^\d{4}-\d{6}-\d{3}-\d{1}$/|max:17|unique:clientes,nit',
            'telefono' => 'required|regex:/^\d{4}-\d{4}$/|max:9|unique:clientes,telefono',
            'direccion' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'correo' => 'required|email|max:255|unique:clientes,correo',
        ];

        $message = [
            'nombre.required' => 'El campo nombre es requerido',
            'nombre.regex' => 'El campo nombre solo puede contener letras y espacios',
            'nombre.max' => 'El campo nombre debe tener máximo 255 caracteres',
            'dui.required' => 'El campo dui es requerido',
            'dui.regex' => 'El formato del DUI es inválido',
            'dui.max' => 'El campo dui debe tener máximo 10 caracteres',
            'dui.unique' => 'El DUI ya está registrado',
            'nit.required' => 'El campo nit es requerido',
            'nit.regex' => 'El formato del NIT es inválido',
            'nit.max' => 'El campo nit debe tener máximo 17 caracteres',
            'nit.unique' => 'El NIT ya está registrado',
            'telefono.required' => 'El campo telefono es requerido',
            'telefono.regex' => 'El campo telefono debe contener 8 dígitos',
            'telefono.max' => 'El campo telefono debe tener máximo 8 caracteres',
            'telefono.unique' => 'El teléfono ya está registrado',
            'direccion.required' => 'El campo direccion es requerido',
            'direccion.string' => 'El campo direccion debe ser un texto',
            'direccion.max' => 'El campo direccion debe tener máximo 255 caracteres',
            'municipio.required' => 'El campo municipio es requerido',
            'municipio.string' => 'El campo municipio debe ser un texto',
            'municipio.max' => 'El campo municipio debe tener máximo 255 caracteres',
            'departamento.required' => 'El campo departamento es requerido',
            'departamento.string' => 'El campo departamento debe ser un texto',
            'departamento.max' => 'El campo departamento debe tener máximo 255 caracteres',
            'correo.required' => 'El campo correo es requerido',
            'correo.email' => 'El campo correo debe ser una dirección de correo válida',
            'correo.max' => 'El campo correo debe tener máximo 255 caracteres',
            'correo.unique' => 'El correo ya está registrado',
        ];

        $this->validate($rules, $message);

        try {
            DB::beginTransaction();
            Clientes::create([
                'nombre' => $this->nombre,
                'dui' => $this->dui,
                'nit' => $this->nit,
                'telefono' => $this->telefono,
                'direccion' => $this->direccion,
                'municipio' => $this->municipio,
                'departamento' => $this->departamento,
                'correo' => $this->correo,
                'estado' => $this->estado,
            ]);
            DB::commit();
            $this->resetUI();
            $this->dispatch("cerrar-modal");
            $this->dispatch("message-success", "Cliente registrado con éxito");
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch("message-error", "Error al registrar el cliente");
        }
    }

    public function edit($id)
    {
        $cliente = Clientes::find($id);
        $this->idSelect = $cliente->id;
        $this->nombre = $cliente->nombre;
        $this->dui = $cliente->dui;
        $this->nit = $cliente->nit;
        $this->telefono = $cliente->telefono;
        $this->direccion = $cliente->direccion;
        $this->correo = $cliente->correo;
        $this->estado = $cliente->estado;
        $this->municipio = $cliente->municipio;
        $this->departamento = $cliente->departamento;
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch("abrir-modal", ['municipio' => $cliente->municipio, 'departamento' => $cliente->departamento]);
    }

    public function update()
    {
        $rules = [
            'nombre' => 'required|regex:/^[A-ZÁÉÍÓÚÑÜ\'’`´\-.\\s]+$/|max:255',
            'dui' => 'required|regex:/^\d{8}-\d{1}$/|max:10|unique:clientes,dui,' . $this->idSelect,
            'nit' => 'required|regex:/^\d{4}-\d{6}-\d{3}-\d{1}$/|max:17|unique:clientes,nit,' . $this->idSelect,
            'telefono' => 'required|regex:/^\d{4}-\d{4}$/|max:9|unique:clientes,telefono,' . $this->idSelect,
            'direccion' => 'required|string|max:255',
            'municipio' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'correo' => 'required|email|max:255|unique:clientes,correo,' . $this->idSelect,
        ];

        $message = [
            'nombre.required' => 'El campo nombre es requerido',
            'nombre.regex' => 'El campo nombre solo puede contener letras y espacios',
            'nombre.max' => 'El campo nombre debe tener máximo 255 caracteres',
            'dui.required' => 'El campo dui es requerido',
            'dui.regex' => 'El formato del DUI es inválido',
            'dui.max' => 'El campo dui debe tener máximo 10 caracteres',
            'dui.unique' => 'El DUI ya está registrado',
            'nit.required' => 'El campo nit es requerido',
            'nit.regex' => 'El formato del NIT es inválido',
            'nit.max' => 'El campo nit debe tener máximo 17 caracteres',
            'nit.unique' => 'El NIT ya está registrado',
            'telefono.required' => 'El campo telefono es requerido',
            'telefono.regex' => 'El campo telefono debe contener 8 dígitos',
            'telefono.max' => 'El campo telefono debe tener máximo 8 caracteres',
            'telefono.unique' => 'El teléfono ya está registrado',
            'direccion.required' => 'El campo direccion es requerido',
            'direccion.string' => 'El campo direccion debe ser un texto',
            'direccion.max' => 'El campo direccion debe tener máximo 255 caracteres',
            'municipio.required' => 'El campo municipio es requerido',
            'municipio.string' => 'El campo municipio debe ser un texto',
            'municipio.max' => 'El campo municipio debe tener máximo 255 caracteres',
            'departamento.required' => 'El campo departamento es requerido',
            'departamento.string' => 'El campo departamento debe ser un texto',
            'departamento.max' => 'El campo departamento debe tener máximo 255 caracteres',
            'correo.required' => 'El campo correo es requerido',
            'correo.email' => 'El campo correo debe ser una dirección de correo válida',
            'correo.max' => 'El campo correo debe tener máximo 255 caracteres',
            'correo.unique' => 'El correo ya está registrado',
        ];

        $this->validate($rules, $message);

        try {
            DB::beginTransaction();
            $cliente = Clientes::find($this->idSelect);
            $cliente->update([
                'nombre' => $this->nombre,
                'dui' => $this->dui,
                'nit' => $this->nit,
                'telefono' => $this->telefono,
                'direccion' => $this->direccion,
                'municipio' => $this->municipio,
                'departamento' => $this->departamento,
                'correo' => $this->correo,
                'estado' => $this->estado,
            ]);
            DB::commit();
            $this->resetUI();
            $this->dispatch("cerrar-modal");
            $this->dispatch("message-success", "Cliente actualizado con éxito");
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch("message-error", "Error al actualizar el cliente");
        }
    }

    #[On("delete")]
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $cliente = Clientes::find($id);
            $cliente->delete();
            DB::commit();
            $this->dispatch("message-success", "Cliente eliminado con éxito");
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch("message-error", "Error al eliminar el cliente");
        }
    }

    public function resetUI()
    {
        $this->idSelect = null;
        $this->nombre = null;
        $this->dui = null;
        $this->nit = null;
        $this->telefono = null;
        $this->direccion = null;
        $this->municipio = "Ahuachapán";
        $this->departamento = "Ahuachapán";
        $this->correo = null;
        $this->estado = "activo";
    }
}
