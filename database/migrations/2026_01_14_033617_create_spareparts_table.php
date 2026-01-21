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
        Schema::create('spareparts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('brand', 50)->nullable();
            $table->string('size', 30)->nullable();
            $table->enum('type', ['tubetype', 'tubeless']);
            $table->decimal('weight', 5, 2); // kg
            $table->tinyInteger('fuzzy_weight_value')
                ->comment('1=ringan, 2=sedang, 3=berat');
            $table->integer('stock')->default(0);
            $table->decimal('price', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spareparts');
    }
};
