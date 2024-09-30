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
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('venta_id')->unsigned();
            $table->string('marca');
            $table->string('modelo');
            $table->string('tipo');
            $table->string('color');
            $table->string('poliza');
            $table->string('motor');
            $table->string('chasis');
            $table->decimal('precio', 10, 2);
            $table->foreign('venta_id')->references('id')->on('ventas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
    }
};
