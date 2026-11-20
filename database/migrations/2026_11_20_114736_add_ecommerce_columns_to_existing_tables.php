<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->string('role')->default('customer')->after('password'));
        Schema::table('categories', fn (Blueprint $table) => $table->string('name')->unique()->after('id'));
        Schema::table('products', function (Blueprint $table): void {
            $table->foreignId('category_id')->after('id')->constrained()->cascadeOnDelete();
            $table->string('name')->after('category_id');
            $table->string('slug')->unique()->after('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
        });
        Schema::table('addresses', function (Blueprint $table): void {
            $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            $table->string('recipient');
            $table->string('phone');
            $table->text('full_address');
        });
        Schema::table('carts', function (Blueprint $table): void {
            $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            $table->unique('user_id');
        });
        Schema::table('cart_items', function (Blueprint $table): void {
            $table->foreignId('cart_id')->after('id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->unique(['cart_id', 'product_id']);
        });
        Schema::table('orders', function (Blueprint $table): void {
            $table->foreignId('user_id')->after('id')->constrained();
            $table->string('invoice_no')->unique();
            $table->decimal('total', 10, 2)->default(0);
            $table->string('status')->default('pending');
            $table->text('shipping_address');
        });
        Schema::table('order_items', function (Blueprint $table): void {
            $table->foreignId('order_id')->after('id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->unsignedInteger('quantity');
            $table->decimal('price', 10, 2);
        });
        Schema::table('payments', function (Blueprint $table): void {
            $table->foreignId('order_id')->after('id')->constrained()->cascadeOnDelete();
            $table->string('method')->default('cash');
            $table->string('status')->default('pending');
            $table->timestamp('paid_at')->nullable();
        });
    }

    public function down(): void {}
};
