<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Ventas;
use Illuminate\Support\Facades\DB;

class DocController extends Controller
{
    public function generate($id)
    {
        $venta = Ventas::with('cliente', 'vendedor', 'detalleVentas')->find($id);

        if (!$venta) {
            return response()->json(['error' => 'Venta no encontrada'], 404);
        }

        $cliente = $venta->cliente;
        $vendedor = $venta->vendedor;
        $detalle = $venta->detalleVentas;

        $data = [
            'venta' => $venta,
            'cliente' => $cliente,
            'vendedor' => $vendedor,
            'detalle' => $detalle
        ];

        return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, "default_paper_size" => "a4", 'default_paper_orientation' => "portrait", "default_media_type" => "screen", "pdf_backend" => "CPDF", "dpi" => 96, "enable_php" => true])->loadView('pdf.venta', $data)->stream("doc-venta-$venta->codigo_venta-" . date('Y-m-d') . ".pdf");
    }

    public function reportes(Request $r)
    {
        $type = $r->input('type');
        $seller_id = $r->input('seller_id');
        $client_id = $r->input('client_id');
        $desde = date('Y-m-d', strtotime($r->input('desde', date('Y-m-d')))) . ' 00:00:00';
        $hasta = date('Y-m-d', strtotime($r->input('hasta', date('Y-m-d')))) . ' 23:59:59';
        $nameFile = $r->input('nameFile');
        $columns = [];
        $data = [];

        switch ($type) {
            case 1:
                // SELECT ventas.codigo_venta,ventas.fecha_compra, COALESCE(clientes.nombre,'Sin Cliente') as cliente,ventas.total,users.name as vendedor,ventas.estado FROM `ventas` LEFT JOIN users ON users.id = ventas.vendedor_id LEFT JOIN clientes ON clientes.id = ventas.cliente_id WHERE ventas.fecha_compra BETWEEN "2024-06-18" AND "2024-09-18" ORDER BY ventas.fecha_compra DESC; 
                $data = DB::table('ventas')->join('users', 'users.id', '=', 'ventas.vendedor_id')->leftJoin('clientes', 'clientes.id', '=', 'ventas.cliente_id')->whereBetween('fecha_compra', [$desde, $hasta])->orderBy('fecha_compra', 'desc')->selectRaw('ventas.codigo_venta as CODIGO,ventas.fecha_compra as FECHA, COALESCE(clientes.nombre,"Sin Cliente") as CLIENTE,ventas.total as TOTAL,users.name as VENDEDOR,ventas.estado as ESTADO')->get();

                $columns = ['CODIGO', 'FECHA', 'CLIENTE', 'TOTAL', 'VENDEDOR', 'ESTADO'];
                break;

            case 2:
                // SELECT ventas.codigo_venta,ventas.fecha_compra, COALESCE(clientes.nombre,'Sin Cliente') as cliente,ventas.total,users.name as vendedor,ventas.estado FROM `ventas` LEFT JOIN users ON users.id = ventas.vendedor_id LEFT JOIN clientes ON clientes.id = ventas.cliente_id WHERE ventas.fecha_compra BETWEEN "2024-06-18" AND "2024-09-18" AND ventas.vendedor_id = 1 ORDER BY ventas.fecha_compra DESC; 
                $data = DB::table('ventas')
                    ->join('users', 'users.id', '=', 'ventas.vendedor_id')
                    ->leftJoin('clientes', 'clientes.id', '=', 'ventas.cliente_id')
                    ->whereBetween('fecha_compra', [$desde, $hasta]);

                if ($seller_id && $seller_id != "") {
                    $data = $data->selectRaw('ventas.codigo_venta as CODIGO, ventas.fecha_compra as FECHA, users.name as VENDEDOR, COALESCE(clientes.nombre, "Sin Cliente") as CLIENTE, ventas.total as TOTAL, ventas.estado as ESTADO')
                        ->where('ventas.vendedor_id', $seller_id)
                        ->orderBy('fecha_compra', 'desc')
                        ->get();
                    $columns = ['CODIGO', 'FECHA', 'CLIENTE', 'TOTAL', 'ESTADO'];
                } else {
                    $data = $data->selectRaw('users.name as VENDEDOR, SUM(ventas.total) as TOTAL')
                        ->groupBy('users.name')
                        ->orderBy('users.name', 'asc')
                        ->get();
                    $columns = ['VENDEDOR', 'TOTAL'];
                }
                break;

            case 3:
                // SELECT ventas.codigo_venta,ventas.fecha_compra, COALESCE(clientes.nombre,'Sin Cliente') as cliente,ventas.total,users.name as vendedor,ventas.estado FROM `ventas` LEFT JOIN users ON users.id = ventas.vendedor_id LEFT JOIN clientes ON clientes.id = ventas.cliente_id WHERE ventas.fecha_compra BETWEEN "2024-06-18" AND "2024-09-18" AND ventas.vendedor_id = 1 ORDER BY ventas.fecha_compra DESC;
                $data = DB::table('ventas')
                    ->join('users', 'users.id', '=', 'ventas.vendedor_id')
                    ->leftJoin('clientes', 'clientes.id', '=', 'ventas.cliente_id')
                    ->whereBetween('fecha_compra', [$desde, $hasta]);

                if ($client_id && $client_id != "") {
                    $data = $data->selectRaw('ventas.codigo_venta as CODIGO, ventas.fecha_compra as FECHA, users.name as VENDEDOR, COALESCE(clientes.nombre, "Sin Cliente") as CLIENTE, ventas.total as TOTAL, ventas.estado as ESTADO')
                        ->where('ventas.cliente_id', $client_id)
                        ->orderBy('fecha_compra', 'desc')
                        ->get();
                    $columns = ['CODIGO', 'FECHA', 'VENDEDOR', 'TOTAL', 'ESTADO'];
                } else {
                    $data = $data->selectRaw('COALESCE(clientes.nombre, "Sin Cliente") as CLIENTE, SUM(ventas.total) as TOTAL')
                        ->groupBy('CLIENTE')
                        ->orderBy('CLIENTE', 'asc')
                        ->get();
                    $columns = ['CLIENTE', 'TOTAL'];
                }
                break;
        }

        return PDF::loadView('pdf.reportes', compact('data', 'columns', 'nameFile'))->stream(str_replace(' ', '-', $nameFile));
    }
}