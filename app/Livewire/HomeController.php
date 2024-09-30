<?php

// Espacio de nombres para la clase HomeController
namespace App\Livewire;

// Importación de clases necesarias
use Livewire\Component;
use App\Models\Clientes;
use App\Models\Ventas;
use Illuminate\Support\Facades\DB;
/**
 * Clase `HomeController` maneja la lógica para la página de inicio, proporcionando información estadística sobre los clientes y las ventas.
 * 
 * Funcionalidades principales:
 * - Contar el número total de clientes y ventas.
 * - Calcular el total de ventas realizadas en el día y en el mes actual, excluyendo las ventas anuladas.
 * - Obtener las últimas 10 ventas realizadas para mostrarlas en la página de inicio.
 * - Calcular el total de ventas diarias para el mes actual y las ventas anuales agrupadas por mes, excluyendo las ventas anuladas.
 * 
 * Métodos principales:
 * - `render()`: Obtiene todos los datos necesarios, incluyendo los contadores y sumas de ventas, y luego renderiza la vista de inicio con esos datos.
 */

class HomeController extends Component
{
    // Método render que obtiene datos y renderiza la vista de inicio
    public function render()
    {
        // Contar el número total de clientes
        $clienteCount = Clientes::count();

        // Contar el número total de ventas
        $ventaCount = Ventas::count();

        // Calcular el total de ventas del día, excluyendo ventas anuladas
        $totalVentaToday = Ventas::whereDate('fecha_compra', date('Y-m-d'))
            ->where("estado", '!=', 'anulada')
            ->sum('total');

        // Calcular el total de ventas del mes actual, excluyendo ventas anuladas
        $totalVentaMonth = Ventas::whereMonth('fecha_compra', date('m'))
            ->where("estado", '!=', 'anulada')
            ->sum('total');

        // Obtener las últimas 10 ventas realizadas, ordenadas de manera descendente
        $lastVentas = Ventas::latest()->take(10)->get();

        // Calcular el total de ventas diarias para el mes actual, excluyendo ventas anuladas
        $totalVentaDiarias = DB::table('ventas')
            ->select(DB::raw('SUM(total) as total, DATE(fecha_compra) as date'))
            ->whereBetween('fecha_compra', [date('Y-m-01') . ' 00:00:00', date('Y-m-d') . ' 23:59:59'])
            ->where('estado', '!=', 'anulada')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Calcular el total de ventas anuales agrupadas por mes, excluyendo ventas anuladas
        $totalVentaAnuales = DB::table('ventas')
            ->select(DB::raw('SUM(total) as total, MONTH(fecha_compra) as month'))
            ->whereYear('fecha_compra', date('Y'))
            ->where('estado', '!=', 'anulada')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Retornar la vista con los datos obtenidos para ser mostrados en la página
        return view('livewire.home', compact(
            'clienteCount', 'ventaCount', 'totalVentaToday', 'totalVentaMonth', 
            'lastVentas', 'totalVentaDiarias', 'totalVentaAnuales'
        ))->extends('layouts.app')->section('content');
    }
}
