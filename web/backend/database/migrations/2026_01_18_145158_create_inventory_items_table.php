<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Categories
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Locations (Gudang, Mobil, Site)
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('warehouse'); // warehouse, vehicle, site
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Suppliers
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Inventory Items (Master Barang)
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id')->unsigned(); // Manual foreign key def to avoid order issues if using foreignId
            $table->string('sku')->unique()->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit')->default('pcs'); // pcs, meters, box, roll
            $table->decimal('min_stock_alert', 10, 2)->default(5);
            $table->decimal('purchase_price', 15, 2)->default(0); // Harga beli terakhir
            $table->decimal('selling_price', 15, 2)->default(0); // Harga jual (jika dijual)
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('inventory_categories')->onDelete('cascade');
        });

        // 5. Stocks (Stok per Lokasi)
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 15, 2)->default(0);
            $table->string('aisle')->nullable(); // Rak/Lorong
            $table->string('bin')->nullable(); // Kotak/Bin
            $table->timestamps();

            $table->unique(['inventory_item_id', 'location_id']);
        });

        // 6. Stock Movements (Riwayat Keluar Masuk)
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(); // Siapa yang input
            $table->enum('type', ['in', 'out', 'transfer', 'adjustment']); 
            $table->decimal('quantity', 15, 2);
            $table->decimal('previous_quantity', 15, 2); // Stok sebelum transaksi
            $table->decimal('new_quantity', 15, 2); // Stok setelah transaksi
            $table->string('reference_number')->nullable(); // PO Number, WO Number
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('inventory_categories');
    }
};
