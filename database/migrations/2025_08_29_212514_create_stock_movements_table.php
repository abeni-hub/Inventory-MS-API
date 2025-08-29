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
        Schema::create('stock_movements', function (Blueprint $table) {
    $table->id();
    $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
    $table->foreignId('item_id')->constrained()->onDelete('cascade');
    $table->integer('quantity'); // Positive for in, negative for out
    $table->string('type'); // e.g., 'purchase', 'sale', 'adjustment', 'transfer'
    $table->morphs('source'); // Polymorphic: PurchaseOrder, SalesOrder, Adjustment, etc.
    $table->text('notes')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
