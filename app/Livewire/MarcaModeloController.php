<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Marcas;
use App\Models\Modelos;
use Illuminate\Support\Facades\DB;
/**
 * Clase `MarcaModeloController` gestiona las operaciones relacionadas con marcas y modelos en la aplicación.
 * 
 * Funcionalidades principales:
 * - Búsqueda y paginación de marcas.
 * - Apertura de modales para crear y editar tanto marcas como modelos.
 * - Creación, edición y eliminación de marcas.
 * - Creación, edición y eliminación de modelos, asegurando que los nombres de los modelos sean únicos por marca.
 * - Validación de los campos de nombre y estado tanto para marcas como para modelos.
 * - Uso de transacciones de base de datos para garantizar la integridad durante las operaciones de creación, edición y eliminación.
 * - Uso de métodos Livewire como `dispatch` para interactuar con la interfaz de usuario y controlar modales y mensajes de error o éxito.
 * 
 * Atributos principales:
 * - `$search`: Para almacenar el término de búsqueda.
 * - `$nombre`, `$activo`: Campos relacionados con las marcas.
 * - `$nombreModelo`, `$activoModelo`, `$marcaSelection`: Campos relacionados con los modelos.
 * - `$listModelos`: Lista de modelos asociados a una marca seleccionada.
 * - `$paginations`: Define la cantidad de elementos por página.
 */
