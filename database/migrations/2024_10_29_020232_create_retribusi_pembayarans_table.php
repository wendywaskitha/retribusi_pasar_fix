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
        Schema::disableForeignKeyConstraints();

        Schema::create('retribusi_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedagang_id')->constrained('pedagangs')->cascadeOnDelete();
            $table->foreignId('pasar_id')->constrained('pasars')->cascadeOnDelete();
            $table->date('tanggal_bayar');
            $table->enum('status', ["pending","lunas"]);
            $table->decimal('total_biaya', 10, 2)->default(0);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('retribusi_pembayaran_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retribusi_pembayaran_id')->constrained()->onDelete('cascade');
            $table->foreignId('retribusi_id')->constrained()->onDelete('cascade');
            $table->decimal('biaya', 10, 2);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retribusi_pembayaran_items');
        Schema::dropIfExists('retribusi_pembayarans');
    }
};
