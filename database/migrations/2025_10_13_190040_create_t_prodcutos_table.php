<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->foreignId('categoria_id')->constrained('t_categorias')->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->decimal('precio',10, 2);
            $table->enum('estado',['A','I'])->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_prodcutos');
    }
};
