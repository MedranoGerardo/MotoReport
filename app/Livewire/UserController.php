<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
/**
 * Clase `UserController` responsable de la gestión de usuarios en el sistema.
 * 
 * Funcionalidades principales:
 * - Búsqueda de usuarios con paginación dinámica.
 * - Creación, edición y eliminación de usuarios, incluyendo validaciones específicas para los campos.
 * - Gestión de roles, contraseñas y autenticación, asegurando que solo los administradores puedan acceder.
 * - Uso de transacciones para la integridad de las operaciones de creación, actualización y eliminación.
 * - Implementación de modales para gestionar el flujo de creación y edición de usuarios.
 * 
 * Atributos principales:
 * - `$search`: Cadena utilizada para filtrar usuarios por nombre, rol o fecha de creación.
 * - `$paginate`: Define cuántos usuarios se muestran por página.
 * - `$user_id`, `$name`, `$username`, `$role`, `$password`: Información del usuario para crear o editar.
 * 
 * Métodos principales:
 * - `mount()`: Inicializa la búsqueda y redirige si el usuario no es administrador.
 * - `paginationView()`: Define la vista de paginación.
 * - `render()`: Muestra los usuarios en la vista con o sin búsqueda, paginados.
 * - `abrir_modal()`: Resetea el estado del formulario y abre el modal.
 * - `destroy()`: Elimina un usuario utilizando transacciones.
 * - `edit()`: Carga la información del usuario para su edición.
 * - `update()`: Actualiza un usuario con validaciones.
 * - `store()`: Crea un nuevo usuario con validaciones.
 * - `resetUi()`: Resetea los atributos relacionados con el usuario.
 */


class UserController extends Component
{
    public $search, $paginate = 10, $user_id, $name, $username, $role, $password;

    use WithPagination;

    public function mount()
    {
        $this->search = '';
        if (auth()->user()->role != 'admin') {
            return redirect()->route('home');
        }
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        if ($this->search != null && $this->search != "") {
            $users = User::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('role', 'like', '%' . $this->search . '%')
                ->orWhere('created_at', 'like', '%' . $this->search . '%')
                ->paginate($this->paginate);
        } else {
            $users = User::orderBy('id', 'desc')->paginate($this->paginate);
        }
        return view(
            'livewire.users',
            compact('users')
        )->extends('layouts.app')->section('content');
    }

    public function abrir_modal()
    {
        $this->resetUI();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch("abrir-modal");
    }

    #[On("delete")]
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $user = User::find($id);
            $user->delete();
            DB::commit();
            $this->dispatch("message-success", "Usuario eliminado correctamente");
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch("message-error", "Error al eliminar el usuario");
        }
    }

    private function resetUi()
    {
        $this->user_id = null;
        $this->name = null;
        $this->username = null;
        $this->role = null;
        $this->password = null;
    }

    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            $this->dispatch("message-error", "Usuario no encontrado");
            return;
        }
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->role = $user->role;
        $this->password = "";
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch("abrir-modal");
    }

    public function update()
    {
        $rules = [
            'name' => 'required|min:3|regex:/^[\pL\s\-]+$/u',
            'username' => 'required|unique:users,username,' . $this->user_id,
            'role' => 'required',
            'password' => [
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', // Minúscula, mayúscula, número, caracter especial
            ],
        ];

        $messages = [
            'name.required' => 'El campo nombre es requerido',
            'name.min' => 'El campo nombre debe tener al menos 3 caracteres',
            'name.regex' => 'El campo nombre solo puede contener letras',
            'username.required' => 'El campo usuario es requerido',
            'username.unique' => 'El usuario ya existe',
            'role.required' => 'El campo rol es requerido',
            'password.min' => 'El campo contraseña debe tener al menos 8 caracteres',
            'password.regex' => 'El campo contraseña debe contener al menos una letra mayúscula, una letra minúscula, un número y un caracter especial'
        ];

        $this->validate($rules, $messages);

        try {
            DB::beginTransaction();
            $user = User::find($this->user_id);
            $user->name = $this->name;
            $user->username = $this->username;
            $user->role = $this->role;
            if ($this->password != "") {
                $user->password = Hash::make($this->password);
            }
            $user->save();
            DB::commit();
            if (Auth::user()->id == $this->user_id) {
                Auth::login($user);
                $this->dispatch('refresh');
            }
            $this->dispatch("message-success", "Usuario actualizado correctamente");
            $this->resetUi();
            $this->dispatch("cerrar-modal");
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch("message-error", "Error al actualizar el usuario");
        }
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min:3|regex:/^[\pL\s\-]+$/u',
            'username' => 'required|unique:users,username',
            'role' => 'required',
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', // Minúscula, mayúscula, número, caracter especial
            ],
        ];

        $messages = [
            'name.required' => 'El campo nombre es requerido',
            'name.min' => 'El campo nombre debe tener al menos 3 caracteres',
            'name.regex' => 'El campo nombre solo puede contener letras',
            'username.required' => 'El campo usuario es requerido',
            'username.unique' => 'El usuario ya existe',
            'role.required' => 'El campo rol es requerido',
            'password.required' => 'El campo contraseña es requerido',
            'password.min' => 'El campo contraseña debe tener al menos 8 caracteres',
            'password.regex' => 'El campo contraseña debe contener al menos una letra mayúscula, una letra minúscula, un número y un caracter especial'
        ];

        $this->validate($rules, $messages);

        try {
            DB::beginTransaction();
            $user = new User();
            $user->name = $this->name;
            $user->username = $this->username;
            $user->role = $this->role;
            $user->password = Hash::make($this->password);
            $user->save();
            DB::commit();
            $this->dispatch("message-success", "Usuario creado correctamente");
            $this->resetUi();
            $this->dispatch("cerrar-modal");
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch("message-error", "Error al crear el usuario");
        }
    }
}
