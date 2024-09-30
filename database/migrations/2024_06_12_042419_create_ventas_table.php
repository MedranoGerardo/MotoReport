<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_venta')->nullable();
            $table->dateTime('fecha_compra');
            $table->bigInteger('cliente_id')->unsigned()->nullable();
            $table->decimal('total', 10, 2);
            $table->bigInteger('vendedor_id')->unsigned();
            $table->text('observaciones')->nullable();
            $table->enum('tipo_venta', ['credito', 'contado']);
            $table->enum('estado', ['pendiente', 'finalizado', 'anulada'])->default('pendiente');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('vendedor_id')->references('id')->on('users');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