class MarcaModeloController extends Component
{
    use WithPagination;
    public $search, $idSelect, $nombre, $activo, $paginations = 10, $nombreModelo, $activoModelo, $marcaSelection, $listModelos = [], $idmodelo;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->search = '';
        if (auth()->user()->role != 'admin') {
            return redirect()->route('home');
        }
    }

    public function render()
    {
        if (strlen($this->search) > 0) {
            $marcas = Marcas::where('nombre', 'like', '%' . $this->search . '%')
                ->orderBy('nombre', 'asc')
                ->paginate($this->paginations);
        } else {
            $marcas = Marcas::orderBy('nombre', 'asc')
                ->paginate($this->paginations);
        }
        return view('livewire.marca-modelo', compact('marcas'))
            ->extends('layouts.app')
            ->section('content');
    }

    public function abrir_modal_marca()
    {
        $this->idSelect = '';
        $this->nombre = '';
        $this->activo = true;
        $this->dispatch('asignar-valores', 'activo', true, 'checkbox');
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch('abrir-modal', 'marca');
    }

    public function abrir_modal_modelo($id)
    {
        $marca = Marcas::find($id);
        if (is_null($marca)) {
            $this->dispatch('message-error', 'Marca no encontrada.');
            return;
        }
        $this->idSelect = $id;
        $this->marcaSelection = $marca->nombre;
        $this->listModelos = $marca->modelos;
        $this->idmodelo = "";
        $this->nombreModelo = '';
        $this->activoModelo = true;
        $this->dispatch('asignar-valores', 'activoModelo', true, 'checkbox');
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch('abrir-modal', 'modelo');
    }

    public function store_marca()
    {
        $rules = [
            'activo' => 'required',
            'nombre' => 'required|min:3|max:50|unique:marcas,nombre',
        ];

        $messages = [
            'activo.required' => 'El campo estado es requerido.',
            'nombre.required' => 'El campo nombre es requerido.',
            'nombre.min' => 'El campo nombre debe tener al menos 3 caracteres.',
            'nombre.max' => 'El campo nombre debe tener máximo 50 caracteres.',
            'nombre.unique' => 'El nombre de la marca ya existe.',
        ];

        $this->validate($rules, $messages);

        try {
            DB::beginTransaction();
            $marca = new Marcas();
            $marca->nombre = $this->nombre;
            $marca->activo = $this->activo;
            $marca->save();
            DB::commit();
            $this->dispatch('cerrar-modal', 'marca');
            $this->isSelect = '';
            $this->nombre = '';
            $this->activo = '';
            $this->reset();
            $this->resetErrorBag();
            $this->dispatch('message-success', 'Marca creada correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('message-error', 'Error al crear la marca.');
        }
    }

    public function edit_marca($id)
    {
        $marca = Marcas::find($id);
        $this->idSelect = $marca->id;
        $this->nombre = $marca->nombre;
        $this->activo = $marca->activo;
        $this->dispatch('asignar-valores', 'activo', boolval($this->activo), 'checkbox');
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch('abrir-modal', 'marca');
    }

    public function update_marca()
    {
        $rules = [
            'activo' => 'required',
            'nombre' => 'required|min:3|max:50|unique:marcas,nombre,' . $this->idSelect,
        ];

        $messages = [
            'activo.required' => 'El campo estado es requerido.',
            'nombre.required' => 'El campo nombre es requerido.',
            'nombre.min' => 'El campo nombre debe tener al menos 3 caracteres.',
            'nombre.max' => 'El campo nombre debe tener máximo 50 caracteres.',
            'nombre.unique' => 'El nombre de la marca ya existe.',
        ];

        $this->validate($rules, $messages);

        try {
            DB::beginTransaction();
            $marca = Marcas::find($this->idSelect);
            $marca->nombre = $this->nombre;
            $marca->activo = $this->activo;
            $marca->save();
            DB::commit();
            $this->dispatch('cerrar-modal', 'marca');
            $this->idSelect = "";
            $this->nombre = '';
            $this->activo = '';
            $this->reset();
            $this->resetErrorBag();
            $this->dispatch('message-success', 'Marca actualizada correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('message-error', 'Error al actualizar la marca.');
        }
    }

    #[On("delete-marca")]
    public function delete_marca($id)
    {
        try {
            DB::beginTransaction();
            $marca = Marcas::find($id);
            $marca->delete();
            DB::commit();
            $this->dispatch('message-success', 'Marca eliminada correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('message-error', 'Error al eliminar la marca.');
        }
    }

    public function store_modelo()
    {
        /* Que el modelo sea unico por marca_id que no se repita para esa marca */
        $rules = [
            'activoModelo' => 'required',
            'nombreModelo' => 'required|min:3|max:50|unique:modelos,nombre,NULL,id,marca_id,' . $this->idSelect,
        ];

        $messages = [
            'activoModelo.required' => 'El campo estado es requerido.',
            'nombreModelo.required' => 'El campo nombre es requerido.',
            'nombreModelo.min' => 'El campo nombre debe tener al menos 3 caracteres.',
            'nombreModelo.max' => 'El campo nombre debe tener máximo 50 caracteres.',
            'nombreModelo.unique' => 'El nombre del modelo ya existe.',
        ];

        $this->validate($rules, $messages);

        try {
            DB::beginTransaction();
            $modelo = new Modelos();
            $modelo->nombre = $this->nombreModelo;
            $modelo->activo = $this->activoModelo;
            $modelo->marca_id = $this->idSelect;
            $modelo->save();
            DB::commit();
            $this->abrir_modal_modelo($this->idSelect);
            $this->dispatch('message-success', 'Modelo creado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('message-error', 'Error al crear el modelo.');
        }
    }

    public function edit_modelo($id)
    {
        $modelo = Modelos::find($id);
        $this->idmodelo = $modelo->id;
        $this->nombreModelo = $modelo->nombre;
        $this->activoModelo = $modelo->activo;
        $this->dispatch('asignar-valores', 'activoModelo', boolval($this->activoModelo), 'checkbox');
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch('abrir-modal', 'modelo');
    }

    public function cancel_edit_modelo()
    {
        $this->idmodelo = "";
        $this->nombreModelo = '';
        $this->activoModelo = true;
        $this->dispatch('asignar-valores', 'activoModelo', true, 'checkbox');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function update_modelo()
    {
        $rules = [
            'activoModelo' => 'required',
            'nombreModelo' => 'required|min:3|max:50|unique:modelos,nombre,' . $this->idmodelo . ',id,marca_id,' . $this->idSelect,
        ];

        $messages = [
            'activoModelo.required' => 'El campo estado es requerido.',
            'nombreModelo.required' => 'El campo nombre es requerido.',
            'nombreModelo.min' => 'El campo nombre debe tener al menos 3 caracteres.',
            'nombreModelo.max' => 'El campo nombre debe tener máximo 50 caracteres.',
            'nombreModelo.unique' => 'El nombre del modelo ya existe.',
        ];

        $this->validate($rules, $messages);

        try {
            DB::beginTransaction();
            $modelo = Modelos::find($this->idmodelo);
            $modelo->nombre = $this->nombreModelo;
            $modelo->activo = $this->activoModelo;
            $modelo->save();
            DB::commit();
            $this->abrir_modal_modelo($this->idSelect);
            $this->dispatch('message-success', 'Modelo actualizado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('message-error', 'Error al actualizar el modelo.');
        }
    }

    #[On("delete-modelo")]
    public function delete_modelo($id)
    {
        try {
            DB::beginTransaction();
            $modelo = Modelos::find($id);
            $modelo->delete();
            DB::commit();
            $this->abrir_modal_modelo($this->idSelect);
            $this->dispatch('message-success', 'Modelo eliminado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('message-error', 'Error al eliminar el modelo.');
        }
    }
}
