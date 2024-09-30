<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Clientes;
use App\Models\User;
/**
 * Clase `ReportesController` encargada de gestionar la visualización de reportes.
 * 
 * Funcionalidades principales:
 * - Recuperación de una lista de usuarios y clientes desde la base de datos.
 * - Los usuarios son recuperados con los campos `id` y `name`, ordenados alfabéticamente por nombre.
 * - Los clientes son recuperados con los campos `id` y `nombre`, también ordenados alfabéticamente.
 * - Renderiza una vista llamada `reportes`, que se extiende desde el layout principal de la aplicación y se ubica en la sección de contenido.
 * 
 * Atributos principales:
 * - Ninguno declarado explícitamente en la clase, ya que sólo se encargan de gestionar las consultas y el renderizado de la vista.
 */


class ReportesController extends Component
{
    public function render()
    {
        $users = User::select('id', 'name')->orderBy('name', 'asc')->get();
        $clientes = Clientes::select('id', 'nombre')->orderBy('nombre', 'asc')->get();
        return view('livewire.reportes', compact('users', 'clientes'))->extends('layouts.app')->section('content');
    }
}
