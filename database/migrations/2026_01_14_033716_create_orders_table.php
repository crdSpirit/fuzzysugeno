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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 30)->unique();
            $table->foreignId('store_id')
                ->constrained('stores')
                ->cascadeOnDelete();

            $table->date('order_date')->nullable();
            $table->enum('status', [
                'draft',
                'submitted',
                'processed',
                'shipped',
                'completed',
                'cancelled',
            ])->default('draft');

            $table->decimal('total_weight', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
