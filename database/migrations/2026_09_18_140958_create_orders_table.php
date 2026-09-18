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
            $table->integer('outlet_id')->nullable()->default(1);
            $table->integer('customer_id');
            $table->integer('user_id');
            $table->string('order_code')->unique();
            $table->enum('status', ['diterima', 'cuci', 'setrika', 'siap_diambil', 'selesai']);
            $table->enum('payment_status', ['belum_bayar', 'dp', 'lunas']);
            $table->decimal('total_price', 10, 2);
            $table->date('estimated_done')->nullable();
            $table->timestamp('finished_at')->nullable();
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
