<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Livewire\HomeController;
use App\Livewire\ClientesController;
use App\Livewire\DetalleVentaController;
use App\Livewire\VentasController;
use App\Http\Controllers\DocController;
use App\Livewire\UserController;
use App\Livewire\MarcaModeloController;
use App\Livewire\ReportesController;

Auth::routes();

Route::get('/register', function () {
    return redirect('/login');
});

Route::get('/init', function () {
    if (User::count() === 0) {
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);
        return 'Admin user created';
    }
    return 'Admin user already exists';
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', HomeController::class)->name('home');
    Route::get('/clientes', ClientesController::class)->name('clientes');
    Route::get('/ventas', VentasController::class)->name('ventas');
    Route::get('/venta/{id}', DetalleVentaController::class)->name('venta');
    Route::get('/venta/{id}/doc.pdf', [DocController::class, 'generate'])->name('docs');
    Route::get('/reportes', ReportesController::class)->name('reportes');
    Route::get('/reportes/doc.pdf', [DocController::class, 'reportes'])->name('reportes-doc');
    Route::get('/configuracion/usuarios', UserController::class)->name('users');
    Route::get('/configuracion/marcas-modelos', MarcaModeloController::class)->name('marcas-modelos');
});
