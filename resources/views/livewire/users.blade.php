@section('title', 'Usuarios')
<div class="card p-3 mt-2 ">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Usuarios</h1>
    </div>

    <div class="modal fade" id="formUser" tabindex="-1" aria-labelledby="userLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userLabel">
                        {{ $user_id ? 'Editar usuario' : 'Nuevo usuario' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Nombre Completo</label>
                        <input wire:model="name" type="text" placeholder="Nombre" id="nombre"
                            class="form-control @error('name') was-validated is-invalid @enderror"
                            oninput="this.value = this.value.toUpperCase();">
                        <div class="invalid-feedback">
                            @error('name') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Rol</label>
                        <select wire:model="role" class="form-select @error('role') was-validated is-invalid @enderror">
                            <option value="">Seleccione un rol</option>
                            <option value="admin">Administrador</option>
                            <option value="vendedor">Vendedor</option>
                        </select>
                        <div class="invalid-feedback">
                            @error('role') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">Nombre de usuario</label>
                        <input wire:model="username" type="text" placeholder="Nombre de usuario" id="username"
                            oninput="this.value = this.value.toLowerCase();"
                            class="form-control @error('username') was-validated is-invalid @enderror">
                        <div class="invalid-feedback">
                            @error('username') {{$message }} @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-2">
                        <label class="form-label">{{ $user_id ? 'Cambiar contraseña' : 'Contraseña' }}</label>
                        <input wire:model="password" type="password" placeholder="Contraseña" id="password"
                            class="form-control @error('password') was-validated is-invalid @enderror">
                        <div class="invalid-feedback">
                            @error('password') {{$message }} @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    @if ($user_id)
                    <button type="button" class="btn btn-warning" wire:click="update">Actualizar</button>
                    @else
                    <button type="button" class="btn btn-primary" wire:click="store">Guardar</button>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <input type="search" class="form-control" placeholder="Buscar usuarios" wire:model.live="search"
                style="max-width: 50%;">
            <button type="button" class="btn btn-primary" wire:click="abrir_modal">
                <i class="fas fa-plus"></i>
                Nuevo Usuario
            </button>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($users->count() > 0)
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" wire:click="edit({{ $user->id }})">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                @if ($user->id != auth()->user()->id)
                                <button class="btn btn-danger btn-sm" onclick="confirmarEliminar({{ $user->id }})">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4" class="text-center">No hay usuarios registrados</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    document.addEventListener('livewire:initialized', function () {
        Livewire.on('cerrar-modal', function () {
            $('.modal').modal('hide');
        });

        Livewire.on('abrir-modal', function () {
            $('.modal').modal('show');
        });

        Livewire.on('refresh', function () {
            window.location.reload();
        });
    });

    const confirmarEliminar = async id => {
        if (await window.Confirm('Eliminar', '¿Estas seguro de eliminar este usuario?', 'warning', 'Si, eliminar', 'Cancelar')) {
            Livewire.dispatch('delete', { id });
        }
    }
</script>