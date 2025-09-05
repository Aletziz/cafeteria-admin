<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Para SQLite, necesitamos recrear la tabla con los nuevos valores
        Schema::table('orders', function (Blueprint $table) {
            // Primero, agregar columnas temporales
            $table->string('status_temp')->default('pending');
            $table->string('payment_status_temp')->default('pending');
        });
        
        // Copiar datos existentes a las columnas temporales
        DB::statement("UPDATE orders SET status_temp = status, payment_status_temp = payment_status");
        
        // Actualizar valores que han cambiado
        DB::statement("UPDATE orders SET status_temp = 'completed' WHERE status = 'confirmed' OR status = 'delivered'");
        
        // Eliminar las columnas originales
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['status', 'payment_status']);
        });
        
        // Recrear las columnas con los nuevos valores
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'preparing', 'ready', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
        });
        
        // Copiar datos de vuelta
        DB::statement("UPDATE orders SET status = status_temp, payment_status = payment_status_temp");
        
        // Eliminar columnas temporales
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['status_temp', 'payment_status_temp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a los valores originales para SQLite
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status_temp')->default('pending');
            $table->string('payment_status_temp')->default('pending');
        });
        
        DB::statement("UPDATE orders SET status_temp = status, payment_status_temp = payment_status");
        DB::statement("UPDATE orders SET status_temp = 'confirmed' WHERE status = 'completed'");
        
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['status', 'payment_status']);
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
        });
        
        DB::statement("UPDATE orders SET status = status_temp, payment_status = payment_status_temp");
        
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['status_temp', 'payment_status_temp']);
        });
    }
};
